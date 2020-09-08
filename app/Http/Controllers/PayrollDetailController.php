<?php

namespace App\Http\Controllers;

use App\PayrollDetailModel;
use Illuminate\Http\Request;

class PayrollDetailController extends Controller
{
    public function getPayrollName(Request $request)
    {
        $payrolls = new PayrollDetailModel();
        if (!empty($request->fiscal_year_id)) {
            $payrolls = $payrolls->where('fiscal_year', $request->fiscal_year_id);
        }
        if (!empty($request->branch_id)) {
            $payrolls = $payrolls->where('branch_id', $request->branch_id);
        }
        if (!empty($request->confirmed)) {
            $payrolls = $payrolls->whereNotNull('confirmed_by');
        }
        $payrolls = $payrolls->get();
        return response()->json($payrolls);
    }
}
