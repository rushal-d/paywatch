<?php

namespace App\Http\Controllers;

use App\AllowanceModelMast;
use App\EmployeeStatus;
use App\FetchAttendance;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\LeaveBalance;
use App\PayrollCalculationData;
use App\PayrollConfirm;
use App\PayrollConfirmAllowance;
use App\PayrollConfirmLeaveInfo;
use App\PayrollDetailModel;
use App\ProFund;
use App\StafMainMastModel;
use App\SundryBalance;
use App\SundryTransaction;
use App\SundryTransactionLog;
use App\SundryType;
use App\SystemHolidayMastModel;
use App\SystemLeaveMastModel;
use App\SystemOfficeMastModel;
use App\SystemPostMastModel;
use App\SystemTdsMastModel;
use App\Traits\PayrollCalculate;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use NumberFormatter;

class PayrollDifferenceController extends Controller
{
    use \App\Traits\AppUtils, PayrollCalculate;

    public function index()
    {
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $payrolls = PayrollDetailModel::whereHas('branch', function ($query) {
            $query->where('office_id', Auth::user()->branch_id);
        })->whereHas('fiscalyear', function ($query) {
            $query->where('fiscal_status', 1);
        })->pluck('payroll_name', 'id');
        $title = "Payroll Difference";

        return view('payrolldifference.index', [
            'branches' => $branches,
            'fiscal_years' => $fiscal_years,
            'payrolls' => $payrolls,
            'title' => $title,
        ]);
    }

    public function show(Request $request)
    {
        $payroll_id = $request->payroll_id;
        $staff_central_id = $request->staff_central_id;
        $response = $this->getPayrollDifferenceSingleStaff($payroll_id, $staff_central_id);
        if ($response['new_sundry_amount'] < 0) {
            $sundryTypes = SundryType::where('type', 2)->pluck('title', 'id');
        } else {
            $sundryTypes = SundryType::where('type', 1)->pluck('title', 'id');
        }
        $response['sundryTypes'] = $sundryTypes;
        return view('payrolldifference.show', $response);
    }

