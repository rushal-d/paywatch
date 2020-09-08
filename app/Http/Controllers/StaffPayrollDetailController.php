<?php

namespace App\Http\Controllers;

use App\FiscalYearModel;
use App\PayrollConfirm;
use App\PayrollDetailModel;
use App\StafMainMastModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class StaffPayrollDetailController extends Controller
{
    public function index()
    {
        $data['staffs'] = StafMainMastModel::with('payrollBranch')->select('id', 'name_eng', 'main_id', 'branch_id', 'payroll_branch_id')->get();
        $data['title'] = "Staff Wise Payroll Details";
        $data['fiscal_years'] = FiscalYearModel::pluck('fiscal_code', 'id');
        $data['month_names'] = Config::get('constants.month_name');
        return view('staffpayrolldetails.index', $data);
    }

    public function show(Request $request)
    {
        $data['payroll_details'] = PayrollDetailModel::with('fiscalyear')->where('fiscal_year', $request->fy_id)->pluck('id')->toArray();
        $data['payroll_confirms'] = PayrollConfirm::with(['payroll' => function ($query) {
            $query->with('branch');
        }])->whereIn('payroll_id', $data['payroll_details'])->where('staff_central_id', $request->staff_central_id)->get();
        if (empty($data['payroll_confirms'])) {
            return redirect()->back()->with('flash', array('status' => 'danger', 'mesg' => 'No Payroll for given information'));
        }
        $data['staff_detail'] = StafMainMastModel::where('id', $request->staff_central_id)->first();
        $data['fiscal_year'] = FiscalYearModel::where('id', $request->fy_id)->first();
        $data['title'] = 'Staff Payroll';
        $data['i'] = 1;
        $data['month_names'] = Config::get('constants.month_name');
        return view('staffpayrolldetails.show', $data);
    }
}
