<?php

namespace App\Http\Controllers;

use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\PayrollDetailModel;
use App\Repositories\DepartmentRepository;
use App\Repositories\StafMainMastRepository;
use App\Repositories\SystemOfficeMastRepository;
use App\StaffType;
use App\StafMainMastModel;
use App\LoanDeduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class LoanDeductController extends Controller
{
    private $departmentRepository;
    private $systemOfficeMastRepository;
    private $stafMainMastRepository;

    public function __construct(DepartmentRepository $departmentRepository,
                                SystemOfficeMastRepository $systemOfficeMastRepository,
                                StafMainMastRepository $stafMainMastRepository)
    {
        $this->systemOfficeMastRepository = $systemOfficeMastRepository;
        $this->stafMainMastRepository = $stafMainMastRepository;
        $this->departmentRepository = $departmentRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $departments = $this->departmentRepository->getAllDepartments();
        $staff_types = StaffType::pluck('staff_type_title', 'staff_type_code');


        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : config('constants.records_per_page');

        $branch_id = $staff_central_id = $department_id = null;

        $loanDeducts = LoanDeduct::select('staff_main_mast.id AS staff_main_mast_id', 'staff_main_mast.payroll_branch_id', 'loan_deducts.*')->with('fiscalYear', 'staff', 'staff.branch')->join('staff_main_mast', 'staff_main_mast.id', '=', 'loan_deducts.staff_central_id');
        if (!empty($request->branch_id)) {
            $branch_id = $request->branch_id;
            $loanDeducts->where('staff_main_mast.payroll_branch_id', $branch_id);
        }

        if (!empty($request->staff_central_id)) {
            $staff_central_id = $request->staff_central_id;
            $loanDeducts->where('loan_deducts.staff_central_id', $staff_central_id);
        }

        if (!empty($request->department_id)) {
            $department_id = $request->department_id;
            $loanDeducts->where('staff_main_mast.department', $department_id);
        }

        if (!empty($request->staff_type)) {
            $staff_type = $request->staff_type;
            $loanDeducts->whereIn('staff_main_mast.staff_type', $staff_type);
        }

        /*$loanDeducts = LoanDeduct::with(['staff', 'staff.branch'])->whereHas('staff', function ($query) use ($branch_id, $staff_central_id, $department_id) {
            if (!empty($branch_id)) {
                $query->where('payroll_branch_id', $branch_id);
            }
            if (!empty($staff_central_id)) {
                $query->where('id', $staff_central_id);
            }

            if (!empty($department_id)) {
                $query->where('department_id', $department_id);
            }

        });*/


//        dd($loanDeducts->count());

        if (!empty($request->loan_type_id)) {
            $loanDeducts = $loanDeducts->where('loan_type', $request->loan_type_id);
        }

        if (!empty($request->fiscal_year_id)) {
            $loanDeducts = $loanDeducts->where('fiscal_year_id', $request->fiscal_year_id);
        }

        if (!empty($request->month_id)) {
            $loanDeducts = $loanDeducts->where('month_id', $request->month_id);
        }
        $loanDeducts = $loanDeducts->latest()->paginate($records_per_page);

        $loanTypes = config('constants.loan_types');
        $records_per_page_options = config('constants.records_per_page_options');

        $staffmains = [];

        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
        $months = Config::get('constants.month_name_with_dashain_and_tihar');
        $currentNepaliDateMonth = BSDateHelper::getBSYearMonthDayArrayFromEnDate(date('Y-m-d'))['month'];

        return view('loan-deduct.index', [
            'currentNepaliDateMonth' => $currentNepaliDateMonth,
            'months' => $months,
            'title' => 'Loan Deduct',
            'fiscal_years' => $fiscal_years,
            'current_fiscal_year_id' => $current_fiscal_year_id,
            'loanDeducts' => $loanDeducts,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'loanTypes' => $loanTypes,
            'branches' => $branches,
            'departments' => $departments,
            'staff_types' => $staff_types,
            'staffmains' => $staffmains,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loanTypes = config('constants.loan_types');
        $fiscalYears = FiscalYearModel::pluck('fiscal_code', 'id');
        $monthNames = config('constants.month_name_with_dashain_and_tihar');
        $currentFiscalYearId = FiscalYearModel::isActiveFiscalYear()->value('id');
        return view('loan-deduct.create',
            [
                'title' => 'Add Loan Deduct',
                'loanTypes' => $loanTypes,
                'fiscalYears' => $fiscalYears,
                'months' => $monthNames,
                'currentFiscalYearId' => $currentFiscalYearId
            ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $existingHouseLoanDeducts = request('existingHouseLoanDeducts');
        $nonExistingHouseLoanDeducts = request('nonExistingHouseLoanDeducts');
        $existingVehicleLoanDeducts = request('existingVehicleLoanDeducts');
        $nonExistingVehicleLoanDeducts = request('nonExistingVehicleLoanDeducts');
        $fiscal_year_id = request('fiscal_year_id');
        $month_id = request('month_id');
        /*$payrollDetail = PayrollDetailModel::where('fiscal_year', $fiscal_year_id)->where('salary_month', $month_id)->where('confirmed_by', '<>', null)->first();
        if (!empty($payrollDetail)) {
            return redirect()->route('loan-deduct-show', ['fiscal_year_id' => $payrollDetail, 'month_id' => $payrollDetail->salary_month]);
        }*/
        try {
            DB::beginTransaction();

            if (!empty($existingHouseLoanDeducts) && count($existingHouseLoanDeducts) > 0) {
                foreach ($existingHouseLoanDeducts as $loanDeductId => $existingHouseLoanDeduct) {
                    $loanDeduct = LoanDeduct::houseLoansType()->where('id', $loanDeductId)->first();

                    if (empty($loanDeduct)) {
                        $loanDeduct = new LoanDeduct();
                        $loanDeduct->loan_type = LoanDeduct::HOUSE_LOAN_TYPE_ID;
                        $loanDeduct->loan_id = $existingHouseLoanDeduct['loan_id'];
                        $loanDeduct->created_by = Auth::user()->id;
                    } else {
                        $loanDeduct->updated_by = Auth::user()->id;
                    }

                    $loanDeduct->fiscal_year_id = $fiscal_year_id;
                    $loanDeduct->month_id = $month_id;
                    $loanDeduct->loan_deduct_amount = $existingHouseLoanDeduct['loan_deduct_amount'];
                    $loanDeduct->staff_central_id = $existingHouseLoanDeduct['staff_central_id'];
                    if (isset($existingHouseLoanDeduct['remarks']) && $existingHouseLoanDeduct['remarks'] != '' && $existingHouseLoanDeduct['remarks'] != null) {
                        $loanDeduct->remarks .= $existingHouseLoanDeduct['remarks'] . '<br>';
                    }
                    $loanDeduct->save();
                }
            }

            if (!empty($nonExistingHouseLoanDeducts) && count($nonExistingHouseLoanDeducts) > 0) {
                foreach ($nonExistingHouseLoanDeducts as $houseId => $nonExistingHouseLoanDeduct) {

                    $loanDeduct = new LoanDeduct();
                    $loanDeduct->loan_type = LoanDeduct::HOUSE_LOAN_TYPE_ID;
                    $loanDeduct->loan_id = $houseId;
                    $loanDeduct->created_by = Auth::user()->id;

                    $loanDeduct->fiscal_year_id = $fiscal_year_id;
                    $loanDeduct->month_id = $month_id;
                    $loanDeduct->loan_deduct_amount = $nonExistingHouseLoanDeduct['loan_deduct_amount'];
                    $loanDeduct->staff_central_id = $nonExistingHouseLoanDeduct['staff_central_id'];
                    if (isset($nonExistingHouseLoanDeduct['remarks']) && $nonExistingHouseLoanDeduct['remarks'] != '' && $nonExistingHouseLoanDeduct['remarks'] != null) {

                        $loanDeduct->remarks .= $nonExistingHouseLoanDeduct['remarks'] . '<br>';
                    }
                    $loanDeduct->save();
                }
            }
            //End of house

            if (!empty($existingVehicleLoanDeducts) && count($existingVehicleLoanDeducts) > 0) {
                foreach ($existingVehicleLoanDeducts as $loanDeductId => $existingVehicleLoanDeduct) {
                    $loanDeduct = LoanDeduct::vehicleLoansType()->where('id', $loanDeductId)->first();

                    if (empty($loanDeduct)) {
                        $loanDeduct = new LoanDeduct();
                        $loanDeduct->loan_type = LoanDeduct::VEHICLE_LOAN_TYPE_ID;
                        $loanDeduct->loan_id = $existingVehicleLoanDeduct['loan_id'];
                        $loanDeduct->created_by = Auth::user()->id;
                    } else {
                        $loanDeduct->updated_by = Auth::user()->id;
                    }

                    $loanDeduct->fiscal_year_id = $fiscal_year_id;
                    $loanDeduct->month_id = $month_id;
                    $loanDeduct->loan_deduct_amount = $existingVehicleLoanDeduct['loan_deduct_amount'];
                    $loanDeduct->staff_central_id = $existingVehicleLoanDeduct['staff_central_id'];
                    if (isset($existingVehicleLoanDeduct['remarks']) && $existingVehicleLoanDeduct['remarks'] != '' && $existingVehicleLoanDeduct['remarks'] != null) {

                        $loanDeduct->remarks .= $existingVehicleLoanDeduct['remarks'] . '<br>';
                    }
                    $loanDeduct->save();
                }
            }

            if (!empty($nonExistingVehicleLoanDeducts) && count($nonExistingVehicleLoanDeducts) > 0) {
                foreach ($nonExistingVehicleLoanDeducts as $vehicleId => $nonExistingVehicleLoanDeduct) {

                    $loanDeduct = new LoanDeduct();
                    $loanDeduct->loan_type = LoanDeduct::VEHICLE_LOAN_TYPE_ID;
                    $loanDeduct->loan_id = $vehicleId;
                    $loanDeduct->created_by = Auth::user()->id;
                    $loanDeduct->fiscal_year_id = $fiscal_year_id;
                    $loanDeduct->month_id = $month_id;
                    $loanDeduct->loan_deduct_amount = $nonExistingVehicleLoanDeduct['loan_deduct_amount'];
                    $loanDeduct->staff_central_id = $nonExistingVehicleLoanDeduct['staff_central_id'];
                    if (isset($nonExistingVehicleLoanDeduct['remarks']) && $nonExistingVehicleLoanDeduct['remarks'] != '' && $nonExistingVehicleLoanDeduct['remarks'] != null) {
                        $loanDeduct->remarks .= $nonExistingVehicleLoanDeduct['remarks'] . '<br>';
                    }
                    $loanDeduct->save();
                }
            }
            DB::commit();
            $saveStatus = true;
        } catch (\Exception $exception) {
            $saveStatus = false;

            DB::rollback();
            if (app()->env == 'local') {
                dd($exception);
            } else {
                return redirect()->back()->withInput()->withErrors(['Error Occurred']);
            }
        }
        $status = ($saveStatus) ? 'success' : 'error';
        $mesg = ($saveStatus) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('loan-deduct-index')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $loanDeducts = new LoanDeduct();
        $payrollDetail = new PayrollDetailModel();
        $month = null;
        $fiscal_year = null;
        if (!empty($request->fiscal_year_id)) {
            $loanDeducts = $loanDeducts->where('fiscal_year_id', $request->fiscal_year_id);
            $payrollDetail = $payrollDetail->where('fiscal_year', $request->fiscal_year_id);
            $fiscal_year = FiscalYearModel::find($request->fiscal_year_id);
        }
        if (!empty($request->loan_type)) {
            $loanDeducts = $loanDeducts->where('loan_type', $request->loan_type);
        }

        if (!empty($request->staff_central_id)) {
            $loanDeducts = $loanDeducts->where('staff_central_id', $request->staff_central_id);
        }
        if (!empty($request->month_id)) {
            $loanDeducts = $loanDeducts->where('month_id', $request->month_id);
            $payrollDetail = $payrollDetail->where('salary_month', $request->month_id);
            $month = Config::get('constants.month_name_with_dashain_and_tihar')[$request->month_id];
        }
        $payrollDetail = $payrollDetail->where('confirmed_by', '<>', null)->first();
        $loanDeducts = $loanDeducts->with('staff')->get();
        $loan_types = Config::get('constants.loan_types');
        $title = 'Loan Deduction Detail';
        return view('loan-deduct.detail', [
            'title' => $title,
            'month' => $month,
            'fiscal_year' => $fiscal_year,
            'loanDeducts' => $loanDeducts,
            'loan_types' => $loan_types,
            'payrollDetail' => $payrollDetail,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loanDeduct = LoanDeduct::find($id);
        if (empty($loanDeduct)) {
            abort(404);
        }

        $fiscalYear = FiscalYearModel::where('id', $loanDeduct->fiscal_year_id)->first();

        $staff = StafMainMastModel::with('payrollBranch')->where('id', $loanDeduct->staff_central_id)->first();
        $payrollDetail = PayrollDetailModel::where('fiscal_year', $loanDeduct->fiscal_year_id)->where('salary_month', $loanDeduct->month_id)->where('confirmed_by', '<>', null)->first();
        if (!empty($payrollDetail)) {
            return redirect()->route('loan-deduct-show', ['fiscal_year_id' => $payrollDetail, 'month_id' => $payrollDetail->salary_month, 'loan_type' => $loanDeduct->loan_type, 'staff_central_id' => $loanDeduct->staff_central_id]);
        }
        return view('loan-deduct.edit', [
            'title' => 'Edit Loan Deduct',
            'fiscalYear' => $fiscalYear,
            'month_name' => config('constants.month_name_with_dashain_and_tihar')[$loanDeduct->month_id],
            'loanDeduct' => $loanDeduct,
            'staff' => $staff
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'loan_deduct_amount' => 'required',
        ],
            [
                'loan_deduct_amount.required' => 'You must enter the Loan Deduct Amount!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('loan-deduct-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info
                $loanDeduct = LoanDeduct::find($id);

                if (isset($request->remarks) && $request->remarks != '' && $request->remarks != null) {
                    $loanDeduct->remarks .= $request->remarks . '<br>';
                }
                $loanDeduct->loan_deduct_amount = $request->loan_deduct_amount;
                $loanDeduct->updated_by = Auth::user()->id;
                if ($loanDeduct->save()) {
                    $status_mesg = true;
                }
            } catch (Exception $e) {
                DB::rollback();
                $status_mesg = false;
            }
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('loan-deduct-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $loanDeduct = LoanDeduct::find($request->id);
            $loanDeduct->deleted_by = Auth::user()->id;
            $loanDeduct->save();
            if ($loanDeduct->delete()) {
                $success = true;
            }
            if ($success) {
                echo 'Successfully Deleted';
            } else {
                echo "Error deleting!";
            }
        } else {
            echo "Error deleting!";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroySelected(Request $request)
    {
        $status_mesg = false;
        if (!empty($request->ids)) {
            $ids = $request->ids;
            //only soft delete
            try {
                //start transaction to prevent unsuccessful deletion
                $exception = DB::transaction(function () use ($ids) {
                    foreach ($ids as $id) {
                        $loanDeduct = LoanDeduct::find($id);
                        $loanDeduct->deleted_by = Auth::user()->id;
                        $loanDeduct->save();
                        $loanDeduct->delete();
                    }
                });
                $status_mesg = is_null($exception) ? true : $exception;
            } catch (Exception $e) {
                $status_mesg = false;
            }
        }
        $mesg = ($status_mesg) ? 'Successfully Deleted' : 'Error deleting!';
        echo $mesg;
    }

}
