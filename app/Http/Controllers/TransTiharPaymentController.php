<?php

namespace App\Http\Controllers;

use App\DashainTiharSetup;
use App\FiscalYearAttendanceSum;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\PayrollDetailModel;
use App\StaffSalaryMastModel;
use App\StafMainMastModel;
use App\SystemJobTypeMastModel;
use App\SystemOfficeMastModel;
use App\SystemPostMastModel;
use App\SystemTdsMastModel;
use App\TaxStatement;
use App\Traits\PayrollStaff;
use App\TransBankStatement;
use App\TransCashStatement;
use App\TransTiharPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class TransTiharPaymentController extends Controller
{
    use PayrollStaff;

    public function index()
    {
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $fiscal_year = FiscalYearModel::pluck('fiscal_code', 'id');
        $month_names = Config::get('constants.month_name');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
        return view('tiharpayment.index', [
            'title' => 'Tihar Payment',
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
        //for getting previous year attendance records
        $previous_fiscal_year = FiscalYearModel::where('fiscal_start_date', '<=', $before_starting_fiscal_year)->where('fiscal_end_date', '>=', $before_starting_fiscal_year)->first();

        $staff_ids = $this->getPayrollStaffs($request->branch_id, null, date('Y-m-01', strtotime($request->payment_date)), date('Y-m-d', strtotime($request->payment_date)))->pluck('id')->toArray();

        $staff_details = StafMainMastModel::with(['jobposition', 'jobtype', 'latestsalary', 'fiscalYearAttendanceSum' => function ($query) use ($previous_fiscal_year) {
            $query->where('fiscal_year', $previous_fiscal_year->id);
            $query->latest();
        }])->whereIn('id',$staff_ids)->get();

        $input = Input::all();
        $branch = SystemOfficeMastModel::where('office_id', $request->branch_id)->first();

        $i = 0;
        $payroll_details = PayrollDetailModel::withoutGlobalScope('has_bonus')->where('fiscal_year', $request->fiscal_year)->where('has_bonus', 2)->first();
        if (!empty($payroll_details)) {

            $trans_tihar_payments = TransTiharPayment::select('staff_central_id', 'tax_amount', 'tihar_bonus_before_tax', 'tax_amount', 'net_payable')->where('payroll_id', $payroll_details->id)->get();
            foreach ($trans_tihar_payments as $trans_tihar_payment) {
                $data[$i]['staff_id'] = $trans_tihar_payment->staff->staff_central_id;
                $data[$i]['branch_id'] = $trans_tihar_payment->staff->main_id;
                $data[$i]['staff_name'] = $trans_tihar_payment->staff->name_eng;
                $data[$i]['tihar_bomus_before_tax'] = $trans_tihar_payment->tihar_bonus_before_tax;
                $data[$i]['tds'] = $trans_tihar_payment->tax_amount;
                $data[$i]['net_payable'] = $trans_tihar_payment->net_payable;
                $i++;
            }
            return view('tiharpayment.show', [
                'i' => 1,
                'title' => 'Tihar Payment',
                'data' => $data,
                'staff_details' => $staff_details,
                'input' => $input,
                'fiscal_year' => $fiscal_year,
                'branch' => $branch
            ]);
        }
        $data = array();


        $tds_object = SystemTdsMastModel::get();
        $dashain_setup = DashainTiharSetup::first();

        foreach ($staff_details as $staff_detail) {
            $fiscal_year_attendance = $staff_detail->fiscalYearAttendanceSum->first()->total_attendance ?? 0;
            //Last year no fiscal year attendance record
            //Also get grade amount
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


            $dashain_allow_extra = (!empty($staff_detail->dashain_allow)) ? $staff_detail->dashain_allow : 0;

            //calculate bonus payable
            $tihar_bonus = round($this->tihar_payment($total_monthly_salary, $job_type_detail->jobtype_name, $fiscal_year_attendance, $dashain_setup->extra_facility_dashain_tihar_rate, $dashain_allow_extra));

            //calculate Tax
            $tax_payable = $tihar_bonus * 12;
            $tds = round($this->tdsDeductionByMaritalStatusAndAmount($tds_object, $staff_marital_status, $tax_payable) / 12);

            $data[$i]['staff_id'] = $staff_detail->staff_central_id;
            $data[$i]['branch_id'] = $staff_detail->main_id;
            $data[$i]['staff_name'] = $staff_detail->name_eng;
            $data[$i]['tihar_bomus_before_tax'] = $tihar_bonus;
            $data[$i]['tds'] = $tds;
            $data[$i]['net_payable'] = $tihar_bonus - $tds;
            $i++;
        }

        return view('tiharpayment.show', [
            'i' => 1,
            'title' => 'Tihar Payment',
            'data' => $data,
            'staff_details' => $staff_details,
            'input' => $input,
            'fiscal_year' => $fiscal_year,
            'branch' => $branch,
        ]);
    }

    public function confirm(Request $request)
    {
        $status_mesg = false;
        try {
            DB::beginTransaction();
            $fiscal_year = FiscalYearModel::find($request->fiscal_year);
            $before_starting_fiscal_year = Carbon::createFromFormat('Y-m-d', $fiscal_year->fiscal_start_date)->subDays(2);
            //for getting previous year attendance records
            $previous_fiscal_year = FiscalYearModel::where('fiscal_start_date', '<=', $before_starting_fiscal_year)->where('fiscal_end_date', '>=', $before_starting_fiscal_year)->first();
            $staff_details = StafMainMastModel::with(['jobposition', 'jobtype', 'latestsalary', 'fiscalYearAttendanceSum' => function ($query) use ($previous_fiscal_year) {
                $query->where('fiscal_year', $previous_fiscal_year->id);
                $query->latest();
            }])->ofPayrollStaffFilter($request->branch_id)->get();
            $data = array();
            $input = Input::all();
            $tds_object = SystemTdsMastModel::get();
            $dashain_setup = DashainTiharSetup::first();
            $payroll_date = $request->payment_date;
            $paresed = date_parse($payroll_date);
            $tihar_payment_month = $paresed['month'];
            $payroll_date_en = BSDateHelper::BsToAd('-', $payroll_date);

            //payroll_detail
            if (PayrollDetailModel::withoutGlobalScope('has_bonus')->where('fiscal_year', $fiscal_year->id)->where('has_bonus', '=', 2)->exists()) {
                dd('Pahila ni tihar ko vaisakexa');
            }
            $payroll_detail = new PayrollDetailModel();
            $payroll_detail->salary_month = $tihar_payment_month;
            $payroll_detail->fiscal_year = $fiscal_year->id;
            $payroll_detail->branch_id = $request->branch_id;
            $payroll_detail->from_date = $payroll_date_en;
            $payroll_detail->from_date_np = $payroll_date;
            $payroll_detail->to_date = $payroll_date_en;
            $payroll_detail->to_date_np = $payroll_date;
            $payroll_detail->prepared_by = Auth::id();
            $payroll_detail->confirmed_by = Auth::id();
            $payroll_detail->has_bonus = 2; //2 refers to tihar
            if ($payroll_detail->save()) {

                foreach ($staff_details as $staff_detail) {
                    $fiscal_year_attendance = $staff_detail->fiscalYearAttendanceSum->first()->total_attendance ?? 0;

                    //First Get Basic Salary from Post (Designation)
                    $basic_salary = $staff_detail->jobposition->basic_salary;
                    $job_type_detail = $staff_detail->jobtype;
                    if (strcasecmp($job_type_detail->jobtype_code, "Con") == 0 || strcasecmp($job_type_detail->jobtype_code, "Con1") == 0) {
                        $basic_salary = $staff_detail->latestsalary->basic_salary;
                    }
                    //Also get grade amount
                    $grade_amount = ($staff_detail->latestsalary->total_grade_amount ?? 0) + ($staff_detail->latestsalary->add_grade_this_fiscal_year ?? 0);

                    //check if has additional salary
                    $additional_salary = $staff_detail->latestsalary->add_salary_amount;

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

                    $dashain_allow_extra = (!empty($staff_detail->dashain_allow)) ? $staff_detail->dashain_allow : 0;

                    //calculate bonus payable
                    $tihar_bonus = $this->tihar_payment($total_monthly_salary, $job_type_detail->jobtype_name, $fiscal_year_attendance, $dashain_setup->extra_facility_dashain_tihar_rate, $dashain_allow_extra);

                    //calculate Tax
                    $tax_payable = $tihar_bonus * 12;
                    $tds = round($this->tdsDeductionByMaritalStatusAndAmount($tds_object, $staff_marital_status, $tax_payable) / 12, 2);

                    $data[$staff_detail->id]['tihar_bonus'] = $tihar_bonus;
                    $data[$staff_detail->id]['tds'] = $tds;
                    $data[$staff_detail->id]['net_payable'] = $tihar_bonus - $tds;

                    $staff_work_date = $staff_detail->work_start_date;
                    $tihar_payment = new TransTiharPayment();
                    $tihar_payment->payroll_id = $payroll_detail->id;
                    $tihar_payment->fiscal_year = $payroll_detail->fiscal_year;
                    $tihar_payment->staff_central_id = $staff_detail->id;
                    $tihar_payment->branch_id = $payroll_detail->branch_id;
                    $tihar_payment->payment_date = $payroll_date;
                    $tihar_payment->advance_amount_taken = 0;
                    $tihar_payment->worked_months = $this->month_difference($staff_work_date, $payroll_date_en);
                    $tihar_payment->tihar_bonus_before_tax = $tihar_bonus;
                    $tihar_payment->tax_amount = $tds;
                    $tihar_payment->tihar_bonus_after_tax = $tihar_bonus - $tds;
                    $tihar_payment->net_payable = $tihar_bonus - $tds;
                    if ($tihar_payment->save()) {

                        if (empty($staff_detail->acc_no) || empty($staff_detail->bank_id)) {
                            $trans_cash_statement = TransCashStatement::where('payroll_id', $payroll_detail->id)->where('staff_central_id', $staff_detail->id)->first();
                            if (empty($bank_statement)) {
                                $trans_cash_statement = new TransCashStatement();
                            }
                            $trans_cash_statement->payroll_id = $payroll_detail->id;
                            $trans_cash_statement->staff_central_id = $staff_detail->id;
                            $trans_cash_statement->branch_id = $request->branch_id;
                            $trans_cash_statement->total_payment = $tihar_bonus - $tds;
                            $trans_cash_statement->remarks = "Tihar";
                            $trans_cash_statement->save();

                        } else {
                            $bank_statement = TransBankStatement::where('payroll_id', $payroll_detail->id)->where('staff_central_id', $staff_detail->id)->first();
                            if (empty($bank_statement)) {
                                $bank_statement = new TransBankStatement();
                            }
                            $bank_statement->payroll_id = $payroll_detail->id;
                            $bank_statement->staff_central_id = $staff_detail->id;
                            $bank_statement->branch_id = $payroll_detail->branch_id;
                            $bank_statement->bank_id = $staff_detail->bank_id;
                            $bank_statement->acc_no = $staff_detail->acc_no;
                            $bank_statement->brcode = substr($staff_detail->acc_no, 0, 3);
                            $bank_statement->trans_type = 'C';
                            $bank_statement->total_payment = $tihar_bonus - $tds;
                            $bank_statement->remarks = 'Tihar';
                            $bank_statement->save();
                        }
                        $tax_statement = new TaxStatement();
                        $tax_statement->payroll_id = $payroll_detail->id;
                        $tax_statement->staff_central_id = $staff_detail->id;
                        $tax_statement->branch_id = $request->branch_id;
                        $tax_statement->post_id = $staff_detail->post_id;
                        $tax_statement->tax_amount = $tds;
                        if ($tax_statement->save()) {
                            $status_mesg = true;
                        }
                    }
                }

            }

        } catch (\Exception $e) {
            DB::rollback();
        }

        if ($status_mesg) {
            DB::commit();
        }
    }


    public function tihar_payment($total_monthly_salary, $job_type_detail, $last_year_attendance, $rate, $extra_facility)
    {
        if (strcasecmp($job_type_detail, "Permanent") == 0) {
            if ($last_year_attendance >= 317) {
                $tihar_bonus = $total_monthly_salary;
            } else {
                $tihar_bonus = $total_monthly_salary * ($last_year_attendance / 317);
            }
            if ($extra_facility == 1) {
                $tihar_bonus *= $rate;
            }
        } else {
            if ($last_year_attendance >= 350) {
                $tihar_bonus = $total_monthly_salary;
            } else {
                $tihar_bonus = $total_monthly_salary * ($last_year_attendance / 350);
            }
            if ($extra_facility == 1) {
                $tihar_bonus *= $rate;
            }
        }
        return round($tihar_bonus / 2, 2);
    }

    public function tdsDeductionByMaritalStatusAndAmount($tds_object, $marital_status, $total_yearly_salary)
    {
        $tds = 0;
        $remaining_amount = 0;
        $tds_slabs = Config::get('constants.tds_slabs');

        //for first slab
        $tds_details_slab = $this->getTDSDetailsBySlab($tds_object, $marital_status, 1);
        $tds_deduction_amount = $tds_details_slab->amount;
        $tds_first_deduction_amount = $tds_details_slab->amount;

        $tds_deduction_percent = ($tds_details_slab->percent / 100);
        $remaining_amount = $total_yearly_salary - $tds_deduction_amount;
        //check if under slab or over slab
        if ($remaining_amount > 0) { //if over first slab

            //first slab tds deduction
            $tds = $tds_deduction_amount * $tds_deduction_percent;
            //echo $tds .'<br>';

            //check for second slab
            //second slab
            $tds_details_slab = $this->getTDSDetailsBySlab($tds_object, $marital_status, 2);
            $tds_deduction_amount = $tds_details_slab->amount + $tds_deduction_amount; //adding previous slab amount too for 2nd slab
            $tds_deduction_percent = ($tds_details_slab->percent / 100);

            //now tds for 2nd slab 100000 amount
            $remaining_amount_slab = $total_yearly_salary - $tds_deduction_amount;
            if ($remaining_amount_slab > 0) { //if over second slab
                $tds += $tds_details_slab->amount * $tds_deduction_percent;
                //echo $tds .'<br>';
                //3rd slab
                $tds_details_slab = $this->getTDSDetailsBySlab($tds_object, $marital_status, 3);
                $tds_deduction_amount = $tds_details_slab->amount; //adding previous slab amount too for 2nd slab
                $tds_deduction_percent = ($tds_details_slab->percent / 100);

                $remaining_amount_slab = $total_yearly_salary - $tds_deduction_amount;

                //get 4th slab
                $tds_next_slab = $this->getTDSDetailsBySlab($tds_object, $marital_status, 4);
                $tds_next_slab_amount = $tds_next_slab->amount;

                if ($total_yearly_salary > $tds_next_slab_amount) { //above fourth slab
                    // echo '<br>Above fourth slab <br>';


                } else { //below fourth slab
                    $remaining_amount = $total_yearly_salary - $tds_deduction_amount;
                    $tds += $remaining_amount * $tds_deduction_percent; //get remainder amount
                    // echo $tds .'<br>';
                }
            } else {
                $remaining_amount = fmod($total_yearly_salary, $tds_first_deduction_amount);
                $tds += $remaining_amount * $tds_deduction_percent; //get remainder amount
                // echo $tds .'<br>';
            }

        } else { //under first slab
            $tds = $total_yearly_salary * $tds_deduction_percent;
            // echo $tds .'<br>';
        }

        return $tds;
    }

    public
    function getTDSDetailsBySlab($tds_object, $marital_status, $slab)
    {
        return $tds_object->where('type', $marital_status)->where('slab', $slab)->first();
    }

    public function month_difference($work_start_date, $payroll_date)
    {
        $start_date = new Carbon($work_start_date);
        $pay_date = new Carbon($payroll_date);
        $diff = $start_date->diffInMonths($pay_date);
        return $diff;
    }
}
