<?php

namespace App\Http\Controllers\Ajax;

use App\FiscalYearModel;
use App\HouseLoanModelMast;
use App\Http\Controllers\Controller;
use App\LoanDeduct;
use App\Repositories\FetchAttendanceRepository;
use App\VehicalLoanModelTrans;
use Illuminate\Http\Request;

class LoanDeductController extends Controller
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


        if (!isset($monthId) && !(in_array($monthId, config('constants.month_name_with_dashain_and_tihar')))) {
            return response()->json([
                'status' => 'false',
                'message' => 'Please select a month'
            ]);
        }

        if (!isset($fiscalYearId)) {
            return response()->json([
                'status' => 'false',
                'message' => 'Please select a fiscal year'
            ]);
        }

        $monthName = config('constants.month_name_with_dashain_and_tihar')[$monthId];

        $fiscalYearModel = FiscalYearModel::where('id', $fiscalYearId)->first();

        if (empty($fiscalYearModel)) {
            return response()->json([
                'status' => 'false',
                'message' => 'Invalid fiscal year'
            ]);
        }

        $fiscalYear = $fiscalYearModel->fiscal_code;

        $houseLoans = HouseLoanModelMast::with('staff')->whereIn('account_status',[0,1])->get();
        $vehicleLoans = VehicalLoanModelTrans::with('staff')->whereIn('account_status',[0,1])->get();

        if ($houseLoans->count() < 1 && $vehicleLoans->count() < 1) {
            return response()->json([
                'status' => 'false',
                'message' => 'No House Loans and Vehicle Loans'
            ]);
        }

        $previousHouseLoanDeductRecords = LoanDeduct::with('houseLoan.staff', 'houseLoan.staff.branch')->houseLoansType()->where('fiscal_year_id', $fiscalYearId)->where('month_id', $monthId)->whereNotNull('loan_id')->get();
        $previousVehicleLoanDeductRecords = LoanDeduct::with('vehicleLoan.staff', 'houseLoan.staff.branch')->vehicleLoansType()->where('fiscal_year_id', $fiscalYearId)->where('month_id', $monthId)->whereNotNull('loan_id')->get();

        $existingHouseLoans = $previousHouseLoanDeductRecords->pluck('loan_id');
        $existingVehicleLoans = $previousVehicleLoanDeductRecords->pluck('loan_id');

        $nonExistingHouseLoanDeductRecords = HouseLoanModelMast::with('staff', 'staff.branch')->whereHas('staff')->whereNotIn('house_id', $existingHouseLoans)->whereIn('account_status',[0,1])->get();

        $nonExistingVehicleLoanDeductRecords = VehicalLoanModelTrans::with('staff', 'staff.branch')->whereHas('staff')->whereNotIn('vehical_id', $existingVehicleLoans)->whereIn('account_status',[0,1])->get();

        $returnHtml = view('loan-deduct.filter-view', [
            'fiscalYear' => $fiscalYear,
            'fiscal_year_id' => $fiscalYearId,
            'monthName' => $monthName,
            'month_id' => $monthId,
            'houseLoans' => $houseLoans,
            'vehicleLoans' => $vehicleLoans,
            'nonExistingHouseLoanDeductRecords' => $nonExistingHouseLoanDeductRecords,
            'previousHouseLoanDeductRecords' => $previousHouseLoanDeductRecords,
            'previousVehicleLoanDeductRecords' => $previousVehicleLoanDeductRecords,
            'nonExistingVehicleLoanDeductRecords' => $nonExistingVehicleLoanDeductRecords,
        ])->render();

        return response()->json(['status' => 'true', 'html' => $returnHtml]);
    }

}
