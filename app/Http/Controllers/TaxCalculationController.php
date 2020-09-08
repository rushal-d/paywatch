<?php

namespace App\Http\Controllers;

use App\AllowanceModelMast;
use App\FiscalYearModel;
use App\StaffTaxSlabPayable;
use App\StafMainMastModel;
use App\SystemOfficeMastModel;
use App\SystemTdsMastModel;
use App\Traits\AppUtils;
use App\Traits\PayrollCalculate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaxCalculationController extends Controller
{
    use AppUtils, PayrollCalculate;

    public function nepalReTaxCalculationIndex()
    {
        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $current_fiscal_id = FiscalYearModel::where('fiscal_status', 1)->first()->id;
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        return view('nepalreinsurance.taxcalculation.index', [
            'title' => 'Nepal Reinsurance Tax Calculation',
            'fiscal_years' => $fiscal_years,
            'current_fiscal_id' => $current_fiscal_id,
            'branches' => $branches,
        ]);
    }

    public function nepalReTaxCalculation(Request $request)
    {
        $branch_id = $request->branch_id;
        $fiscal_year_id = $request->fiscal_year_id;
        $fiscal_year = FiscalYearModel::find($fiscal_year_id);
        $staffs = StafMainMastModel::with([
            'workschedule', 'houseLoanDiffIncome', 'vehicleLoanDiffIncome', 'jobtype', 'payment', 'vehicleLoan',
            'staffCitDeductions' => function ($query) use ($fiscal_year_id) {
                $query->where('fiscal_year_id', $fiscal_year_id);
            }, 'staffBonuses' => function ($query) use ($fiscal_year_id) {
                $query->where('fiscal_year_id', $fiscal_year_id);
            }, 'staffInsurance' => function ($query) use ($fiscal_year_id) {
                $query->where('fiscal_year_id', $fiscal_year_id);
            }, 'staffGrades' => function ($query) {
                $query->with('grade');
            }, 'staffPosts' => function ($query) {
                $query->with('post');
            }
        ])->where('branch_id', $branch_id)->orderBy('staff_main_mast.staff_central_id')->get();
        $allowances = AllowanceModelMast::get();
        $systemtdsmastmodel = SystemTdsMastModel::get();
        $tax_details = [];
        $tax_details = $this->calculateNepalReTax($staffs, $fiscal_year, $allowances, $fiscal_year_id, $systemtdsmastmodel);
        return view(
            'nepalreinsurance.taxcalculation.show', [
                'title' => 'Nepal Re Tax Calculation',
                'allowances' => $allowances,
                'tax_slabs' => $systemtdsmastmodel->where('fy', $fiscal_year_id)->where('type', 1),
                'tax_details' => $tax_details
            ]
        );
    }

    public function taxCalculationSave(Request $request)
    {
        $branch_id = $request->branch_id;
        $fiscal_year_id = $request->fiscal_year_id;
        $fiscal_year = FiscalYearModel::find($fiscal_year_id);
        $staffs = StafMainMastModel::with([
            'workschedule', 'houseLoanDiffIncome' => function ($query) use ($fiscal_year_id) {
                $query->where('fiscal_year_id', $fiscal_year_id);
            }, 'vehicleLoanDiffIncome' => function ($query) use ($fiscal_year_id) {
                $query->where('fiscal_year_id', $fiscal_year_id);
            }, 'jobtype', 'payment', 'vehicleLoan',
            'staffCitDeductions' => function ($query) use ($fiscal_year_id) {
                $query->where('fiscal_year_id', $fiscal_year_id);
            }, 'staffBonuses' => function ($query) use ($fiscal_year_id) {
                $query->where('fiscal_year_id', $fiscal_year_id);
            }, 'staffInsurance' => function ($query) use ($fiscal_year_id) {
                $query->where('fiscal_year_id', $fiscal_year_id);
            }, 'staffGrades' => function ($query) {
                $query->with('grade');
            }, 'staffPosts' => function ($query) {
                $query->with('post');
            }
        ])->where('branch_id', $branch_id)->orderBy('staff_main_mast.staff_central_id')->get();
        $allowances = AllowanceModelMast::get();
        $systemtdsmastmodel = SystemTdsMastModel::get();
        $tax_details = $this->calculateNepalReTax($staffs, $fiscal_year, $allowances, $fiscal_year_id, $systemtdsmastmodel);
        $previousTaxRecords = StaffTaxSlabPayable::where('fiscal_year_id', $fiscal_year_id)->get();
        $status_mesg = false;
        try {
            DB::beginTransaction();
            foreach ($tax_details as $staff_central_id => $tax_detail) {

                $marital_status = ($tax_detail['marital_status'] === "C") ? 1 : 0;

                $tax_slab_records = $systemtdsmastmodel->where('fy', $fiscal_year_id)->where('type', $marital_status);
                foreach ($tax_slab_records as $tax_slab_record) {
                    $tax_slab_payable = $previousTaxRecords->where('staff_central_id', $staff_central_id)->where('tds_detail_id', $tax_slab_record->id)->first();
                    if (empty($tax_slab_payable)) {
                        $tax_slab_payable = new StaffTaxSlabPayable();
                    } else {
                        if ($tax_slab_payable->tax_amount_yearly == $tax_detail['yearly_tax_slab'][$tax_slab_record->slab] &&
                            $tax_slab_payable->tax_amount_monthly == $tax_detail['monthly_tax_slab'][$tax_slab_record->slab]) {
                            continue;
                        }
                    }

                    $tax_slab_payable->staff_central_id = $staff_central_id;
                    $tax_slab_payable->fiscal_year_id = $fiscal_year_id;
                    $tax_slab_payable->tds_detail_id = $tax_slab_record->id;
                    $tax_slab_payable->tax_amount_yearly = $tax_detail['yearly_tax_slab'][$tax_slab_record->slab];
                    $tax_slab_payable->tax_amount_monthly = $tax_detail['monthly_tax_slab'][$tax_slab_record->slab];
                    $status_mesg = $tax_slab_payable->save();
                }

            }
        } catch (\Exception $e) {
            dd($e);
            $status_mesg = false;
            DB::rollBack();
        }
        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Data Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    public function calculateNepalReTax($staffs, $fiscal_year, $allowances, $fiscal_year_id, $systemtdsmastmodel)
    {
        foreach ($staffs as $staff) {
            $tax_details[$staff->id]['post'] = $staff->staffPosts->where('effective_to_date', null)->first()->post->post_title;
            if ($staff->Gender == 1) {
                $title = 'Mr.';
            } elseif ($staff->Gender == 2) {
                if ($staff->marrid_stat == 0) {
                    $title = 'Ms.';
                } else {
                    $title = 'Mrs.';
                }
            }
            $tax_details[$staff->id]['title'] = $title;
            $tax_details[$staff->id]['name'] = $staff->name_eng;
            $basic_salary_response = $this->basicSalaryChangeDivisionByCollection($staff->staffPosts, $fiscal_year->fiscal_start_date, $fiscal_year->fiscal_end_date);
            $basic_salary_payable = 0;
            foreach ($basic_salary_response as $basic_salary_for_fiscal_year) {
                $payable_month = 12 * ($basic_salary_for_fiscal_year['percentage'] / 100);
                $payable = $basic_salary_for_fiscal_year['basic_salary'] * $payable_month;
                $basic_salary_payable += $payable;
            }
            $current_basic_salary = $staff->staffPosts->where('effective_to_date', null)->first()->post->basic_salary;
            $tax_details[$staff->id]['basic_salary_monthly'] = $current_basic_salary;
            $tax_details[$staff->id]['basic_salary'] = $basic_salary_payable;
            $gradeDetails = $this->gradeChangeDivisionByCollection($staff->staffGrades, $fiscal_year->fiscal_start_date, $fiscal_year->fiscal_end_date, $current_basic_salary * 12, $grade_payable);
            if (count($gradeDetails) == 0) {
                //if no grade is allotted to the staff
                $tax_details[$staff->id]['grade_before_increment'] = 0;
                $tax_details[$staff->id]['grade_after_increment'] = 0;
            } elseif (count($gradeDetails) == 1) {
                //if it is the first grade of the staff
                $tax_details[$staff->id]['grade_before_increment'] = $gradeDetails[0]['grade_payable'];
                $tax_details[$staff->id]['grade_after_increment'] = 0;
            } elseif (count($gradeDetails) == 2) {
                //if the grade has been revised in the fiscal year
                $tax_details[$staff->id]['grade_before_increment'] = $gradeDetails[0]['grade_payable'];
                $tax_details[$staff->id]['grade_after_increment'] = $gradeDetails[1]['grade_payable'];
            } else {
                $tax_details[$staff->id]['grade_before_increment'] = $gradeDetails[0]['grade_payable'];
                //if the grade has been changed multiple times in the fiscal ear
                $after_grade = 0;
                for ($i = 1; $i < count($gradeDetails); $i++) {
                    $after_grade += $gradeDetails[$i]['grade_payable'];
                }
                $tax_details[$staff->id]['grade_after_increment'] = $after_grade;
            }
            //allowance yaerly income
            //allowance type=1 : monthly allowance type=2 yearly
            //monthly allowances which are included in payroll
            $monthlyPayableInPayrollAllowances = $allowances->where('allowance_type', 1)->where('include_in_payroll', 1);

            foreach ($monthlyPayableInPayrollAllowances as $allowance) {
                $allowancePayment = $staff->payment->where('allow_id', $allowance->allow_id);
                $allowance_payments = $this->allowanceChangeDivisionByCollection($allowancePayment, $fiscal_year->fiscal_start_date, $fiscal_year->fiscal_end_date);
                $allowance_payable = 0;
                foreach ($allowance_payments as $payment) {
                    $payable_month = 12 * ($payment['percentage'] / 100);
                    $payable = $payment['amount'] * $payable_month;
                    $allowance_payable += $payable;
                }
                $tax_details[$staff->id]['allowance_payment'][$allowance->allow_id] = $allowance_payable;
            }
            //yearly allowances
            $yearlyAllowances = $allowances->where('allowance_type', 2);

            foreach ($yearlyAllowances as $yearlyAllowance) {

                $allowancePayment = $staff->payment->where('allow_id', $yearlyAllowance->allow_id)->where('effective_from', '>=', $fiscal_year->fiscal_start_date)->where('effective_from', '<=', $fiscal_year->fiscal_end_date)->where('allow', 1)->sum('amount');
                $tax_details[$staff->id]['allowance_payment'][$yearlyAllowance->allow_id] = $allowancePayment;
            }
            //monthly allowances which are not included in payroll
            $monthlyPayableInNotPayrollAllowances = $allowances->where('allowance_type', 1)->where('include_in_payroll', 0);
            foreach ($monthlyPayableInNotPayrollAllowances as $allowance) {
                $allowancePayment = $staff->payment->where('allow_id', $allowance->allow_id);
                $allowance_payments = $this->allowanceChangeDivisionByCollection($allowancePayment, $fiscal_year->fiscal_start_date, $fiscal_year->fiscal_end_date);
                $allowance_payable = 0;
                foreach ($allowance_payments as $payment) {
                    $payable_month = 12 * ($payment['percentage'] / 100);
                    $payable = $payment['amount'] * $payable_month;
                    $allowance_payable += $payable;
                }
                $tax_details[$staff->id]['allowance_payment'][$allowance->allow_id] = $allowance_payable;
            }

            $tax_details[$staff->id]['house_loan_diff_income'] = $staff->houseLoanDiffIncome->sum('diff_income');
            $tax_details[$staff->id]['vehicle_loan_diff_income'] = $staff->vehicleLoanDiffIncome->sum('diff_income');

            $overtime_allowance = 0;
            if ($staff->is_overtime_payable == 1) {
                $payble_months = 12;
                $ot_hours = 60;
                $perhour_salary = ($basic_salary_payable / 365) / $staff->workschedule->last()->work_hour;
                $overtime_allowance = $ot_hours * $perhour_salary * $payble_months;
                if (strtotime($staff->appo_date) > strtotime($fiscal_year->fiscal_start_date)) {
                    $working_days_in_fy = $this->daysDifference($staff->appo_date, $fiscal_year->fiscal_end_date);
                    $payable_percentage = $working_days_in_fy / 365;
                    $overtime_allowance = $overtime_allowance * $payable_percentage;
                }
            }

            $tax_details[$staff->id]['overtime_allowance'] = $overtime_allowance;

            $provident_applicable_salary = $tax_details[$staff->id]['basic_salary'] + $tax_details[$staff->id]['grade_before_increment'] + $tax_details[$staff->id]['grade_after_increment'];

            $tax_details[$staff->id]['pf_by_organization'] = $provident_applicable_salary * ($staff->jobtype->profund_contri_per ?? 10) / 100;
            $tax_details[$staff->id]['pf_by_staff'] = $provident_applicable_salary * ($staff->jobtype->profund_per ?? 10) / 100;
            $tax_details[$staff->id]['bonus'] = $staff->staffBonuses->sum('received_amount');
            $tax_details[$staff->id]['gross_annual_income'] = $tax_details[$staff->id]['basic_salary'] + $tax_details[$staff->id]['grade_before_increment'] + $tax_details[$staff->id]['grade_after_increment'] +
                array_sum($tax_details[$staff->id]['allowance_payment']) + $tax_details[$staff->id]['house_loan_diff_income'] + $tax_details[$staff->id]['vehicle_loan_diff_income'] +
                $tax_details[$staff->id]['overtime_allowance'] + $tax_details[$staff->id]['pf_by_organization'] + $tax_details[$staff->id]['bonus'];
            $tax_details[$staff->id]['one_third'] = $tax_details[$staff->id]['gross_annual_income'] / 3;

            $tax_details[$staff->id]['cit_till_date'] = 0;
            $tax_details[$staff->id]['total_pf'] = $tax_details[$staff->id]['pf_by_organization'] + $tax_details[$staff->id]['pf_by_staff'];
            $tax_details[$staff->id]['contribution_in_the_year'] = $tax_details[$staff->id]['one_third'] - $tax_details[$staff->id]['total_pf'];
            $tax_details[$staff->id]['total_contribution'] = $tax_details[$staff->id]['contribution_in_the_year'] + $tax_details[$staff->id]['total_pf'];
            $tax_details[$staff->id]['allowable_contribution'] = min([$tax_details[$staff->id]['total_contribution'], $tax_details[$staff->id]['one_third'], 300000]);
            $tax_details[$staff->id]['insurance_premium'] = $staff->staffInsurance->sum('premium_amount');

            $tax_details[$staff->id]['total_taxable_income'] = $tax_details[$staff->id]['gross_annual_income'] - $tax_details[$staff->id]['allowable_contribution'] - $tax_details[$staff->id]['insurance_premium'];
            $staff_marital_status = (int)$staff->marrid_stat;
            $tax_details[$staff->id]['marital_status'] = ($staff_marital_status == 0) ? 'S' : 'C';
            $tax_details[$staff->id]['gender'] = ($staff->Gender == 1) ? 'M' : 'F';
            $tds = $this->getTdsDeductionAmountBySlabNumber($staff_marital_status, $tax_details[$staff->id]['total_taxable_income'], $fiscal_year, $systemtdsmastmodel);
            $tax_details[$staff->id]['total_taxable_estimated_tax'] = array_sum($tds);
            $tax_details[$staff->id]['total_taxable_estimated_tax_monthly'] = $tax_details[$staff->id]['total_taxable_estimated_tax'] / 12;
            foreach ($systemtdsmastmodel->where('fy', $fiscal_year_id)->where('type', $staff_marital_status) as $tdsSlabs) {
                $tax_details[$staff->id]['yearly_tax_slab'][$tdsSlabs->slab] = isset($tds[$tdsSlabs->slab]) ? $tds[$tdsSlabs->slab] : 0;
                $tax_details[$staff->id]['monthly_tax_slab'][$tdsSlabs->slab] = isset($tds[$tdsSlabs->slab]) ? $tds[$tdsSlabs->slab] / 12 : 0;
            }
        }
        return $tax_details;
    }
}