    public function payrollDifferenceSingleConfirm(Request $request)
    {
        $system_leaves = SystemLeaveMastModel::get();
        $payroll_id = $request->payroll_id;
        $staff_central_id = $request->staff_central_id;
        $response = $this->getPayrollDifferenceSingleStaff($payroll_id, $staff_central_id);
        $system_allowance_mast = AllowanceModelMast::get();
        $payrollDetails = $response['payrollDetails'];
        $staff = $response['staff'];
        $status_mesg = false;
        try {
            //start transaction for rolling back if some problem occurs
            DB::beginTransaction();
            $is_new_staff_in_payroll = false;
            $payroll_information = $response['new_calculation'];
            $payroll_confirm = $response['old_calculation'];
            if (empty($payroll_confirm)) {
                //creation of payroll confirm occurs when the previous generated payroll did not contain the staff!!
                $payroll_confirm = new PayrollConfirm();
                $is_new_staff_in_payroll = true;
            }
            $payroll_confirm->payroll_id = $payroll_id;
            $payroll_confirm->staff_central_id = $staff_central_id;
            $payroll_confirm->min_work_hour = $payroll_information["min_work_hour"];
            $payroll_confirm->tax_code = $payroll_information["marital_status"];
            $payroll_confirm->total_worked_hours = $payroll_information["total_work_hour"];
            $payroll_confirm->days_absent_on_holiday = $payroll_information["days_absent_on_holiday"];
            $payroll_confirm->weekend_work_hours = $payroll_information["weekend_work_hours"];
            $payroll_confirm->public_holiday_work_hours = $payroll_information["public_holiday_work_hours"];;
            $payroll_confirm->present_days = $payroll_information["present_days"];;
            $payroll_confirm->absent_days = $payroll_information["absent_days"];
            $payroll_confirm->redeem_home_leave = $payroll_information["redeem_home_leave"];
            $payroll_confirm->redeem_sick_leave = $payroll_information["redeem_sick_leave"];
            $payroll_confirm->salary_hour_payable = $payroll_information["salary_hour_payable"];;
            $payroll_confirm->ot_hour_payable = $payroll_information["ot_hour_payable"];
            $payroll_confirm->basic_salary = $payroll_information["basic_salary"];
            $payroll_confirm->dearness_allowance = $payroll_information["dearness_allowances"];
            $payroll_confirm->special_allowance = $payroll_information["special_allowances"];
            $payroll_confirm->extra_allowance = $payroll_information["extra_allowance"];
            $payroll_confirm->gratuity_amount = $payroll_information["gratuity_amount"];
            $payroll_confirm->social_security_fund_amount = $payroll_information["social_security_amount"];
            $payroll_confirm->incentive = $payroll_information["incentive"];
            $payroll_confirm->outstation_facility_amount = $payroll_information["outstation_facility_amount"];
            $payroll_confirm->pro_fund = $payroll_information["profund_amount"];
            $payroll_confirm->pro_fund_contribution = $payroll_information["profund_contribution_amount"];
            $payroll_confirm->home_sick_redeem_amount = $payroll_information["redeem_home_leave_amount"] + $payroll_information["redeem_sick_leave_amount"];
            $payroll_confirm->ot_amount = $payroll_information["ot_amount"];
            $payroll_confirm->gross_payable = $payroll_information["gross_payment"];
            $payroll_confirm->loan_payment = $payroll_information["house_loan_installment"] + $payroll_information["vehicle_loan_installment"];
            $payroll_confirm->sundry_dr = $payroll_information["sundry_dr_amount"];
            $payroll_confirm->sundry_cr = $payroll_information["sundry_cr_amount"];
            $payroll_confirm->tax = $payroll_information["tds"];
            $payroll_confirm->net_payable = $payroll_information["net_payment"];
            $payroll_confirm->remarks = '';

            $payroll_confirm->levy_amount = $payroll_information["levy_amount"];
            $payroll_confirm->home_leave_taken = $payroll_information["approved_home_leave"];
            $payroll_confirm->sick_leave_taken = $payroll_information["approved_sick_leave"];
            $payroll_confirm->maternity_leave_taken = $payroll_information["approved_maternity_leave"];
            $payroll_confirm->maternity_care_leave_taken = $payroll_information["approved_maternity_care_leave"];
            $payroll_confirm->funeral_leave_taken = $payroll_information["approved_funeral_leave"];
            $payroll_confirm->substitute_leave_taken = $payroll_information["approved_substitute_leave"];
            $payroll_confirm->unpaid_leave_taken = $payroll_information["unpaid_days"];
            $payroll_confirm->suspended_days = $payroll_information["suspense_days"];
            $payroll_confirm->useable_home_leave = $payroll_information["home_leave_balance"];
            $payroll_confirm->useable_sick_leave = $payroll_information["sick_leave_balance"];
            $payroll_confirm->useable_substitute_leave = $payroll_information["substitute_leave_balance"];
            $payroll_confirm->save();

            $payroll_confirm_leave_counter = $payroll_confirm_allowance_counter = 0;
            $payroll_confirm_leave = [];
            $payroll_confirm_allowances = [];
            $leave_balance_bulk = [];
            $used_home_leave = $payroll_information["approved_home_leave"] + $payroll_information["redeem_home_leave"];
            $used_sick_leave = $payroll_information["approved_sick_leave"] + $payroll_information["redeem_sick_leave"];
            $used_substitute_leave = $payroll_information["approved_substitute_leave"];

            $home_leave_payroll_confirm_info = $payroll_confirm->payrollConfirmLeaveInfos->where('leaveMast.leave_code', '3')->first();
            $sick_leave_payroll_confirm_info = $payroll_confirm->payrollConfirmLeaveInfos->where('leaveMast.leave_code', '4')->first();
            $substitute_leave_payroll_confirm_info = $payroll_confirm->payrollConfirmLeaveInfos->where('leaveMast.leave_code', '8')->first();

            if ($is_new_staff_in_payroll || empty($home_leave_payroll_confirm_info)) {
                //if not payroll confirm data then create
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['leave_id'] = $system_leaves->where('leave_code', '3')->first()->leave_id ?? null;
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['used'] = $used_home_leave;
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['earned'] = $payroll_information['total_home_leave_earned_this_month'];
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['balance'] = $payroll_information["home_leave_balance"];
                $payroll_confirm_leave_counter++;

                $leave_balance_item['staff_central_id'] = $staff_central_id;
                $leave_balance_item['leave_id'] = $system_leaves->where('leave_code', 3)->first()->leave_id;
                $leave_balance_item['fy_id'] = $payrollDetails->fiscal_year;
                $leave_balance_item['description'] = "Redeemed / Earned Leave in Payroll Difference";
                $leave_balance_item['consumption'] = $used_home_leave;
                $leave_balance_item['earned'] = $payroll_information['total_home_leave_earned_this_month'];
                $leave_balance_item['balance'] = $payroll_information["home_leave_balance"];
                $leave_balance_item['created_at'] = Carbon::now();
                $leave_balance_item['updated_at'] = Carbon::now();
                $leave_balance_item['date'] = date('Y-m-d');
                $leave_balance_item['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $leave_balance_item['payroll_id'] = $payroll_id;
                $leave_balance_bulk[] = $leave_balance_item;
            } else {
                if ($staff->homeLeaveBalanceLast->balance != $payroll_information["home_leave_balance"]) {
                    $consumption = 0;
                    $earned = 0;
                    if ($staff->homeLeaveBalanceLast->balance > $payroll_information["home_leave_balance"]) {
                        $consumption = $staff->homeLeaveBalanceLast->balance - $payroll_information["home_leave_balance"];
                    } else {
                        $earned = $payroll_information["home_leave_balance"] - $staff->homeLeaveBalanceLast->balance;
                    }

                    $leave_balance_item['staff_central_id'] = $staff_central_id;
                    $leave_balance_item['leave_id'] = $system_leaves->where('leave_code', 3)->first()->leave_id;
                    $leave_balance_item['fy_id'] = $payrollDetails->fiscal_year;
                    $leave_balance_item['description'] = "Redeemed / Earned Leave in Payroll Difference";
                    $leave_balance_item['consumption'] = $consumption;
                    $leave_balance_item['earned'] = $earned;
                    $leave_balance_item['balance'] = $payroll_information["home_leave_balance"];
                    $leave_balance_item['created_at'] = Carbon::now();
                    $leave_balance_item['updated_at'] = Carbon::now();
                    $leave_balance_item['date'] = date('Y-m-d');
                    $leave_balance_item['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                    $leave_balance_item['payroll_id'] = $payroll_id;
                    $leave_balance_bulk[] = $leave_balance_item;
                }
            }

            if ($is_new_staff_in_payroll || empty($sick_leave_payroll_confirm_info)) {
                //if not payroll confirm data then create
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['leave_id'] = $system_leaves->where('leave_code', '4')->first()->leave_id ?? null;
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['used'] = $used_home_leave;
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['earned'] = $payroll_information['total_sick_leave_earned_this_month'];
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['balance'] = $payroll_information["sick_leave_balance"];
                $payroll_confirm_leave_counter++;

                $leave_balance_item['staff_central_id'] = $staff_central_id;
                $leave_balance_item['leave_id'] = $system_leaves->where('leave_code', 4)->first()->leave_id;
                $leave_balance_item['fy_id'] = $payrollDetails->fiscal_year;
                $leave_balance_item['description'] = "Redeemed / Earned Leave in Payroll Difference";
                $leave_balance_item['consumption'] = $used_sick_leave;
                $leave_balance_item['earned'] = $payroll_information['total_sick_leave_earned_this_month'];
                $leave_balance_item['balance'] = $payroll_information["sick_leave_balance"];
                $leave_balance_item['created_at'] = Carbon::now();
                $leave_balance_item['updated_at'] = Carbon::now();
                $leave_balance_item['date'] = date('Y-m-d');
                $leave_balance_item['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $leave_balance_item['payroll_id'] = $payroll_id;
                $leave_balance_bulk[] = $leave_balance_item;
            } else {
                if ($staff->sickLeaveBalanceLast->balance != $payroll_information["sick_leave_balance"]) {
                    $consumption = 0;
                    $earned = 0;
                    if ($staff->sickLeaveBalanceLast->balance > $payroll_information["sick_leave_balance"]) {
                        $consumption = $staff->sickLeaveBalanceLast->balance - $payroll_information["sick_leave_balance"];
                    } else {
                        $earned = $payroll_information["sick_leave_balance"] - $staff->sickLeaveBalanceLast->balance;
                    }
                    $leave_balance_item['staff_central_id'] = $staff_central_id;
                    $leave_balance_item['leave_id'] = $system_leaves->where('leave_code', 4)->first()->leave_id;
                    $leave_balance_item['fy_id'] = $payrollDetails->fiscal_year;
                    $leave_balance_item['description'] = "Redeemed / Earned Leave in Payroll Difference";
                    $leave_balance_item['consumption'] = $consumption;
                    $leave_balance_item['earned'] = $earned;
                    $leave_balance_item['balance'] = $payroll_information["sick_leave_balance"];
                    $leave_balance_item['created_at'] = Carbon::now();
                    $leave_balance_item['updated_at'] = Carbon::now();
                    $leave_balance_item['date'] = date('Y-m-d');
                    $leave_balance_item['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                    $leave_balance_item['payroll_id'] = $payroll_id;
                    $leave_balance_bulk[] = $leave_balance_item;
                }
            }

            if ($is_new_staff_in_payroll || empty($substitute_leave_payroll_confirm_info)) {
                //if not payroll confirm data then create
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['leave_id'] = $system_leaves->where('leave_code', '8')->first()->leave_id ?? null;
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['used'] = $used_home_leave;
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['earned'] = $payroll_information['total_substitute_leave_earned_this_month'];
                $payroll_confirm_leave[$payroll_confirm_leave_counter]['balance'] = $payroll_information["substitute_leave_balance"];
                $payroll_confirm_leave_counter++;

                $leave_balance_item['staff_central_id'] = $staff_central_id;
                $leave_balance_item['leave_id'] = $system_leaves->where('leave_code', 8)->first()->leave_id;
                $leave_balance_item['fy_id'] = $payrollDetails->fiscal_year;
                $leave_balance_item['description'] = "Redeemed / Earned Leave in Payroll Difference";
                $leave_balance_item['consumption'] = $used_substitute_leave;
                $leave_balance_item['earned'] = $payroll_information['total_substitute_leave_earned_this_month'];
                $leave_balance_item['balance'] = $payroll_information["substitute_leave_balance"];
                $leave_balance_item['created_at'] = Carbon::now();
                $leave_balance_item['updated_at'] = Carbon::now();
                $leave_balance_item['date'] = date('Y-m-d');
                $leave_balance_item['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $leave_balance_item['payroll_id'] = $payroll_id;
                $leave_balance_bulk[] = $leave_balance_item;
            } else {
                if ($staff->substituteLeaveBalanceLast->balance != $payroll_information["substitute_leave_balance"]) {
                    $consumption = 0;
                    $earned = 0;
                    if ($staff->substituteLeaveBalanceLast->balance > $payroll_information["substitute_leave_balance"]) {
                        $consumption = $staff->substituteLeaveBalanceLast->balance - $payroll_information["substitute_leave_balance"];
                    } else {
                        $earned = $payroll_information["substitute_leave_balance"] - $staff->substituteLeaveBalanceLast->balance;
                    }
                    $leave_balance_item['staff_central_id'] = $staff_central_id;
                    $leave_balance_item['leave_id'] = $system_leaves->where('leave_code', 8)->first()->leave_id;
                    $leave_balance_item['fy_id'] = $payrollDetails->fiscal_year;
                    $leave_balance_item['description'] = "Redeemed / Earned Leave in Payroll Difference";
                    $leave_balance_item['consumption'] = $consumption;
                    $leave_balance_item['earned'] = $earned;
                    $leave_balance_item['balance'] = $payroll_information["substitute_leave_balance"];
                    $leave_balance_item['created_at'] = Carbon::now();
                    $leave_balance_item['updated_at'] = Carbon::now();
                    $leave_balance_item['date'] = date('Y-m-d');
                    $leave_balance_item['date_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                    $leave_balance_item['payroll_id'] = $payroll_id;
                    $leave_balance_bulk[] = $leave_balance_item;
                }
            }

            //relational allowance information
            $dearness_allowance_payroll_confirm = $payroll_confirm->payrollConfirmAllowances->where('allowanceMast.allow_code', '001')->first();
            $special_allowance_payroll_confirm = $payroll_confirm->payrollConfirmAllowances->where('allowanceMast.allow_code', '003')->first();
            $outstation_allowance_payroll_confirm = $payroll_confirm->payrollConfirmAllowances->where('allowanceMast.allow_code', '007')->first();
            $extra_allowance_payroll_confirm = $payroll_confirm->payrollConfirmAllowances->where('allowanceMast.allow_code', '008')->first();
            $incentive_allowance_payroll_confirm = $payroll_confirm->payrollConfirmAllowances->where('allowanceMast.allow_code', '006')->first();
            if ($is_new_staff_in_payroll || empty($dearness_allowance_payroll_confirm)) {
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '001')->first()->allow_id ?? null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $payroll_information["dearness_allowances"];
                $payroll_confirm_allowance_counter++;
            } else {
                if ($dearness_allowance_payroll_confirm->amount != $payroll_information["dearness_allowances"]) {
                    $dearness_allowance_payroll_confirm->amount = $payroll_information["dearness_allowances"];
                    $dearness_allowance_payroll_confirm->save();
                }
            }

            if ($is_new_staff_in_payroll || empty($special_allowance_payroll_confirm)) {
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '003')->first()->allow_id ?? null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $payroll_information["special_allowances"];
                $payroll_confirm_allowance_counter++;
            } else {
                if ($special_allowance_payroll_confirm->amount != $payroll_information["special_allowances"]) {
                    $special_allowance_payroll_confirm->amount = $payroll_information["special_allowances"];
                    $special_allowance_payroll_confirm->save();
                }
            }

            if ($is_new_staff_in_payroll || empty($outstation_allowance_payroll_confirm)) {
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '007')->first()->allow_id ?? null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $payroll_information["outstation_facility_amount"];
                $payroll_confirm_allowance_counter++;
            } else {
                if ($outstation_allowance_payroll_confirm->amount != $payroll_information["outstation_facility_amount"]) {
                    $outstation_allowance_payroll_confirm->amount = $payroll_information["outstation_facility_amount"];
                    $outstation_allowance_payroll_confirm->save();
                }
            }
            if ($is_new_staff_in_payroll || empty($extra_allowance_payroll_confirm)) {
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '008')->first()->allow_id ?? null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $payroll_information["extra_allowance"];
                $payroll_confirm_allowance_counter++;
            } else {
                if ($extra_allowance_payroll_confirm->amount != $payroll_information["extra_allowance"]) {
                    $extra_allowance_payroll_confirm->amount = $payroll_information["extra_allowance"];
                    $extra_allowance_payroll_confirm->save();
                }
            }

            if ($is_new_staff_in_payroll || empty($incentive_allowance_payroll_confirm)) {
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['payroll_confirm_id'] = $payroll_confirm->id;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['allow_id'] = $system_allowance_mast->where('allow_code', '006')->first()->allow_id ?? null;
                $payroll_confirm_allowances[$payroll_confirm_allowance_counter]['amount'] = $payroll_information["incentive"];
                $payroll_confirm_allowance_counter++;
            } else {
                if ($incentive_allowance_payroll_confirm->amount != $payroll_information["incentive"]) {
                    $incentive_allowance_payroll_confirm->amount = $payroll_information["incentive"];
                    $incentive_allowance_payroll_confirm->save();
                }
            }

            //profund
            $profundConfirmedRecord = $payrollDetails->profundLedger->where('staff_central_id', $staff_central_id)->first();
            if (empty($profundConfirmedRecord)) {
                $profundConfirmedRecord = new ProFund();
                $profundConfirmedRecord->payroll_id = $payrollDetails->id;
                $profundConfirmedRecord->staff_central_id = $staff_central_id;
                $profundConfirmedRecord->branch_id = $payrollDetails->branch_id;
                $profundConfirmedRecord->post_id = $staff->post_id;
                $profundConfirmedRecord->employee_contri = $payroll_information["profund_amount"];
                $profundConfirmedRecord->company_contri = $payroll_information["profund_contribution_amount"];
                $profundConfirmedRecord->save();
            } else {
                if ($profundConfirmedRecord->employee_contri != $payroll_information["profund_amount"] || $profundConfirmedRecord->company_contri != $payroll_information["profund_contribution_amount"]) {
                    $profundConfirmedRecord->employee_contri = $payroll_information["profund_amount"];
                    $profundConfirmedRecord->company_contri = $payroll_information["profund_contribution_amount"];
                    $profundConfirmedRecord->save();
                }
            }

            //CIT/GRATUITY
            $citConfirmedRecord = $payrollDetails->citLedger->where('staff_central_id', $staff_central_id)->first();
            if (empty($citConfirmedRecord)) {
                $citConfirmedRecord = new ProFund();
                $citConfirmedRecord->payroll_id = $payrollDetails->id;
                $citConfirmedRecord->staff_central_id = $staff_central_id;
                $citConfirmedRecord->branch_id = $payrollDetails->branch_id;
                $citConfirmedRecord->post_id = $staff->post_id;
                $citConfirmedRecord->cit_amount = $payroll_information["gratuity_amount"];
                $citConfirmedRecord->save();
            } else {
                if ($citConfirmedRecord->cit_amount != $payroll_information["gratuity_amount"]) {
                    $citConfirmedRecord->cit_amount = $payroll_information["gratuity_amount"];
                    $citConfirmedRecord->save();
                }
            }

            //social security tax
            $socialSecurityConfirmedRecord = $payrollDetails->socalSecurityTaxPayment->where('staff_central_id', $staff_central_id)->first();
            if (empty($socialSecurityConfirmedRecord)) {
                $socialSecurityConfirmedRecord = new ProFund();
                $socialSecurityConfirmedRecord->payroll_id = $payrollDetails->id;
                $socialSecurityConfirmedRecord->staff_central_id = $staff_central_id;
                $socialSecurityConfirmedRecord->branch_id = $payrollDetails->branch_id;
                $socialSecurityConfirmedRecord->post_id = $staff->post_id;
                $socialSecurityConfirmedRecord->tax_amount = $payroll_information["social_security_amount"];
                $socialSecurityConfirmedRecord->save();
            } else {
                if ($socialSecurityConfirmedRecord->tax_amount != $payroll_information["social_security_amount"]) {
                    $socialSecurityConfirmedRecord->tax_amount = $payroll_information["social_security_amount"];
                    $socialSecurityConfirmedRecord->save();
                }
            }

            //income tax
            $incomeTaxConfirmedRecord = $payrollDetails->incomeTaxPayment->where('staff_central_id', $staff_central_id)->first();
            if (empty($incomeTaxConfirmedRecord)) {
                $incomeTaxConfirmedRecord = new ProFund();
                $incomeTaxConfirmedRecord->payroll_id = $payrollDetails->id;
                $incomeTaxConfirmedRecord->staff_central_id = $staff_central_id;
                $incomeTaxConfirmedRecord->branch_id = $payrollDetails->branch_id;
                $incomeTaxConfirmedRecord->post_id = $staff->post_id;
                $incomeTaxConfirmedRecord->tax_amount = $payroll_information["income_tax"];
                $incomeTaxConfirmedRecord->save();
            } else {
                if ($incomeTaxConfirmedRecord->tax_amount != $payroll_information["income_tax"]) {
                    $incomeTaxConfirmedRecord->tax_amount = $payroll_information["income_tax"];
                    $incomeTaxConfirmedRecord->save();
                }
            }


            PayrollConfirmAllowance::insert($payroll_confirm_allowances);
            PayrollConfirmLeaveInfo::insert($payroll_confirm_leave);
            LeaveBalance::insert($leave_balance_bulk);
            $response['new_sundry_amount'] = abs($response['new_sundry_amount']);
            $sundry = new SundryTransaction();
            $sundry->staff_central_id = $staff_central_id;
            $sundry_type = $request->sundry_type;
            $no_installment = 1;
            $installment_amount = $response['new_sundry_amount'];
            $amount = $response['new_sundry_amount'];
            $is_cr = SundryType::isCR($sundry_type);
            $sundry->transaction_type_id = $sundry_type;
            if ($is_cr) { //cr
                $sundry->cr_installment = $no_installment;
                $sundry->cr_amount = $installment_amount; // installment amount
                $sundry->cr_balance = $amount;
            } else { //dr
                $sundry->dr_installment = $no_installment;
                $sundry->dr_amount = $installment_amount; // installment amount
                $sundry->dr_balance = $amount;
            }
            $sundry->transaction_date = date('Y-m-d');
            $sundry->transaction_date_en = BSDateHelper::BsToAd('-', date('Y-m-d'));
            $sundry->notes = 'Payroll Difference Sundry Confirm';
            $user_id = \Auth::user()->id;
            $sundry->created_by = $user_id;
            if ($sundry->save()) {
                //record of the transactions
                $sundry_transaction_log = new SundryTransactionLog();
                $sundry_transaction_log->sundry_id = $sundry->id;
                $sundry_transaction_log->staff_central_id = $staff_central_id;
                $sundry_transaction_log->transaction_date = date('Y-m-d');
                $sundry_transaction_log->transaction_date_en = BSDateHelper::BsToAd('-', date('Y-m-d'));
                $sundry_transaction_log->notes = 'Begining Transaction';
                $is_cr = SundryType::isCR($sundry_type);
                $sundry_transaction_log->transaction_type_id = $sundry_type;
                if ($is_cr) { //cr
                    $sundry_transaction_log->cr_installment = $no_installment;
                    $sundry_transaction_log->cr_amount = $installment_amount; // installment amount
                    $sundry_transaction_log->cr_balance = $amount;
                } else { //dr
                    $sundry_transaction_log->dr_installment = $no_installment;
                    $sundry_transaction_log->dr_amount = $installment_amount; // installment amount
                    $sundry_transaction_log->dr_balance = $amount;
                }
                $sundry_transaction_log->save();

                //also update the main master account of that same employee
                $sundry_balance = SundryBalance::where('staff_central_id', $staff_central_id)->first();
                if (!empty($sundry_balance)) { //check is empty that means first transaction
                    if ($is_cr) { //cr
                        $sundry_balance->cr_installment = $sundry_balance->cr_installment + $no_installment;
                        $sundry_balance->cr_amount = $sundry_balance->cr_amount + $installment_amount; // installment amount
                        $sundry_balance->cr_balance = $sundry_balance->cr_balance + $amount;
                    } else { //dr
                        $sundry_balance->dr_installment = $sundry_balance->dr_installment + $no_installment;
                        $sundry_balance->dr_amount = $sundry_balance->dr_amount + $installment_amount; // installment amount
                        $sundry_balance->dr_balance = $sundry_balance->dr_balance + $amount;
                    }
                } else { //make new master account for the employee
                    $sundry_balance = new SundryBalance();
                    $sundry_balance->dr_installment = $sundry->dr_installment;
                    $sundry_balance->dr_amount = $sundry->dr_amount;
                    $sundry_balance->dr_balance = $sundry->dr_balance;
                    $sundry_balance->cr_installment = $sundry->cr_installment;
                    $sundry_balance->cr_amount = $sundry->cr_amount;
                    $sundry_balance->cr_balance = $sundry->cr_balance;
                }
                $sundry_balance->staff_central_id = $sundry->staff_central_id;
                $sundry_balance->transaction_date = $sundry->transaction_date;
                $sundry_balance->transaction_date_en = $sundry->transaction_date_en;
                $sundry_balance->transaction_type_id = $sundry->transaction_type_id;
                $sundry_balance->notes = $sundry->notes;
                $sundry_balance->status = 0;
                $sundry_balance->created_by = $user_id;
                if ($sundry_balance->save()) {
                    $status_mesg = true;
                }
            }
        } catch (Exception $e) {
            DB::rollback();
            $status_mesg = false;
        }
        if ($status_mesg) {
            DB::commit();
        }

        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Payroll Difference Resolved Successfully' : 'Error Occured! Try Again!';
        return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
    }


    public function payrollInfo($payroll_id)
    {
        $payrollDetail = PayrollDetailModel::find($payroll_id);
        $months = Config::get('constants.month_name');
        return view('payrolldifference.payroll-info', [
            'payrollDetail' => $payrollDetail,
            'months' => $months,
            'title' => 'Payroll Difference On Payroll'
        ]);
    }

    public function totalPayrollDifference(Request $request)
    {
        $payroll_id = $request->payroll_id;
        $payroll_details = PayrollDetailModel::withoutGlobalScopes()->with(['attendanceSummary', 'fiscalyear', 'payrollConfirm' => function ($query) {
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
        $systemtdsmastmodel = SystemTdsMastModel::get();
        $system_leaves = SystemLeaveMastModel::get();
        $total_sick_leave_from_system = $system_leaves->where('leave_code', 4)->first()->no_of_days ?? 15;
        $total_home_leave_from_system = $system_leaves->where('leave_code', 3)->first()->no_of_days ?? 18;

        $earnable_home_leave = $total_home_leave_from_system / 12;
        $earnable_sick_leave = $total_sick_leave_from_system / 12;

        $minimum_basic_salary = SystemPostMastModel::min('basic_salary');
        $auth_user_id = Auth::user()->id;
        $payroll_data_counter = 0;
        $count = 0;
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
                'remarks' => $request->remarks[$staff_central_id],
                'already_paid_amount' => $payroll_details->payrollConfirm->where('staff_central_id', $staff_central_id)->first()->net_payable ?? 0,
                'difference' => $net_payment - ($payroll_details->payrollConfirm->where('staff_central_id', $staff_central_id)->first()->net_payable ?? 0),
            );
            $count++;
        }
        // if is in previous payroll but not in the recalculated payroll condition
        $previous_staff_central_id = $payroll_details->payrollConfirm->pluck('staff_central_id')->toArray();
        $staff_central_id_not_in_revised_payroll = array_diff($previous_staff_central_id, $staff_central_ids);
        $staff_not_in_recalculations = StafMainMastModel::whereIn('id', $staff_central_id_not_in_revised_payroll)->get();
        foreach ($staff_not_in_recalculations as $staff_not_in_recalculation) {
            $calculation_data[] = array(
                'main_id' => $staff_not_in_recalculation->main_id,
                'staff_central_id' => $staff_not_in_recalculation->staff_central_id,
                'staff_workschedule_total_work_hours' => '-',
                'marital_status' => ($staff_not_in_recalculation->marrid_stat == 1) ? 'Couple' : 'Single',
                'job_type' => '-',
                'post' => '-',
                'staff_name' => $staff_not_in_recalculation->name_eng,
                'basic_salary' => '-',
                'total_work_hours_selected_month' => '-',
                'total_work_hours_salary' => '-',
                'total_ot_hours_selected_month' => '-',
                'total_ot_hours_salary' => '-',
                'dearness_allowance' => '-',
                'extra_allowance' => '-',
                'special_allowance' => '-',
                'outstation_facility' => '-',
                'profund_amt' => '-',
                'gratuity_amt' => '-',
                'social_security_fund_amt' => '-',
                'incentive_amt' => '-',
                'profund_contribution_amt' => '-',
                'tds' => '-',
                'total_days_in_nepali_month' => '-',
                'total_present_days_current_month' => '-',
                'absent_on_holidays' => '-',
                'total_weekend_work_hours' => '-',
                'present_on_public_holiday_hours' => '-',
                'total_approved_home_leave_this_month' => '-',
                'total_approved_sick_leave_this_month' => '-',
                'total_approved_maternity_leave_this_month' => '-',
                'total_approved_funeral_leave_this_month' => '-',
                'total_approved_substitute_leave_this_month' => '-',
                'total_unpaid_leave' => '-',
                'home_leave_balance' => '-',
                'sick_leave_balance' => '-',
                'substitute_leave_balance' => '-',
                'absent_days' => '-',
                'gross_payment' => '-',
                'total_loan' => '-',
                'sundry_cr_amount' => '-',
                'sundry_dr_amount' => '-',
                'is_cash' => '-',
                'redeem_home_leave_amount' => '-',
                'redeem_sick_leave_amount' => '-',
                'redeem_home_leave' => '-',
                'redeem_sick_leave' => '-',
                'total_home_sick_amount' => '-',
                'staff_suspense_days' => '-',
                'net_payment' => 0,
                'levy_amount' => '-',
                'remarks' => '-',
                'already_paid_amount' => $payroll_details->payrollConfirm->where('staff_central_id', $staff_central_id)->first()->net_payable ?? 0,
                'difference' => 0 - ($payroll_details->payrollConfirm->where('staff_central_id', $staff_central_id)->first()->net_payable ?? 0),
            );
        }

        try {
            DB::beginTransaction();;
            PayrollCalculationData::insert($payroll_calculation_data);
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
        return view('attendancedetail.calculate', [
                'title' => 'Payroll Calculation Confirmation',
                'details' => $calculation_data,
                'payroll_details' => $payroll_details
            ]
        );
    }


}


