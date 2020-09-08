<?php

namespace App\Http\Controllers;

use App\FiscalYearModel;
use App\HouseLoanDiffIncome;
use App\HouseLoanModelMast;
use App\StafMainMastModel;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HouseLoanDiffIncomeController extends Controller
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

        $houseLoans = HouseLoanModelMast::pluck('house_id', 'house_id');
        $fiscalYears = FiscalYearModel::pluck('fiscal_code', 'id');
        $houseLoanDiffIncomes = HouseLoanDiffIncome::with(['houseLoan','houseLoan.staff', 'fiscalYear:id,fiscal_code']);

        if (!empty($request->fiscal_year_id)) {
            $houseLoanDiffIncomes = $houseLoanDiffIncomes->where('fiscal_year_id', $request->fiscal_year_id);
        }

        if (!empty($request->house_loan_id)) {
            $houseLoanDiffIncomes = $houseLoanDiffIncomes->where('house_loan_id', $request->house_loan_id);
        }

        if (!empty($request->diff_income)) {
            $houseLoanDiffIncomes = $houseLoanDiffIncomes->where('diff_income', $request->diff_income);
        }

        $houseLoanDiffIncomes = $houseLoanDiffIncomes->paginate($records_per_page);
        $records_per_page_options = config('constants.records_per_page_options');

        return view('house-loan-diff-income.index', [
            'title' => 'House Loan Diff Income',
            'houseLoans' => $houseLoans,
            'fiscalYears' => $fiscalYears,
            'houseLoanDiffIncomes' => $houseLoanDiffIncomes,
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
        if (empty($request->house_loan_id)) {
            return redirect()->route('house-loan-diff-income-index')->withInput()
                ->withErrors([
                    'House Loan not selected'
                ]);
        }
        $houseLoan = HouseLoanModelMast::where('house_id', $request->house_loan_id)->first();
        if (empty($houseLoan)) {
            return redirect()->route('house-loan-diff-income-index')->withInput()
                ->withErrors([
                    'Selected House Loan not found'
                ]);
        }

        $currentFiscalYearId = FiscalYearModel::isActiveFiscalYear()->value('id');
        $fiscalYears = FiscalYearModel::pluck('fiscal_code', 'id');
        return view('house-loan-diff-income.modify',
            [
                'title' => 'Add House Loan Diff Income',
                'fiscalYears' => $fiscalYears,
                'currentFiscalYearId' => $currentFiscalYearId,
                'houseLoan' => $houseLoan
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
            'house_loan_id' => 'exists:trans_house_loan,house_id',
        ],
            [
                'diff_income.required' => 'You must enter diff amount!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('house-loan-diff-income-modify')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();

                $houseLoanDiffIncome = HouseLoanDiffIncome::where('fiscal_year_id', $request->fiscal_year_id)
                    ->where('house_loan_id', $request->house_loan_id)
                    ->first();

                if (empty($houseLoanDiffIncome)) {
                    $houseLoanDiffIncome = new HouseLoanDiffIncome();
                    $houseLoanDiffIncome->fiscal_year_id = $request->fiscal_year_id;
                    $houseLoanDiffIncome->house_loan_id = $request->house_loan_id;
                    $houseLoanDiffIncome->created_by = auth()->id();
                }

                $houseLoanDiffIncome->updated_by = auth()->id();
                $houseLoanDiffIncome->diff_income = $request->diff_income;
                $houseLoanDiffIncome->save();
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
        return redirect()->route('house-loan-diff-income-index', ['house_loan_id' => $request->house_loan_id])->with('flash', array('status' => $status, 'mesg' => $mesg));

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
            $houseLoanDiffIncome = HouseLoanDiffIncome::find($request->id);
            $houseLoanDiffIncome->deleted_by = auth()->id();
            $houseLoanDiffIncome->save();
            if ($houseLoanDiffIncome->delete()) {
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
                $houseLoanDiffIncomes = HouseLoanDiffIncome::whereIn('id', $ids)->get();
                //start transaction to prevent unsuccessful deletion
                $exception = DB::transaction(function () use ($houseLoanDiffIncomes) {
                    foreach ($houseLoanDiffIncomes as $houseLoanDiffIncome) {
                        $houseLoanDiffIncome->deleted_by = Auth::id();
                        $houseLoanDiffIncome->save();
                        $houseLoanDiffIncome->delete();
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
