<?php

namespace App\Http\Controllers;

use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\StaffSalaryModel;
use App\StafMainMastModel;
use App\SystemOfficeMastModel;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class GradeRevisionController extends Controller
{
    public function index(Request $request)
    {
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $selectedBranch = $request->branch_id ?? Auth::user()->branch_id;
        $staffs = StafMainMastModel::where('branch_id', $selectedBranch)->with(['branch', 'latestsalary' => function ($query) {
            $query->with('fiscalyear');
        }])->withAndWhereHas('jobtype', function ($query) {
            $query->where('jobtype_code', 'P');
        });
        if (!empty($request->staff_central_id)) {
            $staffs = $staffs->where('id', $request->staff_central_id);
        }
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $staffs = $staffs->paginate($records_per_page);

        return view('graderevision.index', [
            'title' => 'Grade Revision',
            'staffmains' => $staffs,
            'branches' => $branches,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
        ]);
    }

    public function reviseGrade(Request $request)
    {
        $status_mesg = false;
        try {
            DB::beginTransaction();
            $staffId = $request->staff_central_id;
            $effectiveDateNp = $request->effective_date_np;
            $effectiveDate = BSDateHelper::BsToAd('-', $effectiveDateNp);
            $additionalGradeThisFiscalYear = $request->additional_grade;
            $previousFiscalYearGrade = 0;
            $previousFiscalYearBasicSalary = 0;
            $previousFiscalYearAdditionalSalary = 0;

            $effectiveDateFiscalYear = FiscalYearModel::where('fiscal_start_date', '<=', $effectiveDate)->where('fiscal_end_date', '>=', $effectiveDate)->first();
            $previousFiscalYearDate = date('Y-m-d', strtotime('-30 days', strtotime($effectiveDateFiscalYear->fiscal_start_date)));
            $previousFiscalYear = FiscalYearModel::where('fiscal_start_date', '<=', $previousFiscalYearDate)->where('fiscal_end_date', '>=', $previousFiscalYearDate)->first();

            $staffSalaryMastPreviousFiscalYear = StaffSalaryModel::where('staff_central_id', $staffId)->where('fiscal_year_id', $previousFiscalYear->id)->orderByDesc('salary_effected_date')->first();
            if (!empty($staffSalaryMastPreviousFiscalYear)) {
                $previousFiscalYearGrade = ($staffSalaryMastPreviousFiscalYear->total_grade_amount ?? 0) + ($staffSalaryMastPreviousFiscalYear->add_grade_this_fiscal_year ?? 0);
                $previousFiscalYearBasicSalary = $staffSalaryMastPreviousFiscalYear->basic_salary;
                $previousFiscalYearAdditionalSalary = $staffSalaryMastPreviousFiscalYear->add_salary_amount;
            }
            $staffmain = StafMainMastModel::with('jobtype', 'jobposition')->where('id', $staffId)->first();
            $staffSalaryMast = StaffSalaryModel::where('staff_central_id', $staffId)->whereDate('salary_effected_date', $effectiveDate)->first();
            if (empty($staffSalaryMast)) {
                $staffSalaryMast = new StaffSalaryModel();
            }
            $staffSalaryMast->staff_central_id = $staffmain->id;
            $staffSalaryMast->post_id = $staffmain->post_id;
            $staffSalaryMast->fiscal_year_id = $effectiveDateFiscalYear->id;

            if (!empty($staffmain->jobtype)) {
                if (strcasecmp($staffmain->jobtype->jobtype_code, 'Con') == 0 || strcasecmp($staffmain->jobtype->jobtype_code, 'Con1') == 0) {
                    $staffSalaryMast->basic_salary = $previousFiscalYearBasicSalary;
                } else {
                    $staffSalaryMast->basic_salary = $staffmain->jobposition->basic_salary;
                }
            }
            $staffSalaryMast->add_salary_amount = $previousFiscalYearAdditionalSalary;
            $staffSalaryMast->total_grade_amount = $previousFiscalYearGrade;
            $staffSalaryMast->add_grade_this_fiscal_year = $additionalGradeThisFiscalYear;
            $staffSalaryMast->salary_effected_date = $effectiveDate;
            $staffSalaryMast->salary_effected_date_np = $effectiveDateNp;
            $staffSalaryMast->salary_payment_status = 'A';
            $staffSalaryMast->created_by = Auth::id();
            $staffSalaryMast->updated_by = Auth::id();
            if ($staffSalaryMast->save()) {
                $status_mesg = true;
                DB::commit();
            }
        } catch (\Exception $exception) {
            $status_mesg = false;
            DB::rollBack();
        }

        $staff = StafMainMastModel::with(['latestsalary' => function ($query) {
            $query->with('fiscalyear');
        }])->where('id', $staffId)->first();

        return response()->json([
            'status' => $status_mesg,
            'staff' => $staff,
        ]);
    }

    public function reviseGradeMultiple(Request $request)
    {
        $status_mesg = false;
        try {
            DB::beginTransaction();
            $staffIds = explode(',',$request->staff_central_ids);
            $effectiveDateNp = $request->effective_date_np;
            $effectiveDate = BSDateHelper::BsToAd('-', $effectiveDateNp);
            $additionalGradeThisFiscalYear = $request->additional_grade;

            $effectiveDateFiscalYear = FiscalYearModel::where('fiscal_start_date', '<=', $effectiveDate)->where('fiscal_end_date', '>=', $effectiveDate)->first();
            $previousFiscalYearDate = date('Y-m-d', strtotime('-30 days', strtotime($effectiveDateFiscalYear->fiscal_start_date)));
            $previousFiscalYear = FiscalYearModel::where('fiscal_start_date', '<=', $previousFiscalYearDate)->where('fiscal_end_date', '>=', $previousFiscalYearDate)->first();

            $allStaffPrevFiscalYearSalary = StaffSalaryModel::whereIn('staff_central_id', $staffIds)->where('fiscal_year_id', $previousFiscalYear->id)->orderByDesc('salary_effected_date')->get();
            foreach ($staffIds as $staffId) {
                $previousFiscalYearGrade = 0;
                $previousFiscalYearBasicSalary = 0;
                $previousFiscalYearAdditionalSalary = 0;
                $staffSalaryMastPreviousFiscalYear = $allStaffPrevFiscalYearSalary->where('staff_central_id', $staffId)->first();
                if (!empty($staffSalaryMastPreviousFiscalYear)) {
                    $previousFiscalYearGrade = ($staffSalaryMastPreviousFiscalYear->total_grade_amount ?? 0) + ($staffSalaryMastPreviousFiscalYear->add_grade_this_fiscal_year ?? 0);
                    $previousFiscalYearBasicSalary = $staffSalaryMastPreviousFiscalYear->basic_salary;
                    $previousFiscalYearAdditionalSalary = $staffSalaryMastPreviousFiscalYear->add_salary_amount;
                }
                $staffmain = StafMainMastModel::with('jobtype', 'jobposition')->where('id', $staffId)->first();
                $staffSalaryMast = StaffSalaryModel::where('staff_central_id', $staffId)->whereDate('salary_effected_date', $effectiveDate)->first();
                if (empty($staffSalaryMast)) {
                    $staffSalaryMast = new StaffSalaryModel();
                }
                $staffSalaryMast->staff_central_id = $staffmain->id;
                $staffSalaryMast->post_id = $staffmain->post_id;
                $staffSalaryMast->fiscal_year_id = $effectiveDateFiscalYear->id;

                if (!empty($staffmain->jobtype)) {
                    if (strcasecmp($staffmain->jobtype->jobtype_code, 'Con') == 0 || strcasecmp($staffmain->jobtype->jobtype_code, 'Con1') == 0) {
                        $staffSalaryMast->basic_salary = $previousFiscalYearBasicSalary;
                    } else {
                        $staffSalaryMast->basic_salary = $staffmain->jobposition->basic_salary;
                    }
                }
                $staffSalaryMast->add_salary_amount = $previousFiscalYearAdditionalSalary;
                $staffSalaryMast->total_grade_amount = $previousFiscalYearGrade;
                $staffSalaryMast->add_grade_this_fiscal_year = $additionalGradeThisFiscalYear;
                $staffSalaryMast->salary_effected_date = $effectiveDate;
                $staffSalaryMast->salary_effected_date_np = $effectiveDateNp;
                $staffSalaryMast->salary_payment_status = 'A';
                $staffSalaryMast->created_by = Auth::id();
                $staffSalaryMast->updated_by = Auth::id();
                if ($staffSalaryMast->save()) {
                    $status_mesg = true;
                }

            }

            if ($status_mesg) {
                DB::commit();
            }

        } catch (\Exception $e) {
            $status_mesg = false;
            DB::rollBack();
        }

        $staffs = StafMainMastModel::with(['latestsalary' => function ($query) {
            $query->with('fiscalyear');
        }])->whereIn('id', $staffIds)->get();

        return response()->json([
            'status' => $status_mesg,
            'staffs' => $staffs,
        ]);
    }
}
