<?php

namespace App\Http\Controllers;

use App\HouseLoanModelMast;
use App\HouseLoanTransactionLog;
use App\StafMainMastModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Config;

class HouseLoanControllerMast extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $houseloans = HouseLoanModelMast::with(['staff' => function ($query) {
            $query->with('payrollBranch');
        }])->search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('houseloantrans.index', [
            'title' => 'HouseLoan',
            'houseloans' => $houseloans,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $staffs = StafMainMastModel::with(['branch' => function ($query) {
            $query->select('office_id', 'office_name');
        }])->select('id', 'name_eng', 'main_id', 'branch_id', 'staff_central_id')->take(15)->get();
        return view('houseloantrans.create',
            [
                'title' => 'Add Loans',
                'staffs' => $staffs
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
            'loan_amount' => 'required',
        ],
            [
                'loan_amount.required' => 'You must enter loan Amount!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('houseloan-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $houseloan = new HouseLoanModelMast();
                $houseloan->staff_central_id = $request->staff_central_id;
                $houseloan->trans_date = $request->trans_date;
                $houseloan->loan_amount = $request->loan_amount;
                $houseloan->no_installment = $request->no_installment;
                $houseloan->installment_amount = $request->installment_amount ?? ((int)$request->loan_amount / (int)$request->no_installment);;
                $houseloan->autho_id = Auth::id();
                if ($houseloan->save()) {
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
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('houseloan-create')->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['house_loan'] = HouseLoanModelMast::with('staff', 'houseLoanTransaction', 'houseLoanTransaction.payroll')->where('house_id', $id)->first();
        $data['title'] = "House Loan Details";
        $data['i'] = 1;
        return view('houseloantrans.detail', $data);
    }

    public function detailExport($id)
    {
        $data['house_loan'] = HouseLoanModelMast::with('staff', 'houseLoanTransaction', 'houseLoanTransaction.payroll')->where('house_id', $id)->first();
        $data['i'] = 1;
        \Excel::create('House Loan Transaction Detail ' . $data['house_loan']->staff->name_eng ?? '', function ($excel) use ($data) {
            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                $sheet->loadView('houseloantrans.table', $data);
            });
        })->download('xlsx');;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $houseloan = HouseLoanModelMast::find($id);
        return view('houseloantrans.edit', [
            'title' => 'Edit Houseloan',
            'houseloan' => $houseloan
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
            'loan_amount' => 'required',
        ],
            [
                'loan_amount.required' => 'You must enter the Loan Amount!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('houseloan-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info

                $houseloan = HouseLoanModelMast::find($id);
                $houseloan->staff_central_id = $request->staff_central_id;
                $houseloan->trans_date = $request->trans_date;
                $houseloan->loan_amount = $request->loan_amount;
                $houseloan->no_installment = $request->no_installment;
                $houseloan->installment_amount = $request->installment_amount ?? ((int)$request->loan_amount / (int)$request->no_installment);;
                $houseloan->autho_id = Auth::id();

                if ($houseloan->save()) {
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
        return redirect()->route('houseloan-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

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
            $houseloan = HouseLoanModelMast::find($request->id);
            $houseloan->deleted_by = Auth::id();
            $houseloan->save();
            if ($houseloan->delete()) {
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
                        $houseloan = HouseLoanModelMast::find($id);
                        $houseloan->deleted_by = Auth::id();
                        $houseloan->save();
                        $houseloan->delete();
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

    public function check_house_loan(Request $request)
    {
        $staff_id = $request->id;
        $house_loan = HouseLoanModelMast::where('staff_central_id', $staff_id)->exists();
        if ($house_loan) {
            return response()->json('1');
        } else {
            return response()->json('0');
        }
    }

}
