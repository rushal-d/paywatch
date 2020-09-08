<?php

namespace App\Http\Controllers;

use App\EmployeeStatus;
use App\FetchAttendance;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\OrganizationMastShift;
use App\OrganizationSetup;
use App\StafMainMastModel;
use App\SystemHolidayMastModel;
use App\SystemOfficeMastModel;
use App\Traits\AppUtils;
use App\Traits\OvertimeCalculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class OvertimePayrollController extends Controller
{
    use OvertimeCalculation, AppUtils;

    public function index()
    {
        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $month_names = Config::get('constants.month_name');
        $current_fiscal_year_id = FiscalYearModel::where('fiscal_status', 1)->first()->id;
        return view('overtimepayroll.index', [
            'title' => 'Overtime Payroll',
            'fiscal_years' => $fiscal_years,
            'branches' => $branches,
            'months' => $month_names,
            'current_fiscal_year_id' => $current_fiscal_year_id,
        ]);
    }

    public function calculate(Request $request)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'fiscal_year_id' => 'required',
            'branch_id' => 'required',
            'month_id' => 'required',
            'from_date_np' => 'required',
            'to_date_np' => 'required',
        ],
            [
                'fiscal_year_id.required' => 'Please Select Fiscal Year',
                'branch_id.required' => 'Please Select Branch',
                'month_id.required' => 'Please Select Month',
                'from_date_np.required' => 'Please Select From Date',
                'to_date_np.required' => 'Please Select To Date',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            $overtimePayableStaffs = StafMainMastModel::with(['staffGrades' => function ($query) {
                $query->with('grade');
            }, 'jobposition', 'latestWorkSchedule'])->where('branch_id', $request->branch_id)->where('is_overtime_payable', 1)->get();
            $from_date_en = BSDateHelper::BsToAd('-', $request->from_date_np);
            $to_date_en = BSDateHelper::BsToAd('-', $request->to_date_np);
            $local_attendances = FetchAttendance::whereIn('staff_central_id', $overtimePayableStaffs->pluck('id'))->whereDate('punchin_datetime', '>=', $from_date_en)->whereDate('punchin_datetime', '<=', $to_date_en)->get();
            $overtimeRecords = [];
            $organization = OrganizationSetup::first();
            $suspenses = EmployeeStatus::whereIn('staff_central_id', $overtimePayableStaffs->pluck('id'))
                ->get();
            $salaryPayableDays = $request->total_days;
            $public_holidays = SystemHolidayMastModel::with('branch')->get();
            $organization_shifts = OrganizationMastShift::where('effective_from', '<=', $to_date_en)->orderBy('effective_from', 'desc')->get();
            $total_amount = $gross_amount = 0;
            $fiscal_year = FiscalYearModel::where('fiscal_start_date', '<=', $from_date_en)->where('fiscal_end_date', '>=', $to_date_en)->first();
            foreach ($overtimePayableStaffs as $staff) {
                $total_overtime_work = 0;
                $suspensesData = $suspenses->where('staff_central_id', $staff->id);
                $local_attendanceData = $local_attendances->where('staff_central_id', $staff->id);
                $this->calcuateOvertimeData($from_date_en, $to_date_en, $staff, $suspensesData, $local_attendanceData, $total_overtime_work, $organization, $public_holidays, $organization_shifts);
                if ($total_overtime_work > $organization->max_overtime_hour) {
                    $total_overtime_work = $organization->max_overtime_hour;
                }
                $total_overtime_work = round($total_overtime_work, 2);

                $gradeSplits = $this->gradeChangeDivisionByCollection($staff->staffGrades, $from_date_en, $to_date_en);
                $basicSalary = $staff->jobposition->basic_salary ?? 0;

                $payable_grade = 0;
                if (count($gradeSplits) > 0) {
                    foreach ($gradeSplits as $gradeSplit) {
                        $payable = ($basicSalary / 30) * $gradeSplit['grade'] * ($gradeSplit['percentage'] / 100);
                        $payable_grade += $payable;
                    }
                }
                $overtimeRecords[$staff->id]['staff_name'] = $staff->name_eng;
                $overtimeRecords[$staff->id]['overtime_work_hour'] = $total_overtime_work;
                $overtimeRecords[$staff->id]['grade_amount'] = round($payable_grade, 2);
                $overtimeRecords[$staff->id]['basic_salary'] = $basicSalary;
                $overtimeRecords[$staff->id]['total_payable'] = round($payable_grade, 2) + $basicSalary;
                $overtimeRecords[$staff->id]['payble_per_hour'] = round($overtimeRecords[$staff->id]['total_payable'] / $salaryPayableDays / ($staff->latestWorkSchedule->work_hour ?? 8), 2);
                $overtimeRecords[$staff->id]['total_amount'] = round($overtimeRecords[$staff->id]['payble_per_hour'] * $total_overtime_work, 2);
                $total_amount += $overtimeRecords[$staff->id]['total_amount'];
                $overtimeRecords[$staff->id]['ot_rate'] = 1.5;
                $overtimeRecords[$staff->id]['gross_amount'] = $overtimeRecords[$staff->id]['total_amount'] * $overtimeRecords[$staff->id]['ot_rate'];
                $gross_amount += $overtimeRecords[$staff->id]['gross_amount'];
            }
            $data = [
                'title' => 'Overtime Payroll',
                'overtimeRecords' => $overtimeRecords,
                'total_amount' => $total_amount,
                'gross_amount' => $gross_amount,
                'salary_month_name' => $request->salary_month_name,
                'fiscal_year' => $fiscal_year
            ];

            if ($request->excel_export == 1) {
                \Excel::create('Overtime Payroll Export', function ($excel) use ($data) {
                    $excel->sheet('Overtime Payroll', function ($sheet) use ($data) {
                        $sheet->loadView('overtimepayroll.calculate-table', $data);
                    });
                })->download('xlsx');
            }
            return view('overtimepayroll.calculate', $data);
        }
    }


}
