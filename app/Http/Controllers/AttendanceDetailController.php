<?php

namespace App\Http\Controllers;

use App\AllowanceModelMast;
use App\AttendanceDetailModel;
use App\AttendanceDetailSumModel;
use App\CalenderHolidayModel;
use App\CalenderHolidaySplitMonth;
use App\CitLedger;
use App\EmployeeStatus;
use App\FetchAttendance;
use App\FiscalYearModel;
use App\Helpers\AppUtils;
use App\Helpers\BSDateHelper;
use App\HouseLoanModelMast;
use App\HouseLoanTransactionLog;
use App\LeaveBalance;
use App\LeavePayrollConfirm;
use App\LoanDeduct;
use App\OrganizationSetup;
use App\PayrollCalculationData;
use App\PayrollConfirm;
use App\PayrollConfirmAllowance;
use App\PayrollConfirmLeaveInfo;
use App\PayrollDetailModel;
use App\ProFund;
use App\Repositories\TdsRepository;
use App\SocialSecurityTaxStatement;
use App\StaffSalaryMastModel;
use App\StaffTransferModel;
use App\StaffWorkScheduleMastModel;
use App\StafMainMastModel;
use App\SundryBalance;
use App\SundryTransaction;
use App\SundryTransactionLog;
use App\SundryType;
use App\SystemHolidayMastModel;
use App\SystemJobTypeMastModel;
use App\SystemLeaveMastModel;
use App\SystemOfficeMastModel;
use App\SystemPostMastModel;
use App\SystemTdsMastModel;
use App\TaxStatement;
use App\Traits\PayrollCalculate;
use App\Traits\PayrollStaff;
use App\TransBankStatement;
use App\TransCashStatement;
use App\User;
use App\VehicalLoanModelTrans;
use App\VehicleLoanTransactionLog;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

ini_set('max_execution_time', 1800);//5 Min
ini_set('memory_limit', -1);

class AttendanceDetailController extends Controller
{
    use \App\Traits\AppUtils, PayrollCalculate, PayrollStaff;

