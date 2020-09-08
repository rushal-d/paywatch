<?php

namespace App\Http\Controllers\Ajax;

use App\HouseLoanDiffIncome;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HouseLoanDiffIncomeController extends Controller
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

        if (empty($request->house_loan_id)) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'No House Loan ID'
            ]);
        }

        $previousHouseLoanDiffIncome = HouseLoanDiffIncome::where('house_loan_id', $request->house_loan_id)
            ->where('fiscal_year_id', $request->fiscal_year_id)
            ->first();

        if (empty($previousHouseLoanDiffIncome)) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'No Previous House Loan'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'diff_income' => $previousHouseLoanDiffIncome->diff_income
            ],
            'message' => 'Retrieved successfully!'
        ]);
        
    }
}
