<?php

namespace App\Http\Controllers\Ajax;

use App\FiscalYearModel;
use App\HouseLoanModelMast;
use App\Http\Controllers\Controller;
use App\LoanDeduct;
use App\Repositories\FetchAttendanceRepository;
use App\StaffCitDeduction;
use App\StafMainMastModel;
use App\SystemOfficeMastModel;
use App\VehicalLoanModelTrans;
use Illuminate\Http\Request;

class StaffCitDeductionController extends Controller
{
    public function getLoanDetailsBasedOnLoanTypeId(Request $request)
    {
        $loanTypeId = $request->loan_type_id;

        if (!in_array($loanTypeId, array_keys(config('constants.loan_types')))) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'Loan Type Id not found'
            ]);
        }

        if ($loanTypeId == LoanDeduct::HOUSE_LOAN_TYPE_ID) {
            $houseLoans = HouseLoanModelMast::select('house_id AS loan_id', 'staff_central_id')->get();

            if ($houseLoans->count() < 1) {
                return response()->json([
                    'status' => 'true',
                    'data' => null,
                    'message' => 'No House Loan found'
                ]);
            }

            return response()->json([
                'status' => 'true',
                'data' => $houseLoans,
                'message' => 'House Loan Retrieved Successfully'
            ]);
        }


//        $fetchAttendance = $this->fetchAttendanceRepository->getUserAttendanceByAttendanceDateAndStaffCentralId($attendanceDate, $staff_central_id);

        /*return response()->json([
            'status' => 'true',
            'data' => $fetchAttendance
        ]);*/
    }

    public function filterView(Request $request)
    {
        $monthId = request('month_id');
        $fiscalYearId = request('fiscal_year_id');
        $branch_id = request('branch_id');


        if (!isset($monthId) && !(in_array($monthId, config('constants.month_name_with_dashain_and_tihar')))) {
            return response()->json([
                'status' => false,
                'message' => 'Please select a month'
            ]);
        }

        if (!isset($fiscalYearId)) {
            return response()->json([
                'status' => false,
                'message' => 'Please select a fiscal year'
            ]);
        }

        if (!isset($branch_id)) {
            return response()->json([
                'status' => false,
                'message' => 'Please select a branch'
            ]);
        }

        $branch = SystemOfficeMastModel::where('office_id', $branch_id)->first();

        $monthName = config('constants.month_name_with_dashain_and_tihar')[$monthId];

        $fiscalYearModel = FiscalYearModel::where('id', $fiscalYearId)->first();

        if (empty($fiscalYearModel)) {
            return response()->json([
                'status' => 'false',
                'message' => 'Invalid fiscal year'
            ]);
        }

        $fiscalYear = $fiscalYearModel;

        $staffs = StafMainMastModel::select('id', 'name_eng', 'main_id','branch_id','staff_central_id','default_cit_deduction_amount')
            ->where('branch_id', $branch_id);


        $previousStaffCitDeductions = StaffCitDeduction::with('staff:id,name_eng,main_id,staff_central_id,branch_id', 'branch:office_id,office_name')
            ->whereIn('staff_central_id', $staffs->pluck('id'))
            ->where('branch_id', $branch_id)
            ->where('fiscal_year_id', $fiscalYear->id)
            ->where('month_id', $monthId)
            ->get();

        $nonExistingStaffs = (clone $staffs)
            ->whereDoesntHave('staffCitDeductions', function($query) use($previousStaffCitDeductions){
                $query->whereIn('id', $previousStaffCitDeductions->pluck('id')->toArray());
            })->get();

        $returnHtml = view('staff-cit-deduction.filter-view', [
            'fiscalYear' => $fiscalYear,
            'fiscal_year_id' => $fiscalYearId,
            'branch' => $branch,
            'monthName' => $monthName,
            'month_id' => $monthId,
            'previousStaffCitDeductions' => $previousStaffCitDeductions,
            'nonExistingStaffs' => $nonExistingStaffs
        ])->render();

        return response()->json(['status' => true, 'html' => $returnHtml]);
    }

}
