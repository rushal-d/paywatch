<?php

namespace App\Traits;

use App\AllowanceModelMast;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\LoanDeduct;
use App\PayrollCalculationData;
use App\PayrollConfirm;
use App\PayrollDetailModel;
use App\Repositories\TdsRepository;
use App\StafMainMastModel;
use App\SundryType;
use App\SystemLeaveMastModel;
use App\SystemPostMastModel;
use App\SystemTdsMastModel;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Config;
use NumberFormatter;

trait PayrollCalculate
{
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

    public function getPayrollStaffCollection($payroll_from_en, $payroll_to_en, $payroll, $staff_central_ids)
    {
        $staffs = StafMainMastModel::withoutGlobalScopes()->OfPayrollStaffFilter($payroll->branch_id)->with(['jobposition', 'additionalSalary', 'workschedule', 'jobtype', 'sundryLoan' => function ($query) {
            $query->with('sundryType');
        }, 'houseLoanToDeduct', 'vehicleLoanToDeduct', 'homeLeaveBalanceLast', 'sickLeaveBalanceLast', 'substituteLeaveBalanceLast', 'attendanceDetails' => function ($query) use ($payroll) {
            $query->where('payroll_id', $payroll->id); //get only selected payroll id details
        }, 'staffStatus' => function ($query) use ($payroll_from_en, $payroll_to_en) {
            $query->where('status', '!=', 0);
            $query->where([['date_from', '>=', $payroll_from_en], ['date_to', '<=', $payroll_to_en]])
                ->orWhere([['date_from', '<=', $payroll_from_en], ['date_to', '>=', $payroll_from_en]])
                ->orWhere([['date_from', '<=', $payroll_to_en], ['date_to', '>=', $payroll_to_en]])
                ->orWhere([['date_from', '<=', $payroll_from_en], ['date_to', '>=', $payroll_to_en]])
                ->orWhere([['date_from', '<=', $payroll_to_en], ['date_to', '=', null]]);
        }, 'grantLeave' => function ($query) use ($payroll_from_en, $payroll_to_en) {
            $query->with('leave');
            $query->where([['from_leave_day', '>=', $payroll_from_en], ['to_leave_day', '<=', $payroll_to_en]])
                ->orWhere([['from_leave_day', '<=', $payroll_from_en], ['to_leave_day', '>=', $payroll_from_en]])
                ->orWhere([['from_leave_day', '<=', $payroll_to_en], ['to_leave_day', '>=', $payroll_to_en]])
                ->orWhere([['from_leave_day', '<=', $payroll_from_en], ['to_leave_day', '>=', $payroll_to_en]]);

        }, 'grantLeaveSplits' => function ($query) use ($payroll) {
            $query->where('leave_month', $payroll->salary_month);
            $query->where('calender_holiday_split_months.fiscal_year_id', $payroll->fiscal_year);
            $query->with(['calenderholiday' => function ($query) {
                $query->with('leave');
            }]);
        }, 'loanDeducation' => function ($query) use ($payroll) {
            $query->where('month_id', $payroll->salary_month);
        }])->whereIn('id', $staff_central_ids)->get();

        return $staffs;
    }

