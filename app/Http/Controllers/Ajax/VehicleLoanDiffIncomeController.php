<?php

namespace App\Http\Controllers\Ajax;

use App\VehicleLoanDiffIncome;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VehicleLoanDiffIncomeController extends Controller
{
    public function getPreviousByFilter(Request $request)
    {
        if (empty($request->fiscal_year_id)) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'No Fiscal Year'
            ]);
        }

        if (empty($request->vehicle_loan_id)) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'No Vehicle Loan ID'
            ]);
        }

        $previousVehicleLoanDiffIncome = VehicleLoanDiffIncome::where('vehicle_loan_id', $request->vehicle_loan_id)
            ->where('fiscal_year_id', $request->fiscal_year_id)
            ->first();

        if (empty($previousVehicleLoanDiffIncome)) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'No Previous Vehicle Loan'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'diff_income' => $previousVehicleLoanDiffIncome->diff_income
            ],
            'message' => 'Retrieved successfully!'
        ]);
        
    }
}
