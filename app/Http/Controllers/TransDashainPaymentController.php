<?php

namespace App\Http\Controllers;

use App\DashainTaxStatement;
use App\DashainTiharSetup;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\PayrollDetailModel;
use App\SocialSecurityTaxStatement;
use App\StafMainMastModel;
use App\SystemOfficeMastModel;
use App\SystemTdsMastModel;
use App\TaxStatement;
use App\Traits\PayrollCalculate;
use App\TransBankStatement;
use App\TransCashStatement;
use App\TransDashainPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class TransDashainPaymentController extends Controller
{
    use PayrollCalculate;

    public function index()
    {
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $fiscal_year = FiscalYearModel::pluck('fiscal_code', 'id');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
        $month_names = Config::get('constants.month_name');
        return view('dashainpayment.index', [
            'title' => 'Dashain Payment',
            'branches' => $branches,
            'current_fiscal_year_id' => $current_fiscal_year_id,
            'fiscal_years' => $fiscal_year,
            'month_names' => $month_names
        ]);

    }

    public function show(Request $request)
    {
        $fiscal_year = FiscalYearModel::find($request->fiscal_year);
        $before_starting_fiscal_year = Carbon::createFromFormat('Y-m-d', $fiscal_year->fiscal_start_date)->subDays(2);
        $previous_fiscal_year = FiscalYearModel::where('fiscal_start_date', '<=', $before_starting_fiscal_year)->where('fiscal_end_date', '>=', $before_starting_fiscal_year)->first();
        $staff_details = StafMainMastModel::with(['jobposition', 'jobtype', 'latestsalary', 'fiscalYearAttendanceSum' => function ($query) use ($previous_fiscal_year) {
            $query->where('fiscal_year', $previous_fiscal_year->id);
            $query->latest();
        }])->where('payroll_branch_id', $request->branch_id)->where(function ($innerQuery) {
            $innerQuery->whereHas('workschedule', function ($innerInnerQuery) {
                $innerInnerQuery->whereNotNull('weekend_day')->whereNotNull('work_hour');
            })->where(function ($innerInnerQuery) {
                $innerInnerQuery->where('post_id', '<>', 0)
                    ->whereNotNull('post_id')
                    ->whereNotNull('jobtype_id')
                    ->where('jobtype_id', '<>', 0);
            });
        })
            ->whereIn('staff_type', [0, 1])->get();
        $input = Input::all();
        $branch = SystemOfficeMastModel::where('office_id', $request->branch_id)->first();


        $payroll_details = PayrollDetailModel::withoutGlobalScope('has_bonus')->where('fiscal_year', $request->fiscal_year)->where('has_bonus', 1)->first();

        if (!empty($payroll_details)) {
            $trans_dashain_payments = TransDashainPayment::with(['staff' => function ($query) use ($previous_fiscal_year) {
                $query->with(['fiscalYearAttendanceSum' => function ($query) use ($previous_fiscal_year) {
                    $query->where('fiscal_year', $previous_fiscal_year->id);
                    $query->latest();
                }]);
            }])->select('staff_central_id', 'tax_amount', 'dashain_expense_after_tax', 'dashain_bonus_after_tax', 'special_incentive_amount', 'net_payable')->where('payroll_id', $payroll_details->id)->get();
            $i = 0;
            foreach ($trans_dashain_payments as $trans_dashain_payment) {
                $data[$i]['staff_id'] = $trans_dashain_payment->staff->staff_central_id;
                $data[$i]['branch_id'] = $trans_dashain_payment->staff->main_id;
                $data[$i]['staff_name'] = $trans_dashain_payment->staff->name_eng ?? '';
                $data[$i]['dashain_expense'] = $trans_dashain_payment->dashain_expense_after_tax;
                $data[$i]['total_attendance'] = $trans_dashain_payment->staff->fiscalYearAttendanceSum->first()->total_attendance ?? 0;;
                $data[$i]['dashain_bonus'] = $trans_dashain_payment->dashain_bonus_after_tax;
                $data[$i]['special_incentive'] = $trans_dashain_payment->special_incentive_amount;
                $data[$i]['tds'] = $trans_dashain_payment->tax_amount;
                $data[$i]['net_payable'] = $trans_dashain_payment->net_payable;
                $i++;
            }
            return view('dashainpayment.show', [
                'i' => 1,
                'title' => 'Dashain Payment',
                'data' => $data,
                'staff_details' => $staff_details,
                'input' => $input,
                'fiscal_year' => $fiscal_year,
                'branch' => $branch,
                'already_confirmed' => true,
            ]);
        }

        //for getting previous year attendance records
        $data = array();
        $tds_object = SystemTdsMastModel::get();
        $dashain_setup = DashainTiharSetup::first();
        $i = 0;
        foreach ($staff_details as $staff_detail) {
            $fiscal_year_attendance = $staff_detail->fiscalYearAttendanceSum->first()->total_attendance ?? 0;

            $grade_amount = ($staff_detail->latestsalary->total_grade_amount ?? 0) + ($staff_detail->latestsalary->add_grade_this_fiscal_year ?? 0);
            //First Get Basic Salary from Post (Designation)
            $basic_salary = $staff_detail->jobposition->basic_salary;
            $job_type_detail = $staff_detail->jobtype;
            if (strcasecmp($job_type_detail->jobtype_code, "Con") == 0 || strcasecmp($job_type_detail->jobtype_code, "Con1") == 0) {
                $basic_salary = $staff_detail->latestsalary->basic_salary;
            }
            //Also get grade amount
            //check if has additional salary
            $additional_salary = $staff_detail->latestsalary->add_salary_amount ?? 0;
            //get martial status for tax calculation
            $marital_status = $staff_detail->marrid_stat;
            $staff_marital_status = 0;
            if ($marital_status == 'S') {
                $staff_marital_status = 0;
            } else {
                $staff_marital_status = 1;
            }

            //total monthly salary
            $dearness_allowance = (!empty($staff_detail->dearness_allowance_amount)) ? $staff_detail->dearness_allowance_amount : 0;
            $special_allowance = (!empty($staff_detail->special_allowance_amount)) ? $staff_detail->special_allowance_amount : 0;
            $special_allowance_2 = (!empty($staff_detail->special_allowance_2_amount)) ? $staff_detail->special_allowance_2_amount : 0;
            $risk_allowance = (!empty($staff_detail->risk_allowance_amount)) ? $staff_detail->risk_allowance_amount : 0;

            $total_monthly_salary = $basic_salary + $grade_amount + $additional_salary + $dearness_allowance + $special_allowance + $special_allowance_2 +
                +$risk_allowance;


            //check if extra facility is given to staff or not
            $dashain_allow_extra = (!empty($staff_detail->dashain_allow)) ? $staff_detail->dashain_allow : 0;


            //calculate bonus payable
            $dashain_expense = $this->dashain_payment($total_monthly_salary, $job_type_detail->jobtype_name, $fiscal_year_attendance, $previous_fiscal_year->present_days, $dashain_setup->extra_facility_dashain_tihar_rate, $dashain_allow_extra);
            $bonus_payable = $this->dashain_payment_bonus($total_monthly_salary, $job_type_detail->jobtype_name, $fiscal_year_attendance, $previous_fiscal_year->present_days, $dashain_setup->extra_facility_dashain_tihar_rate, $dashain_allow_extra);

            //calculate Tax

            $tax_payable_bonus = ($bonus_payable) * 12;
            $tds_bonus = round(($this->tdsDeductionByMaritalStatusAndAmount($staff_marital_status, $tax_payable_bonus, $fiscal_year, $tds_object) / 12));

            $tax_payable_expenses = ($dashain_expense) * 12;
            $tds_expenses = round($this->tdsDeductionByMaritalStatusAndAmount($staff_marital_status, $tax_payable_expenses, $fiscal_year, $tds_object) / 12);

            $tds = $tds_bonus + $tds_expenses;

            $staff_work_date = $staff_detail->work_start_date;
            $payroll_date = $request->payment_date;
            $payroll_date_en = BSDateHelper::BsToAd('-', $payroll_date);
            //calculate incentives

            $special_incentive_amount = 0;
//            $special_incentive_amount = $this->special_incentive($staff_work_date, $payroll_date_en, $dashain_setup->incentive_amount);
            $data[$i]['staff_id'] = $staff_detail->staff_central_id;
            $data[$i]['branch_id'] = $staff_detail->main_id;
            $data[$i]['staff_name'] = $staff_detail->name_eng;
            $data[$i]['dashain_expense'] = $dashain_expense;
            $data[$i]['dashain_bonus'] = $bonus_payable;
            $data[$i]['special_incentive'] = $special_incentive_amount;
            $data[$i]['total_attendance'] = $fiscal_year_attendance;
            $data[$i]['tds'] = $tds;
            $data[$i]['net_payable'] = $dashain_expense + $bonus_payable + $special_incentive_amount - $tds;
            $i++;
        }
        return view('dashainpayment.show', [
            'i' => 1,
            'title' => 'Dashain Payment',
            'data' => $data,
            'staff_details' => $staff_details,
            'input' => $input,
            'fiscal_year' => $fiscal_year,
            'branch' => $branch,
            'already_confirmed' => false,
        ]);
    }

    public function confirm(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 1800);
        try {
            DB::beginTransaction();
            $status = false;
            $fiscal_year = FiscalYearModel::find($request->fiscal_year);
            $before_starting_fiscal_year = Carbon::createFromFormat('Y-m-d', $fiscal_year->fiscal_start_date)->subDays(2);

            //for getting previous year attendance records
            $previous_fiscal_year = FiscalYearModel::where('fiscal_start_date', '<=', $before_starting_fiscal_year)->where('fiscal_end_date', '>=', $before_starting_fiscal_year)->first();
            $staff_details = StafMainMastModel::with(['jobposition', 'jobtype', 'latestsalary', 'fiscalYearAttendanceSum' => function ($query) use ($previous_fiscal_year) {
                $query->where('fiscal_year', $previous_fiscal_year->id);
                $query->latest();
            }])->where('payroll_branch_id', $request->branch_id)->where(function ($innerQuery) {
                $innerQuery->whereHas('workschedule', function ($innerInnerQuery) {
                    $innerInnerQuery->whereNotNull('weekend_day')->whereNotNull('work_hour');
                })->where(function ($innerInnerQuery) {
                    $innerInnerQuery->where('post_id', '<>', 0)
                        ->whereNotNull('post_id')
                        ->whereNotNull('jobtype_id')
                        ->where('jobtype_id', '<>', 0);
                });
            })
                ->whereIn('staff_type', [0, 1])->get();

            $data = array();
            $input = Input::all();
            $payroll_date = $request->payment_date;
            $paresed = date_parse($payroll_date);
            $dashian_payment_month = $paresed['month'];
            $payroll_date_en = BSDateHelper::BsToAd('-', $payroll_date);
            $branch = SystemOfficeMastModel::where('office_id', $request->branch_id)->first();
            $tds_object = SystemTdsMastModel::get();
            $dashain_setup = DashainTiharSetup::first();
            $dashain_payment_bulk = [];
            $trans_cash_statement_bulk = [];
            $bank_statement_bulk = [];
            $tax_statement = [];
            $social_security_tax_statement = [];
            $dashain_tax_statement = [];
            //payroll_detail
            if (PayrollDetailModel::withoutGlobalScope('has_bonus')->where('fiscal_year', $fiscal_year->id)->where('has_bonus', '=', 1)->exists()) {
                dd('Pahila ni dashain ko vaisakexa');
            }

            $payroll_detail = new PayrollDetailModel();
            $payroll_detail->payroll_name = $fiscal_year->fiscal_code . ' Dashain' . $branch->office_name;
            $payroll_detail->salary_month = $dashian_payment_month;
            $payroll_detail->fiscal_year = $fiscal_year->id;
            $payroll_detail->branch_id = $request->branch_id;
            $payroll_detail->from_date = $payroll_date_en;
            $payroll_detail->from_date_np = $payroll_date;
            $payroll_detail->to_date = $payroll_date_en;
            $payroll_detail->to_date_np = $payroll_date;
            $payroll_detail->prepared_by = Auth::id();
            $payroll_detail->confirmed_by = Auth::id();
            $payroll_detail->has_bonus = 1; //1 refers to dashain
            if ($payroll_detail->save()) {
                $branch = SystemOfficeMastModel::where('office_id', $request->branch_id)->first();

                foreach ($staff_details as $staff_detail) {
                    $fiscal_year_attendance = $staff_detail->fiscalYearAttendanceSum->first()->total_attendance ?? 0;

                    $grade_amount = ($staff_detail->latestsalary->total_grade_amount ?? 0) + ($staff_detail->latestsalary->add_grade_this_fiscal_year ?? 0);
                    //First Get Basic Salary from Post (Designation)
                    $basic_salary = $staff_detail->jobposition->basic_salary;

                    $job_type_detail = $staff_detail->jobtype;
                    if (strcasecmp($job_type_detail->jobtype_code, "Con") == 0 || strcasecmp($job_type_detail->jobtype_code, "Con1") == 0) {
                        $basic_salary = $staff_detail->latestsalary->basic_salary;
                    }
                    //check if has additional salary
                    $additional_salary = $staff_detail->latestsalary->add_salary_amount ?? 0;

                    //get martial status for tax calculation
                    $marital_status = $staff_detail->marrid_stat;
                    $staff_marital_status = 0;
                    if ($marital_status == 'S') {
                        $staff_marital_status = 0;
                    } else {
                        $staff_marital_status = 1;
                    }

                    //total monthly salary

                    $dearness_allowance = (!empty($staff_detail->dearness_allowance_amount)) ? $staff_detail->dearness_allowance_amount : 0;
                    $special_allowance = (!empty($staff_detail->special_allowance_amount)) ? $staff_detail->special_allowance_amount : 0;
                    $special_allowance_2 = (!empty($staff_detail->special_allowance_2_amount)) ? $staff_detail->special_allowance_2_amount : 0;
                    $risk_allowance = (!empty($staff_detail->risk_allowance_amount)) ? $staff_detail->risk_allowance_amount : 0;

                    $total_monthly_salary = $basic_salary + $grade_amount + $additional_salary + $dearness_allowance + $special_allowance + $special_allowance_2 + $risk_allowance;


                    //check if extra facility is given to staff or not
                    $dashain_allow_extra = (!empty($staff_detail->dashain_allow)) ? $staff_detail->dashain_allow : 0;

                    //calculate bonus payable
                    $dashain_expense = $this->dashain_payment($total_monthly_salary, $job_type_detail->jobtype_name, $fiscal_year_attendance, $previous_fiscal_year->present_days, $dashain_setup->extra_facility_dashain_tihar_rate, $dashain_allow_extra);
                    $bonus_payable = $this->dashain_payment_bonus($total_monthly_salary, $job_type_detail->jobtype_name, $fiscal_year_attendance, $previous_fiscal_year->present_days, $dashain_setup->extra_facility_dashain_tihar_rate, $dashain_allow_extra);

                    //calculate Tax
                    $tax_payable_bonus = ($bonus_payable) * 12;
                    $tds_bonus_array = $this->getTdsDeductionAmountBySlabNumber($staff_marital_status, $tax_payable_bonus, $fiscal_year, $tds_object);
                    $social_security_bonus_tds = round($tds_bonus_array[SystemTdsMastModel::firstSlab] / 12);
                    unset($tds_bonus_array[SystemTdsMastModel::firstSlab]);
                    $income_tax_bonus = round(array_sum($tds_bonus_array) / 12);
                    $tds_bonus = $social_security_bonus_tds + $income_tax_bonus;

                    $tax_payable_expenses = ($dashain_expense) * 12;
                    $tds_expenses_array = $this->getTdsDeductionAmountBySlabNumber($staff_marital_status, $tax_payable_expenses, $fiscal_year, $tds_object);
                    $social_security_expenses_tds = round($tds_expenses_array[SystemTdsMastModel::firstSlab] / 12);
                    unset($tds_expenses_array[SystemTdsMastModel::firstSlab]);
                    $income_tax_expenses = round(array_sum($tds_expenses_array) / 12);
                    $tds_expenses = $social_security_expenses_tds + $income_tax_expenses;

                    $tds = $tds_bonus + $tds_expenses;

                    $staff_work_date = $staff_detail->work_start_date;

                    //calculate incentives
                    $special_incentive_amount = 0;
//                    $special_incentive_amount = $this->special_incentive($staff_work_date, $payroll_date_en, $dashain_setup->incentive_amount);
                    $netpayment = $dashain_expense + $bonus_payable + $special_incentive_amount - $tds;
                    $dashain_expense_before_tax = $dashain_expense - $tds_expenses;
                    $bonus_payable_before_tax = $bonus_payable - $tds_bonus;

                    $dashain_payment_bulk[$staff_detail->id]['payroll_id'] = $payroll_detail->id;
                    $dashain_payment_bulk[$staff_detail->id]['fiscal_year'] = $payroll_detail->fiscal_year;
                    $dashain_payment_bulk[$staff_detail->id]['staff_central_id'] = $staff_detail->id;
                    $dashain_payment_bulk[$staff_detail->id]['branch_id'] = $payroll_detail->branch_id;
                    $dashain_payment_bulk[$staff_detail->id]['payment_date'] = $payroll_date;
                    $dashain_payment_bulk[$staff_detail->id]['advance_amount_taken'] = 0;
                    $dashain_payment_bulk[$staff_detail->id]['worked_months'] = $this->month_difference($staff_work_date, $payroll_date_en);
                    $dashain_payment_bulk[$staff_detail->id]['dashain_expense_before_tax'] = $dashain_expense;
                    $dashain_payment_bulk[$staff_detail->id]['dashain_bonus_before_tax'] = $bonus_payable;
                    $dashain_payment_bulk[$staff_detail->id]['gross_payment'] = $dashain_expense + $bonus_payable;
                    $dashain_payment_bulk[$staff_detail->id]['tax_amount'] = $tds;
                    $dashain_payment_bulk[$staff_detail->id]['dashain_expense_after_tax'] = $dashain_expense_before_tax;
                    $dashain_payment_bulk[$staff_detail->id]['dashain_bonus_after_tax'] = $bonus_payable_before_tax;
                    $dashain_payment_bulk[$staff_detail->id]['special_incentive_amount'] = $special_incentive_amount;
                    $dashain_payment_bulk[$staff_detail->id]['net_payable'] = $netpayment;
                    $dashain_payment_bulk[$staff_detail->id]['created_at'] = Carbon::now();
                    $dashain_payment_bulk[$staff_detail->id]['updated_at'] = Carbon::now();


                    if (empty($staff_detail->acc_no) || empty($staff_detail->bank_id)) {

                        $trans_cash_statement_bulk[$staff_detail->id]['payroll_id'] = $payroll_detail->id;
                        $trans_cash_statement_bulk[$staff_detail->id]['staff_central_id'] = $staff_detail->id;
                        $trans_cash_statement_bulk[$staff_detail->id]['branch_id'] = $request->branch_id;
                        $trans_cash_statement_bulk[$staff_detail->id]['total_payment'] = $dashain_expense + $bonus_payable + $special_incentive_amount - $tds;
                        $trans_cash_statement_bulk[$staff_detail->id]['remarks'] = "Dashain";
                        $trans_cash_statement_bulk[$staff_detail->id]['created_at'] = Carbon::now();
                        $trans_cash_statement_bulk[$staff_detail->id]['updated_at'] = Carbon::now();
                    } else {
                        $bank_statement_bulk[$staff_detail->id]['payroll_id'] = $payroll_detail->id;
                        $bank_statement_bulk[$staff_detail->id]['staff_central_id'] = $staff_detail->id;
                        $bank_statement_bulk[$staff_detail->id]['branch_id'] = $payroll_detail->branch_id;
                        $bank_statement_bulk[$staff_detail->id]['bank_id'] = $staff_detail->bank_id;
                        $bank_statement_bulk[$staff_detail->id]['acc_no'] = $staff_detail->acc_no;
                        $bank_statement_bulk[$staff_detail->id]['brcode'] = substr($staff_detail->acc_no, 0, 3);
                        $bank_statement_bulk[$staff_detail->id]['trans_type'] = 'C';
                        $bank_statement_bulk[$staff_detail->id]['total_payment'] = $dashain_expense + $bonus_payable + $special_incentive_amount - $tds;
                        $bank_statement_bulk[$staff_detail->id]['remarks'] = 'Dashain';
                        $bank_statement_bulk[$staff_detail->id]['created_at'] = Carbon::now();
                        $bank_statement_bulk[$staff_detail->id]['updated_at'] = Carbon::now();
                    }

                    $tax_statement[$staff_detail->id]['payroll_id'] = $payroll_detail->id;
                    $tax_statement[$staff_detail->id]['staff_central_id'] = $staff_detail->id;
                    $tax_statement[$staff_detail->id]['branch_id'] = $request->branch_id;
                    $tax_statement[$staff_detail->id]['post_id'] = $staff_detail->post_id;
                    $tax_statement[$staff_detail->id]['tax_amount'] = $income_tax_bonus + $income_tax_expenses;
                    $tax_statement[$staff_detail->id]['created_at'] = Carbon::now();
                    $tax_statement[$staff_detail->id]['updated_at'] = Carbon::now();

                    $social_security_tax_statement[$staff_detail->id]['payroll_id'] = $payroll_detail->id;
                    $social_security_tax_statement[$staff_detail->id]['staff_central_id'] = $staff_detail->id;
                    $social_security_tax_statement[$staff_detail->id]['branch_id'] = $request->branch_id;
                    $social_security_tax_statement[$staff_detail->id]['post_id'] = $staff_detail->post_id;
                    $social_security_tax_statement[$staff_detail->id]['tax_amount'] = $social_security_bonus_tds + $social_security_expenses_tds;
                    $social_security_tax_statement[$staff_detail->id]['created_at'] = Carbon::now();
                    $social_security_tax_statement[$staff_detail->id]['updated_at'] = Carbon::now();

                    $dashain_tax_statement[$staff_detail->id]['payroll_id'] = $payroll_detail->id;
                    $dashain_tax_statement[$staff_detail->id]['expenses_social_security_tax'] = $social_security_expenses_tds;
                    $dashain_tax_statement[$staff_detail->id]['expenses_income_tax'] = $income_tax_expenses;
                    $dashain_tax_statement[$staff_detail->id]['bonus_social_security_tax'] = $social_security_bonus_tds;
                    $dashain_tax_statement[$staff_detail->id]['bonus_income_tax'] = $income_tax_bonus;
                    $dashain_tax_statement[$staff_detail->id]['created_at'] = Carbon::now();
                    $dashain_tax_statement[$staff_detail->id]['updated_at'] = Carbon::now();


                    $data[$staff_detail->id]['staff_id'] = $staff_detail->staff_central_id;
                    $data[$staff_detail->id]['staff_name'] = $staff_detail->name_eng ?? '';
                    $data[$staff_detail->id]['total_attendance'] = $fiscal_year_attendance;
                    $data[$staff_detail->id]['dashain_expense'] = $dashain_expense;
                    $data[$staff_detail->id]['dashain_bonus'] = $bonus_payable;
                    $data[$staff_detail->id]['special_incentive'] = $special_incentive_amount;
                    $data[$staff_detail->id]['tds'] = $tds;
                    $data[$staff_detail->id]['net_payable'] = $dashain_expense + $bonus_payable + $special_incentive_amount - $tds;

                }
                TransDashainPayment::insert($dashain_payment_bulk);
                TransCashStatement::insert($trans_cash_statement_bulk);
                TransBankStatement::insert($bank_statement_bulk);
                TaxStatement::insert($tax_statement);
                SocialSecurityTaxStatement::insert($social_security_tax_statement);
                DashainTaxStatement::insert($dashain_tax_statement);
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollback();
        }


        return view('dashainpayment.show', [
            'i' => 1,
            'title' => 'Dashain Payment',
            'data' => $data,
            'staff_details' => $staff_details,
            'input' => $input,
            'fiscal_year' => $fiscal_year,
            'branch' => $branch,
            'already_confirmed' => false,
        ]);
    }

    public function dashain_payment($total_monthly_salary, $job_type_detail, $fiscal_year_attendance, $last_year_working_days, $rate, $extra_facility)
    {
        if (strcasecmp($job_type_detail, "Permanent") == 0) {
            if ($fiscal_year_attendance >= 183) {
                $bonus_payable = $total_monthly_salary;
            } else {
                $bonus_payable = $total_monthly_salary * ($fiscal_year_attendance / $last_year_working_days);
            }
            if ($extra_facility == 1) {
                $bonus_payable = $bonus_payable * $rate;
            }
        } else {
            if ($fiscal_year_attendance >= (365 / 2)) {
                $bonus_payable = $total_monthly_salary;
            } else {
                $bonus_payable = 0;
            }
            if ($extra_facility == 1) {
                $bonus_payable = $bonus_payable * $rate;
            }
        }
        return round($bonus_payable);
    }

    public function dashain_payment_bonus($total_monthly_salary, $job_type_detail, $fiscal_year_attendance, $last_year_working_days, $rate, $extra_facility)
    {
        if (strcasecmp($job_type_detail, "Permanent") == 0) {
            if ($fiscal_year_attendance >= 183) {
                $bonus_payable = $total_monthly_salary;
            } else {
                $bonus_payable = $total_monthly_salary * ($fiscal_year_attendance / $last_year_working_days);
            }
            if ($extra_facility == 1) {
                $bonus_payable = $bonus_payable * $rate;
            }
        } else {
            if ($fiscal_year_attendance >= 350) {
                $bonus_payable = $total_monthly_salary;
            } else {
                $bonus_payable = $total_monthly_salary * ($fiscal_year_attendance / 350);
            }
            if ($extra_facility == 1) {
                $bonus_payable = $bonus_payable * $rate;
            }
        }
        return round($bonus_payable);
    }

    public function special_incentive($work_start_date, $payroll_date, $incentive_amount)
    {
        $start_date = new Carbon($work_start_date);
        $pay_date = new Carbon($payroll_date);
        $diff = $start_date->diffInMonths($pay_date);
        if ($diff >= 12) {
            $special_incentive = $incentive_amount;
        } else {
            $special_incentive = ($incentive_amount / 12) * $diff;
        }
        return $special_incentive;
    }

    public function month_difference($work_start_date, $payroll_date)
    {
        $start_date = new Carbon($work_start_date);
        $pay_date = new Carbon($payroll_date);
        $diff = $start_date->diffInMonths($pay_date);
        return $diff;
    }


}
