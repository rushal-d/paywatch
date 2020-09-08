<?php

namespace App\Http\Controllers;

use App\Department;
use App\FetchAttendance;
use App\Helpers\BSDateHelper;
use App\Repositories\DepartmentRepository;
use App\Repositories\FetchAttendanceRepository;
use App\Repositories\FiscalYearRepository;
use App\Repositories\ShiftRepository;
use App\Repositories\SystemJobTypeMastRepository;
use App\Repositories\SystemOfficeMastRepository;
use App\Repositories\SystemPostMastRepository;
use App\StafMainMastModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkForceAttendanceController extends Controller
{
    /**
     * @var DepartmentRepository
     */
    private $departmentRepository;
    /**
     * @var SystemOfficeMastRepository
     */
    private $systemOfficeMastRepository;
    /**
     * @var FiscalYearRepository
     */
    private $fiscalYearRepository;
    /**
     * @var SystemJobTypeMastRepository
     */
    private $systemJobTypeMastRepository;
    private $fetchAttendanceRepository;
    private $systemPostMastRepository;
    private $shiftRepository;

    public function __construct(
        DepartmentRepository $departmentRepository,
        SystemOfficeMastRepository $systemOfficeMastRepository,
        FiscalYearRepository $fiscalYearRepository,
        SystemJobTypeMastRepository $systemJobTypeMastRepository,
        FetchAttendanceRepository $fetchAttendanceRepository,
        SystemPostMastRepository $systemPostMastRepository,
        ShiftRepository $shiftRepository
    )
    {
        $this->departmentRepository = $departmentRepository;
        $this->systemOfficeMastRepository = $systemOfficeMastRepository;
        $this->fiscalYearRepository = $fiscalYearRepository;
        $this->systemJobTypeMastRepository = $systemJobTypeMastRepository;
        $this->fetchAttendanceRepository = $fetchAttendanceRepository;
        $this->systemPostMastRepository = $systemPostMastRepository;
        $this->shiftRepository = $shiftRepository;
    }

    /**
     * It will display the forced attendance show page
     */
    public function show()
    {
        $title = 'Bulk Force Attendance';

        $departments = $this->departmentRepository->getAllDepartments();
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $fiscalYears = $this->fiscalYearRepository->getAllFiscalYears();
        $jobTypes = $this->systemJobTypeMastRepository->getAllJobTypes()->pluck('jobtype_name', 'jobtype_id');
        $currentFiscalYearId = $this->fiscalYearRepository->getCurrentFiscalYear()->pluck('id');
        $designations = $this->systemPostMastRepository->getAllPosts()->pluck('post_title', 'post_id');

        return view('bulkforceattendance.show',
            compact('title', 'departments', 'branches', 'fiscalYears', 'currentFiscalYearId', 'jobTypes', 'designations'));
    }

    public function filter(Request $request)
    {
        $title = 'Filter Bulk Attendance';
        $status = false;

        if (!isset($request->branch_id)) {
            return redirect()->route('bulk-force-show')
                ->with('flash', ['status' => $status, 'mesg' => 'Please select a branch']);
        }

        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $departments = $this->departmentRepository->getAllDepartments();
        $shifts = $this->shiftRepository->getAllShiftsByBranch($request->branch_id)->pluck('shift_name', 'id');
        $jobTypes = $this->systemJobTypeMastRepository->getAllJobTypes()->pluck('jobtype_name', 'jobtype_id');
        $designations = $this->systemPostMastRepository->getAllPosts()->pluck('post_title', 'post_id');

        $staffs = StafMainMastModel::with('branch');

        if (isset($request->job_type_id)) {
            $staffs->where('jobtype_id', $request->job_type_id);
        }

        if (isset($request->department_id)) {
            $staffs->where('department', $request->department_id);
        }

        if (isset($request->branch_id)) {
            $staffs->where('branch_id', $request->branch_id);
        }

        if (isset($request->designation_id)) {
            $staffs->where('post_id', $request->designation_id);
        }

        if (isset($request->shift_id)) {
            $staffs->where('shift_id', $request->shift_id);
        }
        if ($staffs->count() > 50) {
            $breakCount = (ceil($staffs->count() / 3));
        } else {
            $breakCount = $staffs->count();
        }

        $staffs = $staffs->get();
        $increment = 1;

        return view('bulkforceattendance.filter', compact('staffs', 'branches', 'jobTypes', 'designations', 'shifts', 'departments', 'breakCount', 'title', 'increment', 'breakCount'));
    }

    public function filterView(Request $request)
    {
        $staff_central_ids = request('staff_central_ids');
        $branch_id = request('branch_id');

        if (isset($staff_central_ids) && count($staff_central_ids) > 0) {
            $staffs = StafMainMastModel::with(['fetchAttendances' => function ($query) {
                $query->whereDate('punchin_datetime', request('from_date'));
            }])->whereIn('id', $staff_central_ids)->get();


            $from_date_np = $request->from_date_np;
            $from_date = $request->from_date;

            $returnHtml = view('bulkforceattendance.filter-view', compact('staffs', 'from_date_np', 'branch_id', 'from_date'))->render();

            return response()->json(['status' => 'true', 'html' => $returnHtml]);
        }

    }


    /**
     * This will store all the forced attendance with different inputs fields.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $status = false;
        if (is_null($request->staffs)) {
            return redirect()->route('bulk-force-show-filter')
                ->with('flash', ['status' => $status, 'mesg' => 'Please select a staff']);
        }

        $attendance_date_np = $request->attendance_date_np;
        $attendance_date = BSDateHelper::BsToAd('-', $request->attendance_date_np);

        $branch_id = $request->branch_id;
        //        $attendance_date_np

        $staffInputs = $request->staffs;

        $staffCentralIds = array_keys($staffInputs);

        $staffWithPreviousDetails = FetchAttendance::whereIn('staff_central_id', $staffCentralIds)
            ->where('branch_id', $branch_id)
            ->whereDate('punchin_datetime', $attendance_date);

        $staffCentralIdWithPreviousDetails = $staffWithPreviousDetails->pluck('staff_central_id');

        $staffCentralIdWithNoPreviousDetails = array_diff($staffCentralIds, $staffCentralIdWithPreviousDetails->toArray());

        try {
            DB::beginTransaction();
            foreach ($staffInputs as $staffCentralId => $staffInput) {
                $inputs = $this->fetchAttendanceRepository->getInputsForDateTimeFields($staffInput);
                $inputs['status'] = FetchAttendance::forceLeave;
                $inputs['branch_id'] = $branch_id;
                $inputs['total_work_hour'] = 0;
                $inputs['sync'] = FetchAttendance::sync;
                //init array here to avoid last value of personalIn, personalOut when prev value is there
                $datesArray = [];

                if (!empty($inputs['personalin_datetime_np'])) {
                    $datesArray['personalIn'] = Carbon::parse($inputs['personalin_datetime_np']);
                }

                if (!empty($inputs['personalout_datetime_np'])) {
                    $datesArray['personalOut'] = Carbon::parse($inputs['personalout_datetime_np']);
                }

                if (!empty($inputs['punchout_datetime']) && !empty($inputs['punchin_datetime'])) {
                    $datesArray['punchOut'] = $inputs['punchout_datetime'];
                    $datesArray['punchIn'] = $inputs['punchin_datetime'];
                    $inputs['total_work_hour'] = $this->fetchAttendanceRepository->calculateTotalHoursWork($datesArray);
                }
                if ($this->fetchAttendanceRepository->hasPreviousFetchAttendance($staffCentralId, $staffCentralIdWithPreviousDetails->toArray())) {
                    $inputs['updated_at'] = Carbon::now();
                    $inputs['updated_by'] = auth()->id();
                    $previousFetchAttendance = FetchAttendance::whereDate('punchin_datetime', $attendance_date)->where('staff_central_id', $staffCentralId);

                    if ((empty($previousFetchAttendance->first()->remarks) || $previousFetchAttendance->first()->remarks == '' || trim($previousFetchAttendance->first()->remarks) == '') && (empty($staffInput['remarks']) || $staffInput['remarks'] == '' || trim($staffInput['remarks']) == '')) {
                        $inputs['remarks'] = '';
                    } else if (empty($staffInput['remarks']) || $staffInput['remarks'] == '' || trim($staffInput['remarks']) == '') {
                        $inputs['remarks'] = $previousFetchAttendance->first()->remarks;
                    } else if (empty($previousFetchAttendance->first()->remarks) || $previousFetchAttendance->first()->remarks == '' || trim($previousFetchAttendance->first()->remarks) == '') {
                        $inputs['remarks'] = $staffInput['remarks'];
                    } else {
                        $inputs['remarks'] = $previousFetchAttendance->first()->remarks . '<br>' . $staffInput['remarks'];
                    }

                    FetchAttendance::whereDate('punchin_datetime', $attendance_date)->where('staff_central_id', $staffCentralId)->update($inputs);
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
                    $inputs['is_force'] = 1;
                    FetchAttendance::insert($inputs);
                }
            }
            $status = true;
        } catch (\Exception $e) {
            DB::rollBack();
        }

        if ($status) {
            DB::commit();
            return redirect()->route('bulk-force-show')->with('flash', [
                'status' => $status,
                'mesg' => 'Bulk Force Attendance complete!'
            ]);
        }

    }

}
