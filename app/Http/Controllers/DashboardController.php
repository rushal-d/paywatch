<?php

namespace App\Http\Controllers;

use App\CalenderHolidayModel;
use App\FetchAttendance;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\PayrollConfirm;
use App\PayrollDetailModel;
use App\Repositories\SystemOfficeMastRepository;
use App\StaffTransferModel;
use App\StafMainMastModel;
use App\SystemHolidayMastModel;
use App\SystemOfficeMastModel;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    /**
     * @var SystemOfficeMastRepository
     */
    private $systemOfficeMastRepository;

    public function __construct(SystemOfficeMastRepository $systemOfficeMastRepository)
    {
        $this->middleware("auth");
        $this->systemOfficeMastRepository = $systemOfficeMastRepository;
    }

    public function index()
    {
        Config::set('role', true);
        $branches = SystemOfficeMastModel::all();
        $data['branchesCount'] = $branches->count();
        $data['branches'] = $this->systemOfficeMastRepository->retrieveAllBranchListWithPlaceHolder();
        $data['fiscal_year'] = FiscalYearModel::where('fiscal_status', 1)->first();
        $data['staff_count'] = StafMainMastModel::count();
        $data['user_count'] = User::count();
        $data['payroll'] = PayrollDetailModel::where('fiscal_year', $data['fiscal_year'])->get();
        $data['calender_holiday'] = CalenderHolidayModel::where('from_leave_day', '<=', date('Y-m-d'))->where('to_leave_day', '>=', date('Y-m-d'))->get();
        $user_branch_id = Auth::user()->branch_id;
        $data['public_holiday'] = SystemHolidayMastModel::where('from_date', '>', date('Y-m-d'));
        if (!empty($user_branch_id)) {
            $data['public_holiday'] = $data['public_holiday']->whereHas('branch', function ($query) use ($user_branch_id) {
                $query->where('branch_id', $user_branch_id);
            });
        }
        $data['public_holiday'] = $data['public_holiday']->first();
        $data['staffsPresentCount'] = FetchAttendance::whereDate('punchin_datetime', date('Y-m-d'))->distinct('staff_central_id')->count();
        $data['staffsAbsentCount'] = $data['staff_count'] - $data['staffsPresentCount'];

        $data['punch_out_warnings_yesterday'] = FetchAttendance::where('branch_id', $user_branch_id)->with('staff')->whereDate('punchin_datetime', date('Y-m-d', strtotime('-1 day')))->where(function ($query) {
            $query->where('total_work_hour', 0);
            $query->orWhere('total_work_hour', null);
        })->paginate(5);
        $data['transfer_out'] = StaffTransferModel::withoutGlobalScopes()->with(['office' => function ($query) {
            $query->withoutGlobalScopes();
        }, 'staff' => function ($query) {
            $query->withoutGlobalScopes();
        }])->whereNotNull('office_id')->orderBy('transfer_date', 'DESC')->where('office_from', $user_branch_id)->take(5)->get();
        $data['transfer_in'] = StaffTransferModel::withoutGlobalScopes()->with(['office_from_get' => function ($query) {
            $query->withoutGlobalScopes();
        }, 'staff' => function ($query) {
            $query->withoutGlobalScopes();
        }])->whereNotNull('office_id')->orderBy('transfer_date', 'DESC')->where('office_id', $user_branch_id)->take(5)->get();

        $data['recently_added_staffs'] = StafMainMastModel::orderBy('appo_date', 'desc')->take(5)->get();
        $nepali_date = BSDateHelper::AdToBs('-', date('Y-m-d'));
        $month = explode('-', $nepali_date)[1];
        $year = explode('-', $nepali_date)[0];
        $last_day = BSDateHelper::getLastDayByYearMonth((int)$year, (int)$month);
        $start_date = BSDateHelper::BsToAd('-', $year . '-' . $month . '-01');
        $end_date = date('Y-m-d');

        $interval = date_diff(date_create($start_date), date_create($end_date));
        $data['interval_days'] = (int)$interval->format('%a');

        $data['most_work_hours'] = FetchAttendance::where('branch_id', $user_branch_id)->whereHas('staff', function ($query) {
            $query->where('staff_status', 1);
        })->with(['staff'])->select('staff_central_id', DB::raw('sum(total_work_hour) as total_work_hour_sum'))
            ->whereDate('punchin_datetime', '>=', $start_date)->whereDate('punchin_datetime', '<=', $end_date)->groupBy('staff_central_id')->orderBy('total_work_hour_sum', 'desc')->take(5)->get();

        $data['most_absent_staffs'] = FetchAttendance::where('branch_id', $user_branch_id)->whereHas('staff', function ($query) {
            $query->where('staff_status', 1);
        })->with(['staff'])->select('staff_central_id', DB::raw('count(*) as present_count'))
            ->whereDate('punchin_datetime', '>=', $start_date)->whereDate('punchin_datetime', '<=', $end_date)->groupBy('staff_central_id')->orderBy('present_count')->take(5)->get();
        return view('dashboard.index', $data);
    }

    public function settings()
    {
        return redirect()->route('dashboard');
    }

    public function payroll_details()
    {
        $fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
        $month_names = Config::get('constants.month_name');
        foreach ($month_names as $month_num => $month_name) {
            $payroll_details = PayrollDetailModel::where('salary_month', $month_num)->where('fiscal_year', $fiscal_year->id)->first();
            if (!empty($payroll_details)) {
                $payroll_confirm = PayrollConfirm::where('payroll_id', $payroll_details->id)->get();
                if (!empty($payroll_confirm)) {
                    $details[$month_num] = $payroll_confirm->sum('net_payable');
                } else {
                    $details[$month_num] = 0;
                }
            } else {
                $details[$month_num] = 0;
            }
        }
        return response()->json(['data' => $details]);
    }

    public function getAttendanceDetailNumberOfStaffs(Request $request)
    {
        $staffs = StafMainMastModel::with('workschedule');

        $branchId = $request->branch_id;
        $today = Carbon::now();

        $staffsLeave = StafMainMastModel::whereHas('grantLeave', function ($query) use ($today) {
            $query->where('from_leave_day', '<=', $today)
                ->where('to_leave_day', '>=', $today);
        });

        if (!empty($branchId)) {
            $staffsLeave = $staffsLeave->where('branch_id', $branchId);
            $staffs = $staffs->where('branch_id', $branchId);
        }

        $staffs = $staffs->get();

        $staffsWeekendCount = 0;

        foreach ($staffs as $staff) {
            $weekend_on_this_date = $staff->workschedule->where('effect_day', '<=', $today)->last();
            if (!empty($weekend_on_this_date) && date('N', strtotime($today)) == $weekend_on_this_date->weekend_day) {
                $staffsWeekendCount++;
            }
        }

        $staffsLeaveCount = $staffsLeave->count();

        $data['staffsPresentCount'] = FetchAttendance::whereHas('branch', function ($query) use ($branchId) {
            if (!empty($branchId))
                $query->where('branch_id', $branchId);
        })->whereDate('punchin_datetime', $today->toDateString())->distinct('staff_central_id')->count();

        $data['staffsAbsentCount'] = $staffs->count() - $data['staffsPresentCount'];
        $data['staffsLeaveCount'] = $staffsLeaveCount;
        $data['staffsWeekendCount'] = $staffsWeekendCount;

        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