    private $dates = array(
        0 => array(2000, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        1 => array(2001, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        2 => array(2002, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        3 => array(2003, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        4 => array(2004, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        5 => array(2005, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        6 => array(2006, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        7 => array(2007, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        8 => array(2008, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31),
        9 => array(2009, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        10 => array(2010, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        11 => array(2011, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        12 => array(2012, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
        13 => array(2013, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        14 => array(2014, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        15 => array(2015, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        16 => array(2016, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
        17 => array(2017, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        18 => array(2018, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        19 => array(2019, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        20 => array(2020, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
        21 => array(2021, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        22 => array(2022, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
        23 => array(2023, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        24 => array(2024, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
        25 => array(2025, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        26 => array(2026, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        27 => array(2027, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        28 => array(2028, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        29 => array(2029, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30),
        30 => array(2030, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        31 => array(2031, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        32 => array(2032, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        33 => array(2033, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        34 => array(2034, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        35 => array(2035, 30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31),
        36 => array(2036, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        37 => array(2037, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        38 => array(2038, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        39 => array(2039, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
        40 => array(2040, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        41 => array(2041, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        42 => array(2042, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        43 => array(2043, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
        44 => array(2044, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        45 => array(2045, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        46 => array(2046, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        47 => array(2047, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
        48 => array(2048, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        49 => array(2049, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
        50 => array(2050, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        51 => array(2051, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
        52 => array(2052, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        53 => array(2053, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
        54 => array(2054, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        55 => array(2055, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        56 => array(2056, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30),
        57 => array(2057, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        58 => array(2058, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        59 => array(2059, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        60 => array(2060, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        61 => array(2061, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        62 => array(2062, 30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31),
        63 => array(2063, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        64 => array(2064, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        65 => array(2065, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        66 => array(2066, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31),
        67 => array(2067, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        68 => array(2068, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        69 => array(2069, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        70 => array(2070, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
        71 => array(2071, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        72 => array(2072, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        73 => array(2073, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        74 => array(2074, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
        75 => array(2075, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        76 => array(2076, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
        77 => array(2077, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        78 => array(2078, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
        79 => array(2079, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        80 => array(2080, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
        81 => array(2081, 31, 31, 32, 32, 31, 30, 30, 30, 29, 30, 30, 30),
        82 => array(2082, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30),
        83 => array(2083, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30),
        84 => array(2084, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30),
        85 => array(2085, 31, 32, 31, 32, 30, 31, 30, 30, 29, 30, 30, 30),
        86 => array(2086, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30),
        87 => array(2087, 31, 31, 32, 31, 31, 31, 30, 30, 29, 30, 30, 30),
        88 => array(2088, 30, 31, 32, 32, 30, 31, 30, 30, 29, 30, 30, 30),
        89 => array(2089, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30),
        90 => array(2090, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30));
    /**
     * @var TdsRepository
     */
    private $tdsRepository;

    /**
     * Display a listing of the resource.
     *
     * @param TdsRepository $tdsRepository
     */

    public function __construct(TdsRepository $tdsRepository)
    {
        $this->systemtdsmastmodel = SystemTdsMastModel::get();
        $this->tdsRepository = $tdsRepository;
    }

    public function payroll()
    {
        $branch = SystemOfficeMastModel::select('office_id', 'office_name', 'office_location')->get();
        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
        $months = Config::get('constants.month_name');
        $organization = OrganizationSetup::first();
        return view('attendancedetail.create',
            [
                'branch' => $branch,
                'fiscal_years' => $fiscal_years,
                'current_fiscal_year_id' => $current_fiscal_year_id,
                'months' => $months,
                'organization' => $organization,
                'title' => 'Add Information'
            ]);
    }

    public function warningBeforePayroll(Request $request)
    {
        $branch_id = $request->branch_id;
        $date_from_np = $request->from_date_np;
        $date_to_np = $request->to_date_np;
        $date_from = BSDateHelper::BsToAd('-', $date_from_np);
        $date_to = BSDateHelper::BsToAd('-', $date_to_np);

        $staffs = $this->getPayrollStaffs($branch_id, null, $date_to_np, $date_to_np);
        $punchoutWarningStaffs = StafMainMastModel::withAndWhereHas('fetchAttendances', function ($query) use ($date_from, $date_to, $branch_id) {
            if (!empty($date_from)) {
                $query->whereDate('punchin_datetime', '>=', $date_from);
            }
            if (!empty($date_to)) {
                $query->whereDate('punchin_datetime', '<=', $date_to);
            }
            if (!empty($branch_id)) {
                $query->where('branch_id', $branch_id);
            }

            $query->where('punchout_datetime', null);
        })->whereIn('id', $staffs->pluck('id')->toArray());
        $punchoutWarningStaffs = $punchoutWarningStaffs->get()->sortByDesc(function ($query) {
            return $query->fetchAttendances->count();
        });

        $noPresentStaff = StafMainMastModel::whereDoesntHave('fetchAttendances', function ($query) use ($date_from, $date_to, $branch_id) {
            if (!empty($date_from)) {
                $query->whereDate('punchin_datetime', '>=', $date_from);
            }
            if (!empty($date_to)) {
                $query->whereDate('punchin_datetime', '<=', $date_to);
            }
        })->whereIn('id', $staffs->pluck('id')->toArray())->get();

        return response()->json([
            'punchoutwarning' => $punchoutWarningStaffs,
            'noPresentStaff' => $noPresentStaff,
        ]);
    }

    public function payrollCreate(Request $request)
    {
        $branch_id = $request->branch_id;
        $date_from_np = $request->from_date_np;
        $date_to_np = $request->to_date_np;

        $date_from = BSDateHelper::BsToAd('-', $date_from_np);
        $date_to = BSDateHelper::BsToAd('-', $date_to_np);
        $total_days = date_diff(date_create($date_from), (date_create($date_to)))->format("%a") + 1; //for nepali day +1 should be there so date from day is also there
        $payrollFiscalYear = FiscalYearModel::where('fiscal_start_date', '<=', $date_from)->where('fiscal_end_date', '>=', $date_to)->first();
        if (!empty($payrollFiscalYear)) {
            $currentFiscalYear = $payrollFiscalYear->id;
        } else {
            //date from and date to must be from the same fiscal year
            $status = false;
            $mesg = 'Date Range Must be from the same fiscal year!';
            return response()->json([
                'status' => $status,
                'mesg' => $mesg,
            ]);
        }


        $fiscal_year = $currentFiscalYear;
        $salary_month = $request->salary_month;

        //check if the same payroll is already created
        $check_if_already_created = PayrollDetailModel::where('branch_id', $branch_id)->where('from_date', '<=', $date_from)->where('to_date', '>=', $date_to)->where('has_bonus', null)->first();
        //show already created message
        if (!empty($check_if_already_created)) {
            $status = false;
            $mesg = 'Payroll of this branch for this duration is already created! Please check the information correctly!';
            return response()->json([
                'status' => $status,
                'mesg' => $mesg,
            ]);
        }
        $branch = SystemOfficeMastModel::find($branch_id);
        $months = Config::get('constants.month_name');
        $payrolldetail = new PayrollDetailModel();
        $payrolldetail->branch_id = $branch_id;
        $payrolldetail->payroll_name = $payrollFiscalYear->fiscal_code . '-' . $months[$salary_month] . '-' . $branch->office_name;
        $payrolldetail->salary_month = $salary_month;
        $payrolldetail->fiscal_year = $fiscal_year;
        $payrolldetail->prepared_by = Auth::id();


        //get all public holidays
        $public_holidays = SystemHolidayMastModel::with('branch')->where('fy_year', $currentFiscalYear)->get();
        $total_public_holidays = 0;
        $date_from_start = $date_from;
        //get total holidays
        for ($d = 1; $d <= $total_days; $d++) {
            $is_holiday = ($this->checkIfPublicHoliday($date_from_start, $public_holidays, null, $branch_id)) ? true : false;
            if ($is_holiday) {
                $total_public_holidays++;
            }
            $date_from_start = date('Y-m-d', strtotime('+1 day', strtotime($date_from_start))); //adding 1 day to from date
        }
        $payrolldetail->total_days = $total_days;
        $payrolldetail->from_date_np = $date_from_np;
        $payrolldetail->to_date_np = $date_to_np;
        $payrolldetail->to_date = $date_to;
        $payrolldetail->from_date = $date_from;
        $payrolldetail->total_public_holidays = $total_public_holidays;
        $status = $payrolldetail->save();
        $mesg = ($status) ? 'Payroll Created Successfully' : 'Error Occured! Try Again!';
        return response()->json([
            'status' => $status,
            'mesg' => $mesg,
            'payroll' => $payrolldetail,

        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $fiscalyear = FiscalYearModel::select('id', 'fiscal_code')->get();
        $branch = SystemOfficeMastModel::select('office_id', 'office_name', 'office_location')->get();
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        DB::enableQueryLog();
        $model = new PayrollDetailModel();

        //check if has month from filter
        if ($request->has('month_id') && !empty($request->month_id)) {
            $model = $model->where('salary_month', $request->month_id);
        }

        //check if has Branch
//          dd($request->branch_id);
        if ($request->has('branch_id') && !empty($request->branch_id)) {
            $model = $model->where('branch_id', $request->branch_id);

        }


        //check if has Fiscal Year
        if ($request->has('fiscal_year') && !empty($request->fiscal_year)) {

            $model = $model->where('fiscal_year', $request->fiscal_year);
        }

        $month_names = Config::get('constants.month_name');
        $attendance = $model->search($search_term)->paginate($records_per_page);
        $users = User::all();
        $records_per_page_options = Config::get('constants.records_per_page_options');

        return view('attendancedetail.index', [
            'title' => 'Attendance Detail',
            'attendance' => $attendance,
            'users' => $users,
            'branch' => $branch,
            'fiscalyear' => $fiscalyear,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'month_names' => $month_names
        ]);
    }

    public function index(Request $request)
    {
        $fiscalyear = FiscalYearModel::select('id', 'fiscal_code')->get();
        $branch = SystemOfficeMastModel::select('office_id', 'office_name', 'office_location')->get();
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $attendance = PayrollDetailModel::search($search_term)->latest()->paginate($records_per_page);
        $month_names = Config::get('constants.month_name');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $users = User::all();
        return view('attendancedetail.index', [
            'title' => 'Attendance Detail',
            'attendance' => $attendance,
            'users' => $users,
            'branch' => $branch,
            'fiscalyear' => $fiscalyear,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'month_names' => $month_names
        ]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'branch_id' => 'required',
        ],
            [
                'branch_id.required' => 'You must select branch!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('attendance-detail-payroll')
                ->withInput()
                ->withErrors($validator);
        } else {
            $branch_id = $request->branch_id;
            $date_from_np = $request->from_date_np;
            $date_to_np = $request->to_date_np;

            $date_from = BSDateHelper::BsToAd('-', $date_from_np);
            $date_to = BSDateHelper::BsToAd('-', $date_to_np);
            $total_days = date_diff(date_create($date_from), (date_create($date_to)))->format("%a") + 1; //for nepali day +1 should be there so date from day is also there
            $fiscal_year = $request->fiscal_year;
            $salary_month = $request->salary_month;

            //check if the same payroll is already created
            $check_if_already_created = PayrollDetailModel::where('branch_id', $branch_id)->where('from_date', '<=', $date_from)->where('to_date', '>=', $date_to)->first();
            //show already created message
            if (!empty($check_if_already_created)) {
                $status = false;
                $mesg = 'Payroll of this branch for this duration is already created! Please check the information correctly!';
                return redirect()->route('attendance-detail-payroll')->with('flash', array('status' => $status, 'mesg' => $mesg));
            }
            //start transaction to save the data
            try {
                DB::beginTransaction();
                $payrolldetail = new PayrollDetailModel();
                $payrolldetail->branch_id = $branch_id;
                //start transaction for rolling back if some problem occurs
                $payrolldetail->salary_month = $salary_month;
                $payrolldetail->fiscal_year = $fiscal_year;

                //get all public holidays
                $currentFiscalYear = FiscalYearModel::IsActiveFiscalYear()->value('id');
                $public_holidays = SystemHolidayMastModel::with('branch')->where('fy_year', $currentFiscalYear)->get();
                $total_public_holidays = 0;
                $date_from_start = $date_from;
                //get total holidays
                for ($d = 1; $d <= $total_days; $d++) {
                    $is_holiday = ($this->checkIfPublicHoliday($date_from_start, $public_holidays)) ? true : false;
                    if ($is_holiday) {
                        $total_public_holidays++;
                    }
                    $date_from_start = date('Y-m-d', strtotime('+1 day', strtotime($date_from_start))); //adding 1 day to from date
                }
                $payrolldetail->total_days = $total_days;
                $payrolldetail->from_date_np = $date_from_np;
                $payrolldetail->to_date_np = $date_to_np;
                $payrolldetail->to_date = $date_to;
                $payrolldetail->from_date = $date_from;
                $payrolldetail->total_public_holidays = $total_public_holidays;
                $payrolldetail->save();
                $branch_id = $payrolldetail->branch_id;
                $payroll_id = $payrolldetail->id;
                /*excel import start */
                if ($request->file('imported-file')) {

                    $path = $request->file('imported-file')->getRealPath();
                    //select first sheet only
                    $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                        $reader->ignoreEmpty();
                    })->get();

                    if (!empty($data) && $data->count()) {
                        foreach ($data->toArray() as $row) {
                            if (!empty($row)) {
                                //check if last row of the file
                                if (($row['staff_id'] == 'END' || $row['staff_id'] == 'end' || $row['staff_id'] == 'End')) {
                                    break;
                                }
                                if (empty($row['total_work_hour'])) {
                                    $row['total_work_hour'] = null;
                                }
                                $dataArray[] =
                                    [
                                        'payroll_id' => $payroll_id,
                                        'staff_central_id' => $row['staff_id'],
                                        'date' => $row['date'],
                                        'total_work_hour' => $row['total_work_hour']
                                    ];
                            }
                        }
                        if (!empty($dataArray)) {
                            $row = 2;
                            foreach ($dataArray as $index => $attendance_data) {

                                $staff_central_id = $attendance_data['staff_central_id'];

                                //check valid staff
                                if (!$this->isValidStaffID($staff_central_id, $branch_id)) {
                                    $status = false;
                                    $mesg = 'Not a Valid Staff ID ' . $staff_central_id . ' on row ' . $row . ' on column 1 ! Staff may be of different branch!';
                                    return redirect()->route('attendance-detail-payroll')->with('flash', array('status' => $status, 'mesg' => $mesg));
                                }

                                $attendance_date = $attendance_data['date']->format('Y/m/d');
                                $payroll_date = BSDateHelper::BsToAd('/', $attendance_date);
                                //check if excel data matches with inputted date from and to
                                $date_from_payroll_start = $date_from;
                                //iterate date from to day to
                                $valid_date_in_excel = false;
                                for ($d = 1; $d <= $total_days; $d++) {
                                    if (date('Y-m-d', strtotime($date_from_payroll_start)) == date('Y-m-d', strtotime($payroll_date))) {
                                        $valid_date_in_excel = true;
                                        break;
                                    }

                                    $date_from_payroll_start = date('Y-m-d', strtotime('+1 day', strtotime($date_from_payroll_start))); //adding 1 day to from date
                                }

                                if (!$valid_date_in_excel) { //invalid date
                                    $status = false;
                                    $mesg = 'Invalid date ' . $attendance_date . ' on row ' . $row . ' on column 2 ' . ' of staff id ' . $staff_central_id . '. <br> '
                                        . ' Please check if date in excel and payroll date matches!';
                                    return redirect()->route('attendance-detail-payroll')->with('flash', array('status' => $status, 'mesg' => $mesg));
                                }

                                $staff_workschedule = StaffWorkScheduleMastModel::where('staff_central_id', $staff_central_id)->first();
                                //now save the attendance details to db
                                $attendance_detail_model = new AttendanceDetailModel();
                                $attendance_detail_model->staff_central_id = $staff_central_id;
                                $attendance_detail_model->payroll_id = $payroll_id;
                                $attendance_detail_model->date = $payroll_date;
                                $attendance_detail_model->date_np = $attendance_date;
                                $attendance_detail_model->weekend_holiday = ($this->checkIfWeekendDay($attendance_detail_model->date, $staff_workschedule->weekend_day)) ? 1 : 0;
                                $attendance_detail_model->public_holiday = ($this->checkIfPublicHoliday($attendance_detail_model->date, $public_holidays)) ? 1 : 0;
                                $attendance_detail_model->total_work_hour = $attendance_data['total_work_hour'];
                                //calculate OT
                                $total_OT = 0;
                                if ($attendance_detail_model->total_work_hour > $staff_workschedule->work_hour) {
                                    $total_OT = $attendance_detail_model->total_work_hour - $staff_workschedule->work_hour;
                                }
                                $attendance_detail_model->total_ot_hour = $total_OT;

                                $attendance_detail_model->status = (empty($attendance_detail_model->total_work_hour)) ? 0 : 1; //0 is absent and 1 is present
                                $attendance_detail_model->save();
                                $row++;

                            }

                            //when single data are stored in db then also calculate the sum for each staff attendance details
                            $staff_this_payroll = AttendanceDetailModel::selectRaw('
								SUM(weekend_holiday) as weekend_holidays,
								SUM(public_holiday) as public_holidays,
								SUM(total_work_hour) as total_work_hours,
								SUM(total_ot_hour) as total_ot_hours,
								staff_central_id
								')->where('payroll_id', $payroll_id)->groupBy('staff_central_id')->get();


                            foreach ($staff_this_payroll as $staff) {
                                $attendance_detail_sum_model = new AttendanceDetailSumModel();
                                $attendance_detail_sum_model->staff_central_id = $staff->staff_central_id;
                                $attendance_detail_sum_model->payroll_id = $payroll_id;
                                $attendance_detail_sum_model->branch_id = $branch_id;
                                $attendance_detail_sum_model->date = date('Y-m-d');
                                $attendance_detail_sum_model->date_np = BSDateHelper::AdToBs('-', $attendance_detail_sum_model->date);
                                $attendance_detail_sum_model->weekend_holiday = $staff->weekend_holidays;
                                $attendance_detail_sum_model->public_holiday = $staff->public_holidays;
                                $attendance_detail_sum_model->total_work_hour = $staff->total_work_hours;
                                $attendance_detail_sum_model->total_ot_hour = $staff->total_ot_hours;

                                $weekend_holidays = AttendanceDetailModel::where('staff_central_id', $staff->staff_central_id)->where('weekend_holiday', 1)->where('status', 1)->get();
                                $public_holidays = AttendanceDetailModel::where('staff_central_id', $staff->staff_central_id)->where('public_holiday', 1)->where('status', 1)->get();
                                $weekend_holiday_hour = 0;
                                $public_holiday_hour = 0;
                                $min_work_hour = StaffWorkScheduleMastModel::where('staff_central_id', $staff->staff_central_id)->latest()->first()->work_hour;
                                foreach ($weekend_holidays as $weekend_holiday) {
                                    if (!empty($weekend_holiday->total_work_hour)) {
                                        if ($weekend_holiday->total_work_hour > $min_work_hour) {
                                            $weekend_holiday_hour = $weekend_holiday_hour + $min_work_hour;
                                        } else {
                                            $weekend_holiday_hour = $weekend_holiday_hour + $weekend_holiday->total_work_hour;
                                        }
                                    }
                                }
                                foreach ($public_holidays as $public_holiday) {
                                    if (!empty($public_holiday->total_work_hour)) {
                                        if ($public_holiday->total_work_hour > $min_work_hour) {
                                            $public_holiday_hour = $public_holiday_hour + $min_work_hour;
                                        } else {
                                            $public_holiday_hour = $public_holiday_hour + $public_holiday->total_work_hour;
                                        }
                                    }
                                }

                                $attendance_detail_sum_model->weekend_holiday_hours = $weekend_holiday_hour;
                                $attendance_detail_sum_model->public_holiday_hours = $public_holiday_hour;

                                $attendance_detail_sum_model->save();

                            }
                            $status_mesg = true;
                        }
                    }
                }

            } catch
            (Exception $e) {
                DB::rollBack();
                $status_mesg = false;
            }
        }
        if ($status_mesg) {
            //also upload the file to server
            if ($request->hasFile('imported-file')) {
                $file = $request->file('imported-file');
                $payroll_upload_dir = public_path(Config::get('constants.payroll_upload_dir'));
                if (!is_dir($payroll_upload_dir)) {
                    mkdir($payroll_upload_dir, 0755, true);
                }

                $file->move($payroll_upload_dir, $file->getClientOriginalName());
                $payrolldetail = PayrollDetailModel::find($payroll_id);
                $payrolldetail->payroll_file = Config::get('constants.payroll_files') . $file->getClientOriginalName();
                $payrolldetail->save();
                DB::commit();
            }
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Imported Successfully' : 'Error Occured! Try Again!';

        return redirect()->route('attendance-action', $payroll_id)->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    public function fetchAttendanceToAttendanceDetail(Request $request)
    {
        ini_set('max_execution_time', 3 * 180);
        try {
            $leaves = SystemLeaveMastModel::get();
            $total_sick_leave_from_system = $leaves->where('leave_code', 4)->first()->no_of_days ?? 15;
            $total_home_leave_from_system = $leaves->where('leave_code', 3)->first()->no_of_days ?? 18;

            $earnable_home_leave = $total_home_leave_from_system / 12;
            $earnable_sick_leave = $total_sick_leave_from_system / 12;

            $staff_central_ids = array_column($request->staff_central_ids, 'id');
            $branch_id = $request->branch_id;

            $branch = SystemOfficeMastModel::find($branch_id);

            $payroll_id = $request->payroll_id;
            $payroll = PayrollDetailModel::find($payroll_id);
            $systemtdsmastmodel = SystemTdsMastModel::get();

            $date_from_np = $request->from_date_np;
            $date_to_np = $request->to_date_np;
            if (empty($request->from_date_np) || empty($request->to_date_np)) {
                $date_from_np = $payroll->from_date_np;
                $date_to_np = $payroll->to_date_np;
            }
            $original_date_from = $date_from = BSDateHelper::BsToAd('-', $date_from_np);
            $date_to = BSDateHelper::BsToAd('-', $date_to_np);

            $total_days_in_nepali_month = round(((strtotime($date_to) - strtotime($date_from)) / (60 * 60 * 24))) + 1;

            $this_month_first_en = $original_date_from;
            $this_month_last_en = $date_to;

            //get payroll staff collection
            $staffs = $this->getPayrollStaffCollection($this_month_first_en, $this_month_last_en, $payroll, $staff_central_ids);
            $fetch_attendances = FetchAttendance::whereIn('staff_central_id', $staff_central_ids)->where('punchin_datetime', '>=', $date_from . ' 00:00:00')
                ->where('punchin_datetime', '<=', $date_to . ' 23:59:00')->get();
            $public_holidays = SystemHolidayMastModel::with('branch')->where('fy_year', $payroll->fiscalyear->id)->get();

            AttendanceDetailModel::whereIn('staff_central_id', $staffs->pluck('id')->toArray())->where('date', '>=', $date_from . ' 00:00:00')
                ->where('date', '<=', $date_to . ' 23:59:00')->delete();
            AttendanceDetailSumModel::whereIn('staff_central_id', $staffs->pluck('id')->toArray())->where('payroll_id', $payroll_id)->delete();
            $attendance_detail_bulk = [];
            while (strtotime($date_from) <= strtotime($date_to)) {
                foreach ($staffs as $staff_id) {
                    //startf
                    $staff_workschedule = $staff_id->workschedule->where('effect_day', '<=', $date_from)->last();
                    if (empty($staff_workschedule)) {
                        $staff_workschedule = $staff_id->workschedule->last();
                    }
                    $local_attendance = $fetch_attendances->where('staff_central_id', $staff_id->id)->where('punchin_datetime', '>', $date_from . ' 00:00:00')
                        ->where('punchin_datetime', '<', $date_from . ' 23:59:00')->first();


                    $attendance_detail = [];
                    $attendance_detail['payroll_id'] = $payroll_id;
                    $attendance_detail['staff_central_id'] = $staff_id->id;
                    $attendance_detail['date'] = $date_from;
                    $attendance_detail['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($date_from)));
                    $attendance_detail['suspense'] = 0;
                    $attendance_detail['leave_id'] = null;
                    $checkResignDays = $staff_id->staffStatus->whereNotIn('status', [EmployeeStatus::STATUS_WORKING, EmployeeStatus::STATUS_SUSPENSE])->where('date_from', '<=', $date_from);

                    if ($checkResignDays->count() > 0) {
                        $attendance_detail['total_work_hour'] = 0;
                        $attendance_detail['weekend_holiday'] = 0;
                        $attendance_detail['public_holiday'] = 0;
                        $attendance_detail['total_ot_hour'] = 0;
                        $attendance_detail['status'] = 0;
                        $attendance_detail['weekend_holiday'] = 0;
                        $attendance_detail['suspense'] = 2;
                        $attendance_detail['created_at'] = Carbon::now();
                        $attendance_detail['updated_at'] = Carbon::now();
                        $attendance_detail_bulk[] = $attendance_detail;
                        continue;
                    }

                    $checkSuspenseDays = $staff_id->staffStatus->where('status', EmployeeStatus::STATUS_SUSPENSE)->where('date_from', '<=', $date_from);

                    if ($checkSuspenseDays->count() > 0) {
                        $attendance_detail['total_work_hour'] = 0;
                        $attendance_detail['weekend_holiday'] = 0;
                        $attendance_detail['public_holiday'] = 0;
                        $attendance_detail['total_ot_hour'] = 0;
                        $attendance_detail['status'] = 0;
                        $attendance_detail['weekend_holiday'] = 0;
                        $attendance_detail['suspense'] = 1;
                        $attendance_detail['created_at'] = Carbon::now();
                        $attendance_detail['updated_at'] = Carbon::now();
                        $attendance_detail_bulk[] = $attendance_detail;
                        continue;
                    }

                    $attendance_detail['total_work_hour'] = empty($local_attendance->total_work_hour) ? 0 : $local_attendance->total_work_hour;
                    if ($staff_id->is_holding == 1) {
                        if ($attendance_detail['total_work_hour'] > 8) {
                            $attendance_detail['total_work_hour'] = 8;
                        }
                    }
                    $total_OT = 0;
                    if ($attendance_detail['total_work_hour'] > $staff_workschedule->work_hour) {
                        $total_OT = $attendance_detail['total_work_hour'] - $staff_workschedule->work_hour;
                    }
                    $attendance_detail['total_ot_hour'] = $total_OT;
                    $attendance_detail['status'] = empty($local_attendance->punchin_datetime) ? 0 : 1; //0 is absent and 1 is present

                    //if not approved then check if public holiday or weekend else do not check if public holiday or weekend
                    if (empty($this->checkifApprovedLeave($date_from, $staff_id->grantLeave))) {
                        $attendance_detail['weekend_holiday'] = (($this->checkIfWeekendDay($date_from, $staff_workschedule->weekend_day))) ? 1 : 0;
                        $attendance_detail['public_holiday'] = (($this->checkIfPublicHoliday($date_from, $public_holidays, $staff_id))) ? 1 : 0;
                        if ($attendance_detail['weekend_holiday'] == 1 && $this->checkIfPublicHoliday($date_from, $public_holidays, $staff_id)) {
                            $attendance_detail['weekend_holiday'] = 0;
                        }
                        if ($attendance_detail['weekend_holiday'] == 1 && $attendance_detail['status'] == 0) {

                            if (($original_date_from != $date_from) && ($date_from != $date_to)) {
                                $prevDay = date("Y-m-d", strtotime("-1 day", strtotime($date_from)));
                                $nextDay = date("Y-m-d", strtotime("+1 day", strtotime($date_from)));
                                if (!($this->checkIfPublicHoliday($prevDay, $public_holidays, $staff_id)) && !($this->checkIfPublicHoliday($nextDay, $public_holidays, $staff_id))) {
                                    $prevDayWorkHour = $fetch_attendances->where('staff_central_id', $staff_id->id)->where('punchin_datetime', '>', $prevDay . ' 00:00:00')
                                            ->where('punchin_datetime', '<', $prevDay . ' 23:59:00')->first()->total_work_hour ?? 0;

                                    $nextDayWorkHour = $fetch_attendances->where('staff_central_id', $staff_id->id)->where('punchin_datetime', '>', $nextDay . ' 00:00:00')
                                            ->where('punchin_datetime', '<', $nextDay . ' 23:59:00')->first()->total_work_hour ?? 0;
                                    if ($prevDayWorkHour == 0 && $nextDayWorkHour == 0) {
                                        $attendance_detail['weekend_holiday'] = 0;
                                    }
                                }
                            }
                        }
                    } else {
                        $attendance_detail['weekend_holiday'] = 0;
                        $attendance_detail['public_holiday'] = 0;
                    }

                    $attendance_detail['leave_id'] = $this->checkifApprovedLeave($date_from, $staff_id->grantLeave);
                    $attendance_detail['created_at'] = Carbon::now();
                    $attendance_detail['updated_at'] = Carbon::now();
                    $attendance_detail_bulk[] = $attendance_detail;
                }
                $date_from = date("Y-m-d", strtotime("+1 day", strtotime($date_from)));
            }

            AttendanceDetailModel::insert($attendance_detail_bulk);
            $attendance_detail_model_data = AttendanceDetailModel::where('payroll_id', $payroll_id)->whereIn('staff_central_id', $staffs->pluck('id')->toArray())->get();
            $staff_this_payroll = AttendanceDetailModel::selectRaw('
								SUM(weekend_holiday) as weekend_holidays,
								SUM(public_holiday) as public_holidays,
								SUM(total_work_hour) as total_work_hours,
								SUM(total_ot_hour) as total_ot_hours,
								staff_central_id
								')->where('payroll_id', $payroll_id)->groupBy('staff_central_id')->whereIn('staff_central_id', $staffs->pluck('id')->toArray())->get();

            foreach ($staff_this_payroll as $staff) {
                //staff Status
                $staff_suspense_days = $attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('suspense', 1)->count();

                $staff_workschedule = $staffs->where('id', $staff->staff_central_id)->first()->workschedule->last();
                $jobtype = $staffs->where('id', $staff->staff_central_id)->first()->jobtype;
                $attendanceDetailSum = new AttendanceDetailSumModel();
                $attendanceDetailSum->staff_central_id = $staff->staff_central_id;
                $attendanceDetailSum->payroll_id = $payroll_id;
                $attendanceDetailSum->branch_id = $branch_id;
                $attendanceDetailSum->date = date('Y-m-d');
                $attendanceDetailSum->date_np = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $attendanceDetailSum->weekend_holiday = $staff->weekend_holidays;
                $attendanceDetailSum->public_holiday = $staff->public_holidays;
                $attendanceDetailSum->total_work_hour = $staff->total_work_hours;
                $attendanceDetailSum->total_ot_hour = $staff->total_ot_hours;
                $min_work_hour = $staff_workschedule->work_hour ?? 0;

                $weekend_holiday_hour = (($attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('weekend_holiday', 1)->where('public_holiday', '<>', 1)->where('status', 1)->where('total_work_hour', '>=', $min_work_hour)->count() * $min_work_hour)
                    + round($attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('weekend_holiday', 1)->where('public_holiday', '<>', 1)->where('status', 1)->where('total_work_hour', '<', $min_work_hour)->sum('total_work_hour')));
                $public_holiday_hour = (($attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('public_holiday', 1)->where('status', 1)->where('total_work_hour', '>=', $min_work_hour)->count() * $min_work_hour)
                    + round($attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('public_holiday', 1)->where('status', 1)->where('total_work_hour', '<', $min_work_hour)->sum('total_work_hour')));


                $absent_on_weekend = ($attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('weekend_holiday', 1)->where('status', 0)->count());

                $total_weekend_days = $attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('weekend_holiday', 1)->where('public_holiday', '<>', 1)->count();
                $absent_on_public_holiday = ($attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('public_holiday', 1)->where('status', 0)->count());
                $absent_on_public_holiday_on_weekend = ($attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('public_holiday', 1)->where('weekend_holiday', 1)->where('status', 0)->count());

                $staff_absent_dates_this_payroll_month = $attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('leave_id', null)->where('status', 0)->count() - $absent_on_public_holiday - $staff_suspense_days;

                if ($branch->manual_weekend_enable == 1) {
                    if ($staff_absent_dates_this_payroll_month >= $total_weekend_days) {
                        $absent_on_weekend = $total_weekend_days;
                        $present_weekend_days = 0;
                    } else {
                        $absent_on_weekend = $staff_absent_dates_this_payroll_month;
                        $present_weekend_days = $total_weekend_days - $staff_absent_dates_this_payroll_month;
                    }
                    $all_weekend_holiday_hour = $attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('status', '=', 1)->where('weekend_holiday', 1)->sortByDesc('total_work_hour')->take($present_weekend_days);
                    $weekend_holiday_hour = 0;
                    foreach ($all_weekend_holiday_hour as $w_work_hour) {
                        if ($w_work_hour->total_work_hour >= $min_work_hour) {
                            $weekend_holiday_hour += $min_work_hour;
                        } else {
                            $weekend_holiday_hour += round($w_work_hour->total_work_hour);
                        }
                    }
                }

                if ($staffs->where('id', $staff->staff_central_id)->first()->manual_attendance_enable == 1) {
                    if ($staff_absent_dates_this_payroll_month >= 4) {
                        $absent_on_weekend = 4;
                        $present_weekend_days = 0;
                    } else {
                        $absent_on_weekend = $staff_absent_dates_this_payroll_month;
                        $present_weekend_days = 4 - $staff_absent_dates_this_payroll_month;
                    }
                    $weekend_holiday_hour = $present_weekend_days * $min_work_hour;
                }


                if ($attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('status', 1)->count() == 0) {
                    $weekend_holiday_hour = 0;
                    $absent_on_weekend = 0;
                    $absent_on_public_holiday = 0;
                    $public_holiday_hour = 0;
                    $absent_on_public_holiday_on_weekend = 0;
                }
                $attendanceDetailSum->weekend_holiday_hours = $weekend_holiday_hour;
                $attendanceDetailSum->public_holiday_hours = $public_holiday_hour;


                //total present days in this current month
                $approved_leave_days = $attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('leave_id', '<>', null)->where('status', 0)->count();
                $total_present_days_current_month = $attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('status', 1)->count(); // status 1 is present that day
                $total_absent_days = $total_days_in_nepali_month - $total_present_days_current_month - $absent_on_weekend - $absent_on_public_holiday + $absent_on_public_holiday_on_weekend - $staff_suspense_days - $approved_leave_days;
                $total_public_holidays = $attendance_detail_model_data->where('staff_central_id', $staff->staff_central_id)->where('public_holiday', '>', 0)->count();


                $staff_workschedule_total_work_hours = $staff_workschedule->work_hour;

                $total_home_leave_earned_this_month = 0;
                $total_sick_leave_earned_this_month = 0;
                if ($total_present_days_current_month >= 20) {
                    if (strcasecmp($jobtype->jobtype_code, "P") == 0) {
                        $total_home_leave_earned_this_month = $earnable_home_leave;
                        $total_sick_leave_earned_this_month = $earnable_sick_leave;
                    }
                    if (strcasecmp($jobtype->jobtype_code, "NP") == 0) {
                        $total_sick_leave_earned_this_month = ($earnable_sick_leave / $total_days_in_nepali_month) * $total_present_days_current_month;
                    }
                }
                $total_substitute_leave_earned_this_month = $this->substituteLeaveEarningCalculation($total_present_days_current_month, $public_holiday_hour, $total_public_holidays, $staff_workschedule_total_work_hours);
                $attendanceDetailSum->earned_home_leave = $total_home_leave_earned_this_month;
                $attendanceDetailSum->earned_sick_leave = $total_sick_leave_earned_this_month;
                $attendanceDetailSum->earned_substitute_leave = $total_substitute_leave_earned_this_month;
                $attendanceDetailSum->present_days = $total_present_days_current_month;
                $attendanceDetailSum->absent_on_weekend = $absent_on_weekend;
                $attendanceDetailSum->absent_on_public_holiday = $absent_on_public_holiday;
                $attendanceDetailSum->absent_on_public_holiday_on_weekend = $absent_on_public_holiday_on_weekend;
                $attendanceDetailSum->absent_days = $total_absent_days;
                $attendanceDetailSum->suspense_days = $staff_suspense_days;
                $attendanceDetailSum->approved_leave = $approved_leave_days;
                $attendanceDetailSum->save();

                $staff_details = $this->getPayrollStaffCollection($this_month_first_en, $this_month_last_en, $payroll, [$staff->staff_central_id])->first();

                $payroll_details = PayrollDetailModel::withoutGlobalScopes()->with(['attendanceSummary' => function ($query) use ($staff) {
                    $query->where('staff_central_id', $staff->staff_central_id);
                }, 'payrollConfirm' => function ($query) {
                    $query->with(['payrollConfirmLeaveInfos' => function ($query) {
                        $query->with('leaveMast');
                    }, 'payrollConfirmAllowances' => function ($query) {
                        $query->with('allowanceMast');
                    }]);
                }], 'fiscalyear')->find($payroll_id);
                $calculation_response = $this->calculatePayroll($staff_details, $payroll_details, $earnable_home_leave, $earnable_sick_leave, null, 0, 0, 0, 0, 0, 0, 0, 0, 1, $systemtdsmastmodel, $staff_details->houseLoanToDeduct->installment_amount ?? 0, $staff_details->vehicleLoanToDeduct->installment_amount ?? 0);

                $netpayment = $calculation_response["net_payment"];
                $attendanceDetailSum->net_payment = $netpayment;
                $attendanceDetailSum->save();
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
        }

        return response()->json(true);


    }

    public function getNetpayment(Request $request)
    {
        $leaves = SystemLeaveMastModel::get();
        $systemtdsmastmodel = SystemTdsMastModel::get();
        $total_sick_leave_from_system = $leaves->where('leave_code', 4)->first()->no_of_days ?? 15;
        $total_home_leave_from_system = $leaves->where('leave_code', 3)->first()->no_of_days ?? 18;

        $earnable_home_leave = $total_home_leave_from_system / 12;
        $earnable_sick_leave = $total_sick_leave_from_system / 12;

        $payroll_id = $request->payroll_id;
        $payroll = PayrollDetailModel::withoutGlobalScopes()->with(['attendanceSummary', 'fiscalyear', 'payrollConfirm' => function ($query) {
            $query->with(['payrollConfirmLeaveInfos' => function ($query) {
                $query->with('leaveMast');
            }, 'payrollConfirmAllowances' => function ($query) {
                $query->with('allowanceMast');
            }]);
        }])->find((int)$payroll_id);
        $payroll_month = $payroll->salary_month;
        $fiscal_year = $this->getYearByMonth($payroll_month, $payroll->fiscalyear->fiscal_code);
        $total_days_in_nepali_month = $this->getTotalNumberOfDaysByMonth($payroll_month, $fiscal_year);

        $this_month_first = $fiscal_year . '-' . $payroll_month . '-' . '01';
        $this_month_last = $fiscal_year . '-' . $payroll_month . '-' . $total_days_in_nepali_month;

        $this_month_first_en = BSDateHelper::BsToAd('-', $this_month_first);
        $this_month_last_en = BSDateHelper::BsToAd('-', $this_month_last);

        $grant_home_leave = $request->grant_home_leave ?? 0;
        $grant_sick_leave = $request->grant_sick_leave ?? 0;
        $grant_maternity_leave = $request->grant_maternity_leave ?? 0;
        $grant_maternity_care_leave = $request->grant_maternity_care_leave ?? 0;
        $grant_funeral_leave = $request->grant_funeral_leave ?? 0;
        $grant_substitute_leave = $request->grant_substitute_leave ?? 0;
        $redeem_home_leave = $request->redeem_home_leave ?? 0;
        $redeem_sick_leave = $request->redeem_sick_leave ?? 0;
        $home_loan_installment = $request->home_loan_installment ?? 0;
        $vehicle_loan_installment = $request->vehicle_loan_installment ?? 0;
        $deduct_sundry = $request->deduct_sundry ?? 1;

        $staff_details = $this->getPayrollStaffCollection($this_month_first_en, $this_month_last_en, $payroll, [$request->staff_central_id])->first();
        $calculation_response = $this->calculatePayroll($staff_details, $payroll, $earnable_home_leave, $earnable_sick_leave, null, $grant_home_leave, $grant_sick_leave, $grant_substitute_leave, $grant_maternity_leave, $grant_maternity_care_leave, $grant_funeral_leave, $redeem_home_leave, $redeem_sick_leave, $deduct_sundry, $systemtdsmastmodel, $home_loan_installment, $vehicle_loan_installment);
        $netpayment = $calculation_response['net_payment'];
        $netpayment = round($netpayment, 2);
        return response()->json($netpayment);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payroll_details = PayrollDetailModel::where('id', $id)->first();
        $payroll_confirm = PayrollConfirm::with(['jobposition', 'jobtype', 'staff' => function ($query) {
            $query->with('jobposition', 'jobtype');
            $query->orderBy('staff_main_mast.staff_central_id');
        }])->where('payroll_id', $id)->get();
        $payroll_confirm = $payroll_confirm->sortBy('staff.staff_central_id');

        return view('attendancedetail.payroll_show', [
            'payroll_details' => $payroll_details,
            'details' => $payroll_confirm,
            'title' => 'Payroll Details',
            'i' => 1
        ]);
    }

    public function download($id)
    {
        $payroll_details = PayrollDetailModel::where('id', $id)->first();
        $payroll_confirm = PayrollConfirm::with(['jobposition', 'jobtype', 'staff' => function ($query) {
            $query->with('jobposition', 'jobtype');
        }])->where('payroll_id', $id)->get();

        Excel::create($payroll_details->payroll_name, function ($excel) use ($payroll_details, $payroll_confirm) {
            $excel->sheet('New sheet', function ($sheet) use ($payroll_details, $payroll_confirm) {
                $sheet->setFreeze('I3');
                $sheet->loadView('attendancedetail.excel-view', [
                    'payroll_details' => $payroll_details,
                    'details' => $payroll_confirm,
                    'title' => 'Payroll Details',
                    'i' => 1
                ]);

            });

        })->download('xlsx');;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function action($id)
    {
        $payroll_details = PayrollDetailModel::with(['payrollConfirm' => function ($query) {
            $query->with(['payrollConfirmLeaveInfos' => function ($query) {
                $query->with('leaveMast');
            }]);
        }])->find($id);
        if (empty($payroll_details->confirmed_by)) {
            $payroll_calculation_data = PayrollCalculationData::where('payroll_id', $id)->get();
        } else {
            $payroll_calculation_data = collect([]);
        }

        $attendance = AttendanceDetailSumModel::with(['staff' => function ($query) use ($payroll_details) {
            $query->with(['homeLeaveBalanceLast', 'sickLeaveBalanceLast', 'houseLoanToDeduct', 'substituteLeaveBalanceLast',
                'maternityLeaveBalanceLast', 'maternityCareLeaveBalanceLast', 'funeralLeaveBalanceLast', 'vehicleLoanToDeduct',
                'loanDeducation' => function ($query) use ($payroll_details) {
                    $query->where('fiscal_year_id', $payroll_details->fiscal_year);
                    $query->where('month_id', $payroll_details->salary_month);
                }]);
        }])->where('payroll_id', $id)->get()->sortBy('staff.main_id');
        $total_sick_leave_from_system = SystemLeaveMastModel::getSickLeave()->value('max_days');
        $total_home_leave_from_system = SystemLeaveMastModel::getHomeLeave()->value('max_days');
        return view('attendancedetail.action', [
                'title' => 'Payroll Action',
                'attendance' => $attendance,
                'total_sick_leave_from_system' => $total_sick_leave_from_system,
                'total_home_leave_from_system' => $total_home_leave_from_system,
                'payroll_id' => $id,
                'payroll_details' => $payroll_details,
                'payroll_calculation_data' => $payroll_calculation_data,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $success = false;
        try {
            DB::beginTransaction();
            $payroll_detail = PayrollDetailModel::with('payrollConfirmAllowances', 'payrollConfirmLeaveInfos')->find($request->id);
            PayrollCalculationData::where('payroll_id', $request->id)->delete();
            PayrollConfirm::where('payroll_id', $request->id)->delete();
            AttendanceDetailModel::where('payroll_id', $request->id)->delete();
            AttendanceDetailSumModel::where('payroll_id', $request->id)->delete();
            PayrollConfirmAllowance::whereIn('id', $payroll_detail->payrollConfirmAllowances->pluck('id')->toArray())->delete();
            PayrollConfirmLeaveInfo::whereIn('id', $payroll_detail->payrollConfirmLeaveInfos->pluck('id')->toArray())->delete();
            $payroll_detail->deleted_by = Auth::id();
            $payroll_detail->save();
            $success = $payroll_detail->delete();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        if ($success) {
            DB::commit();
            echo 'Successfully Deleted';
        } else {
            echo "Error deleting!";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroySelected(Request $request)
    {
    }

    /**  Checking is staff id is valid or not
     * @param int $staff_central_id
     * @return bool
     */
    public function isValidStaffID($staff_central_id, $branch_id)
    {
        if (!empty($staff_central_id)) {
            $staff = StafMainMastModel::where('id', $staff_central_id)->where('branch_id', $branch_id)->first();
            if (!empty($staff)) {
                return true;
            }
        }
        return false;
    }

    /** Calculate the payroll accordingly
     * @param Request $request
     */

    public function calculate(Request $request)
    {
        $status = false;
        try {
            DB::beginTransaction();
            $calculation_data = array();
            //find total number of days in nepali month
            $payroll_id = $request->payroll_id;
            $payroll_details = PayrollDetailModel::withoutGlobalScopes()->with(['attendanceSummary', 'fiscalyear', 'payrollConfirmAllowances', 'payrollConfirmLeaveInfos', 'payrollConfirm' => function ($query) {
                $query->with(['payrollConfirmLeaveInfos' => function ($query) {
                    $query->with('leaveMast');
                }, 'payrollConfirmAllowances' => function ($query) {
                    $query->with('allowanceMast');
                }]);
            }])->find($payroll_id);
            $payroll_month = $payroll_details->salary_month;
            $fiscal_year = $this->getYearByMonth($payroll_month, $payroll_details->fiscalyear->fiscal_code);
            $total_days_in_nepali_month = $this->getTotalNumberOfDaysByMonth($payroll_month, $fiscal_year);
            $staff_central_ids = $request->staff_central_id;

            $this_month_first = $payroll_details->from_date_np;
            $this_month_last = $payroll_details->to_date_np;

            $this_month_first_en = BSDateHelper::BsToAd('-', $this_month_first);
            $this_month_last_en = BSDateHelper::BsToAd('-', $this_month_last);

            $staffmainmastmodel = $this->getPayrollStaffCollection($this_month_first_en, $this_month_last_en, $payroll_details, $staff_central_ids);


            $branch = SystemOfficeMastModel::find($payroll_details->branch_id);
            //ordering the staff according to the staff excel file
            if (!empty($branch->order_staff_ids)) {
                $staff_order_ids = array_map('intval', explode(',', $branch->order_staff_ids));
                $staff_order_ids = array_merge($staff_order_ids, array_diff($staffmainmastmodel->pluck('main_id')->toArray(), $staff_order_ids));
                $staffmainmastmodel = $staffmainmastmodel->sortBy(function ($model) use ($staff_order_ids) {
                    return array_search($model->main_id, $staff_order_ids);
                });
            }
            $staff_central_ids = $staffmainmastmodel->pluck('id')->toArray();
            $system_allowance_mast = AllowanceModelMast::get();
            PayrollCalculationData::withoutGlobalScopes()->where('payroll_id', $payroll_id)->delete();
            PayrollConfirm::withoutGlobalScopes()->where('payroll_id', $payroll_id)->delete();
            PayrollConfirmAllowance::whereIn('id', $payroll_details->payrollConfirmAllowances->pluck('id')->toArray())->delete();
            PayrollConfirmLeaveInfo::whereIn('id', $payroll_details->payrollConfirmLeaveInfos->pluck('id')->toArray())->delete();
            $systemtdsmastmodel = SystemTdsMastModel::get();
            $system_leaves = SystemLeaveMastModel::get();
            $total_sick_leave_from_system = $system_leaves->where('leave_code', 4)->first()->no_of_days ?? 15;
            $total_home_leave_from_system = $system_leaves->where('leave_code', 3)->first()->no_of_days ?? 18;

            $earnable_home_leave = $total_home_leave_from_system / 12;
            $earnable_sick_leave = $total_sick_leave_from_system / 12;

            $minimum_basic_salary = SystemPostMastModel::min('basic_salary');
            $count = 0;
            $auth_user_id = Auth::user()->id;
            $payroll_data_counter = 0;
            $payroll_confirm_counter = 0;
            $payroll_confirm_allowance_counter = 0;
            $payroll_confirm_leave_info_counter = 0;
            $payroll_confirm_allowance_counter_map_staff_central_id = [];
            $payroll_confirm_leave_info_counter_map_staff_central_id = [];
            $payroll_confirm_allowances = [];
            $payroll_confirm_leave_infos = [];
            foreach ($staff_central_ids as $staff_central_id) {
                $payroll_calculation_data[$payroll_data_counter]['payroll_id'] = $request->payroll_id;
                $payroll_calculation_data[$payroll_data_counter]['staff_central_id'] = $staff_central_id;
                $payroll_calculation_data[$payroll_data_counter]['redeem_home_leave'] = $request->redeem_home_leave[$staff_central_id];
                $payroll_calculation_data[$payroll_data_counter]['redeem_sick_leave'] = $request->redeem_sick_leave[$staff_central_id];
                $payroll_calculation_data[$payroll_data_counter]['check_house_loan'] = 1;
                $payroll_calculation_data[$payroll_data_counter]['check_vehicle_loan'] = 1;
                $payroll_calculation_data[$payroll_data_counter]['check_sundry_loan'] = 1;
                $payroll_calculation_data[$payroll_data_counter]['house_loan_installment'] = $request->home_loan_installment_amount[$staff_central_id];
                $payroll_calculation_data[$payroll_data_counter]['vehicle_loan_installment'] = $request->vehicle_loan_installment_amount[$staff_central_id];
                $payroll_calculation_data[$payroll_data_counter]['misc_amount'] = $request->misc_amount[$count];
                $payroll_calculation_data[$payroll_data_counter]['remarks'] = $request->remarks[$staff_central_id];
                $payroll_calculation_data[$payroll_data_counter]['grant_home_leave'] = $request->grant_home_leave[$staff_central_id];
                $payroll_calculation_data[$payroll_data_counter]['grant_sick_leave'] = $request->grant_sick_leave[$staff_central_id];
                $payroll_calculation_data[$payroll_data_counter]['grant_substitute_leave'] = $request->grant_substitute_leave[$staff_central_id];
                $payroll_calculation_data[$payroll_data_counter]['grant_maternity_leave'] = $request->grant_maternity_leave[$staff_central_id];
                $payroll_calculation_data[$payroll_data_counter]['grant_maternity_care_leave'] = $request->grant_maternity_care_leave[$staff_central_id];
                $payroll_calculation_data[$payroll_data_counter]['grant_funeral_leave'] = $request->grant_funeral_leave[$staff_central_id];
                $payroll_calculation_data[$payroll_data_counter]['prepared_by'] = $auth_user_id;
                $payroll_calculation_data[$payroll_data_counter]['created_at'] = Carbon::now();
                $payroll_calculation_data[$payroll_data_counter]['updated_at'] = Carbon::now();
                $payroll_data_counter++;

                //get staff details
                $staff_details = $staffmainmastmodel->where('id', $staff_central_id)->first();
                $main_id = $staff_details->main_id;
                if (empty($staff_details->acc_no)) {
                    $is_cash = 'Cash';
                } else {
                    $is_cash = '-';
                }
                $payroll_information = $this->calculatePayroll($staff_details, $payroll_details, $earnable_home_leave, $earnable_sick_leave, $minimum_basic_salary, $request->grant_home_leave[$staff_central_id], $request->grant_sick_leave[$staff_central_id], $request->grant_substitute_leave[$staff_central_id]
                    , $request->grant_maternity_leave[$staff_central_id], $request->grant_maternity_care_leave[$staff_central_id], $request->grant_funeral_leave[$staff_central_id], $request->redeem_home_leave[$staff_central_id], $request->redeem_sick_leave[$staff_central_id],
                    $request->check_sundry_loan[$staff_central_id], $systemtdsmastmodel, $request->home_loan_installment_amount[$staff_central_id], $request->vehicle_loan_installment_amount[$staff_central_id]);

                $min_work_hour = $payroll_information["min_work_hour"];
                $total_work_hour = $payroll_information["total_work_hour"];
                $days_absent_on_holiday = $payroll_information["days_absent_on_holiday"];
                $marital_status = $payroll_information["marital_status"];
                $total_weekend_work_hours = $payroll_information["weekend_work_hours"];
                $present_on_public_holiday_hours = $payroll_information["public_holiday_work_hours"];
                $total_present_days_current_month = $payroll_information["present_days"];
                $staff_absent_dates_this_payroll_month = $payroll_information["absent_days"];
                $redeem_home_leave = $payroll_information["redeem_home_leave"];
                $redeem_sick_leave = $payroll_information["redeem_sick_leave"];
                $redeem_home_leave_amount = $payroll_information["redeem_home_leave_amount"];
                $redeem_sick_leave_amount = $payroll_information["redeem_sick_leave_amount"];
                $salary_payable_hours = $payroll_information["salary_hour_payable"];
                $overtime_payable_hours = $payroll_information["ot_hour_payable"];
                $new_basic_salary = $payroll_information["basic_salary"];
                $new_dearness_allowance = $payroll_information["dearness_allowances"];
                $new_special_allowance = $payroll_information["special_allowances"];
                $new_outstation_facility_amount = $payroll_information["outstation_facility_amount"];
                $extra_allowance = $payroll_information["extra_allowance"];
                $gratuity_amt = $payroll_information["gratuity_amount"];
                $social_security_fund_amt = $payroll_information["social_security_amount"];
                $new_incentive_amount = $payroll_information["incentive"];
                $profund_amt = $payroll_information["profund_amount"];
                $profund_contribution_amt = $payroll_information["profund_contribution_amount"];
                $total_redeem_home_and_sick_amt = $payroll_information["redeem_home_leave_amount"] + $payroll_information["redeem_sick_leave_amount"];
                $ot_amount = $payroll_information["ot_amount"];
                $gross_payment = $payroll_information["gross_payment"];
                $total_loan = $payroll_information["house_loan_installment"] + $payroll_information["vehicle_loan_installment"];
                $dr_amount = $payroll_information["sundry_dr_amount"];
                $cr_amount = $payroll_information["sundry_cr_amount"];
                $tds = $payroll_information["tds"];
                $net_payment = $payroll_information["net_payment"];
                $levy_amount = $payroll_information["levy_amount"];
                $total_approved_home_leave_this_month = $payroll_information["approved_home_leave"];
                $total_approved_sick_leave_this_month = $payroll_information["approved_sick_leave"];
                $total_approved_maternity_leave_this_month = $payroll_information["approved_maternity_leave"];
                $total_approved_substitute_leave_this_month = $payroll_information["approved_substitute_leave"];
                $total_approved_funeral_leave_this_month = $payroll_information["approved_funeral_leave"];
                $total_approved_maternity_care_leave_this_month = $payroll_information["approved_maternity_care_leave"];
                $total_unpaid_leave = $payroll_information["unpaid_days"];
                $staff_suspense_days = $payroll_information["suspense_days"];
                $home_leave_balance = $payroll_information["home_leave_balance"];
                $sick_leave_balance = $payroll_information["sick_leave_balance"];
                $substitute_leave_balance = $payroll_information["substitute_leave_balance"];
                $post = $payroll_information["working_position"];

                $home_leave_earned = $payroll_information["total_home_leave_earned_this_month"];
                $sick_leave_earned = $payroll_information["total_sick_leave_earned_this_month"];
                $substitute_leave_earned = $payroll_information["total_substitute_leave_earned_this_month"];

                //saving in table

                $payroll_confirm[$payroll_confirm_counter]['payroll_id'] = $payroll_id;
                $payroll_confirm[$payroll_confirm_counter]['staff_central_id'] = $staff_central_id;
                $payroll_confirm[$payroll_confirm_counter]['min_work_hour'] = $min_work_hour;
                $payroll_confirm[$payroll_confirm_counter]['tax_code'] = $marital_status;
                $payroll_confirm[$payroll_confirm_counter]['total_worked_hours'] = $total_work_hour;
                $payroll_confirm[$payroll_confirm_counter]['days_absent_on_holiday'] = $days_absent_on_holiday;
                $payroll_confirm[$payroll_confirm_counter]['weekend_work_hours'] = $total_weekend_work_hours;
                $payroll_confirm[$payroll_confirm_counter]['public_holiday_work_hours'] = $present_on_public_holiday_hours;
                $payroll_confirm[$payroll_confirm_counter]['present_days'] = $total_present_days_current_month;
                $payroll_confirm[$payroll_confirm_counter]['absent_days'] = $staff_absent_dates_this_payroll_month;
                $payroll_confirm[$payroll_confirm_counter]['redeem_home_leave'] = $redeem_home_leave;
                $payroll_confirm[$payroll_confirm_counter]['redeem_sick_leave'] = $redeem_sick_leave;
                $payroll_confirm[$payroll_confirm_counter]['salary_hour_payable'] = $salary_payable_hours;
                $payroll_confirm[$payroll_confirm_counter]['ot_hour_payable'] = $overtime_payable_hours;
                $payroll_confirm[$payroll_confirm_counter]['basic_salary'] = $new_basic_salary;
                $payroll_confirm[$payroll_confirm_counter]['dearness_allowance'] = $new_dearness_allowance;
                $payroll_confirm[$payroll_confirm_counter]['special_allowance'] = $new_special_allowance;
                $payroll_confirm[$payroll_confirm_counter]['extra_allowance'] = $extra_allowance;
                $payroll_confirm[$payroll_confirm_counter]['gratuity_amount'] = $gratuity_amt;
                $payroll_confirm[$payroll_confirm_counter]['social_security_fund_amount'] = $social_security_fund_amt;
                $payroll_confirm[$payroll_confirm_counter]['incentive'] = $new_incentive_amount;
                $payroll_confirm[$payroll_confirm_counter]['outstation_facility_amount'] = $new_outstation_facility_amount;
                $payroll_confirm[$payroll_confirm_counter]['pro_fund'] = $profund_amt;
                $payroll_confirm[$payroll_confirm_counter]['pro_fund_contribution'] = $profund_contribution_amt;
                $payroll_confirm[$payroll_confirm_counter]['home_sick_redeem_amount'] = $total_redeem_home_and_sick_amt;
                $payroll_confirm[$payroll_confirm_counter]['ot_amount'] = $ot_amount;
                $payroll_confirm[$payroll_confirm_counter]['gross_payable'] = $gross_payment;
                $payroll_confirm[$payroll_confirm_counter]['loan_payment'] = $total_loan;
                $payroll_confirm[$payroll_confirm_counter]['sundry_dr'] = $dr_amount;
                $payroll_confirm[$payroll_confirm_counter]['sundry_cr'] = $cr_amount;
                $payroll_confirm[$payroll_confirm_counter]['tax'] = $tds;
                $payroll_confirm[$payroll_confirm_counter]['net_payable'] = $net_payment;
                $payroll_confirm[$payroll_confirm_counter]['levy_amount'] = $levy_amount;
                $payroll_confirm[$payroll_confirm_counter]['home_leave_taken'] = $total_approved_home_leave_this_month;
                $payroll_confirm[$payroll_confirm_counter]['sick_leave_taken'] = $total_approved_sick_leave_this_month;
                $payroll_confirm[$payroll_confirm_counter]['maternity_leave_taken'] = $total_approved_maternity_leave_this_month;
                $payroll_confirm[$payroll_confirm_counter]['maternity_care_leave_taken'] = $total_approved_maternity_care_leave_this_month;
                $payroll_confirm[$payroll_confirm_counter]['funeral_leave_taken'] = $total_approved_funeral_leave_this_month;
                $payroll_confirm[$payroll_confirm_counter]['substitute_leave_taken'] = $total_approved_substitute_leave_this_month;
                $payroll_confirm[$payroll_confirm_counter]['unpaid_leave_taken'] = $total_unpaid_leave;
                $payroll_confirm[$payroll_confirm_counter]['suspended_days'] = $staff_suspense_days;
                $payroll_confirm[$payroll_confirm_counter]['useable_home_leave'] = $home_leave_balance;
                $payroll_confirm[$payroll_confirm_counter]['useable_sick_leave'] = $sick_leave_balance;
                $payroll_confirm[$payroll_confirm_counter]['useable_substitute_leave'] = $substitute_leave_balance;
                $payroll_confirm[$payroll_confirm_counter]['remarks'] = $request->remarks[$staff_central_id];
                $payroll_confirm[$payroll_confirm_counter]['created_at'] = Carbon::now();
                $payroll_confirm[$payroll_confirm_counter]['updated_at'] = Carbon::now();
                $payroll_confirm_counter++;

                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '001')->first()->allow_id ?? null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $new_dearness_allowance;
                $payroll_confirm_allowance_counter_map_staff_central_id[$staff_central_id][] = $payroll_confirm_allowance_counter;
                $payroll_confirm_allowance_counter++;

                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '003')->first()->allow_id ?? null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $new_special_allowance;
                $payroll_confirm_allowance_counter_map_staff_central_id[$staff_central_id][] = $payroll_confirm_allowance_counter;
                $payroll_confirm_allowance_counter++;

                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '007')->first()->allow_id ?? null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $new_outstation_facility_amount;
                $payroll_confirm_allowance_counter_map_staff_central_id[$staff_central_id][] = $payroll_confirm_allowance_counter;
                $payroll_confirm_allowance_counter++;

                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '008')->first()->allow_id ?? null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $extra_allowance;
                $payroll_confirm_allowance_counter_map_staff_central_id[$staff_central_id][] = $payroll_confirm_allowance_counter;
                $payroll_confirm_allowance_counter++;

                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '006')->first()->allow_id ?? null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $new_incentive_amount;
                $payroll_confirm_allowance_counter_map_staff_central_id[$staff_central_id][] = $payroll_confirm_allowance_counter;
                $payroll_confirm_allowance_counter++;


                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['payroll_confirm_id'] = null;
                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['leave_id'] = $system_leaves->where('leave_code', '3')->first()->leave_id ?? null;
                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['used'] = $total_approved_home_leave_this_month + $redeem_home_leave;
                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['earned'] = $home_leave_earned;
                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['balance'] = $home_leave_balance;
                $payroll_confirm_leave_info_counter_map_staff_central_id[$staff_central_id][] = $payroll_confirm_leave_info_counter;
                $payroll_confirm_leave_info_counter++;

                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['payroll_confirm_id'] = null;
                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['leave_id'] = $system_leaves->where('leave_code', '4')->first()->leave_id ?? null;
                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['used'] = $total_approved_sick_leave_this_month + $redeem_sick_leave;
                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['earned'] = $sick_leave_earned;
                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['balance'] = $sick_leave_balance;
                $payroll_confirm_leave_info_counter_map_staff_central_id[$staff_central_id][] = $payroll_confirm_leave_info_counter;
                $payroll_confirm_leave_info_counter++;

                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['payroll_confirm_id'] = null;
                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['leave_id'] = $system_leaves->where('leave_code', '8')->first()->leave_id ?? null;
                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['used'] = $total_approved_substitute_leave_this_month;
                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['earned'] = $substitute_leave_earned;
                $payroll_confirm_leave_infos[$payroll_confirm_leave_info_counter]['balance'] = $substitute_leave_balance;
                $payroll_confirm_leave_info_counter_map_staff_central_id[$staff_central_id][] = $payroll_confirm_leave_info_counter;
                $payroll_confirm_leave_info_counter++;

                $calculation_data[] = array(
                    'main_id' => $main_id,
                    'staff_central_id' => $staff_central_id,
                    'staff_workschedule_total_work_hours' => $min_work_hour,
                    'marital_status' => ($staff_details->marrid_stat == 1) ? 'Couple' : 'Single',
                    'job_type' => $marital_status,
                    'post' => $post,
                    'staff_name' => $staff_details->name_eng,
                    'basic_salary' => $new_basic_salary,
                    'total_work_hours_selected_month' => $total_work_hour,
                    'total_work_hours_salary' => $salary_payable_hours,
                    'total_ot_hours_selected_month' => $overtime_payable_hours,
                    'total_ot_hours_salary' => $ot_amount,
                    'dearness_allowance' => $new_dearness_allowance,
                    'extra_allowance' => $extra_allowance,
                    'special_allowance' => $new_special_allowance,
                    'outstation_facility' => $new_outstation_facility_amount,
                    'profund_amt' => $profund_amt,
                    'gratuity_amt' => $gratuity_amt,
                    'social_security_fund_amt' => $social_security_fund_amt,
                    'incentive_amt' => $new_incentive_amount,
                    'profund_contribution_amt' => $profund_contribution_amt,
                    'tds' => $tds,
                    'total_days_in_nepali_month' => $total_days_in_nepali_month,
                    'total_present_days_current_month' => $total_present_days_current_month,
                    'absent_on_holidays' => $days_absent_on_holiday,
                    'total_weekend_work_hours' => $total_weekend_work_hours,
                    'present_on_public_holiday_hours' => $present_on_public_holiday_hours,
                    'total_approved_home_leave_this_month' => $total_approved_home_leave_this_month,
                    'total_approved_sick_leave_this_month' => $total_approved_sick_leave_this_month,
                    'total_approved_maternity_leave_this_month' => $total_approved_maternity_leave_this_month,
                    'total_approved_funeral_leave_this_month' => $total_approved_funeral_leave_this_month,
                    'total_approved_substitute_leave_this_month' => $total_approved_substitute_leave_this_month,
                    'total_unpaid_leave' => $total_unpaid_leave,
                    'home_leave_balance' => $home_leave_balance,
                    'sick_leave_balance' => $sick_leave_balance,
                    'substitute_leave_balance' => $substitute_leave_balance,
                    'absent_days' => $staff_absent_dates_this_payroll_month,
                    'gross_payment' => $gross_payment,
                    'total_loan' => $total_loan,
                    'sundry_cr_amount' => $cr_amount,
                    'sundry_dr_amount' => $dr_amount,
                    'is_cash' => $is_cash,
                    'redeem_home_leave_amount' => $redeem_home_leave_amount,
                    'redeem_sick_leave_amount' => $redeem_sick_leave_amount,
                    'redeem_home_leave' => $redeem_home_leave,
                    'redeem_sick_leave' => $redeem_sick_leave,
                    'total_home_sick_amount' => $total_redeem_home_and_sick_amt,
                    'staff_suspense_days' => $staff_suspense_days,
                    'net_payment' => $net_payment,
                    'levy_amount' => $levy_amount,
                    'remarks' => $request->remarks[$staff_central_id]
                );
                $count++;
            }

            PayrollCalculationData::insert($payroll_calculation_data);
            PayrollConfirm::insert($payroll_confirm);
            $payrollConfirms = PayrollConfirm::where('payroll_id', $payroll_id)->get();
            foreach ($payrollConfirms as $payrollConfirm) {
                $counters = $payroll_confirm_allowance_counter_map_staff_central_id[$payrollConfirm->staff_central_id];
                foreach ($counters as $counter) {
                    $payroll_confirm_allowances[$counter]['payroll_confirm_id'] = $payrollConfirm->id;
                }

                $leaveCounters = $payroll_confirm_leave_info_counter_map_staff_central_id[$payrollConfirm->staff_central_id];
                foreach ($leaveCounters as $counter) {
                    $payroll_confirm_leave_infos[$counter]['payroll_confirm_id'] = $payrollConfirm->id;
                }
            }
            PayrollConfirmAllowance::insert($payroll_confirm_allowances);
            PayrollConfirmLeaveInfo::insert($payroll_confirm_leave_infos);
            $status = true;
        } catch (\Exception $e) {
            DB::rollBack();
            $status = false;
            return redirect()->back()->with('error', 'Error Something went wrong!');
        }
        if ($status) {
            DB::commit();
        }
        return view('attendancedetail.calculate', [
                'title' => 'Payroll Calculation Confirmation',
                'details' => $calculation_data,
                'payroll_details' => $payroll_details
            ]
        );
    }

    public function calculate_save($payroll_id)
    {
        ini_set('memory_limit', -1);
        $status_mesg = false;

        try {
            //start transaction for rolling back if some problem occurs
            DB::beginTransaction();

            $payroll_data = PayrollCalculationData::where('payroll_id', $payroll_id)->get();
            $calculation_data = array();
            //find total number of days in nepali month
            $payroll_id = $payroll_data->first()->payroll_id; // payroll of temporary data table
            $systemtdsmastmodel = SystemTdsMastModel::get();
            //retriving from temporary table
            foreach ($payroll_data as $pay_data) {
                $staff_central_ids[] = $pay_data->staff_central_id;
                $redeem_home_leave[$pay_data->staff_central_id] = $pay_data->redeem_home_leave;
                $redeem_sick_leave[$pay_data->staff_central_id] = $pay_data->redeem_sick_leave;
                $check_house_loan[$pay_data->staff_central_id] = $pay_data->check_house_loan;
                $check_vehicle_loan[$pay_data->staff_central_id] = $pay_data->check_vehicle_loan;
                $check_sundry_loan[$pay_data->staff_central_id] = $pay_data->check_sundry_loan;
                $misc_amount[$pay_data->staff_central_id] = $pay_data->misc_amount;
                $remarks[$pay_data->staff_central_id] = $pay_data->remarks;
                $manual_house_loan_installment[$pay_data->staff_central_id] = $pay_data->house_loan_installment;
                $manual_vehicle_loan_installment[$pay_data->staff_central_id] = $pay_data->vehicle_loan_installment;
                $grant_home_leave[$pay_data->staff_central_id] = $pay_data->grant_home_leave;
                $grant_sick_leave[$pay_data->staff_central_id] = $pay_data->grant_sick_leave;
                $grant_substitute_leave[$pay_data->staff_central_id] = $pay_data->grant_substitute_leave;
                $grant_maternity_leave[$pay_data->staff_central_id] = $pay_data->grant_maternity_leave;
                $grant_maternity_care_leave[$pay_data->staff_central_id] = $pay_data->grant_maternity_care_leave;
                $grant_funeral_leave[$pay_data->staff_central_id] = $pay_data->grant_funeral_leave;
            }

            $system_allowance_mast = AllowanceModelMast::get();
            $payroll_details = PayrollDetailModel::withoutGlobalScopes()->with(['fiscalyear', 'attendanceSummary', 'branch'])->find($payroll_id);
            $payroll_month = $payroll_details->salary_month;
            $fiscal_year = $this->getYearByMonth($payroll_month, $payroll_details->fiscalyear->fiscal_code);
            $fiscal_year_details = FiscalYearModel::where('fiscal_code', $payroll_details->fiscalyear->fiscal_code)->first();
            $total_days_in_nepali_month = $this->getTotalNumberOfDaysByMonth($payroll_month, $fiscal_year);

            $staff_central_ids = PayrollCalculationData::select('staff_central_id')->where('payroll_id', $payroll_id)->get();

            $this_month_first = $payroll_details->from_date_np;
            $this_month_last = $payroll_details->to_date_np;

            $this_month_first_en = BSDateHelper::BsToAd('-', $this_month_first);
            $this_month_last_en = BSDateHelper::BsToAd('-', $this_month_last);

            $staffmainmastmodel = $this->getPayrollStaffCollection($this_month_first_en, $this_month_last_en, $payroll_details, $staff_central_ids);
            $total_working_days_in_curent_fiscal_year = FiscalYearModel::IsActiveFiscalYear()->value('present_days');

            $system_leaves = SystemLeaveMastModel::get();
            $total_sick_leave_from_system = $system_leaves->where('leave_code', 4)->first()->no_of_days ?? 15;
            $total_home_leave_from_system = $system_leaves->where('leave_code', 3)->first()->no_of_days ?? 18;

            $earnable_home_leave = $total_home_leave_from_system / 12;
            $earnable_sick_leave = $total_sick_leave_from_system / 12;
            $minimum_basic_salary = SystemPostMastModel::min('basic_salary');
            //time to calculate the payroll
            $count = 0;

            TransCashStatement::where('payroll_id', $payroll_id)->delete();
            TransBankStatement::where('payroll_id', $payroll_id)->delete();
            ProFund::where('payroll_id', $payroll_id)->delete();
            CitLedger::where('payroll_id', $payroll_id)->delete();
            SocialSecurityTaxStatement::where('payroll_id', $payroll_id)->delete();
            TaxStatement::where('payroll_id', $payroll_id)->delete();
            $payroll_confirms = PayrollConfirm::with(['payrollConfirmAllowances' => function ($query) {
                $query->with('allowanceMast');
            }, 'payrollConfirmLeaveInfos' => function ($query) {
                $query->with('leaveMast');
            }])->where('payroll_id', $payroll_id)->get();

            $cash_statement_bulk_array = [];
            $bank_statement_bulk_array = [];
            $profund_statement_bulk_array = [];
            $cit_statement_bulk_array = [];
            $socialSecurity_statement_bulk_array = [];
            $tax_statement_bulk_array = [];
            $leave_balance_bulk = [];
            $grant_leave_bulk = [];
            $payroll_confirm_leave_counter = $payroll_confirm_allowance_counter = 0;
            $payroll_confirm_leave = $payroll_confirm_allowances = [];

            foreach ($staff_central_ids as $staff_central_id) {

                $staff_central_id = $staff_central_id->staff_central_id;
                //get staff details
                $staff_details = $staffmainmastmodel->where('id', $staff_central_id)->first();
                $main_id = $staff_details->main_id;
                if (empty($staff_details->acc_no)) {
                    $is_cash = 'Cash';
                } else {
                    $is_cash = '-';
                }
                $staff_workschedule = $staff_details->workschedule->last();
                $staff_workschedule_total_work_hours = $staff_workschedule->work_hour;
                $post = $staff_details->jobposition->post_title;
                $basic_salary = $staff_details->jobposition->basic_salary;
                $job_type_details = $staff_details->jobtype;
                if (strcasecmp($job_type_details->jobtype_code, "Con") == 0 || strcasecmp($job_type_details->jobtype_code, "Con1") == 0) {
                    $basic_salary = $staff_details->additionalSalary->last()->basic_salary;
                }
                //Also get grade amount
                $grade_amount = ($staff_details->additionalSalary->last()->total_grade_amount ?? 0) + ($staff_details->additionalSalary->last()->add_grade_this_fiscal_year ?? 0);

                //check if has additional salary
                $additional_salary = $staff_details->additionalSalary->last()->add_salary_amount ?? 0;
                //total monthly salary
                $total_monthly_salary = $basic_salary + $grade_amount + $additional_salary;
                //get total work hours of selected month and total OT hour salary
                $selected_month_summary = $payroll_details->attendanceSummary->where('staff_central_id', $staff_central_id)->first();
                $total_work_hours_selected_month = round($selected_month_summary->total_work_hour);

                //staff Status
                $staff_suspense_days = $selected_month_summary->suspense_days ?? 0;
                $staff_attendance_details = $staff_details->attendanceDetails->where('payroll_id', $payroll_details->id);
                $total_weekend_days = $selected_month_summary->weekend_holiday_hours ?? 0;
                $absent_weekend_days = $selected_month_summary->absent_on_weekend;
                $absent_public_holiday_on_weekend_days = $selected_month_summary->absent_on_public_holiday_on_weekend;
                $total_weekend_work_hours = $selected_month_summary->weekend_holiday_hours;
                $total_public_holidays = $selected_month_summary->public_holiday;
                $absent_on_public_holiday = $selected_month_summary->absent_on_public_holiday;
                $present_on_public_holiday_hours = $selected_month_summary->public_holiday_hours;
                $total_present_days_current_month = $selected_month_summary->present_days; // status 1 is present that day
                $staff_absent_dates_this_payroll_month = $selected_month_summary->absent_days;

                $total_home_leave_earned_this_month = 0;
                $total_sick_leave_earned_this_month = 0;
                if ($total_present_days_current_month >= 20) {
                    if (strcasecmp($job_type_details->jobtype_code, "P") == 0) {
                        $total_home_leave_earned_this_month = $earnable_home_leave;
                        $total_sick_leave_earned_this_month = $earnable_sick_leave;
                    }
                    if (strcasecmp($job_type_details->jobtype_code, "NP") == 0) {
                        $total_sick_leave_earned_this_month = ($earnable_sick_leave / $total_days_in_nepali_month) * $total_present_days_current_month;
                    }
                }

                $total_substitute_leave_earned_this_month = $this->substituteLeaveEarningCalculation($total_present_days_current_month, $present_on_public_holiday_hours, $total_public_holidays, $staff_workschedule_total_work_hours);


                $input_field_grant_home_leave = $grant_home_leave[$staff_central_id] ?? 0;
                $input_field_grant_sick_leave = $grant_sick_leave[$staff_central_id] ?? 0;
                $input_field_grant_substitute_leave = $grant_substitute_leave[$staff_central_id] ?? 0;
                $input_field_grant_maternity_leave = $grant_maternity_leave[$staff_central_id] ?? 0;
                $input_field_grant_maternity_care_leave = $grant_maternity_care_leave[$staff_central_id] ?? 0;
                $input_field_grant_funeral_leave = $grant_funeral_leave[$staff_central_id] ?? 0;

                $consumed_home_leave = $consumed_sick_leave = $consumed_substitute_leave = $consumed_maternity_leave = $consumed_maternity_care_leave = $consumed_funeral_leave = 0;
                $grant_leave_detail_ids = [];

                $home_leave_taken_days = $staff_attendance_details->where('public_holiday', 0)->where('weekend_holiday', 0)->where('status', 0)->whereNotIn('id', $grant_leave_detail_ids)->take($input_field_grant_home_leave);
                $grant_leave_detail_ids = array_merge($grant_leave_detail_ids, $home_leave_taken_days->pluck('id')->toArray());
                foreach ($home_leave_taken_days as $granted_leave) {
                    $consumed_home_leave++;
                    $grant_leave_item['fiscal_year_id'] = $fiscal_year_details->id;
                    $grant_leave_item['staff_central_id'] = $staff_central_id;
                    $grant_leave_item['leave_id'] = $system_leaves->where('leave_code', 3)->first()->leave_id;
                    $grant_leave_item['from_leave_day'] = $granted_leave->date;
                    $grant_leave_item['to_leave_day'] = $granted_leave->date;
                    $grant_leave_item['from_leave_day_np'] = $granted_leave->date_np;
                    $grant_leave_item['to_leave_day_np'] = $granted_leave->date_np;
                    $grant_leave_item['leave_days'] = 1;
                    $grant_leave_item['authorized_by'] = Auth::id();
                    $grant_leave_item['created_at'] = Carbon::now();
                    $grant_leave_item['updated_at'] = Carbon::now();
                    $grant_leave_item['payroll_id'] = $payroll_id;
                    $grant_leave_bulk[] = $grant_leave_item;
                }


                $sick_leave_taken_days = $staff_attendance_details->where('public_holiday', 0)->where('weekend_holiday', 0)->where('status', 0)->whereNotIn('id', $grant_leave_detail_ids)->take($input_field_grant_sick_leave);
                $grant_leave_detail_ids = array_merge($grant_leave_detail_ids, $sick_leave_taken_days->pluck('id')->toArray());
                foreach ($sick_leave_taken_days as $granted_leave) {
                    $consumed_sick_leave++;
                    $grant_leave_item['fiscal_year_id'] = $fiscal_year_details->id;
                    $grant_leave_item['staff_central_id'] = $staff_central_id;
                    $grant_leave_item['leave_id'] = $system_leaves->where('leave_code', 4)->first()->leave_id;
                    $grant_leave_item['from_leave_day'] = $granted_leave->date;
                    $grant_leave_item['to_leave_day'] = $granted_leave->date;
                    $grant_leave_item['from_leave_day_np'] = $granted_leave->date_np;
                    $grant_leave_item['to_leave_day_np'] = $granted_leave->date_np;
                    $grant_leave_item['leave_days'] = 1;
                    $grant_leave_item['authorized_by'] = Auth::id();
                    $grant_leave_item['created_at'] = Carbon::now();
                    $grant_leave_item['updated_at'] = Carbon::now();
                    $grant_leave_item['payroll_id'] = $payroll_id;
                    $grant_leave_bulk[] = $grant_leave_item;
                }

                $substitute_leave_taken_days = $staff_attendance_details->where('public_holiday', 0)->where('weekend_holiday', 0)->where('status', 0)->whereNotIn('id', $grant_leave_detail_ids)->take($input_field_grant_substitute_leave);
                $grant_leave_detail_ids = array_merge($grant_leave_detail_ids, $substitute_leave_taken_days->pluck('id')->toArray());
                foreach ($substitute_leave_taken_days as $granted_leave) {
                    $consumed_substitute_leave++;
                    $grant_leave_item['fiscal_year_id'] = $fiscal_year_details->id;
                    $grant_leave_item['staff_central_id'] = $staff_central_id;
                    $grant_leave_item['leave_id'] = $system_leaves->where('leave_code', 8)->first()->leave_id;
                    $grant_leave_item['from_leave_day'] = $granted_leave->date;
                    $grant_leave_item['to_leave_day'] = $granted_leave->date;
                    $grant_leave_item['from_leave_day_np'] = $granted_leave->date_np;
                    $grant_leave_item['to_leave_day_np'] = $granted_leave->date_np;
                    $grant_leave_item['leave_days'] = 1;
                    $grant_leave_item['authorized_by'] = Auth::id();
                    $grant_leave_item['created_at'] = Carbon::now();
                    $grant_leave_item['updated_at'] = Carbon::now();
                    $grant_leave_item['payroll_id'] = $payroll_id;
                    $grant_leave_bulk[] = $grant_leave_item;
                }

                $maternity_leave_taken_days = $staff_attendance_details->where('public_holiday', 0)->where('weekend_holiday', 0)->where('status', 0)->whereNotIn('id', $grant_leave_detail_ids)->take($input_field_grant_maternity_leave);
                $grant_leave_detail_ids = array_merge($grant_leave_detail_ids, $maternity_leave_taken_days->pluck('id')->toArray());
                foreach ($maternity_leave_taken_days as $granted_leave) {
                    $consumed_maternity_leave++;
                    $grant_leave_item['fiscal_year_id'] = $fiscal_year_details->id;
                    $grant_leave_item['staff_central_id'] = $staff_central_id;
                    $grant_leave_item['leave_id'] = $system_leaves->where('leave_code', 5)->first()->leave_id;
                    $grant_leave_item['from_leave_day'] = $granted_leave->date;
                    $grant_leave_item['to_leave_day'] = $granted_leave->date;
                    $grant_leave_item['from_leave_day_np'] = $granted_leave->date_np;
                    $grant_leave_item['to_leave_day_np'] = $granted_leave->date_np;
                    $grant_leave_item['leave_days'] = 1;
                    $grant_leave_item['authorized_by'] = Auth::id();
                    $grant_leave_item['created_at'] = Carbon::now();
                    $grant_leave_item['updated_at'] = Carbon::now();
                    $grant_leave_item['payroll_id'] = $payroll_id;
                    $grant_leave_bulk[] = $grant_leave_item;
                }

                $maternity_care_leave_taken_days = $staff_attendance_details->where('public_holiday', 0)->where('weekend_holiday', 0)->where('status', 0)->whereNotIn('id', $grant_leave_detail_ids)->take($input_field_grant_maternity_care_leave);
                $grant_leave_detail_ids = array_merge($grant_leave_detail_ids, $maternity_care_leave_taken_days->pluck('id')->toArray());
                foreach ($maternity_care_leave_taken_days as $granted_leave) {
                    $consumed_maternity_care_leave++;
                    $grant_leave_item['fiscal_year_id'] = $fiscal_year_details->id;
                    $grant_leave_item['staff_central_id'] = $staff_central_id;
                    $grant_leave_item['leave_id'] = $system_leaves->where('leave_code', 5)->first()->leave_id;
                    $grant_leave_item['from_leave_day'] = $granted_leave->date;
                    $grant_leave_item['to_leave_day'] = $granted_leave->date;
                    $grant_leave_item['from_leave_day_np'] = $granted_leave->date_np;
                    $grant_leave_item['to_leave_day_np'] = $granted_leave->date_np;
                    $grant_leave_item['leave_days'] = 1;
                    $grant_leave_item['authorized_by'] = Auth::id();
                    $grant_leave_item['created_at'] = Carbon::now();
                    $grant_leave_item['updated_at'] = Carbon::now();
                    $grant_leave_item['payroll_id'] = $payroll_id;
                    $grant_leave_bulk[] = $grant_leave_item;
                }

                $funeral_leave_taken_days = $staff_attendance_details->where('public_holiday', 0)->where('weekend_holiday', 0)->where('status', 0)->whereNotIn('id', $grant_leave_detail_ids)->take($input_field_grant_funeral_leave);
                $grant_leave_detail_ids = array_merge($grant_leave_detail_ids, $funeral_leave_taken_days->pluck('id')->toArray());
                foreach ($funeral_leave_taken_days as $granted_leave) {
                    $consumed_funeral_leave++;
                    $grant_leave_item['fiscal_year_id'] = $fiscal_year_details->id;
                    $grant_leave_item['staff_central_id'] = $staff_central_id;
                    $grant_leave_item['leave_id'] = $system_leaves->where('leave_code', 5)->first()->leave_id;
                    $grant_leave_item['from_leave_day'] = $granted_leave->date;
                    $grant_leave_item['to_leave_day'] = $granted_leave->date;
                    $grant_leave_item['from_leave_day_np'] = $granted_leave->date_np;
                    $grant_leave_item['to_leave_day_np'] = $granted_leave->date_np;
                    $grant_leave_item['leave_days'] = 1;
                    $grant_leave_item['authorized_by'] = Auth::id();
                    $grant_leave_item['created_at'] = Carbon::now();
                    $grant_leave_item['updated_at'] = Carbon::now();
                    $grant_leave_item['payroll_id'] = $payroll_id;
                    $grant_leave_bulk[] = $grant_leave_item;
                }


                $home_leave_balance = $staff_details->homeLeaveBalanceLast->balance ?? 0;
                $sick_leave_balance = $staff_details->sickLeaveBalanceLast->balance ?? 0;
                $substitute_leave_balance = $staff_details->substituteLeaveBalanceLast->balance ?? 0;

                //home leave balance update
                if (empty($redeem_home_leave[$staff_central_id])) {
                    $redeem_home_leave[$staff_central_id] = 0;
                }

                if (empty($redeem_sick_leave[$staff_central_id])) {
                    $redeem_sick_leave[$staff_central_id] = 0;
                }

                $new_home_leave_balance = $home_leave_balance - (float)$redeem_home_leave[$staff_central_id];
                $new_sick_leave_balance = $sick_leave_balance - (float)$redeem_sick_leave[$staff_central_id];
                $new_substitute_leave_balance = $substitute_leave_balance;

                $total_home_leave_balance = $new_home_leave_balance + $total_home_leave_earned_this_month - $consumed_home_leave;
                $total_sick_leave_balance = $new_sick_leave_balance + $total_sick_leave_earned_this_month - $consumed_sick_leave;
                $total_substitute_leave_balance = $new_substitute_leave_balance + $total_substitute_leave_earned_this_month - $consumed_substitute_leave;

                $leave_balance_item['staff_central_id'] = $staff_central_id;
                $leave_balance_item['leave_id'] = $system_leaves->where('leave_code', 3)->first()->leave_id;
                $leave_balance_item['fy_id'] = $fiscal_year_details->id;
                $leave_balance_item['description'] = "Redeemed / Earned Leave in Payroll";
                $leave_balance_item['consumption'] = $redeem_home_leave[$staff_central_id] + $consumed_home_leave;
                $leave_balance_item['earned'] = $total_home_leave_earned_this_month;
                $leave_balance_item['balance'] = $total_home_leave_balance;
                $leave_balance_item['created_at'] = Carbon::now();
                $leave_balance_item['updated_at'] = Carbon::now();
                $leave_balance_item['date'] = date('Y-m-d');
                $leave_balance_item['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $leave_balance_item['payroll_id'] = $payroll_id;
                $leave_balance_bulk[] = $leave_balance_item;

                $leave_balance_item['staff_central_id'] = $staff_central_id;
                $leave_balance_item['leave_id'] = $system_leaves->where('leave_code', 4)->first()->leave_id;
                $leave_balance_item['fy_id'] = $fiscal_year_details->id;
                $leave_balance_item['description'] = "Redeemed / Earned Leave in Payroll";
                $leave_balance_item['consumption'] = $redeem_sick_leave[$staff_central_id] + $consumed_sick_leave;
                $leave_balance_item['earned'] = $total_sick_leave_earned_this_month;
                $leave_balance_item['balance'] = $total_sick_leave_balance;
                $leave_balance_item['created_at'] = Carbon::now();
                $leave_balance_item['updated_at'] = Carbon::now();
                $leave_balance_item['date'] = date('Y-m-d');
                $leave_balance_item['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $leave_balance_item['payroll_id'] = $payroll_id;
                $leave_balance_bulk[] = $leave_balance_item;

                $leave_balance_item['staff_central_id'] = $staff_central_id;
                $leave_balance_item['leave_id'] = $system_leaves->where('leave_code', 8)->first()->leave_id;
                $leave_balance_item['fy_id'] = $fiscal_year_details->id;
                $leave_balance_item['description'] = "Redeemed / Earned Leave in Payroll";
                $leave_balance_item['consumption'] = $consumed_substitute_leave;
                $leave_balance_item['earned'] = $total_substitute_leave_earned_this_month;
                $leave_balance_item['balance'] = $total_substitute_leave_balance;
                $leave_balance_item['created_at'] = Carbon::now();
                $leave_balance_item['updated_at'] = Carbon::now();
                $leave_balance_item['date'] = date('Y-m-d');
                $leave_balance_item['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $leave_balance_item['payroll_id'] = $payroll_id;
                $leave_balance_bulk[] = $leave_balance_item;

                if ($consumed_maternity_leave > 0) {
                    $maternity_leave_balance = $staff_details->maternityLeaveBalanceLast->balance ?? 0;
                    $total_maternity_leave_balance = $maternity_leave_balance - $consumed_maternity_leave;
                    $leave_balance_item['staff_central_id'] = $staff_central_id;
                    $leave_balance_item['leave_id'] = $system_leaves->where('leave_code', 5)->first()->leave_id;
                    $leave_balance_item['fy_id'] = $fiscal_year_details->id;
                    $leave_balance_item['description'] = "Consumed Leave in Payroll";
                    $leave_balance_item['consumption'] = $consumed_maternity_leave;
                    $leave_balance_item['earned'] = 0;
                    $leave_balance_item['balance'] = $total_maternity_leave_balance;
                    $leave_balance_item['created_at'] = Carbon::now();
                    $leave_balance_item['updated_at'] = Carbon::now();
                    $leave_balance_item['date'] = date('Y-m-d');
                    $leave_balance_item['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                    $leave_balance_item['payroll_id'] = $payroll_id;
                    $leave_balance_bulk[] = $leave_balance_item;
                }

                if ($consumed_maternity_care_leave > 0) {
                    $maternity_care_leave_balance = $staff_details->maternity_careCareLeaveBalanceLast->balance ?? 0;
                    $total_maternity_care_leave_balance = $maternity_care_leave_balance - $consumed_maternity_care_leave;
                    $leave_balance_item['staff_central_id'] = $staff_central_id;
                    $leave_balance_item['leave_id'] = $system_leaves->where('leave_code', 6)->first()->leave_id;
                    $leave_balance_item['fy_id'] = $fiscal_year_details->id;
                    $leave_balance_item['description'] = "Consumed Leave in Payroll";
                    $leave_balance_item['consumption'] = $consumed_maternity_care_leave;
                    $leave_balance_item['earned'] = 0;
                    $leave_balance_item['balance'] = $total_maternity_care_leave_balance;
                    $leave_balance_item['created_at'] = Carbon::now();
                    $leave_balance_item['updated_at'] = Carbon::now();
                    $leave_balance_item['date'] = date('Y-m-d');
                    $leave_balance_item['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                    $leave_balance_item['payroll_id'] = $payroll_id;
                    $leave_balance_bulk[] = $leave_balance_item;
                }

                if ($consumed_funeral_leave > 0) {
                    $funeral_leave_balance = $staff_details->funeralLeaveBalanceLast->balance ?? 0;
                    $total_funeral_leave_balance = $funeral_leave_balance - $consumed_funeral_leave;
                    $leave_balance_item['staff_central_id'] = $staff_central_id;
                    $leave_balance_item['leave_id'] = $system_leaves->where('leave_code', 7)->first()->leave_id;
                    $leave_balance_item['fy_id'] = $fiscal_year_details->id;
                    $leave_balance_item['description'] = "Consumed Leave in Payroll";
                    $leave_balance_item['consumption'] = $consumed_funeral_leave;
                    $leave_balance_item['earned'] = 0;
                    $leave_balance_item['balance'] = $total_funeral_leave_balance;
                    $leave_balance_item['created_at'] = Carbon::now();
                    $leave_balance_item['updated_at'] = Carbon::now();
                    $leave_balance_item['date'] = date('Y-m-d');
                    $leave_balance_item['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                    $leave_balance_item['payroll_id'] = $payroll_id;
                    $leave_balance_bulk[] = $leave_balance_item;
                }

                /*approved leave from grant leave module*/
                $already_approved_home_leave = $staff_details->grantLeaveSplits->where('calenderholiday.leave.leave_code', 3)->sum('leave_days');
                $already_approved_sick_leave = $staff_details->grantLeaveSplits->where('calenderholiday.leave.leave_code', 4)->sum('leave_days');
                $already_approved_maternity_leave = $staff_details->grantLeaveSplits->where('calenderholiday.leave.leave_code', 5)->sum('leave_days');
                $already_approved_maternity_care_leave = $staff_details->grantLeaveSplits->where('calenderholiday.leave.leave_code', 6)->sum('leave_days');
                $already_approved_funeral_leave = $staff_details->grantLeaveSplits->where('calenderholiday.leave.leave_code', 7)->sum('leave_days');
                $already_approved_substitute_leave = $staff_details->grantLeaveSplits->where('calenderholiday.leave.leave_code', 8)->sum('leave_days');

                $total_approved_home_leave_this_month = $input_field_grant_home_leave + $already_approved_home_leave;
                $total_approved_sick_leave_this_month = $input_field_grant_sick_leave + $already_approved_sick_leave;
                $total_approved_maternity_leave_this_month = $input_field_grant_maternity_leave + $already_approved_maternity_leave;
                $total_approved_maternity_care_leave_this_month = $input_field_grant_maternity_care_leave + $already_approved_maternity_care_leave;
                $total_approved_funeral_leave_this_month = $input_field_grant_funeral_leave + $already_approved_funeral_leave;
                $total_approved_substitute_leave_this_month = $input_field_grant_substitute_leave + $already_approved_substitute_leave;

                $total_approved_leave = $total_approved_home_leave_this_month + $total_approved_sick_leave_this_month +
                    $total_approved_maternity_leave_this_month + $total_approved_maternity_care_leave_this_month + $total_approved_funeral_leave_this_month +
                    $total_approved_substitute_leave_this_month;

                //check conditions for allowances
                $dearness_allowance = (!empty($staff_details->dearness_allowance_amount)) ? $staff_details->dearness_allowance_amount : 0;
                $special_allowance = (!empty($staff_details->special_allowance_amount)) ? $staff_details->special_allowance_amount : 0;
                $special_allowance_2 = (!empty($staff_details->special_allowance_2_amount)) ? $staff_details->special_allowance_2_amount : 0;
                $gratuity_allowance = ($staff_details->gratuity_allow == 1) ? $this->getAllowanceByID(3) : 0;
                $risk_allowance = (!empty($staff_details->risk_allowance_amount)) ? $staff_details->risk_allowance_amount : 0;
                $incentive_amount = (!empty($staff_details->incentive_amount)) ? $staff_details->incentive_amount : 0;
                $dashain_allowance = ($staff_details->dashain_allow == 1) ? $this->getAllowanceByID(5) : 0;
                $other_allowance = ($staff_details->other_allow == 1) ? $this->getAllowanceByID(6) : 0;
                $outstation_facility_allowance = ($staff_details->outstation_facility_allow == 1) ? $staff_details->outstation_facility_amount : 0;


                $salary_payable_hours = $this->salary_hour_payable($total_present_days_current_month, $absent_weekend_days, $absent_on_public_holiday, $absent_public_holiday_on_weekend_days, $total_work_hours_selected_month, $total_approved_leave, $staff_workschedule_total_work_hours, $total_days_in_nepali_month);

                if (strcasecmp($job_type_details->jobtype_code, "Con") != 0) {
                    $overtime_payable_hours = $this->overtime_hour_payable($total_present_days_current_month, $total_work_hours_selected_month, $staff_workschedule_total_work_hours, $present_on_public_holiday_hours, $total_public_holidays, $total_weekend_work_hours);
                } else {
                    $overtime_payable_hours = 0;
                }

                $basic_salary = $basic_salary + $grade_amount + $additional_salary;
                $special_allowance = $special_allowance + $special_allowance_2 + $risk_allowance;


                if (Config::get('constants.payroll_type_implemented') == 1) {
                    $new_basic_salary = round($this->basic_salary($basic_salary, $staff_workschedule_total_work_hours, $salary_payable_hours, $total_days_in_nepali_month));
                    $new_dearness_allowance = round($this->dearness_allowance($dearness_allowance, $staff_workschedule_total_work_hours, $total_days_in_nepali_month, $salary_payable_hours));
                    $new_special_allowance = round($this->special_allowance($special_allowance, $staff_workschedule_total_work_hours, $total_days_in_nepali_month, $salary_payable_hours));
                    $new_outstation_facility_amount = round($this->outstation_allowance($outstation_facility_allowance, $staff_workschedule_total_work_hours, $total_days_in_nepali_month, $salary_payable_hours));
                    $new_incentive_amount = round($this->incentive($salary_payable_hours, $incentive_amount, $total_present_days_current_month, $absent_weekend_days, $absent_on_public_holiday, $absent_public_holiday_on_weekend_days, $total_present_days_current_month));
                    $extra_allowance = 0;
                    if ($staff_details->extra_allow == 1) {
                        $extra_allowance = $this->extra_allowance($salary_payable_hours, $basic_salary, $dearness_allowance, $special_allowance, $staff_workschedule_total_work_hours, $total_days_in_nepali_month, $total_weekend_work_hours, $present_on_public_holiday_hours);
                        $extra_allowance = round($extra_allowance);
                    }
                } else {
                    $total_days_payable = $total_present_days_current_month + $total_weekend_days + $total_public_holidays + $total_approved_leave;
                    $new_basic_salary = round($this->basic_salary_by_day($basic_salary, $total_days_payable, $total_days_in_nepali_month));
                    $new_dearness_allowance = round($this->dearness_allowance_by_day($dearness_allowance, $total_days_payable, $total_days_in_nepali_month));
                    $new_special_allowance = round($this->special_allowance_by_day($special_allowance, $total_days_payable, $total_days_in_nepali_month));
                    $new_outstation_facility_amount = round($this->outstation_allowance_by_day($outstation_facility_allowance, $total_days_payable, $total_days_in_nepali_month));
                    $new_incentive_amount = round($this->incentive_by_day($incentive_amount, $total_days_payable, $total_days_in_nepali_month));
                    $extra_allowance = 0;
                    if ($staff_details->extra_allow == 1) {
                        $extra_allowance = $this->extra_allowance_by_day($salary_payable_hours, $total_days_payable, $total_days_in_nepali_month);
                        $extra_allowance = round($extra_allowance);
                    }
                }

                $basic_salary_for_ot = $basic_salary;
                if (strcasecmp($job_type_details->jobtype_code, "Con") == 0) {
                    $basic_salary_for_ot = $minimum_basic_salary;
                }
                $ot_amount = round($this->ot_amount($basic_salary_for_ot, $staff_workschedule_total_work_hours, $total_days_in_nepali_month, $overtime_payable_hours));


                //tds calculations
                //first get the marital status of the staff
                $staff_marital_status = (int)$staff_details->marrid_stat;
                if (empty($staff_details->social_security_fund_acc_no)) {
                    $profund_percent = $job_type_details->profund_per;
                    $gratuity_percent = $job_type_details->gratuity;
                } else {
                    $profund_percent = 0;
                    $gratuity_percent = $job_type_details->profund_per + $job_type_details->gratuity;
                }
                $profund_contri_per = $job_type_details->profund_contri_per;
                $social_security_fund_per = $job_type_details->social_security_fund_per;

                if ($job_type_details->jobtype_code == "Con" || $job_type_details->jobtype_code == "Con1") {
                    $gratuity_amt = 0;
                    $profund_contribution_amt = 0;
                    $profund_amt = 0;
                    $social_security_fund_amt = 0;
                } else {
                    $gratuity_amt = round(($new_basic_salary * ($gratuity_percent / 100)));
                    $profund_contribution_amt = round($new_basic_salary * ($profund_contri_per / 100));
                    $profund_amt = round($new_basic_salary * ($profund_percent / 100));
                    $social_security_fund_amt = round($new_basic_salary * ($social_security_fund_per / 100));
                }


                //get attendance details for the staff

                $total_unpaid_leave = $total_days_in_nepali_month - $total_present_days_current_month - $absent_weekend_days - $absent_on_public_holiday - $total_approved_leave + $absent_public_holiday_on_weekend_days;

                $redeem_home_leave_amount = $redeem_home_leave[$staff_central_id] * (($basic_salary + $dearness_allowance + $special_allowance) / 30);
                $redeem_sick_leave_amount = $redeem_sick_leave[$staff_central_id] * (($basic_salary + $dearness_allowance + $special_allowance) / 30);
                $total_redeem_home_and_sick_amt = round($redeem_home_leave_amount + $redeem_sick_leave_amount);

                //outstation facility
                $outstation_facility = ($staff_details->outstation_facility_allow == 0) ? $staff_details->outstation_facility_amount : $this->getAllowanceByID(8);
                $total_profund_amt = $profund_amt + $profund_contribution_amt;
                //gross payment
                $gross_payment = $new_basic_salary + $new_dearness_allowance + $new_special_allowance + $new_outstation_facility_amount + $incentive_amount + $gratuity_amt + $extra_allowance
                    + $profund_amt + $ot_amount + $total_redeem_home_and_sick_amt + $social_security_fund_amt;

                //deduct profund and gratuity from total salary
                $total_monthly_salary_after_pf_g = round($gross_payment - $gratuity_amt - $profund_amt - $profund_contribution_amt - $social_security_fund_amt);

                //tds slab by marital status and total salary
                $total_taxable_yearly_salary = ($total_monthly_salary_after_pf_g) * config('constants.tax_payable_months_number');

                $tds = round($this->tdsDeductionByMaritalStatusAndAmount($staff_marital_status, $total_taxable_yearly_salary, $fiscal_year_details, $systemtdsmastmodel) / config('constants.tax_payable_months_number'));
                $gross_payment_after_tax = ($gross_payment - ($profund_amt + $profund_contribution_amt + $gratuity_amt + $social_security_fund_amt + $tds));
                //loan deduction

                //variable Initialization
                $house_loan_installment = 0;
                $vehicle_loan_installment = 0;
                $cr_amount = 0;
                $dr_amount = 0;

                //check if houseloan check box is checked
                $house_loan = $staff_details->houseLoanToDeduct;
                if (!empty($house_loan)) { // if has house loan
                    $house_loan_installment = $staff_details->loanDeducation->where('loan_type', LoanDeduct::HOUSE_LOAN_TYPE_ID)->first()->loan_deduct_amount ?? 0;
                    if ($house_loan_installment != 0 && $house_loan_installment > $gross_payment_after_tax) { // if has house loan
                        if ($house_loan_installment > (($house_loan->loan_amount ?? 0) - ($house_loan->paid_amt ?? 0))) {
                            $house_loan_installment = (($house_loan->loan_amount ?? 0) - ($house_loan->paid_amt ?? 0));
                        } else {
                            $house_loan_installment = 0;
                        }
                    }

                    $house_loan->deduct_amt = $house_loan->deduct_amt + $house_loan_installment;
                    $house_loan->paid_amt = $house_loan->paid_amt + $house_loan_installment;
                    $house_loan->balance_amt = $house_loan->balance_amt + $house_loan_installment;
                    $house_loan->account_status = 1;
                    if ($house_loan->loan_amount == $house_loan->paid_amt) {
                        $house_loan->account_status = 2;
                    }
                    if ($house_loan->save()) {
                        $house_loan_transaction_log = new HouseLoanTransactionLog();
                        $house_loan_transaction_log->house_id = $house_loan->house_id;
                        $house_loan_transaction_log->staff_central_id = $house_loan->staff_central_id;
                        $house_loan_transaction_log->trans_date = Date('Y-m-d');
                        $house_loan_transaction_log->paid_installment_amt = $house_loan_installment;
                        $house_loan_transaction_log->remaining_amt = $house_loan->loan_amount - $house_loan->paid_amt;
                        $house_loan_transaction_log->detail_note = 'Paid in the payroll month of ' . $payroll_month;
                        $house_loan_transaction_log->deduc_salary_month = $payroll_month;
                        $house_loan_transaction_log->payroll_id = $payroll_id;
                        if ($house_loan_transaction_log->save()) {
                            $status_mesg = true;
                        } else {
                            $status_mesg = false;
                        }
                    }
                }

                $vehicle_loan = $staff_details->vehicleLoanToDeduct;
                if (!empty($vehicle_loan)) {
                    $vehicle_loan_installment = $staff_details->loanDeducation->where('loan_type', LoanDeduct::VEHICLE_LOAN_TYPE_ID)->first()->loan_deduct_amount ?? 0;
                    if ($vehicle_loan_installment != 0 && $vehicle_loan_installment > ($gross_payment_after_tax - $house_loan_installment)) { // if has vehicle loan
                        if ($vehicle_loan_installment > (($vehicle_loan->loan_amount ?? 0) - ($vehicle_loan->paid_amt ?? 0))) {
                            $vehicle_loan_installment = (($vehicle_loan->loan_amount ?? 0) - ($vehicle_loan->paid_amt ?? 0));
                        } else {
                            $vehicle_loan_installment = 0;
                        }
                    }

                    $vehicle_loan->deduct_amt = $vehicle_loan->deduct_amt + $vehicle_loan_installment;
                    $vehicle_loan->paid_amt = $vehicle_loan->paid_amt + $vehicle_loan_installment;
                    $vehicle_loan->balance_amt = $vehicle_loan->balance_amt + $vehicle_loan_installment;
                    $vehicle_loan->account_status = 1;
                    if ($vehicle_loan->loan_amount == $vehicle_loan->paid_amt) {
                        $vehicle_loan->account_status = 2;
                    }
                    if ($vehicle_loan->save()) {
                        $vehicle_loan_transaction_log = new VehicleLoanTransactionLog();
                        $vehicle_loan_transaction_log->vehical_id = $vehicle_loan->vehical_id;
                        $vehicle_loan_transaction_log->staff_central_id = $vehicle_loan->staff_central_id;
                        $vehicle_loan_transaction_log->trans_date = Date('Y-m-d');
                        $vehicle_loan_transaction_log->paid_installment_amt = $vehicle_loan_installment;
                        $vehicle_loan_transaction_log->remaining_amt = $vehicle_loan->loan_amount - $vehicle_loan->paid_amt;
                        $vehicle_loan_transaction_log->detail_note = 'Paid in the payroll month of ' . $payroll_month;
                        $vehicle_loan_transaction_log->deduc_salary_month = $payroll_month;
                        $vehicle_loan_transaction_log->payroll_id = $payroll_id;
                        if ($vehicle_loan_transaction_log->save()) {
                            $status_mesg = true;
                        } else {
                            $status_mesg = false;
                        }
                    }
                }
                $total_loan = $house_loan_installment + $vehicle_loan_installment;

                if (($gross_payment_after_tax - $total_loan) > 0) {
                    $sundry_stat = array(0, 1);
                    $sundrys = $staff_details->sundryLoan->where('transaction_date_en', '<=', date('Y-m-d'))->whereIn('status', $sundry_stat);

                    if (!empty($sundrys)) {
                        foreach ($sundrys as $sundry) {
                            $is_cr = SundryType::isCR($sundry->transaction_type_id);//if cr get cr's installment amount to be paid
                            if ($is_cr) { //the transaction is cr so get cr amount

                                $cr_amount += $sundry->cr_amount;
                                if (empty($sundry->dr_installment)) {
                                    $sundry->dr_installment = 1;
                                } else {
                                    $sundry->dr_installment += 1;
                                }
                                $sundry->dr_amount = $sundry->cr_amount;
                                if (empty($sundry->dr_balance)) {
                                    $sundry->dr_balance = $sundry->cr_amount;
                                } else {
                                    $sundry->dr_balance += $sundry->cr_amount;
                                }

                                $sundry->status = 1;
                                if ($sundry->dr_balance == $sundry->cr_balance) {
                                    $sundry->status = 2;
                                }
                                if ($sundry->save()) {
                                    $sundry_logs = new SundryTransactionLog();
                                    $sundry_logs->sundry_id = $sundry->id;
                                    $sundry_logs->staff_central_id = $staff_central_id;
                                    $sundry_logs->transaction_date = BSDateHelper::AdToBs('-', Date('Y-m-d'));
                                    $sundry_logs->transaction_date_en = Date('Y-m-d');
                                    $sundry_logs->transaction_type_id = $sundry->transaction_type_id;
                                    $sundry_logs->dr_installment = 1;
                                    $sundry_logs->dr_amount = $sundry->cr_amount;
                                    $sundry_logs->dr_balance = $sundry->dr_balance;
                                    $sundry_logs->notes = 'Deducted in the payroll month of ' . $payroll_month;
                                    $sundry_logs->payroll_id = $payroll_id;
                                    if ($sundry_logs->save()) {
                                        $status_mesg = true;
                                    } else {
                                        $status_mesg = false;
                                    }
                                }
                            } else { //the transaction is dr so get dr amount

                                $dr_amount += $sundry->dr_amount;
                                if (empty($sundry->cr_installment)) {
                                    $sundry->cr_installment = 0;
                                }
                                $sundry->cr_installment += 1;
                                $sundry->cr_amount = $sundry->dr_amount;
                                if (empty($sundry->cr_balance)) {
                                    $sundry->cr_balance = 0;
                                }
                                $sundry->cr_balance += $sundry->dr_amount;
                                $sundry->status = 1;
                                if ($sundry->cr_balance == $sundry->dr_balance) {
                                    $sundry->status = 2;
                                }
                                if ($sundry->save()) {
                                    $sundry_logs = new SundryTransactionLog();
                                    $sundry_logs->sundry_id = $sundry->id;
                                    $sundry_logs->staff_central_id = $staff_central_id;
                                    $sundry_logs->transaction_date = BSDateHelper::AdToBs('-', Date('Y-m-d'));
                                    $sundry_logs->transaction_date_en = Date('Y-m-d');
                                    $sundry_logs->transaction_type_id = $sundry->transaction_type_id;
                                    $sundry_logs->cr_installment = 1;
                                    $sundry_logs->cr_amount = $sundry->dr_amount;
                                    $sundry_logs->cr_balance = $sundry->cr_balance;
                                    $sundry_logs->notes = 'Deducted in the payroll month of ' . $payroll_month;
                                    $sundry_logs->payroll_id = $payroll_id;
                                    if ($sundry_logs->save()) {
                                        $status_mesg = true;
                                    } else {
                                        $status_mesg = false;
                                    }
                                }
                            }
                        }
                    }
                }

                $sundry_balance = SundryBalance::where('staff_central_id', $staff_central_id)->first();
                if (!empty($sundry_balance)) {
                    if (empty($sundry_balance->dr_installment)) {
                        $sundry_balance->dr_installment = 1;

                    } else {
                        $sundry_balance->dr_installment += 1;
                    }

                    $sundry_balance->dr_amount += $cr_amount;
                    if (empty($sundry_balance->dr_balance)) {
                        $sundry_balance->dr_balance = 0;
                    }
                    $sundry_balance->dr_balance += $cr_amount;
                    if (empty($sundry_balance->cr_installment)) {
                        $sundry_balance->cr_installment = 0;
                    }
                    $sundry_balance->cr_installment += 1;
                    $sundry_balance->cr_amount += $dr_amount;
                    if (empty($sundry_balance->cr_balance)) {
                        $sundry_balance->cr_balance = 0;
                    }
                    $sundry_balance->cr_balance += $dr_amount;
                    if ($sundry_balance->save()) {
                        $status_mesg = true;
                    } else {
                        $status_mesg = false;
                    }
                }

                $sundry_difference = $cr_amount - $dr_amount;

                $levy_amount = 0;
                if ($staff_details->deduct_levy == 1 && $gross_payment_after_tax > 0) {
                    if (($job_type_details->jobtype_code == "P") || ($job_type_details->jobtype_code == "NP")) {
                        $levy_amount = (Config::get('constants.levy_amount'));
                    }
                }

                $net_payment = ($gross_payment - ($profund_amt + $profund_contribution_amt + $gratuity_amt + $social_security_fund_amt + $tds)) - $levy_amount + $sundry_difference - $total_loan;
                $net_payment = round($net_payment);
                if (empty($staff_details->acc_no) || empty($staff_details->bank_id)) {
                    $cash_statement_item['payroll_id'] = $payroll_id;
                    $cash_statement_item['staff_central_id'] = $staff_central_id;
                    $cash_statement_item['branch_id'] = $payroll_details->branch_id;
                    $cash_statement_item['total_payment'] = $net_payment;
                    $cash_statement_item['remarks'] = '';
                    $cash_statement_bulk_array[] = $cash_statement_item;

                } else {
                    $bank_statement_item['payroll_id'] = $payroll_id;
                    $bank_statement_item['staff_central_id'] = $staff_central_id;
                    $bank_statement_item['branch_id'] = $payroll_details->branch_id;
                    $bank_statement_item['bank_id'] = $staff_details->bank_id;
                    $bank_statement_item['acc_no'] = $staff_details->acc_no;
                    $bank_statement_item['brcode'] = substr($staff_details->acc_no, 0, 3);
                    $bank_statement_item['trans_type'] = 'C';
                    $bank_statement_item['total_payment'] = $net_payment;
                    $bank_statement_item['remarks'] = '';
                    $bank_statement_bulk_array[] = $bank_statement_item;
                }
                $profund_statement['payroll_id'] = $payroll_id;
                $profund_statement['staff_central_id'] = $staff_central_id;
                $profund_statement['branch_id'] = $payroll_details->branch_id;
                $profund_statement['post_id'] = $staff_details->post_id;
                $profund_statement['employee_contri'] = $profund_amt;
                $profund_statement['company_contri'] = $profund_contribution_amt;
                $profund_statement_bulk_array[] = $profund_statement;


                $cit_statement['payroll_id'] = $payroll_id;
                $cit_statement['staff_central_id'] = $staff_central_id;
                $cit_statement['branch_id'] = $payroll_details->branch_id;
                $cit_statement['post_id'] = $staff_details->post_id;
                $cit_statement['cit_amount'] = $gratuity_amt;
                $cit_statement_bulk_array[] = $cit_statement;

                $tdsArray = $this->getTdsDeductionAmountBySlabNumber($staff_marital_status, $total_taxable_yearly_salary, $fiscal_year_details, $systemtdsmastmodel);
                $tdsFirstSlab = $tdsArray[SystemTdsMastModel::firstSlab];

                // removing first slab from tds for TaxStatement
                unset($tdsArray[SystemTdsMastModel::firstSlab]);
                $tdsWithoutFirstSlab = $tdsArray;


                $socialSecurity_statement['payroll_id'] = $payroll_id;
                $socialSecurity_statement['staff_central_id'] = $staff_central_id;
                $socialSecurity_statement['branch_id'] = $payroll_details->branch_id;
                $socialSecurity_statement['post_id'] = $staff_details->post_id;
                $socialSecurity_statement['tax_amount'] = $tdsFirstSlab;
                $socialSecurity_statement_bulk_array[] = $socialSecurity_statement;


                $tax_statement['payroll_id'] = $payroll_id;
                $tax_statement['staff_central_id'] = $staff_central_id;
                $tax_statement['branch_id'] = $payroll_details->branch_id;
                $tax_statement['post_id'] = $staff_details->post_id;
                $tax_statement['tax_amount'] = array_sum($tdsWithoutFirstSlab) / 12;
                $tax_statement_bulk_array[] = $tax_statement;


                $payroll_confirm = $payroll_confirms->where('staff_central_id', $staff_central_id)->first();
                if (empty($payroll_confirm)) {
                    $payroll_confirm = new PayrollConfirm();
                }
                $payroll_confirm->payroll_id = $payroll_id;
                $payroll_confirm->staff_central_id = $staff_central_id;
                $payroll_confirm->min_work_hour = $staff_workschedule_total_work_hours;
                $payroll_confirm->tax_code = $job_type_details->jobtype_name;
                $payroll_confirm->total_worked_hours = $total_work_hours_selected_month;
                $payroll_confirm->days_absent_on_holiday = $absent_on_public_holiday + $absent_weekend_days - $absent_public_holiday_on_weekend_days;
                $payroll_confirm->weekend_work_hours = $total_weekend_work_hours;
                $payroll_confirm->public_holiday_work_hours = $present_on_public_holiday_hours;
                $payroll_confirm->present_days = $total_present_days_current_month;
                $payroll_confirm->absent_days = $staff_absent_dates_this_payroll_month;
                $payroll_confirm->redeem_home_leave = $redeem_home_leave[$staff_central_id];
                $payroll_confirm->redeem_sick_leave = $redeem_sick_leave[$staff_central_id];
                $payroll_confirm->salary_hour_payable = $salary_payable_hours;
                $payroll_confirm->ot_hour_payable = $overtime_payable_hours;
                $payroll_confirm->basic_salary = $new_basic_salary;
                $payroll_confirm->dearness_allowance = $new_dearness_allowance;
                $payroll_confirm->special_allowance = $new_special_allowance;
                $payroll_confirm->extra_allowance = $extra_allowance;
                $payroll_confirm->gratuity_amount = $gratuity_amt;
                $payroll_confirm->social_security_fund_amount = $social_security_fund_amt;
                $payroll_confirm->incentive = $new_incentive_amount;
                $payroll_confirm->outstation_facility_amount = $new_outstation_facility_amount;
                $payroll_confirm->pro_fund = $profund_amt;
                $payroll_confirm->pro_fund_contribution = $profund_contribution_amt;
                $payroll_confirm->home_sick_redeem_amount = $total_redeem_home_and_sick_amt;
                $payroll_confirm->ot_amount = $ot_amount;
                $payroll_confirm->gross_payable = $gross_payment;
                $payroll_confirm->loan_payment = $total_loan;
                $payroll_confirm->sundry_dr = $dr_amount;
                $payroll_confirm->sundry_cr = $cr_amount;
                $payroll_confirm->tax = $tds;
                $payroll_confirm->net_payable = $net_payment;
                $payroll_confirm->remarks = $remarks[$staff_central_id];

                $payroll_confirm->levy_amount = $levy_amount;
                $payroll_confirm->home_leave_taken = $total_approved_home_leave_this_month;
                $payroll_confirm->sick_leave_taken = $total_approved_sick_leave_this_month;
                $payroll_confirm->maternity_leave_taken = $total_approved_maternity_leave_this_month;
                $payroll_confirm->maternity_care_leave_taken = $total_approved_maternity_care_leave_this_month;
                $payroll_confirm->funeral_leave_taken = $total_approved_funeral_leave_this_month;
                $payroll_confirm->substitute_leave_taken = $total_approved_substitute_leave_this_month;
                $payroll_confirm->unpaid_leave_taken = $total_unpaid_leave;
                $payroll_confirm->suspended_days = $staff_suspense_days;
                $payroll_confirm->useable_home_leave = $total_home_leave_balance;
                $payroll_confirm->useable_sick_leave = $total_sick_leave_balance;
                $payroll_confirm->useable_substitute_leave = $total_substitute_leave_balance;


                if ($payroll_confirm->save()) {

                    $home_leave_payroll_confirm_info = $payroll_confirm->payrollConfirmLeaveInfos->where('leaveMast.leave_code', '3')->first();
                    $sick_leave_payroll_confirm_info = $payroll_confirm->payrollConfirmLeaveInfos->where('leaveMast.leave_code', '4')->first();
                    $substitute_leave_payroll_confirm_info = $payroll_confirm->payrollConfirmLeaveInfos->where('leaveMast.leave_code', '8')->first();


                    if (empty($home_leave_payroll_confirm_info)) {
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['leave_id'] = $system_leaves->where('leave_code', '3')->first()->leave_id ?? null;
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['used'] = $redeem_home_leave[$staff_central_id] + $consumed_home_leave;
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['earned'] = $earnable_home_leave;
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['balance'] = $home_leave_balance;
                        $payroll_confirm_leave_counter++;
                    } else {
                        if ($home_leave_payroll_confirm_info->used != ($redeem_home_leave[$staff_central_id] + $consumed_home_leave)
                            || $home_leave_payroll_confirm_info->earned != $earnable_home_leave
                            || $home_leave_payroll_confirm_info->balance != $home_leave_balance) {
                            $home_leave_payroll_confirm_info->used = ($redeem_home_leave[$staff_central_id] + $consumed_home_leave);
                            $home_leave_payroll_confirm_info->earned = $earnable_home_leave;
                            $home_leave_payroll_confirm_info->balance = $home_leave_balance;
                            $home_leave_payroll_confirm_info->save();
                        }
                    }

                    if (empty($sick_leave_payroll_confirm_info)) {
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['leave_id'] = $system_leaves->where('leave_code', '4')->first()->leave_id ?? null;
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['used'] = $redeem_sick_leave[$staff_central_id] + $consumed_sick_leave;
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['earned'] = $earnable_sick_leave;
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['balance'] = $sick_leave_balance;
                        $payroll_confirm_leave_counter++;
                    } else {
                        if ($sick_leave_payroll_confirm_info->used != ($redeem_sick_leave[$staff_central_id] + $consumed_sick_leave)
                            || $sick_leave_payroll_confirm_info->earned != $earnable_sick_leave
                            || $sick_leave_payroll_confirm_info->balance != $sick_leave_balance) {
                            $sick_leave_payroll_confirm_info->used = ($redeem_sick_leave[$staff_central_id] + $consumed_sick_leave);
                            $sick_leave_payroll_confirm_info->earned = $earnable_sick_leave;
                            $sick_leave_payroll_confirm_info->balance = $sick_leave_balance;
                            $sick_leave_payroll_confirm_info->save();
                        }
                    }

                    if (empty($substitute_leave_payroll_confirm_info)) {
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['leave_id'] = $system_leaves->where('leave_code', '8')->first()->leave_id ?? null;
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['used'] = $consumed_substitute_leave;
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['earned'] = $total_substitute_leave_earned_this_month;
                        $payroll_confirm_leave[$payroll_confirm_leave_counter]['balance'] = $substitute_leave_balance;
                        $payroll_confirm_leave_counter++;
                    } else {
                        if ($substitute_leave_payroll_confirm_info->used != $consumed_substitute_leave
                            || $substitute_leave_payroll_confirm_info->earned != $total_substitute_leave_earned_this_month
                            || $substitute_leave_payroll_confirm_info->balance != $substitute_leave_balance) {
                            $substitute_leave_payroll_confirm_info->used = $consumed_substitute_leave;
                            $substitute_leave_payroll_confirm_info->earned = $total_substitute_leave_earned_this_month;
                            $substitute_leave_payroll_confirm_info->balance = $substitute_leave_balance;
                            $substitute_leave_payroll_confirm_info->save();
                        }
                    }


                    //relational allowance information
                    $dearness_allowance_payroll_confirm = $payroll_confirm->payrollConfirmAllowances->where('allowanceMast.allow_code', '001')->first();
                    $special_allowance_payroll_confirm = $payroll_confirm->payrollConfirmAllowances->where('allowanceMast.allow_code', '003')->first();
                    $outstation_allowance_payroll_confirm = $payroll_confirm->payrollConfirmAllowances->where('allowanceMast.allow_code', '007')->first();
                    $extra_allowance_payroll_confirm = $payroll_confirm->payrollConfirmAllowances->where('allowanceMast.allow_code', '008')->first();
                    $incentive_allowance_payroll_confirm = $payroll_confirm->payrollConfirmAllowances->where('allowanceMast.allow_code', '006')->first();

                    if (empty($dearness_allowance_payroll_confirm)) {
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '001')->first()->allow_id ?? null;
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $new_dearness_allowance;
                        $payroll_confirm_allowance_counter++;
                    } else {
                        if ($dearness_allowance_payroll_confirm->amount != $new_dearness_allowance) {
                            $dearness_allowance_payroll_confirm->amount = $new_dearness_allowance;
                            $dearness_allowance_payroll_confirm->save();
                        }
                    }

                    if (empty($special_allowance_payroll_confirm)) {
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '003')->first()->allow_id ?? null;
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $new_special_allowance;
                        $payroll_confirm_allowance_counter++;
                    } else {
                        if ($special_allowance_payroll_confirm->amount != $new_special_allowance) {
                            $special_allowance_payroll_confirm->amount = $new_special_allowance;
                            $special_allowance_payroll_confirm->save();
                        }
                    }

                    if (empty($outstation_allowance_payroll_confirm)) {
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '007')->first()->allow_id ?? null;
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $new_outstation_facility_amount;
                        $payroll_confirm_allowance_counter++;
                    } else {
                        if ($outstation_allowance_payroll_confirm->amount != $new_outstation_facility_amount) {
                            $outstation_allowance_payroll_confirm->amount = $new_outstation_facility_amount;
                            $outstation_allowance_payroll_confirm->save();
                        }
                    }
                    if (empty($extra_allowance_payroll_confirm)) {
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '008')->first()->allow_id ?? null;
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $extra_allowance;
                        $payroll_confirm_allowance_counter++;
                    } else {
                        if ($extra_allowance_payroll_confirm->amount != $extra_allowance) {
                            $extra_allowance_payroll_confirm->amount = $extra_allowance;
                            $extra_allowance_payroll_confirm->save();
                        }
                    }

                    if (empty($incentive_allowance_payroll_confirm)) {
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '006')->first()->allow_id ?? null;
                        $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $new_incentive_amount;
                        $payroll_confirm_allowance_counter++;
                    } else {
                        if ($incentive_allowance_payroll_confirm->amount != $new_incentive_amount) {
                            $incentive_allowance_payroll_confirm->amount = $new_incentive_amount;
                            $incentive_allowance_payroll_confirm->save();
                        }
                    }
                    $status_mesg = true;
                } else {
                    $status_mesg = false;
                }


                $calculation_data[] = array(
                    'staff_central_id' => $staff_central_id,
                    'main_id' => $main_id,
                    'staff_workschedule_total_work_hours' => $staff_workschedule_total_work_hours,
                    'marital_status' => ($staff_details->marrid_stat == 1) ? 'Couple' : 'Single',
                    'job_type' => $job_type_details->jobtype_name,
                    'post' => $post,
                    'absent_on_holidays' => $absent_on_public_holiday + $absent_weekend_days - $absent_public_holiday_on_weekend_days,
                    'staff_name' => $staff_details->name_eng,
                    'basic_salary' => $new_basic_salary,
                    'grade_amount' => $grade_amount,
                    'additional_salary' => $additional_salary,
                    'total_monthly_salary' => $total_monthly_salary,
                    'total_work_hours_selected_month' => $total_work_hours_selected_month,
                    'total_work_hours_salary' => $salary_payable_hours,
                    'total_ot_hours_selected_month' => $overtime_payable_hours,
                    'total_ot_hours_salary' => $ot_amount,
                    'dearness_allowance' => $new_dearness_allowance,
                    'extra_allowance' => $extra_allowance,
                    'special_allowance' => $new_special_allowance,
                    'gratuity_allowance' => $gratuity_allowance,
                    'dashain_allowance' => $dashain_allowance,
                    'risk_allowance' => $risk_allowance,
                    'other_allowance' => $other_allowance,
                    'staff_marital_status' => Config::get('constants.tds_options')[$staff_marital_status],
                    'total_monthly_salary_after_pf_g' => $total_monthly_salary_after_pf_g,
                    'profund_percent' => $profund_percent,
                    'profund_amt' => $profund_amt,
                    'gratuity_percent' => $gratuity_percent,
                    'gratuity_amt' => $gratuity_amt,
                    'social_security_fund_amt' => $social_security_fund_amt,
                    'incentive_amt' => $incentive_amount,
                    'profund_contri_per' => $profund_contri_per,
                    'profund_contribution_amt' => $profund_contribution_amt,
                    'total_taxable_yearly_salary' => $total_taxable_yearly_salary,
                    'tds' => $tds,
                    'total_days_in_nepali_month' => $total_days_in_nepali_month,
                    'total_present_days_current_month' => $total_present_days_current_month,
                    'total_weekend_days' => $total_weekend_days,
                    'absent_weekend_days' => $absent_weekend_days,
                    'total_weekend_work_hours' => $total_weekend_work_hours,
                    'total_public_holidays' => $total_public_holidays,
                    'absent_on_public_holiday' => $absent_on_public_holiday,
                    'absent_on_weekend_with_public_holiday' => $absent_public_holiday_on_weekend_days,
                    'present_on_public_holiday_hours' => $present_on_public_holiday_hours,
                    'total_working_days_in_curent_fiscal_year' => $total_working_days_in_curent_fiscal_year,
                    'total_sick_leave_earned_this_month' => $total_sick_leave_earned_this_month,
                    'total_home_leave_earned_this_month' => $total_home_leave_earned_this_month,
                    'total_approved_home_leave_this_month' => $total_approved_home_leave_this_month,
                    'total_approved_sick_leave_this_month' => $total_approved_sick_leave_this_month,
                    'total_approved_maternity_leave_this_month' => $total_approved_maternity_leave_this_month,
                    'total_approved_funeral_leave_this_month' => $total_approved_funeral_leave_this_month,
                    'total_approved_substitute_leave_this_month' => $total_approved_substitute_leave_this_month,
                    'total_unpaid_leave' => $total_unpaid_leave,
                    'home_leave_balance' => $total_home_leave_balance,
                    'sick_leave_balance' => $total_sick_leave_balance,
                    'substitute_leave_balance' => $total_substitute_leave_balance,
                    'absent_days' => $staff_absent_dates_this_payroll_month,
                    'total_profund_amt' => $total_profund_amt,
                    'gross_payment' => $gross_payment,
                    'outstation_facility' => $outstation_facility,
                    'total_loan' => $total_loan,
                    'sundry_cr_amount' => $cr_amount,
                    'sundry_dr_amount' => $dr_amount,
                    'sundry_difference' => $sundry_difference,
                    'is_cash' => $is_cash,
                    'redeem_home_leave_amount' => $redeem_home_leave_amount,
                    'redeem_sick_leave_amount' => $redeem_sick_leave_amount,
                    'redeem_home_leave' => $redeem_home_leave[$staff_central_id],
                    'redeem_sick_leave' => $redeem_sick_leave[$staff_central_id],
                    'total_home_sick_amount' => $total_redeem_home_and_sick_amt,
                    'staff_suspense_days' => $staff_suspense_days,
                    'net_payment' => $net_payment,
                    'remarks' => $remarks[$staff_central_id],
                    'levy_amount' => $levy_amount,
                );
                $count++;
            }
            PayrollConfirmLeaveInfo::insert($payroll_confirm_leave);
            PayrollConfirmAllowance::insert($payroll_confirm_allowances);
            LeaveBalance::insert($leave_balance_bulk);
            TransCashStatement::insert($cash_statement_bulk_array);
            TransBankStatement::insert($bank_statement_bulk_array);
            ProFund::insert($profund_statement_bulk_array);
            CitLedger::insert($cit_statement_bulk_array);
            SocialSecurityTaxStatement::insert($socialSecurity_statement_bulk_array);
            TaxStatement::insert($tax_statement_bulk_array);
            CalenderHolidayModel::insert($grant_leave_bulk);
            $calenderHolidayRecords = CalenderHolidayModel::where('payroll_id', $payroll_id)->get();
            $calenderHolidaySplitRecords = [];
            $calenderHolidayCounter = 0;
            foreach ($calenderHolidayRecords as $calenderHolidayRecord) {
                $calenderHolidaySplitRecords[$calenderHolidayCounter]['calender_holiday_id'] = $calenderHolidayRecord->id;
                $calenderHolidaySplitRecords[$calenderHolidayCounter]['fiscal_year_id'] = $calenderHolidayRecord->fiscal_year_id;
                $calenderHolidaySplitRecords[$calenderHolidayCounter]['leave_month'] = $payroll_details->salary_month;
                $calenderHolidaySplitRecords[$calenderHolidayCounter]['leave_days'] = 1;
                $calenderHolidayCounter++;
            }
            CalenderHolidaySplitMonth::insert($calenderHolidaySplitRecords);
        } catch (Exception $e) {
            DB::rollback();
            $status_mesg = false;
        }
        if ($status_mesg) {
            $payroll_details->confirmed_by = Auth::user()->id;
            $payroll_details->save();
            DB::commit();
        }
        return view('attendancedetail.calculate', [
                'title' => 'Payroll Calculation Confirmation',
                'details' => $calculation_data,
                'payroll_details' => $payroll_details,
                'after_save' => 1
            ]
        );

    }

    public function nepalrepayroll($payroll_id)
    {
        $payroll_detail = PayrollDetailModel::find($payroll_id);
        $payroll_information = $this->calculatePayrollNepalRe($payroll_detail);
        $data['payroll_details'] = $payroll_detail;
        $data['payroll_informations'] = $payroll_information;
        $data['title'] = 'Payroll';
        return view('nepalreinsurance.payroll.show', $data);
    }

    public function nepalrepayroll_confirm(Request $request)
    {
        $payroll_id = $request->payroll_id;
        $payroll_detail = PayrollDetailModel::find($payroll_id);
        $payroll_informations = $this->calculatePayrollNepalRe($payroll_detail);
        $allowance = AllowanceModelMast::where('allow_code', 'COP')->first();
        $cash_statement_bulk_array = [];
        $bank_statement_bulk_array = [];
        $profund_statement = [];
        $cit_statement = [];
        $socialSecurity_statement = [];
        $tax_statement = [];
        $status_mesg = false;
        try {
            DB::beginTransaction();
            foreach ($payroll_informations as $payroll_information) {
                $staff_details = $payroll_information['staff_detail'];
                $salary_without_loan_amount = $payroll_information['amount_sent_to_bank'] - $payroll_information['house_loan'] - $payroll_information['vehicle_loan'];
                //check if houseloan check box is checked
                $house_loan_installment = 0;
                $house_loan = $staff_details->houseLoanToDeduct;
                if (!empty($house_loan)) { // if has house loan
                    $house_loan_installment = $payroll_information['house_loan'];
                    if ($house_loan_installment != 0 && $house_loan_installment > $salary_without_loan_amount) { // if has house loan
                        if ($house_loan_installment > (($house_loan->loan_amount ?? 0) - ($house_loan->paid_amt ?? 0))) {
                            $house_loan_installment = (($house_loan->loan_amount ?? 0) - ($house_loan->paid_amt ?? 0));
                        } else {
                            $house_loan_installment = 0;
                        }
                    }

                    $house_loan->deduct_amt = $house_loan->deduct_amt + $house_loan_installment;
                    $house_loan->paid_amt = $house_loan->paid_amt + $house_loan_installment;
                    $house_loan->balance_amt = $house_loan->balance_amt + $house_loan_installment;
                    $house_loan->account_status = 1;
                    if ($house_loan->loan_amount == $house_loan->paid_amt) {
                        $house_loan->account_status = 2;
                    }
                    if ($house_loan->save()) {
                        $house_loan_transaction_log = new HouseLoanTransactionLog();
                        $house_loan_transaction_log->house_id = $house_loan->house_id;
                        $house_loan_transaction_log->staff_central_id = $house_loan->staff_central_id;
                        $house_loan_transaction_log->trans_date = Date('Y-m-d');
                        $house_loan_transaction_log->paid_installment_amt = $house_loan_installment;
                        $house_loan_transaction_log->remaining_amt = $house_loan->loan_amount - $house_loan->paid_amt;
                        $house_loan_transaction_log->detail_note = 'Paid in the payroll month of ' . $payroll_detail->salary_month;
                        $house_loan_transaction_log->deduc_salary_month = $payroll_detail->salary_month;
                        $house_loan_transaction_log->payroll_id = $payroll_id;
                        if ($house_loan_transaction_log->save()) {
                            $status_mesg = true;
                        } else {
                            $status_mesg = false;
                        }
                    }
                }
                $salary_without_loan_amount -= $house_loan_installment;

                $vehicle_loan_installment = 0;
                $vehicle_loan = $staff_details->vehicleLoanToDeduct;
                if (!empty($vehicle_loan)) {
                    $vehicle_loan_installment = $staff_details->loanDeducation->where('loan_type', LoanDeduct::VEHICLE_LOAN_TYPE_ID)->first()->loan_deduct_amount ?? 0;
                    if ($vehicle_loan_installment != 0 && $vehicle_loan_installment > $salary_without_loan_amount) { // if has vehicle loan
                        if ($vehicle_loan_installment > (($vehicle_loan->loan_amount ?? 0) - ($vehicle_loan->paid_amt ?? 0))) {
                            $vehicle_loan_installment = (($vehicle_loan->loan_amount ?? 0) - ($vehicle_loan->paid_amt ?? 0));
                        } else {
                            $vehicle_loan_installment = 0;
                        }
                    }

                    $vehicle_loan->deduct_amt = $vehicle_loan->deduct_amt + $vehicle_loan_installment;
                    $vehicle_loan->paid_amt = $vehicle_loan->paid_amt + $vehicle_loan_installment;
                    $vehicle_loan->balance_amt = $vehicle_loan->balance_amt + $vehicle_loan_installment;
                    $vehicle_loan->account_status = 1;
                    if ($vehicle_loan->loan_amount == $vehicle_loan->paid_amt) {
                        $vehicle_loan->account_status = 2;
                    }
                    if ($vehicle_loan->save()) {
                        $vehicle_loan_transaction_log = new VehicleLoanTransactionLog();
                        $vehicle_loan_transaction_log->vehical_id = $vehicle_loan->vehical_id;
                        $vehicle_loan_transaction_log->staff_central_id = $vehicle_loan->staff_central_id;
                        $vehicle_loan_transaction_log->trans_date = Date('Y-m-d');
                        $vehicle_loan_transaction_log->paid_installment_amt = $vehicle_loan_installment;
                        $vehicle_loan_transaction_log->remaining_amt = $vehicle_loan->loan_amount - $vehicle_loan->paid_amt;
                        $vehicle_loan_transaction_log->detail_note = 'Paid in the payroll month of ' . $payroll_detail->salary_month;
                        $vehicle_loan_transaction_log->deduc_salary_month = $payroll_detail->salary_month;
                        $vehicle_loan_transaction_log->payroll_id = $payroll_id;
                        if ($vehicle_loan_transaction_log->save()) {
                            $status_mesg = true;
                        } else {
                            $status_mesg = false;
                        }
                    }
                }
                $total_loan = $house_loan_installment + $vehicle_loan_installment;

                $social_security_tax = $request->slab_one[$staff_details->id];
                $tax_amount = $request->slab_other[$staff_details->id] + $request->slab_36[$staff_details->id];

                $net_payable = $payroll_information['total_salary']
                    - $payroll_information['total_pf_deduction']
                    - $total_loan - $payroll_information['cit_deduction']
                    - $social_security_tax
                    - $tax_amount;

                $payroll_confirm = new PayrollConfirm();
                $payroll_confirm->payroll_id = $payroll_id;
                $payroll_confirm->staff_central_id = $payroll_information['id'];
                $payroll_confirm->min_work_hour = 7;
                $payroll_confirm->tax_code = $payroll_confirm['marital_status'];
                $payroll_confirm->total_worked_hours = 0;
                $payroll_confirm->present_days = 0;
                $payroll_confirm->salary_hour_payable = 0;
                $payroll_confirm->ot_hour_payable = 0;
                $payroll_confirm->basic_salary = $payroll_information['basic_salary'];
                $payroll_confirm->pro_fund = $payroll_information['profund_by_staff'];
                $payroll_confirm->pro_fund_contribution = $payroll_information['profund_by_organization'];
                $payroll_confirm->gross_payable = $payroll_information['total_salary'];
                $payroll_confirm->loan_payment = $total_loan;
                $payroll_confirm->sundry_dr = 0;
                $payroll_confirm->sundry_cr = 0;
                $payroll_confirm->tax = $tax_amount;
                $payroll_confirm->net_payable = $net_payable;
                $payroll_confirm->remarks = $request->remarks[$payroll_information['id']];
                $payroll_confirm->social_security_fund_amount = $social_security_tax;
                $payroll_confirm->grade_amount = $payroll_information['grade'];
                $payroll_confirm->save();

                $payroll_confirm_allowance = new PayrollConfirmAllowance();
                $payroll_confirm_allowance->payroll_confirm_id = $payroll_confirm->id;
                $payroll_confirm_allowance->allow_id = $allowance->allow_id;
                $payroll_confirm_allowance->amount = $payroll_information['allowance'];
                $payroll_confirm_allowance->save();

                if (empty($staff_details->acc_no) || empty($staff_details->bank_id)) {
                    $cash_statement_item['payroll_id'] = $payroll_id;
                    $cash_statement_item['staff_central_id'] = $staff_details->id;
                    $cash_statement_item['branch_id'] = $payroll_detail->branch_id;
                    $cash_statement_item['total_payment'] = $net_payable;
                    $cash_statement_item['remarks'] = '';
                    $cash_statement_bulk_array[] = $cash_statement_item;

                } else {
                    $bank_statement_item['payroll_id'] = $payroll_id;
                    $bank_statement_item['staff_central_id'] = $staff_details->id;
                    $bank_statement_item['branch_id'] = $payroll_detail->branch_id;
                    $bank_statement_item['bank_id'] = $staff_details->bank_id;
                    $bank_statement_item['acc_no'] = $staff_details->acc_no;
                    $bank_statement_item['brcode'] = substr($staff_details->acc_no, 0, 3);
                    $bank_statement_item['trans_type'] = 'C';
                    $bank_statement_item['total_payment'] = $net_payable;
                    $bank_statement_item['remarks'] = '';
                    $bank_statement_bulk_array[] = $bank_statement_item;
                }

                $profund_statement['payroll_id'] = $payroll_id;
                $profund_statement['staff_central_id'] = $staff_details->id;
                $profund_statement['branch_id'] = $payroll_detail->branch_id;
                $profund_statement['post_id'] = $staff_details->post_id;
                $profund_statement['employee_contri'] = $payroll_confirm['profund_by_staff'];
                $profund_statement['company_contri'] = $payroll_confirm['profund_by_organization'];
                $profund_statement_bulk_array[] = $profund_statement;

                $cit_statement['payroll_id'] = $payroll_id;
                $cit_statement['staff_central_id'] = $staff_details->id;
                $cit_statement['branch_id'] = $payroll_detail->branch_id;
                $cit_statement['post_id'] = $staff_details->post_id;
                $cit_statement['cit_amount'] = $payroll_confirm['cit_deduction'];
                $cit_statement_bulk_array[] = $cit_statement;

                $socialSecurity_statement['payroll_id'] = $payroll_id;
                $socialSecurity_statement['staff_central_id'] = $staff_details->id;
                $socialSecurity_statement['branch_id'] = $payroll_detail->branch_id;
                $socialSecurity_statement['post_id'] = $staff_details->post_id;
                $socialSecurity_statement['tax_amount'] = $social_security_tax;
                $socialSecurity_statement_bulk_array[] = $socialSecurity_statement;

                $tax_statement['payroll_id'] = $payroll_id;
                $tax_statement['staff_central_id'] = $staff_details->id;
                $tax_statement['branch_id'] = $payroll_detail->branch_id;
                $tax_statement['post_id'] = $staff_details->post_id;
                $tax_statement['tax_amount'] = $tax_amount;
                $tax_statement_bulk_array[] = $tax_statement;
            }

            TransCashStatement::insert($cash_statement_bulk_array);
            TransBankStatement::insert($bank_statement_bulk_array);
            ProFund::insert($profund_statement_bulk_array);
            CitLedger::insert($cit_statement_bulk_array);
            SocialSecurityTaxStatement::insert($socialSecurity_statement_bulk_array);
            TaxStatement::insert($tax_statement_bulk_array);
            $status_mesg = true;
        } catch (\Exception $e) {
            $status_mesg = false;
            DB::rollBack();
        }
        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Payroll Confirmed Successfully' : 'Error Occurred! Try Again!';
        return redirect()->route('attendance-detail')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /** Deduct Loan
     * @param $loans
     * @param $loan_status
     * @param $type
     * @return int
     */
    public function deductLoan($loans, $loan_status, $type)
    {
        if (count($loans) > 0) {
            $loan = $loans->last();
            $loan_installment_amount = 0;
            $loan_id = 0;
            if ($loan->balance_amt != $loan->loan_amount) { //check last balance and loan amount
                //has to pay installment
                //get installment amount
                $loan_installment_amount = $loans->where('account_status', $loan_status[0])->last()->installment_amount; //from granted loan, may be case of rescheduling
                $loan_installment_no = 1; //1st installment default
                //get last_installment
                $last_installment_details = $loans->where('account_status', $loan_status[1])->last();
                if (!empty($last_installment_details)) {
                    $loan_installment_no = (int)$last_installment_details->no_installment + 1;
                }
                //save details to transaction table

                $loan_model = null;
                switch ($type) {
                    case 1:
                        $loan_model = new HouseLoanModelMast();
                        break;
                    case 2:
                        $loan_model = new VehicalLoanModelTrans();
                        break;
                    case 3:
                        $loan_model = new SundryTransaction();
                        break;
                }
                $loan_model->trans_date = date('Y-m-d');
                $loan_model->loan_amount = $loan->loan_amount;
                $loan_model->no_installment = $loan_installment_no;
                $loan_model->installment_amount = $loan_installment_amount;
                $loan_model->deduct_amt = $loan_installment_amount;
                $loan_model->paid_amt = $loan_installment_amount;
                $loan_model->balance_amt = $last_installment_details->balance_amt + $loan_model->paid_amt;
                $loan_model->autho_id = 0;
                if ($loan_model->balance_amt == $loan_model->loan_amount) {
                    $loan_model->account_status = $loan_status[1]; //installment
                } else {
                    $loan_model->account_status = $loan_status[2]; //completed
                }
                $loan_model->detail_note = 'Paid ' . $loan_model->paid_amt . ' on ' . $loan_model->trans_date . ' from salary';
                $loan_model->save();
                $loan_id = $loan_model->id;
            }
            return [
                'loan_deduction_amount' => $loan_installment_amount,
                'loan_id' => $loan_id
            ];
        }
        return 0;
    }

    public function listPayrollStaffofBranch(Request $request)
    {
        $staffs = $this->getPayrollStaffs($request->branch_id, $request->department_id, $request->from_date_np, $request->to_date_np);
        $branch = SystemOfficeMastModel::find($request->branch_id);
        if (!empty($branch->order_staff_ids)) {
            $staff_order_ids = array_map('intval', explode(',', $branch->order_staff_ids));
            $staff_order_ids = array_merge($staff_order_ids, array_diff($staffs->pluck('main_id')->toArray(), $staff_order_ids));
            $staffs = $staffs->sortBy(function ($model) use ($staff_order_ids) {
                return array_search($model->main_id, $staff_order_ids);
            });
        }
        $title = "Payroll Staff List";
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        return view('attendancedetail.payrollstaff', compact('staffs', 'title', 'branches'));
    }

    public function payrollRollback($payroll_id)
    {
        $payroll = PayrollDetailModel::with(['leaveBalances' => function ($query) {
            $query->with(['staff' => function ($query) {
                $query->with(['homeLeaveBalanceLast', 'sickLeaveBalanceLast', 'substituteLeaveBalanceLast', 'maternityLeaveBalanceLast', 'maternityCareLeaveBalanceLast',
                    'funeralLeaveBalanceLast']);
            }, 'leave']);
        }, 'houseLoanTransactionLogs' => function ($query) {
            $query->with('houseLoanMasterRecord');
        }, 'vehicleLoanTransactionLogs' => function ($query) {
            $query->with('vehicleLoanMasterRecord');
        }, 'sundryLoanTransactionLogs' => function ($query) {
            $query->with('sundryTransaction');
        }])->where('id', $payroll_id)->first();
        $sundryStaffCentralIds = $payroll->sundryLoanTransactionLogs->pluck('staff_central_id')->toArray();
        $sundryBalances = SundryBalance::whereIn('staff_central_id', $sundryStaffCentralIds)->get();

        try {
            DB::beginTransaction();
            $leave_balance_bulk = [];
            //rollback leave balances$leave_balance_bulk
            foreach ($payroll->leaveBalances as $payrollEffectLeaveBalance) {
                //leave type
                $balance = 0;
                switch ($payrollEffectLeaveBalance->leave->leave_code) {
                    case 3:
                        //home leave
                        $balance = $payrollEffectLeaveBalance->staff->homeLeaveBalanceLast->balance;
                        break;
                    case 4:
                        //sick leave
                        $balance = $payrollEffectLeaveBalance->staff->sickLeaveBalanceLast->balance;
                        break;
                    case 5:
                        //maternity leave
                        $balance = $payrollEffectLeaveBalance->staff->maternityLeaveBalanceLast->balance;
                        break;
                    case 6:
                        //maternity care leave
                        $balance = $payrollEffectLeaveBalance->staff->maternityCareLeaveBalanceLast->balance;
                        break;
                    case 7:
                        //funeral care leave
                        $balance = $payrollEffectLeaveBalance->staff->funeralLeaveBalanceLast->balance;
                        break;
                    case 8:
                        //substitute care leave
                        $balance = $payrollEffectLeaveBalance->staff->substituteLeaveBalanceLast->balance;
                        break;
                    default:
                        break;
                }

                $leaveBalanceRecord = [];
                $leaveBalanceRecord['staff_central_id'] = $payrollEffectLeaveBalance->staff_central_id;
                $leaveBalanceRecord['leave_id'] = $payrollEffectLeaveBalance->leave_id;
                $leaveBalanceRecord['fy_id'] = $payrollEffectLeaveBalance->fy_id;
                $leaveBalanceRecord['description'] = "Payroll Confirmation Rollback";
                $leaveBalanceRecord['consumption'] = $payrollEffectLeaveBalance->earned;
                $leaveBalanceRecord['earned'] = $payrollEffectLeaveBalance->consumption;
                $leaveBalanceRecord['balance'] = $balance - $payrollEffectLeaveBalance->earned + $payrollEffectLeaveBalance->consumption;
                $leaveBalanceRecord['created_at'] = Carbon::now();
                $leaveBalanceRecord['updated_at'] = Carbon::now();
                $leaveBalanceRecord['date'] = date('Y-m-d');
                $leaveBalanceRecord['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $leaveBalanceRecord['payroll_id'] = null;
                $leave_balance_bulk[] = $leaveBalanceRecord;
            }
            LeaveBalance::insert($leave_balance_bulk);

            //house loan payroll rollback
            //revert the transaction
            foreach ($payroll->houseLoanTransactionLogs as $houseLoanTransactionLog) {
                $house_loan_transaction_log = new HouseLoanTransactionLog();
                $house_loan_transaction_log->house_id = $houseLoanTransactionLog->house_id;
                $house_loan_transaction_log->staff_central_id = $houseLoanTransactionLog->staff_central_id;
                $house_loan_transaction_log->trans_date = Date('Y-m-d');
                $house_loan_transaction_log->paid_installment_amt = -$houseLoanTransactionLog->paid_installment_amt;
                $house_loan_transaction_log->remaining_amt = -$houseLoanTransactionLog->remaining_amt;
                $house_loan_transaction_log->detail_note = 'Payroll Confirmation Rollback';
                $house_loan_transaction_log->deduc_salary_month = $houseLoanTransactionLog->deduc_salary_month;
                $house_loan_transaction_log->payroll_id = null;
                $house_loan_transaction_log->save();
                //revert the master record
                $masterRecord = $house_loan_transaction_log->houseLoanMasterRecord;
                $masterRecord->deduct_amt -= -$houseLoanTransactionLog->paid_installment_amt;
                $masterRecord->paid_amt -= -$houseLoanTransactionLog->paid_installment_amt;
                $masterRecord->balance_amt -= -$houseLoanTransactionLog->paid_installment_amt;
                $masterRecord->account_status = 1;
                $masterRecord->save();
            }
            foreach ($payroll->sundryLoanTransactionLogs as $sundryLoanTransactionLog) {
                $sundry_transaction = $sundryLoanTransactionLog->sundryTransaction;
                if ($sundryLoanTransactionLog->transaction_type_id == 1) {
                    //is cr-- revert the code to dr side
                    $sundry_logs = new SundryTransactionLog();
                    $sundry_logs->sundry_id = $sundryLoanTransactionLog->sundry_id;
                    $sundry_logs->staff_central_id = $sundryLoanTransactionLog->staff_central_id;
                    $sundry_logs->transaction_date = BSDateHelper::AdToBs('-', Date('Y-m-d'));
                    $sundry_logs->transaction_date_en = Date('Y-m-d');
                    $sundry_logs->transaction_type_id = 2;
                    $sundry_logs->cr_installment = 1;
                    $sundry_logs->cr_amount = $sundryLoanTransactionLog->dr_amount;
                    $sundry_logs->cr_balance = $sundryLoanTransactionLog->dr_balance;
                    $sundry_logs->notes = 'Payroll Confirmation Rollback';
                    $sundry_logs->payroll_id = null;
                    if ($sundry_logs->save()) {
                        $sundry_transaction->dr_installment -= 1;
                        $sundry_transaction->dr_amount -= $sundryLoanTransactionLog->dr_amount;
                        $sundry_transaction->dr_balance -= $sundryLoanTransactionLog->dr_balance;
                        $sundry_transaction->save();

                        $sundryBalance = $sundryBalances->where('staff_central_id', $sundryLoanTransactionLog->staff_central_id)->first();
                        if (!empty($sundryBalance)) {
                            $sundryBalance->dr_installment -= 1;
                            $sundryBalance->dr_amount -= $sundryLoanTransactionLog->dr_amount;
                            $sundryBalance->dr_balance -= $sundryLoanTransactionLog->dr_balance;
                            $sundryBalance->save();
                        }
                    }
                } else {
                    //is cr-- revert the code to dr side
                    $sundry_logs = new SundryTransactionLog();
                    $sundry_logs->sundry_id = $sundryLoanTransactionLog->sundry_id;
                    $sundry_logs->staff_central_id = $sundryLoanTransactionLog->staff_central_id;
                    $sundry_logs->transaction_date = BSDateHelper::AdToBs('-', Date('Y-m-d'));
                    $sundry_logs->transaction_date_en = Date('Y-m-d');
                    $sundry_logs->transaction_type_id = 1;
                    $sundry_logs->dr_installment = 1;
                    $sundry_logs->dr_amount = $sundryLoanTransactionLog->cr_amount;
                    $sundry_logs->dr_balance = $sundryLoanTransactionLog->cr_balance;
                    $sundry_logs->notes = 'Payroll Confirmation Rollback';
                    $sundry_logs->payroll_id = null;
                    if ($sundry_logs->save()) {
                        $sundry_transaction->cr_installment -= 1;
                        $sundry_transaction->cr_amount -= $sundryLoanTransactionLog->cr_amount;
                        $sundry_transaction->cr_balance -= $sundryLoanTransactionLog->cr_balance;
                        $sundry_transaction->save();

                        $sundryBalance = $sundryBalances->where('staff_central_id', $sundryLoanTransactionLog->staff_central_id)->first();
                        if (!empty($sundryBalance)) {
                            $sundryBalance->cr_installment -= 1;
                            $sundryBalance->cr_amount -= $sundryLoanTransactionLog->cr_amount;
                            $sundryBalance->cr_balance -= $sundryLoanTransactionLog->cr_balance;
                            $sundryBalance->save();
                        }
                    }
                }
            };
            PayrollCalculationData::where('payroll_id', $payroll_id)->delete();
            PayrollConfirm::where('payroll_id', $payroll_id)->delete();
            AttendanceDetailModel::where('payroll_id', $payroll_id)->delete();
            AttendanceDetailSumModel::where('payroll_id', $payroll_id)->delete();
            TransCashStatement::where('payroll_id', $payroll_id)->delete();
            TransBankStatement::where('payroll_id', $payroll_id)->delete();
            ProFund::where('payroll_id', $payroll_id)->delete();
            CitLedger::where('payroll_id', $payroll_id)->delete();
            SocialSecurityTaxStatement::where('payroll_id', $payroll_id)->delete();
            TaxStatement::where('payroll_id', $payroll_id)->delete();
            $calenderHolidayIds = CalenderHolidayModel::where('payroll_id', $payroll_id)->pluck('id')->toArray();
            CalenderHolidayModel::where('payroll_id', $payroll_id)->delete();
            CalenderHolidaySplitMonth::whereIn('calender_holiday_id', $calenderHolidayIds)->delete();
            $payroll->deleted_by = Auth::id();
            $payroll->save();
            $success = $payroll->delete();
        } catch (Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    public function destroyAttendanceDetail($payroll_id)
    {
        $status = true;
        try {
            DB::beginTransaction();
            AttendanceDetailSumModel::where('payroll_id', $payroll_id)->delete();
            AttendanceDetailModel::where('payroll_id', $payroll_id)->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            $status = false;
        }
        if ($status) {
            DB::commit();
        }
        return response()->json($status);
    }

}
