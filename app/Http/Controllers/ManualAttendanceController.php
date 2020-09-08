<?php

namespace App\Http\Controllers;

use App\Department;
use App\FetchAttendance;
use App\FiscalYearModel;
use App\Helpers\ArrayHelper;
use App\Helpers\BSDateHelper;
use App\Repositories\FetchAttendanceRepository;
use App\Shift;
use App\StafMainMastModel;
use App\SystemJobTypeMastModel;
use App\SystemOfficeMastModel;
use App\SystemPostMastModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManualAttendanceController extends Controller
{
    private $fetchAttendanceRepository;

    public function __construct(FetchAttendanceRepository $fetchAttendanceRepository)
    {
        $this->fetchAttendanceRepository = $fetchAttendanceRepository;
    }


    public function index()
    {
        $title = 'Manual Attendance';

        $departments = Department::pluck('department_name', 'id');
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $fiscalYears = FiscalYearModel::pluck('fiscal_code', 'id');
        $jobTypes = SystemJobTypeMastModel::pluck('jobtype_name', 'jobtype_id');
        $currentFiscalYearId = FiscalYearModel::where('fiscal_status', 1)->pluck('id');
        $designations = SystemPostMastModel::pluck('post_title', 'post_id');

        return view('manualattendance.index',
            compact('title', 'departments', 'branches', 'fiscalYears', 'currentFiscalYearId', 'jobTypes', 'designations'));
    }

    public function filter(Request $request)
    {
        $title = 'Manual Attendance Filter';
        $status = false;

        if (!isset($request->branch_id)) {
            return redirect()->back()
                ->with('flash', ['status' => $status, 'mesg' => 'Please select a branch']);
        }

        $departments = Department::pluck('department_name', 'id');
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $jobTypes = SystemJobTypeMastModel::pluck('jobtype_name', 'jobtype_id');
        $designations = SystemPostMastModel::pluck('post_title', 'post_id');
        $shifts = Shift::where('branch_id', $request->branch_id)->where('active', 1)->pluck('shift_name', 'id');

        $staffs = StafMainMastModel::with('branch');

        if (!ArrayHelper::is_array_empty($request->job_type_id)) {
            $staffs->whereIn('jobtype_id', array_values($request->job_type_id));
        }

        if (!ArrayHelper::is_array_empty($request->department_id)) {
            $staffs->whereIn('department', array_values($request->department_id));
        }

        if (!empty($request->branch_id)) {
            $staffs->where('branch_id', $request->branch_id);
        }

        if (!ArrayHelper::is_array_empty($request->designation_id)) {
            $staffs->whereIn('post_id', array_values($request->designation_id));
        }

        if (!ArrayHelper::is_array_empty($request->shift_id)) {
            $staffs->whereIn('shift_id', array_values(array_filter($request->shift_id)));
        }

        if ($staffs->count() > 50) {
            $breakCount = (ceil($staffs->count() / 3));
        } else {
            $breakCount = $staffs->count();
        }

        $staffs = $staffs->get();


        $increment = 1;
        return view('manualattendance.filter', compact('staffs', 'branches', 'jobTypes', 'designations', 'shifts', 'departments', 'breakCount', 'title', 'increment', 'breakCount'));

    }

    public function filterView(Request $request)
    {
        $staff_central_ids = $request->staff_central_id;
        $branch_id = request('branch_id');
        $from_date = BSDateHelper::BsToAd('-', $request->from_date);
        $to_date = BSDateHelper::BsToAd('-', $request->to_date);

        if (isset($staff_central_ids) && count($staff_central_ids) > 0) {
            $staffs = StafMainMastModel::with(['fetchAttendances' => function ($query) use ($from_date, $to_date) {
                $query->whereDate('punchin_datetime', '>=', $from_date . ' 00:00:00');
                $query->whereDate('punchin_datetime', '<=', $to_date . ' 23:59:59');
            }, 'latestShift', 'workschedule'])->whereIn('id', $staff_central_ids)->get();
            return view('manualattendance.filter-view', compact('staffs', 'branch_id', 'from_date', 'to_date'));
            /*$returnHtml = view('manualattendance.filter-view', compact('staffs', 'from_date_np', 'branch_id', 'from_date'))->render();

            return response()->json(['status' => 'true', 'html' => $returnHtml]);*/
        }
    }

    public function store(Request $request)
    {
        $staffsAttendanceDetails = $request->staffs;
        $status = false;
        if (count($staffsAttendanceDetails) < 1) {
            return redirect()->route('manual-attendance')
                ->with('flash', ['status' => $status, 'mesg' => 'Please select a staff']);
        }
        $branch_id = null;
        try {
//            DB::beginTransaction();
            $staffCentralIds = [];
            foreach ($staffsAttendanceDetails as $attendance_date_en => $detailsArray) {
                $attendance_date_np = BSDateHelper::AdToBs('-', $attendance_date_en);
                foreach ($detailsArray as $staffCentralId => $staffInputs) {
                    $selectStaffCentral = StafMainMastModel::where('id', $staffCentralId)->first();
                    $branch_id = $selectStaffCentral->branch_id;
                    if (strtolower(config('constants.manual_attendance.status')[$staffInputs['status']]) == 'present') {

                        $staffInputs['attendance_date_np'] = $attendance_date_np;
                        $staffInputs['attendance_date_en'] = $attendance_date_en;
                        array_push($staffCentralIds, $staffCentralId);

                        $staffWithPreviousDetails = FetchAttendance::whereIn('staff_central_id', $staffCentralIds)
                            ->whereDate('punchin_datetime', $attendance_date_en);

                        $staffCentralIdWithPreviousDetails = $staffWithPreviousDetails->pluck('staff_central_id');
                        $inputs = $this->fetchAttendanceRepository->getInputsForDateTimeFields($staffInputs);
                        $inputs['status'] = FetchAttendance::manualAttendance;
                        $inputs['branch_id'] = $branch_id;
                        $inputs['total_work_hour'] = 0;
                        $inputs['sync'] = FetchAttendance::unSync;
                        //init array here to avoid last value of personalIn, personalOut when prev value is there
                        $datesArray = [];

                        if (!is_null($inputs['punchout_datetime_np'])) {
                            $datesArray['punchOut'] = Carbon::parse($inputs['punchout_datetime']);
                            $datesArray['punchIn'] = Carbon::parse($inputs['punchin_datetime']);
                            $inputs['total_work_hour'] = $this->fetchAttendanceRepository->calculateTotalHoursWork($datesArray);
                        }

                        if ($this->fetchAttendanceRepository->hasPreviousFetchAttendance($staffCentralId, $staffCentralIdWithPreviousDetails->toArray())) {
                            $inputs['updated_by'] = auth()->id();
                            $previousFetchAttendance = FetchAttendance::whereDate('punchin_datetime', $attendance_date_en)->where('staff_central_id', $staffCentralId);
                            $status = FetchAttendance::whereDate('punchin_datetime', $attendance_date_en)->where('staff_central_id', $staffCentralId)->update($inputs);
                        } else {
                            $staffWithNoPreviousRecord = StafMainMastModel::where('id', $staffCentralId)->first();
                            $inputs['staff_central_id'] = $staffCentralId;
                            $inputs['shift_id'] = $staffWithNoPreviousRecord->shift_id;

                            if (empty($staffInput['remarks']) || $staffInput['remarks'] == '' || trim($staffInput['remarks']) == '') {
                                $inputs['remarks'] = '';
                            } else {
                                $inputs['remarks'] = $staffInput['remarks'];
                            }
                            $inputs['created_at'] = Carbon::now();
                            $inputs['updated_at'] = Carbon::now();
                            $inputs['created_by'] = auth()->id();
                            FetchAttendance::insert($inputs);

                        }
                    } else if (strtolower(config('constants.manual_attendance.status')[$staffInputs['status']]) == 'absent') {
                        $toRemoveFetchAttendance = FetchAttendance::whereDate('punchin_datetime', $attendance_date_en)->where('staff_central_id', $staffCentralId);
                        $toRemoveFetchAttendance->update([
                            'status' => FetchAttendance::manualAttendance
                        ]);
                        $toRemoveFetchAttendance->delete();
                    } else {

                    }
                }
            }
            $status = true;
        } catch
        (\Exception $exception) {
//            dd($exception);
            $status = false;
//            DB::rollBack();
        }

        if ($status) {
//            DB::commit();
            return response()->json([
                'status' => $status,
                'message' => 'Manual Attendance Added successfully',
                'data' => null
            ]);
        } else {
            return response()->json([
                'status' => $status,
                'message' => 'Manual Attendance Error Occurred'
            ]);
        }
    }
}
