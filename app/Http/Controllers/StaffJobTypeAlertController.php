<?php

namespace App\Http\Controllers;

use App\FetchAttendance;
use App\Helpers\BSDateHelper;
use App\Repositories\SystemOfficeMastRepository;
use App\StaffSalaryModel;
use App\StafMainMastModel;
use App\SystemJobTypeMastModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class StaffJobTypeAlertController extends Controller
{
    private $systemOfficeMastRepository;

    public function __construct(
        SystemOfficeMastRepository $systemOfficeMastRepository
    )
    {
        $this->middleware("auth");
        $this->systemOfficeMastRepository = $systemOfficeMastRepository;
    }

    public function index()
    {
        return view('staff_jobtype_alert.index', [
            'title' => 'Filter Staff Job Type Alert',
            'job_type_alerts' => config('constants.job_alert_types'),
            'branches' => $this->systemOfficeMastRepository->retrieveAllBranchList()
        ]);
    }

    public function show(Request $request)
    {
        $request_job_alert_type = $request->job_alert_type;
        $request_branch_id = $request->branch_id;
        $request_staff_central_id = $request->staff_central_id;
        if (!empty($request_job_alert_type) && in_array($request_job_alert_type, array_keys(config('constants.job_alert_types')))) {
            if ($request_job_alert_type == 1) {
                return redirect()->route('staff-job-type-alert.non-permanent-to-permanent', [
                    'branch_id' => $request_branch_id,
                    'staff_central_id' => $request_staff_central_id
                ]);

            } elseif ($request_job_alert_type == 2) {
                return redirect()->route('staff-job-type-alert.age-limit', [
                    'branch_id' => $request_branch_id,
                    'staff_central_id' => $request_staff_central_id
                ]);
            } elseif ($request_job_alert_type == 3) {
                return redirect()->route('staff-job-type-alert.trainee-to-non-permanent', [
                    'branch_id' => $request_branch_id,
                    'staff_central_id' => $request_staff_central_id
                ]);
            }
        } else {
            return redirect()->route('staff-job-type-alert')->withErrors([
                'Alert Job Type Not Found'
            ]);
        }
    }

    public function nonPermanentToPermanent(Request $request)
    {
        $todayDate = Carbon::now();
        $total_working_days = 365 ?? config('constants.staff_permanent_promotion.minimum_work_days');
        $appointed_from_date = $todayDate->subDays($total_working_days);

        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $staffmains = StafMainMastModel::nonPermanentToPermanent()->withCount('fetchAttendances')->where('appo_date', '<=', $appointed_from_date)->orderBy('name_eng', 'asc');

        if (!empty($request->branch_id)) {
            $staffmains = $staffmains->where('branch_id', $request->branch_id);
        }

        if (!empty($request->staff_central_id)) {
            $staffmains = $staffmains->where('id', $request->staff_central_id);
        }

        $staffmains = $staffmains->paginate($records_per_page);


        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $records_per_page_options = Config::get('constants.records_per_page_options');


        return view('staff_jobtype_alert.non_permanent_to_permanent', [
            'title' => 'Non Permanent To Permanent Staff Alert',
            'staffmains' => $staffmains,
            'branches' => $branches,
            'records_per_page' => $records_per_page,
            'records_per_page_options' => $records_per_page_options,
        ]);
    }

    public function traineeToNonPermanent(Request $request)
    {
        $todayDate = Carbon::now();
        $total_working_days = config('constants.staff_trainee_to_non_permanent_promotion.minimum_work_days');
        $appointed_from_date = $todayDate->subDays($total_working_days);

        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $staffmains = StafMainMastModel::traineeToNonPermanent()->withCount('fetchAttendances')->where('appo_date', '<=', $appointed_from_date)->orderBy('name_eng', 'asc');

        if (!empty($request->branch_id)) {
            $staffmains = $staffmains->where('branch_id', $request->branch_id);
        }

        if (!empty($request->staff_central_id)) {
            $staffmains = $staffmains->where('id', $request->staff_central_id);
        }

        $staffmains = $staffmains->paginate($records_per_page);

        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $records_per_page_options = Config::get('constants.records_per_page_options');


        return view('staff_jobtype_alert.trainee_to_non_permanent', [
            'title' => 'Trainee To Non Permanent Alert',
            'staffmains' => $staffmains,
            'branches' => $branches,
            'records_per_page' => $records_per_page,
            'records_per_page_options' => $records_per_page_options,
        ]);
    }

    public function ageLimit(Request $request)
    {
        $yearsOld = config('constants.staff_above_age_promotion.maximum_age_in_years');
        $staffmains = StafMainMastModel::with(['workschedule', 'jobposition', 'jobtype', 'branch', 'shift'])->dateOfBirthOlderThanGivenNumberOfYearsOld($yearsOld)->where(function ($query) use ($request) {
            if (isset($request->staff_central_id)) {
                $query->where('id', $request->staff_central_id);
            }

            if (isset($request->job_type_id)) {
                $query->where('jobtype_id', $request->job_type_id);
            }

            if (isset($request->department_id)) {
                $query->where('department', $request->department_id);
            }

            if (isset($request->branch_id)) {
                $query->where('branch_id', $request->branch_id);
            }

            if (isset($request->designation_id)) {
                $query->where('post_id', $request->designation_id);
            }

            if (isset($request->shift_id)) {
                $query->where('shift_id', $request->shift_id);
            }

            $query->whereIn('staff_type', [0, 1]);
        });

        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $records_per_page_options = Config::get('constants.records_per_page_options');


        $staffmains = $staffmains->orderBy('name_eng', 'asc')->paginate($records_per_page);

        return view('staff_jobtype_alert.age_limit', [
            'title' => 'Age Limit Staff Alert',
            'staffmains' => $staffmains,
            'branches' => $branches,
            'records_per_page' => $records_per_page,
            'records_per_page_options' => $records_per_page_options,
        ]);
    }

    public function changeNonPermanentToPermanent(Request $request)
    {
        $permanentDate = $request->permanent_date;
        $staffCentralId = explode(',', $request->staff_central_id);

        if (empty($permanentDate)) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'Permanent Date Not Found'
            ]);
        }

        if (empty($staffCentralId)) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'Staff ID Not Provided'
            ]);
        }
        $staff = StafMainMastModel::whereIn('id', $staffCentralId);

        if ($staff->count() < 1) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'Staff Not Found'
            ]);
        }

        DB::beginTransaction();
        try {
            $staff->update([
                'permanent_date_np' => $permanentDate,
                'permanent_date' => BSDateHelper::BsToAd('-', $permanentDate),
                'jobtype_id' => 1,
            ]);

            DB::commit();
            $updateStatus = true;
        } catch (\Exception $exception) {
            $updateStatus = false;
            DB::rollBack();
        }
        if ($updateStatus) {
            return response()->json([
                'status' => 'true',
                'message' => 'Staff Permanent Date Updated Successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Problem Occurred during updating'
            ]);
        }

    }

    public function changeTraineeToNonPermanent(Request $request)
    {
        $nonPermanentDate = $request->non_permanent;
        $staffCentralId = explode(',', $request->staff_central_id);

        if (empty($nonPermanentDate)) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'Temporary Date Not Found'
            ]);
        }

        if (empty($staffCentralId)) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'Staff ID Not Provided'
            ]);
        }

        $staff = StafMainMastModel::whereIn('id', $staffCentralId);

        if ($staff->count() < 1) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'Staff Not Found'
            ]);
        }

        DB::beginTransaction();
        try {
            $staff->update([
                'temporary_con_date_np' => $nonPermanentDate,
                'temporary_con_date' => BSDateHelper::BsToAd('-', $nonPermanentDate),
                'jobtype_id' => 2,
            ]);

            DB::commit();
            $updateStatus = true;
        } catch (\Exception $exception) {
            $updateStatus = false;
            DB::rollBack();
        }
        if ($updateStatus) {
            return response()->json([
                'status' => 'true',
                'message' => 'Staff Temporary Date Updated Successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Problem Occurred during updating'
            ]);
        }

    }

    public function changeAboveAgeContract(Request $request)
    {
        $salary = $request->salary;
        $staffCentralId = explode(',', $request->staff_central_id);

        if (empty($salary)) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'Salary Not Provided'
            ]);
        }

        if ($salary <= 0) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'Please insert salary greater than 0'
            ]);
        }

        if (empty($staffCentralId)) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'Staff ID Not Provided'
            ]);
        }

        $staff = StafMainMastModel::whereIn('id', $staffCentralId);
        if ($staff->count() < 1) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'Staff Not Found'
            ]);
        }

        $bulkCreateInputs = [];

        foreach ($staffCentralId as $staffId) {
            $currentStaff = StafMainMastModel::where('id', $staffId)->first();

            if (!empty($currentStaff)) {
                $bulkCreateInputs[$staffId]['staff_central_id'] = $staffId;
                $bulkCreateInputs[$staffId]['post_id'] = $currentStaff->post_id;
                $bulkCreateInputs[$staffId]['basic_salary'] = $salary;
                $bulkCreateInputs[$staffId]['created_by'] = auth()->id();
                $bulkCreateInputs[$staffId]['created_at'] = Carbon::now();
            }
        }

        DB::beginTransaction();
        try {
            $staff->update([
                'jobtype_id' => 4,
            ]);

            StaffSalaryModel::insert($bulkCreateInputs);

            DB::commit();
            $updateStatus = true;
        } catch (\Exception $exception) {
            $updateStatus = false;
            DB::rollBack();
        }
        if ($updateStatus) {
            return response()->json([
                'status' => 'true',
                'message' => 'Salary Updated Successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Problem Occurred during updating'
            ]);
        }

    }


}
