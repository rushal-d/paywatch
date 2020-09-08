<?php

namespace App\Http\Controllers;

use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\PayrollDetailModel;
use App\Repositories\DepartmentRepository;
use App\Repositories\StafMainMastRepository;
use App\Repositories\SystemOfficeMastRepository;
use App\StafMainMastModel;
use App\StaffCitDeduction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class StaffCitDeductionController extends Controller
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
        $staffmains = [];

        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : config('constants.records_per_page');

        $staffCitDeductions = StaffCitDeduction::with('staff:id,staff_central_id,main_id,name_eng', 'branch:office_id,office_name', 'fiscalYear:id,fiscal_code', 'payroll:id,payroll_name');

        if (!empty($request->branch_id)) {
            $staffCitDeductions->where('branch_id', $request->branch_id);
        }

        if (!empty($request->staff_central_id)) {
            $staffCitDeductions->where('staff_central_id', $request->fiscal_year_id);
        }

        if (!empty($request->month_id)) {
            $staffCitDeductions->where('month_id', $request->month_id);
        }

        if (!empty($request->payroll_id)) {
            $staffCitDeductions->whereIn('payroll_id', $request->payroll_id);
        }

        if (isset($request->cit_deduction_amount)) {
            $staffCitDeductions->where('cit_deduction_amount', $request->cit_deduction_amount);
        }

        $staffCitDeductions = $staffCitDeductions->latest()->paginate($records_per_page);

        $records_per_page_options = config('constants.records_per_page_options');

        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
        $months = config('constants.month_name');
        $currentNepaliDateMonth = BSDateHelper::getBSYearMonthDayArrayFromEnDate(date('Y-m-d'))['month'];

        return view('staff-cit-deduction.index', [
            'currentNepaliDateMonth' => $currentNepaliDateMonth,
            'months' => $months,
            'title' => 'Staff Cit Deduction',
            'fiscal_years' => $fiscal_years,
            'current_fiscal_year_id' => $current_fiscal_year_id,
            'staffCitDeductions' => $staffCitDeductions,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'branches' => $branches,
            'departments' => $departments,
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
        $fiscalYears = FiscalYearModel::pluck('fiscal_code', 'id');
        $monthNames = config('constants.month_name');
        $currentFiscalYearId = FiscalYearModel::isActiveFiscalYear()->value('id');
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();

        return view('staff-cit-deduction.create',
            [
                'title' => 'Add Staff Cit Deduction',
                'fiscalYears' => $fiscalYears,
                'months' => $monthNames,
                'currentFiscalYearId' => $currentFiscalYearId,
                'branches' => $branches
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
        $fiscalYearId = $request->fiscal_year_id;
        $month_id = $request->month_id;
        $branch_id = $request->branch_id;

        $previousStaffCitDeductionRecord = $request->previousStaffCitDeductionRecord;
        $nonExistingStaffs = $request->nonExistingStaffs;
        $authenticatedId = auth()->id();
        $nowTime = Carbon::now();

        try {
            DB::beginTransaction();
            if (!empty($previousStaffCitDeductionRecord)) {
                $staffCitDeductionCollection = StaffCitDeduction::where('branch_id', $branch_id)
                    ->where('month_id', $month_id)
                    ->where('fiscal_year_id', $fiscalYearId)
                    ->get();

                foreach ($previousStaffCitDeductionRecord as $previousStaffCitId => $staffCitDeductionArray) {
                    $staffCitDeduction = $staffCitDeductionCollection->where('id', $previousStaffCitId)->first();

                    if (empty($staffCitDeduction)) {
                        continue;
                    }

                    $staffCitDeduction->cit_deduction_amount = $staffCitDeductionArray['cit_deduction_amount'] ?? 0;
                    $staffCitDeduction->updated_by = $authenticatedId;
                    $staffCitDeduction->save();
                }
            }

            if (!empty($nonExistingStaffs)) {

                $insertBulkRecord = [];

                foreach ($nonExistingStaffs as $staffId => $citDeductionArray) {
                    $tempInsertBulkRecord['fiscal_year_id'] = $fiscalYearId;
                    $tempInsertBulkRecord['month_id'] = $month_id;
                    $tempInsertBulkRecord['branch_id'] = $branch_id;
                    $tempInsertBulkRecord['staff_central_id'] = $staffId;
                    $tempInsertBulkRecord['cit_deduction_amount'] = $citDeductionArray['cit_deduction_amount'] ?? 0;
                    $tempInsertBulkRecord['created_at'] = $nowTime;
                    $tempInsertBulkRecord['updated_at'] = $nowTime;
                    $tempInsertBulkRecord['created_by'] = $authenticatedId;
                    $tempInsertBulkRecord['updated_by'] = $authenticatedId;
                    $insertBulkRecord[] = $tempInsertBulkRecord;
                }

                StaffCitDeduction::insert($insertBulkRecord);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();

            if (app()->env == 'local') {
                dd($exception);
            } else {
                return redirect()->back()->withInput()->withErrors(['Error Occurred']);
            }
        }
        return redirect()->route('staff-cit-deduction-index')->with('success', 'Created Successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $staffCitDeduction = StaffCitDeduction::find($id);
        if (empty($staffCitDeduction)) {
            abort(404);
        }

        $payrollDetail = PayrollDetailModel::where('confirmed_by', '<>', null)->where('id', $staffCitDeduction->payroll_id)->first();

        if (!empty($payrollDetail)) {
            return redirect()->route('staff-cit-deduction-index')->withErrors('Cannot edit since the payroll is confirmed');
        }

        return view('staff-cit-deduction.edit', [
            'title' => 'Edit Staff Cit Deduction',
            'staffCitDeduction' => $staffCitDeduction
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
            'cit_deduction_amount' => 'required',
        ],
            [
                'cit_deduction_amount.required' => 'You must enter the Staff Cit Deduction Amount!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('staff-cit-deduction-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            try {
                DB::beginTransaction();
                $staffCitDeduction = StaffCitDeduction::find($id);

                $staffCitDeduction->cit_deduction_amount = $request->cit_deduction_amount;
                $staffCitDeduction->updated_by = Auth::user()->id;
                if ($staffCitDeduction->save()) {
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
        return redirect()->route('staff-cit-deduction-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $staffCitDeduction = StaffCitDeduction::find($request->id);

            $payrollDetail = PayrollDetailModel::where('confirmed_by', '<>', null)->where('id', $staffCitDeduction->payroll_id)->first();

            if (!empty($payrollDetail)) {
                return redirect()->route('staff-cit-deduction-index')->withErrors('Cannot edit since the payroll is confirmed');
            }

            $staffCitDeduction->deleted_by = Auth::user()->id;
            $staffCitDeduction->deleted_at = Carbon::now();
            if ($staffCitDeduction->save()) {
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
            $deletedBy = auth()->id();
            $staffCitDeductions = StaffCitDeduction::with('payroll:id,confirmed_by')
                ->whereIn('id', $request->ids)
                ->get();
            //only soft delete
            try {
                //start transaction to prevent unsuccessful deletion
                $exception = DB::transaction(function () use ($staffCitDeductions, $deletedBy) {
                    foreach ($staffCitDeductions as $staffCitDeduction) {
                        if (!empty($staffCitDeduction->payroll->confirmed_by)) {
                            continue;
                        }
                        $staffCitDeduction->deleted_by = $deletedBy;
                        $staffCitDeduction->deleted_at = Carbon::now();
                        $staffCitDeduction->save();
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
