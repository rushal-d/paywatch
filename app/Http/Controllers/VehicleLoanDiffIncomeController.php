<?php

namespace App\Http\Controllers;

use App\FiscalYearModel;
use App\VehicleLoanDiffIncome;
use App\VehicalLoanModelTrans;
use App\StafMainMastModel;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VehicleLoanDiffIncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : config('constants.records_per_page');

        $vehicleLoans = VehicalLoanModelTrans::pluck('vehical_id', 'vehical_id');
        $fiscalYears = FiscalYearModel::pluck('fiscal_code', 'id');
        $vehicleLoanDiffIncomes = VehicleLoanDiffIncome::with(['vehicleLoan','vehicleLoan.staff', 'fiscalYear:id,fiscal_code']);

        if (!empty($request->fiscal_year_id)) {
            $vehicleLoanDiffIncomes = $vehicleLoanDiffIncomes->where('fiscal_year_id', $request->fiscal_year_id);
        }

        if (!empty($request->vehicle_loan_id)) {
            $vehicleLoanDiffIncomes = $vehicleLoanDiffIncomes->where('vehicle_loan_id', $request->vehicle_loan_id);
        }

        if (!empty($request->diff_income)) {
            $vehicleLoanDiffIncomes = $vehicleLoanDiffIncomes->where('diff_income', $request->diff_income);
        }

        $vehicleLoanDiffIncomes = $vehicleLoanDiffIncomes->paginate($records_per_page);
        $records_per_page_options = config('constants.records_per_page_options');

        return view('vehicle-loan-diff-income.index', [
            'title' => 'Vehicle Loan Diff Income',
            'vehicleLoans' => $vehicleLoans,
            'fiscalYears' => $fiscalYears,
            'vehicleLoanDiffIncomes' => $vehicleLoanDiffIncomes,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function modify(Request $request)
    {
        if (empty($request->vehicle_loan_id)) {
            return redirect()->route('vehicle-loan-diff-income-index')->withInput()
                ->withErrors([
                    'Vehicle Loan not selected'
                ]);
        }
        $vehicleLoan = VehicalLoanModelTrans::where('vehical_id', $request->vehicle_loan_id)->first();
        if (empty($vehicleLoan)) {
            return redirect()->route('vehicle-loan-diff-income-index')->withInput()
                ->withErrors([
                    'Selected Vehicle Loan not found'
                ]);
        }

        $currentFiscalYearId = FiscalYearModel::isActiveFiscalYear()->value('id');
        $fiscalYears = FiscalYearModel::pluck('fiscal_code', 'id');
        return view('vehicle-loan-diff-income.modify',
            [
                'title' => 'Add Vehicle Loan Diff Income',
                'fiscalYears' => $fiscalYears,
                'currentFiscalYearId' => $currentFiscalYearId,
                'vehicleLoan' => $vehicleLoan
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
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'diff_income' => 'required',
            'fiscal_year_id' => 'exists:fiscal_year,id',
            'vehicle_loan_id' => 'exists:trans_vehical_loan,vehical_id',
        ],
            [
                'diff_income.required' => 'You must enter diff amount!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('vehicle-loan-diff-income-modify')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();

                $vehicleLoanDiffIncome = VehicleLoanDiffIncome::where('fiscal_year_id', $request->fiscal_year_id)
                    ->where('vehicle_loan_id', $request->vehicle_loan_id)
                    ->first();

                if (empty($vehicleLoanDiffIncome)) {
                    $vehicleLoanDiffIncome = new VehicleLoanDiffIncome();
                    $vehicleLoanDiffIncome->fiscal_year_id = $request->fiscal_year_id;
                    $vehicleLoanDiffIncome->vehicle_loan_id = $request->vehicle_loan_id;
                    $vehicleLoanDiffIncome->created_by = auth()->id();
                }

                $vehicleLoanDiffIncome->updated_by = auth()->id();
                $vehicleLoanDiffIncome->diff_income = $request->diff_income;
                $vehicleLoanDiffIncome->save();
                $status_mesg = true;
            } catch (Exception $e) {
                DB::rollback();
                $status_mesg = false;
            }
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Modified Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('vehicle-loan-diff-income-index', ['vehicle_loan_id' => $request->vehicle_loan_id])->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $vehicleLoanDiffIncome = VehicleLoanDiffIncome::find($request->id);
            $vehicleLoanDiffIncome->deleted_by = auth()->id();
            $vehicleLoanDiffIncome->save();
            if ($vehicleLoanDiffIncome->delete()) {
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
                $vehicleLoanDiffIncomes = VehicleLoanDiffIncome::whereIn('id', $ids)->get();
                //start transaction to prevent unsuccessful deletion
                $exception = DB::transaction(function () use ($vehicleLoanDiffIncomes) {
                    foreach ($vehicleLoanDiffIncomes as $vehicleLoanDiffIncome) {
                        $vehicleLoanDiffIncome->deleted_by = Auth::id();
                        $vehicleLoanDiffIncome->save();
                        $vehicleLoanDiffIncome->delete();
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