    public function calculatePayroll($staff_details, $payroll_details, $earnable_home_leave, $earnable_sick_leave, $minimum_basic_salary, $grant_home_leave, $grant_sick_leave, $grant_substitute_leave, $grant_maternity_leave, $grant_maternity_care_leave, $grant_funeral_leave, $redeem_home_leave, $redeem_sick_leave, $deduct_sundry, $systemtdsmastmodel, $input_home_loan_installment = 0, $input_vehicle_loan_installment = 0)
    {

        if (empty($minimum_basic_salary)) {
            $minimum_basic_salary = SystemPostMastModel::min('basic_salary');
        }
        $payroll_month = $payroll_details->salary_month;
        $fiscal_year = $this->getYearByMonth($payroll_month, $payroll_details->fiscalyear->fiscal_code);
        $fiscal_year_details = $payroll_details->fiscalyear;
        $total_days_in_nepali_month = $this->getTotalNumberOfDaysByMonth($payroll_month, $fiscal_year);

        $staff_central_id = $staff_details->id;
        //get staff workschedule
        $staff_workschedule = $staff_details->workschedule->last();

        $min_work_hour = $staff_workschedule->work_hour;
        //First Get Basic Salary from Post (Designation)
        $basic_salary = $staff_details->jobposition->basic_salary;

        $job_type_details = $staff_details->jobtype;
        if (strcasecmp($job_type_details->jobtype_code, "Con") == 0 || strcasecmp($job_type_details->jobtype_code, "Con1") == 0) {
            $basic_salary = $staff_details->additionalSalary->last()->basic_salary;
        }

        //Also get grade amount
        $grade_amount = ($staff_details->additionalSalary->last()->total_grade_amount ?? 0) + ($staff_details->additionalSalary->last()->add_grade_this_fiscal_year ?? 0);
        //check if has additional salary
        $additional_salary = $staff_details->additionalSalary->last()->add_salary_amount ?? 0;

        $selected_month_summary = $payroll_details->attendanceSummary->where('staff_central_id', $staff_central_id)->first();
        $total_work_hours_selected_month = round($selected_month_summary->total_work_hour ?? 0);

        //staff Status
        $staff_suspense_days = $selected_month_summary->suspense_days ?? 0;
        $total_weekend_days = $selected_month_summary->weekend_holiday_hours ?? 0;
        $absent_weekend_days = $selected_month_summary->absent_on_weekend;
        $absent_public_holiday_on_weekend_days = $selected_month_summary->absent_on_public_holiday_on_weekend;
        $total_public_holidays = $selected_month_summary->public_holiday;
        $absent_on_public_holiday = $selected_month_summary->absent_on_public_holiday;
        $present_on_public_holiday_hours = $selected_month_summary->public_holiday_hours;
        $total_weekend_work_hours = $selected_month_summary->weekend_holiday_hours;
        $total_present_days_current_month = $selected_month_summary->present_days;
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
        //if payroll had already been confirmed -- to make balance of useable balance
        $previous_earned_home_leave = 0;
        $previous_earned_sick_leave = 0;
        $previous_earned_substitute_leave = 0;
        if (!empty($payroll_details->confirmed_by)) {
            $payrollConfirm = $payroll_details->payrollConfirm->where('staff_central_id', $staff_central_id)->first();
            if (!empty($payrollConfirm)) {
                $previous_earned_home_leave = $payrollConfirm->payrollConfirmLeaveInfos->where('leaveMast.leave_code', 3)->first()->earned ?? 0;
                $previous_earned_sick_leave = $payrollConfirm->payrollConfirmLeaveInfos->where('leaveMast.leave_code', 4)->first()->earned ?? 0;
                $previous_earned_substitute_leave = $payrollConfirm->payrollConfirmLeaveInfos->where('leaveMast.leave_code', 8)->first()->earned ?? 0;
            }
        }

        $total_substitute_leave_earned_this_month = $this->substituteLeaveEarningCalculation($total_present_days_current_month, $present_on_public_holiday_hours, $total_public_holidays, $min_work_hour);

        /*approved leave from grant leave module*/
        $already_approved_home_leave = $staff_details->grantLeaveSplits->where('calenderholiday.leave.leave_code', 3)->sum('leave_days');
        $already_approved_sick_leave = $staff_details->grantLeaveSplits->where('calenderholiday.leave.leave_code', 4)->sum('leave_days');
        $already_approved_maternity_leave = $staff_details->grantLeaveSplits->where('calenderholiday.leave.leave_code', 5)->sum('leave_days');
        $already_approved_maternity_care_leave = $staff_details->grantLeaveSplits->where('calenderholiday.leave.leave_code', 6)->sum('leave_days');
        $already_approved_funeral_leave = $staff_details->grantLeaveSplits->where('calenderholiday.leave.leave_code', 7)->sum('leave_days');
        $already_approved_substitute_leave = $staff_details->grantLeaveSplits->where('calenderholiday.leave.leave_code', 8)->sum('leave_days');


        $total_approved_home_leave_this_month = ($grant_home_leave ?? 0) + $already_approved_home_leave;
        $total_approved_sick_leave_this_month = ($grant_sick_leave ?? 0) + $already_approved_sick_leave;
        $total_approved_maternity_leave_this_month = ($grant_maternity_leave ?? 0) + $already_approved_maternity_leave;
        $total_approved_maternity_care_leave_this_month = ($grant_maternity_care_leave ?? 0) + $already_approved_maternity_care_leave;
        $total_approved_funeral_leave_this_month = ($grant_funeral_leave ?? 0) + $already_approved_funeral_leave;
        $total_approved_substitute_leave_this_month = ($grant_substitute_leave ?? 0) + $already_approved_substitute_leave;

        $total_approved_leave = $total_approved_home_leave_this_month + $total_approved_sick_leave_this_month +
            $total_approved_maternity_leave_this_month + $total_approved_funeral_leave_this_month + $total_approved_maternity_care_leave_this_month +
            $total_approved_substitute_leave_this_month;


        //check conditions for allowances
        $dearness_allowance = (!empty($staff_details->dearness_allowance_amount)) ? $staff_details->dearness_allowance_amount : 0;
        $special_allowance = (!empty($staff_details->special_allowance_amount)) ? $staff_details->special_allowance_amount : 0;
        $special_allowance_2 = (!empty($staff_details->special_allowance_2_amount)) ? $staff_details->special_allowance_2_amount : 0;
        $risk_allowance = (!empty($staff_details->risk_allowance_amount)) ? $staff_details->risk_allowance_amount : 0;
        $incentive_amount = (!empty($staff_details->incentive_amount)) ? $staff_details->incentive_amount : 0;
        $outstation_facility_allowance = ($staff_details->outstation_facility_allow == 1) ? $staff_details->outstation_facility_amount : 0;


        $salary_payable_hours = $this->salary_hour_payable($total_present_days_current_month, $absent_weekend_days, $absent_on_public_holiday, $absent_public_holiday_on_weekend_days, $total_work_hours_selected_month, $total_approved_leave, $min_work_hour, $total_days_in_nepali_month);
        if (strcasecmp($job_type_details->jobtype_code, "Con") != 0) {
            $overtime_payable_hours = $this->overtime_hour_payable($total_present_days_current_month, $total_work_hours_selected_month, $min_work_hour, $present_on_public_holiday_hours, $total_public_holidays, $total_weekend_work_hours);
        } else {
            $overtime_payable_hours = 0;
        }
        $basic_salary = $basic_salary + $grade_amount + $additional_salary;
        $special_allowance = $special_allowance + $special_allowance_2 + $risk_allowance;
        if (env('Payroll_Type_Implemented', 1) == 1) {
            $new_basic_salary = round($this->basic_salary($basic_salary, $min_work_hour, $salary_payable_hours, $total_days_in_nepali_month));
            $new_dearness_allowance = round($this->dearness_allowance($dearness_allowance, $min_work_hour, $total_days_in_nepali_month, $salary_payable_hours));
            $new_special_allowance = round($this->special_allowance($special_allowance, $min_work_hour, $total_days_in_nepali_month, $salary_payable_hours));
            $new_outstation_facility_amount = round($this->outstation_allowance($outstation_facility_allowance, $min_work_hour, $total_days_in_nepali_month, $salary_payable_hours));
            $new_incentive_amount = round($this->incentive($salary_payable_hours, $incentive_amount, $total_present_days_current_month, $absent_weekend_days, $absent_on_public_holiday, $absent_public_holiday_on_weekend_days, $total_present_days_current_month));
            $extra_allowance = 0;
            if ($staff_details->extra_allow == 1) {
                $extra_allowance = $this->extra_allowance($salary_payable_hours, $basic_salary, $dearness_allowance, $special_allowance, $min_work_hour, $total_days_in_nepali_month, $total_weekend_work_hours, $present_on_public_holiday_hours);
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
        $ot_amount = round($this->ot_amount($basic_salary_for_ot, $min_work_hour, $total_days_in_nepali_month, $overtime_payable_hours));
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
        $total_unpaid_leave = $total_days_in_nepali_month - $total_present_days_current_month - $absent_weekend_days - $absent_on_public_holiday - $total_approved_leave - $staff_suspense_days + $absent_public_holiday_on_weekend_days;
        $home_leave_balance = $staff_details->homeLeaveBalanceLast->balance ?? 0;
        $sick_leave_balance = $staff_details->sickLeaveBalanceLast->balance ?? 0;
        $substitute_leave_balance = $staff_details->substituteLeaveBalanceLast->balance ?? 0;

        $home_leave_balance = $home_leave_balance - $redeem_home_leave + $total_home_leave_earned_this_month - ($grant_home_leave ?? 0) - $previous_earned_home_leave;
        $sick_leave_balance = $sick_leave_balance - $redeem_sick_leave + $total_sick_leave_earned_this_month - ($grant_sick_leave ?? 0) - $previous_earned_sick_leave;
        $substitute_leave_balance = $substitute_leave_balance + $total_substitute_leave_earned_this_month - (int)($grant_substitute_leave ?? 0) - $previous_earned_substitute_leave;
        $redeem_home_leave_amount = round($redeem_home_leave * (($basic_salary + $dearness_allowance + $special_allowance) / 30));
        $redeem_sick_leave_amount = round($redeem_sick_leave * (($basic_salary + $dearness_allowance + $special_allowance) / 30));

        $total_redeem_home_and_sick_amt = round($redeem_home_leave_amount + $redeem_sick_leave_amount);

        //gross payment
        $gross_payment = $new_basic_salary + $new_dearness_allowance + $new_special_allowance + $new_outstation_facility_amount + $incentive_amount + $gratuity_amt + $extra_allowance
            + $profund_amt + $ot_amount + $total_redeem_home_and_sick_amt + $social_security_fund_amt;
        //loan deduction
        //deduct profund and gratuity from total salary
        $total_monthly_salary_after_pf_g = round($gross_payment - $gratuity_amt - $profund_amt - $profund_contribution_amt - $social_security_fund_amt);

        //tds slab by marital status and total salary
        $total_taxable_yearly_salary = ($total_monthly_salary_after_pf_g) * config('constants.tax_payable_months_number');

        $tdsArray = $this->getTdsDeductionAmountBySlabNumber($staff_marital_status, $total_taxable_yearly_salary, $fiscal_year_details, $systemtdsmastmodel);
        $social_security_tax = $tdsArray[SystemTdsMastModel::firstSlab];
        unset($tdsArray[SystemTdsMastModel::firstSlab]);
        $tdsWithoutFirstSlab = $tdsArray;
        $income_tax = array_sum($tdsWithoutFirstSlab);

        $tds = round($this->tdsDeductionByMaritalStatusAndAmount($staff_marital_status, $total_taxable_yearly_salary, $fiscal_year_details, $systemtdsmastmodel) / config('constants.tax_payable_months_number'));
        //get attendance details for the staff

        $gross_payment_after_tax = ($gross_payment - ($profund_amt + $profund_contribution_amt + $gratuity_amt + $social_security_fund_amt + $tds));

        $house_loan_installment = 0;
        $vehicle_loan_installment = 0;
        $cr_amount = 0;
        $dr_amount = 0;

        $house_loan = $staff_details->houseLoanToDeduct;
        if (!empty($house_loan)) {
            $house_loan_installment = $staff_details->loanDeducation->where('loan_type', LoanDeduct::HOUSE_LOAN_TYPE_ID)->first()->loan_deduct_amount ?? 0;
            if ($house_loan_installment != 0 && $house_loan_installment > $gross_payment_after_tax) { // if has house loan
                if ($house_loan_installment > (($house_loan->loan_amount ?? 0) - ($house_loan->paid_amt ?? 0))) {
                    $house_loan_installment = (($house_loan->loan_amount ?? 0) - ($house_loan->paid_amt ?? 0));
                } else {
                    $house_loan_installment = 0;
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
        }

        $total_loan = $house_loan_installment + $vehicle_loan_installment;
        if ($deduct_sundry == 1 && $gross_payment_after_tax > 0) {
            $sundry_stat = array(0, 1);
            $sundrys = $staff_details->sundryLoan->where('transaction_date_en', '<=', date('Y-m-d'))->whereIn('status', $sundry_stat);
            if (!empty($sundrys)) {
                foreach ($sundrys as $sundry) {
                    $is_cr = ($sundry->sundryType->type == 1);//if cr get cr's installment amount to be paid
                    if ($is_cr) { //the transaction is cr so get cr amount
                        $cr_amount += $sundry->cr_amount;
                    } else { //the transaction is dr so get dr amount
                        $dr_amount += $sundry->dr_amount;
                    }
                }
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

        $data['total_work_hour'] = $total_work_hours_selected_month;
        $data['min_work_hour'] = $min_work_hour;
        $data['marital_status'] = ($staff_marital_status == 0) ? 'U' : 'M';
        $data['working_position'] = $staff_details->jobtype->jobtype_name ?? '';
        $data['days_absent_on_holiday'] = $absent_weekend_days + $absent_on_public_holiday - $absent_public_holiday_on_weekend_days;
        $data['weekend_work_hours'] = $total_weekend_work_hours;
        $data['public_holiday_work_hours'] = $present_on_public_holiday_hours;
        $data['present_days'] = $total_present_days_current_month;
        $data['absent_days'] = $staff_absent_dates_this_payroll_month;
        $data['suspense_days'] = $staff_suspense_days;
        $data['unpaid_days'] = $total_unpaid_leave;
        $data['redeem_home_leave'] = $redeem_home_leave;
        $data['redeem_sick_leave'] = $redeem_sick_leave;
        $data['home_leave_balance'] = $home_leave_balance;
        $data['sick_leave_balance'] = $sick_leave_balance;
        $data['substitute_leave_balance'] = $substitute_leave_balance;
        $data['approved_home_leave'] = $total_approved_home_leave_this_month;
        $data['approved_sick_leave'] = $total_approved_sick_leave_this_month;
        $data['approved_substitute_leave'] = $total_approved_substitute_leave_this_month;
        $data['approved_maternity_leave'] = $total_approved_maternity_leave_this_month;
        $data['approved_maternity_care_leave'] = $total_approved_maternity_care_leave_this_month;
        $data['approved_funeral_leave'] = $total_approved_funeral_leave_this_month;
        $data['salary_hour_payable'] = $salary_payable_hours;
        $data['ot_hour_payable'] = $overtime_payable_hours;
        $data['basic_salary'] = $new_basic_salary;
        $data['dearness_allowances'] = $new_dearness_allowance;
        $data['special_allowances'] = $new_special_allowance;
        $data['special_allowances2'] = $special_allowance_2;
        $data['extra_allowance'] = $extra_allowance;
        $data['outstation_facility_amount'] = $new_outstation_facility_amount;
        $data['profund_amount'] = $profund_amt;
        $data['profund_contribution_amount'] = $profund_contribution_amt;
        $data['gratuity_amount'] = $gratuity_amt;
        $data['social_security_amount'] = $social_security_fund_amt;
        $data['redeem_home_leave_amount'] = $redeem_home_leave_amount;
        $data['redeem_sick_leave_amount'] = $redeem_sick_leave_amount;
        $data['incentive'] = $new_incentive_amount;
        $data['ot_amount'] = $ot_amount;
        $data['gross_payment'] = $gross_payment;
        $data['levy_amount'] = $levy_amount;
        $data['sundry_dr_amount'] = $dr_amount;
        $data['sundry_cr_amount'] = $cr_amount;
        $data['sundry_difference'] = $sundry_difference;
        $data['house_loan_installment'] = $house_loan_installment;
        $data['vehicle_loan_installment'] = $vehicle_loan_installment;
        $data['tds'] = $tds;
        $data['social_security_tax'] = $social_security_tax;
        $data['income_tax'] = $income_tax;
        $data['net_payment'] = $net_payment;
        $data['total_home_leave_earned_this_month'] = $total_home_leave_earned_this_month;
        $data['total_sick_leave_earned_this_month'] = $total_sick_leave_earned_this_month;
        $data['total_substitute_leave_earned_this_month'] = $total_substitute_leave_earned_this_month;
        return $data;
    }

    public function getPayrollDifferenceSingleStaff($payroll_id, $staff_central_id)
    {
        $staff_payroll_details = PayrollConfirm::with(['payrollConfirmAllowances', 'payrollConfirmLeaveInfos' => function ($query) {
            $query->with('leaveMast');
        }])
            ->where('payroll_id', $payroll_id)
            ->where('staff_central_id', $staff_central_id)
            ->first();
        $payrollCalculationData = PayrollCalculationData::where('payroll_id', $payroll_id)->where('staff_central_id', $staff_central_id)->first();

        $payrollDetails = PayrollDetailModel::with(['fiscalyear', 'branch', 'socalSecurityTaxPayment', 'incomeTaxPayment', 'citLedger', 'profundLedger', 'payrollConfirm' => function ($query) {
            $query->with(['payrollConfirmLeaveInfos' => function ($query) {
                $query->with('leaveMast');
            }, 'payrollConfirmAllowances' => function ($query) {
                $query->with('allowanceMast');
            }]);
        }])->where('id', $payroll_id)->first();
        $date_from = $payrollDetails->from_date;
        $date_to = $payrollDetails->to_date;

        $date_from_np = BSDateHelper::AdToBs('-', $date_from);
        $date_to_np = BSDateHelper::AdToBs('-', $date_to);
        $leaves = SystemLeaveMastModel::get();
        $total_sick_leave_from_system = $leaves->where('leave_code', 4)->first()->no_of_days ?? 15;
        $total_home_leave_from_system = $leaves->where('leave_code', 3)->first()->no_of_days ?? 18;
        $earnable_home_leave = $total_home_leave_from_system / 12;
        $earnable_sick_leave = $total_sick_leave_from_system / 12;
        $staff = $this->getPayrollStaffCollection($date_from, $date_to, $payrollDetails, [$staff_central_id])->first();
        $systemtdsmastmodel = SystemTdsMastModel::get();

        $total_approved_home_leave_this_month = 0;
        $total_approved_sick_leave_this_month = 0;
        $total_approved_maternity_leave_this_month = 0;
        $total_approved_maternity_care_leave_this_month = 0;
        $total_approved_funeral_leave_this_month = 0;
        $total_approved_substitute_leave_this_month = 0;
        //donot include the sundry as it takes the upcoming sundries
        $calculation_response = $this->calculatePayroll($staff, $payrollDetails, $earnable_home_leave, $earnable_sick_leave, null, $total_approved_home_leave_this_month,
            $total_approved_sick_leave_this_month, $total_approved_substitute_leave_this_month, $total_approved_maternity_leave_this_month,
            $total_approved_maternity_care_leave_this_month, $total_approved_funeral_leave_this_month, $staff_payroll_details->redeem_home_leave ?? 0, $staff_payroll_details->redeem_sick_leave ?? 0, 0, $systemtdsmastmodel, 0, 0);
        $new_payable_now = $calculation_response['net_payment'] - (($staff_payroll_details->sundry_cr ?? 0) - ($staff_payroll_details->sundry_dr ?? 0));
        $title = 'Payroll Differences';
        $new_sundry_amount = $new_payable_now - ($staff_payroll_details->net_payable ?? 0);

        $numberFormatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $new_sundry_amount_in_words = $numberFormatter->format($new_sundry_amount);

        return [
            'title' => $title,
            'staff' => $staff,
            'payrollDetails' => $payrollDetails,
            'new_calculation' => $calculation_response,
            'old_calculation' => $staff_payroll_details,
            'old_calculation_data' => $payrollCalculationData,
            'new_sundry_amount' => $new_sundry_amount,
            'new_sundry_amount_in_words' => $new_sundry_amount_in_words,
            'i' => 1,
            'leave_counter' => 1
        ];
    }

    public function calculatePayrollNepalRe($payroll_detail)
    {

        $staffs = StafMainMastModel::with(['jobtype', 'payment' => function ($query) {
            $query->whereHas('allowance', function ($query) {
                $query->where('allowance_type', 1); //monthly payable
                $query->where('include_in_payroll', 1); //is included on payroll
            });
        }, 'sundryLoan', 'staffCitDeductions' => function ($query) use ($payroll_detail) {
            $query->where('month_id', $payroll_detail->salary_month);
        }, 'staffGrades' => function ($query) {
            $query->with('grade');
        }, 'staffPosts' => function ($query) {
            $query->with('post');
        }, 'taxPayable' => function ($query) use ($payroll_detail) {
            $query->where('fiscal_year_id', $payroll_detail->fiscal_year);
            $query->with('taxDetail');
        },'houseLoanToDeduct','vehicleLoanToDeduct'])
            ->where('appo_date', '<=', $payroll_detail->to_date)->orderBy('staff_main_mast.staff_central_id')->get();
        $payroll_information = [];
        $i = 0;
        foreach ($staffs as $staff) {
            $payroll_information[$i]['id'] = $staff->id;
            $payroll_information[$i]['staff_name'] = $staff->name_eng;
            $payroll_information[$i]['designation'] = $staff->staffPosts->last()->post->post_title ?? '';
            $payroll_information[$i]['staff_central_id'] = $staff->staff_central_id;
            $payroll_information[$i]['branch_id'] = $staff->branch_id;
            $payroll_information[$i]['job_type'] = $staff->jobtype->jobtype_name ?? '';
            $payroll_information[$i]['marital_status'] = ($staff->marrid_stat==0) ? 'S':'C';
            $basic_salary_payable = $grade_payable = $allowance_payable = 0;

            $this->basicSalaryChangeDivisionByCollection($staff->staffPosts, $payroll_detail->from_date, $payroll_detail->to_date, $basic_salary_payable);
            $payroll_information[$i]['basic_salary'] = $basic_salary_payable = round($basic_salary_payable, 2);

            $this->gradeChangeDivisionByCollection($staff->staffGrades, $payroll_detail->from_date, $payroll_detail->to_date, $basic_salary_payable, $grade_payable);
            $grade_payable = round($grade_payable, 2);
            $payroll_information[$i]['grade'] = $grade_payable;

            $this->allowanceChangeDivisionByCollection($staff->payment, $payroll_detail->from_date, $payroll_detail->to_date, $allowance_payable);
            $payroll_information[$i]['allowance'] = $allowance_payable = round($allowance_payable, 2);
            $payroll_information[$i]['cit_deduction'] = $staff->staffCitDeductions->first()->cit_deduction_amount ?? 0;
            $payroll_information[$i]['house_loan'] = $staff->houseLoanToDeduct->installment_amount ?? 0;
            $payroll_information[$i]['vehicle_loan'] = $staff->vehicleLoanToDeduct->installment_amount ?? 0;
            $profund_organization_percentage = $staff->jobtype->profund_contri_per ?? 0;
            $profund_staff_percentage = $staff->jobtype->profund_per ?? 0;
            $payroll_information[$i]['profund_by_organization'] = ($basic_salary_payable+$grade_payable) * ($profund_organization_percentage / 100);
            $payroll_information[$i]['profund_by_staff'] = ($basic_salary_payable+$grade_payable) * ($profund_staff_percentage / 100);
            $payroll_information[$i]['total_pf_deduction'] = $payroll_information[$i]['profund_by_organization'] + $payroll_information[$i]['profund_by_staff'];
            $payroll_information[$i]['total_salary'] = $basic_salary_payable + $grade_payable + $allowance_payable + $payroll_information[$i]['profund_by_organization'];
            $payroll_information[$i]['slab_one'] = $staff->taxPayable->where('taxDetail.percent', 1)->sum('tax_amount_monthly');
            $payroll_information[$i]['other'] = $staff->taxPayable->where('taxDetail.slab', '<>', 1)->where('taxDetail.slab', '<>', 5)->sum('tax_amount_monthly');
            $payroll_information[$i]['slab_36'] = $staff->taxPayable->where('taxDetail.percent', 36)->sum('tax_amount_monthly');
            $payroll_information[$i]['staff_detail'] = $staff;

            $payroll_information[$i]['salary_before_tax'] = $payroll_information[$i]['total_salary']
                - $payroll_information[$i]['total_pf_deduction']
                - $payroll_information[$i]['house_loan']
                - $payroll_information[$i]['vehicle_loan']
                - $payroll_information[$i]['cit_deduction'];

            $payroll_information[$i]['total_deduction'] = $payroll_information[$i]['total_pf_deduction']
                + $payroll_information[$i]['house_loan']
                + $payroll_information[$i]['vehicle_loan']
                + $payroll_information[$i]['cit_deduction']
                + $payroll_information[$i]['slab_one'] + $payroll_information[$i]['other'] + $payroll_information[$i]['slab_36'];

            $payroll_information[$i]['total_deduction_before_tax'] = $payroll_information[$i]['total_deduction'] -
                ($payroll_information[$i]['slab_one'] + $payroll_information[$i]['other'] + $payroll_information[$i]['slab_36']);

            $payroll_information[$i]['amount_sent_to_bank'] = $payroll_information[$i]['total_salary'] - $payroll_information[$i]['total_deduction'];

            $i++;
        }
        return $payroll_information;
    }

    public
    function getYearByMonth(int $month, string $fiscalYear)
    {
        $year = 0;
        $fiscalYearCode = explode('/', $fiscalYear);
        $start_year = $fiscalYearCode[0];
        $year_num = substr($start_year, 0, 2);
        $end_year = $year_num . $fiscalYearCode[1];
        //if greater than asadh and less than chaitra
        if ($month >= 4 && $month <= 12) {
            $year = $start_year;
        } else { //baisakh to asadh
            $year = $end_year;
        }
        return $year;
    }

    public
    function getTotalNumberOfDaysByMonth(int $month, int $year)
    {
        $last_two_digits = $year - 2000;
        $month = intval($month);
        $months = $this->dates[$last_two_digits];
        return $months[$month];
    }

    public
    function checkIfWeekendDay($date, $weekend_day)
    {
        $wday = date('N', strtotime($date));
        if ($wday == $weekend_day) {
            return true;
        }
        return false;
    }

    public
    function substituteLeaveEarningCalculation($present_days, $public_holiday_work_hour, $number_of_public_holiday, $min_work_hour)
    {
        if ($present_days < 20) {
            return 0;
        } else {
            if ($public_holiday_work_hour / 8 >= $number_of_public_holiday) {
                return $number_of_public_holiday;
            } else {
                return floor($public_holiday_work_hour / $min_work_hour);
            }
        }
    }

    public
    function salary_hour_payable($present_days, $weekend_holidays_taken, $public_holiday_taken, $absent_on_weekend_with_public_holday, $total_work_hours, $total_approved_leaves, $min_work_hours, $total_days_in_month)
    {
        $check_salary_payable_one = ($present_days + $weekend_holidays_taken + $public_holiday_taken - $absent_on_weekend_with_public_holday + $total_approved_leaves) * $min_work_hours;
        $check_salary_payable_two = $total_work_hours + ($weekend_holidays_taken + $public_holiday_taken - $absent_on_weekend_with_public_holday + $total_approved_leaves) * $min_work_hours;
        if ($check_salary_payable_one < $check_salary_payable_two) {
            $salary_payable_choose = $check_salary_payable_one;
        } else {
            $salary_payable_choose = $check_salary_payable_two;
        }

        if ($salary_payable_choose > ($min_work_hours * $total_days_in_month)) {
            return $min_work_hours * $total_days_in_month;
        } else {
            return $salary_payable_choose;
        }
    }

    public
    function overtime_hour_payable($present_days, $total_work_hours, $min_work_hours, $public_holiday_work_hour, $public_holiday_in_month, $weekend_work_hour)
    {
        $value_one = $total_work_hours - ($present_days * $min_work_hours);
        if ($value_one < 0) {
            $value_one = 0;
        }
        $value_two = 0;
        if ($public_holiday_work_hour <= ($public_holiday_in_month * $min_work_hours)) {
            $value_two = $public_holiday_work_hour;
        }

        return $value_one + $value_two + $weekend_work_hour;
    }

    public
    function basic_salary($basic_salary, $min_work_hour, $salary_hours_payable, $days_in_month)
    {
        return ($basic_salary / ($min_work_hour * $days_in_month)) * $salary_hours_payable;
    }

    public
    function dearness_allowance($dearness_allowance, $min_work_hour, $days_in_month, $salary_hour_payable)
    {
        return ($dearness_allowance / ($min_work_hour * $days_in_month)) * $salary_hour_payable;
    }

    public
    function special_allowance($special_allowance, $min_work_hour, $days_in_month, $salary_hour_payable)
    {
        return ($special_allowance / ($min_work_hour * $days_in_month)) * $salary_hour_payable;
    }

    public
    function outstation_allowance($outstation_allowance, $min_work_hour, $days_in_month, $salary_hour_payable)
    {
        return ($outstation_allowance / ($min_work_hour * $days_in_month)) * $salary_hour_payable;
    }

    public
    function extra_allowance($salary_payable_hour, $basic_salary, $dearness_allowance, $special_allowance, $min_work_hour, $days_in_month, $present_weekend_holiday_hour, $present_public_holiday_hour)
    {
        $extra = 0;
        if ($salary_payable_hour == 0) {
            return 0;
        }

        if ($present_public_holiday_hour == 0 && $present_weekend_holiday_hour == 0) {
            return 0;
        }
        $total = $basic_salary + $dearness_allowance + $special_allowance;
        $divisible = ($min_work_hour * $days_in_month);
        $extra = ($total / $divisible) * ($present_public_holiday_hour + $present_weekend_holiday_hour);
        return $extra;
    }

    public function incentive($salary_hour_payable, $incentive_amount, $total_present_days, $absent_on_weekend, $absent_on_public_holiday, $absent_on_weekend_withpublic_holiday, $days_in_month)
    {
        if (($total_present_days + $absent_on_weekend + $absent_on_public_holiday + $absent_on_weekend_withpublic_holiday) >= 15) {
            return ($incentive_amount / (8 * $days_in_month)) * $salary_hour_payable;
        } else {
            return 0;
        }
    }

    public
    function ot_amount($basic_salary, $min_work_hour, $days_in_month, $ot_hour_payable)
    {
        return round(($basic_salary / ($min_work_hour * $days_in_month)) * 1.5 * $ot_hour_payable);
    }

    public function basic_salary_by_day($basic_salary, $total_days_payable, $days_in_nepali_month)
    {
        return $basic_salary * ($total_days_payable / $days_in_nepali_month);
    }

    public function dearness_allowance_by_day($dearness_allowance, $total_days_payable, $days_in_nepali_month)
    {
        return $dearness_allowance * ($total_days_payable / $days_in_nepali_month);
    }

    public function special_allowance_by_day($special_allowance, $total_days_payable, $days_in_nepali_month)
    {
        return $special_allowance * ($total_days_payable / $days_in_nepali_month);
    }

    public function outstation_allowance_by_day($outstation_allowance, $total_days_payable, $days_in_nepali_month)
    {
        return $outstation_allowance * ($total_days_payable / $days_in_nepali_month);
    }

    public function extra_allowance_by_day($outstation_allowance, $total_days_payable, $days_in_nepali_month)
    {
        return $outstation_allowance * ($total_days_payable / $days_in_nepali_month);
    }

    public function incentive_by_day($incentive, $total_days_payable, $days_in_nepali_month)
    {
        return $incentive * ($total_days_payable / $days_in_nepali_month);
    }

    public function tdsDeductionByMaritalStatusAndAmount($martial_status, $total_yearly_salary, $fiscal_year, $systemtdsmastmodel)
    {
        $martialStatus = $martial_status;
        $numberOfSlabs = config('constants.tds_slabs');
        $tds = 0;
        $lastSlab = false;
        $remaining_amount = $total_yearly_salary;

        foreach ($numberOfSlabs as $slabNumber) {
            $tds_details_slab = $this->getTDSDetailsBySlab($martialStatus, $slabNumber, $fiscal_year, $systemtdsmastmodel);
            $tds_deduction_amount = $tds_details_slab->amount;
            $tds_deduction_percent = ($tds_details_slab->percent / 100);

            // Last slab being 5
            if ($remaining_amount - $tds_deduction_amount > 0 && ($slabNumber < config('constants.tds_last_slab_number'))) {
                $remaining_amount = $remaining_amount - $tds_deduction_amount;
            } else {
                $tds_deduction_amount = $remaining_amount;
                $lastSlab = true;
            }

            if ($slabNumber == 1 || $remaining_amount > 0 || $lastSlab == true) {
                $tds += $tds_deduction_amount * $tds_deduction_percent;
                if ($lastSlab == true) {
                    break;
                }
            }
        }
        return $tds;
    }

    public function getTdsDeductionAmountBySlabNumber($martialStatus, $totalYearlySalary, $fiscal_year, $systemtdsmastmodel)
    {
        $numberOfSlabs = config('constants.tds_slabs');
        $tds = [];
        $lastSlab = false;
        $remaining_amount = $totalYearlySalary;

        foreach ($numberOfSlabs as $slabNumber) {
            $tds_details_slab = $this->getTdsDetailsBySlab($martialStatus, $slabNumber, $fiscal_year, $systemtdsmastmodel);
            $tds_deduction_amount = $tds_details_slab->amount;
            $tds_deduction_percent = ($tds_details_slab->percent / 100);

            // Last slab being 5
            if ($remaining_amount - $tds_deduction_amount > 0 && ($slabNumber < config('constants.tds_last_slab_number'))) {
                $remaining_amount = $remaining_amount - $tds_deduction_amount;
            } else {
                $tds_deduction_amount = $remaining_amount;
                $lastSlab = true;
            }

            if ($slabNumber == 1 || $remaining_amount > 0 || $lastSlab == true) {
                $tds[$slabNumber] = $tds_deduction_amount * $tds_deduction_percent;
                if ($lastSlab == true) {
                    break;
                }
            }
        }
        return $tds;
    }


    public
    function getTDSDetailsBySlab($marital_status, $slab, $fiscal_year, $systemtdsmastmodel)
    {
        //dd($this->systemtdsmastmodel);
        return $systemtdsmastmodel->where('type', $marital_status)->where('fy', $fiscal_year->id)->where('slab', $slab)->first();
    }

    public function countLeave($leave_code, $payroll_month, $leavesTaken, &$absent_on_weekend, &$absent_on_public_holiday, $public_holidays, $payroll_month_first, $payroll_month_last, $weekend)
    {
        $leaveTaken = $leavesTaken->where('leave.leave_code', $leave_code);
        $total_leave_taken = 0;
        foreach ($leaveTaken as $leavetaken) {
            $from_date = date_parse_from_format('Y-m-d', $leavetaken->from_leave_day_np);
            $to_date = date_parse_from_format('Y-m-d', $leavetaken->to_leave_day_np);
            if ($from_date['month'] == $payroll_month) {
                $leaveStartPayrollMonth = BSDateHelper::BsToAd('-', $leavetaken->from_leave_day_np);
                if ($to_date["month"] == $payroll_month) {
                    $leaveEndPayrollMonth = BSDateHelper::BsToAd('-', $leavetaken->to_leave_day_np);
                } else {
                    $leaveEndPayrollMonth = BSDateHelper::BsToAd('-', $payroll_month_last);
                }
            } elseif ($to_date["month"] == $payroll_month) {
                $leaveStartPayrollMonth = BSDateHelper::BsToAd('-', $payroll_month_first);
                $leaveEndPayrollMonth = BSDateHelper::BsToAd('-', $leavetaken->to_leave_day_np);
            } else {
                if ($to_date["month"] > $payroll_month) {
                    $leaveStartPayrollMonth = BSDateHelper::BsToAd('-', $payroll_month_first);
                    $leaveEndPayrollMonth = BSDateHelper::BsToAd('-', $payroll_month_last);
                }
            }
            $start = new DateTime($leaveStartPayrollMonth);
            $end = new DateTime($leaveEndPayrollMonth);
            $total_leave_taken = date_diff($end, $start)->format('%a') + 1;
            $weekend_on_leave = 0;
            $public_holiday_on_leave = 0;

            for ($i = strtotime($leaveStartPayrollMonth); $i <= strtotime($leaveEndPayrollMonth); $i = strtotime("+1 day", $i)) {
                if ($this->checkIfWeekendDay(date('Y-m-d', $i), $weekend)) {
                    $weekend_on_leave++;
                }
                if ($this->checkIfPublicHoliday(date('Y-m-d', $i), $public_holidays)) {
                    $public_holiday_on_leave++;
                }
            }
            $absent_on_weekend -= $weekend_on_leave;
            $absent_on_public_holiday -= $public_holiday_on_leave;
        }

        return $total_leave_taken;
    }

    public function checkIfPublicHoliday($date, $publicHolidays, $staff = null, $branch_id = null)
    {
        $date = date('Y-m-d', strtotime($date));
        $isHoliday = $publicHolidays->filter(function ($public_holidays) use ($date, $staff, $branch_id) {
            $checkDateStatus = true;
            if (!($public_holidays->from_date <= $date && $public_holidays->to_date >= $date)) {
                $checkDateStatus = false;
            }
            $checkGenderStatus = true;
            if (!empty($staff)) {
                if (empty($public_holidays->gender_id)) {
                    $checkGenderStatus = true;
                } elseif ($public_holidays->gender_id == $staff->Gender) {
                    $checkGenderStatus = true;
                } else {
                    $checkGenderStatus = false;
                }
            }
            $checkBranchStatus = true;
            if (!empty($staff) || !empty($branch_id)) {
                $branch_id_to_check = !empty($staff) ? $staff->branch_id : null;
                if (empty($branch_id_to_check)) {
                    $branch_id_to_check = $branch_id;
                }
                $checkBranchStatus = false;
                if ($public_holidays->branch->where('office_id', $branch_id_to_check)->count() > 0) {
                    $checkBranchStatus = true;
                }
            }


            return ($checkDateStatus && $checkGenderStatus && $checkBranchStatus);
        });


        if ($isHoliday->count() > 0) {
            return true;
        }
        return false;
    }


    public function checkifApprovedLeave($date, $approvedLeaves)
    {
        $date = date('Y-m-d', strtotime($date));
        $isApproved = $approvedLeaves->filter(function ($approvedLeave) use ($date) {
            return $approvedLeave->from_leave_day <= $date && $approvedLeave->to_leave_day >= $date;
        })->first();
        return $isApproved->leave_id ?? null;
    }

    public function calculatePayrollNepalReinsurance($staffDetails, $from_date, $to_date)
    {

    }

}
