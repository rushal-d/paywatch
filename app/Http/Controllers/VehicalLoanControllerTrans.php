<?php

namespace App\Http\Controllers;

use App\StafMainMastModel;
use App\VehicalLoanModelTrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Config;

class VehicalLoanControllerTrans extends Controller
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
        $vehicalloans = VehicalLoanModelTrans::with(['staff'=>function($query){
            $query->with('payrollBranch');
        }])->search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('vehicalloantrans.index', [
            'title' => 'Vehical Loan',
            'vehicalloans' => $vehicalloans,
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
        $staffs = StafMainMastModel::with('branch')->select('id', 'name_eng','branch_id','staff_central_id','main_id')->take(15)->get();
        return view('vehicalloantrans.create',
            [
                'title' => 'Add Vehical Loan',
                'staffs' => $staffs
            ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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
            return redirect()->route('vehicalloan-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $vehicalloan = new VehicalLoanModelTrans();
                $vehicalloan->staff_central_id = $request->staff_central_id;
                $vehicalloan->trans_date = $request->trans_date;
                $vehicalloan->loan_amount = $request->loan_amount;
                $vehicalloan->no_installment = $request->no_installment;
                $vehicalloan->installment_amount = $request->installment_amount;
                $vehicalloan->autho_id = Auth::id();
                if ($vehicalloan->save()) {
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
        return redirect()->route('vehicalloan-create')->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vehicalloan = VehicalLoanModelTrans::find($id);
        return view('vehicalloantrans.edit', [
            'title' => 'Edit Vehical Loan',
            'vehicalloan' => $vehicalloan
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
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
            return redirect()->route('vehicalloan-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info
                $vehicalloan = VehicalLoanModelTrans::find($id);
                $vehicalloan->staff_central_id = $request->staff_central_id;
                $vehicalloan->trans_date = $request->trans_date;
                $vehicalloan->loan_amount = $request->loan_amount;
                $vehicalloan->no_installment = $request->no_installment;
                $vehicalloan->installment_amount = $request->installment_amount;
                $vehicalloan->autho_id = Auth::id();
                if ($vehicalloan->save()) {
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
        return redirect()->route('vehicalloan-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $vehicaloan = VehicalLoanModelTrans::find($request->id);
            $vehicaloan->deleted_by=Auth::id();
            $vehicaloan->save();
            if ($vehicaloan->delete()) {
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
     * @param  Request $request
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
                        $vehicaloan = VehicalLoanModelTrans::find($id);
                        $vehicaloan->deleted_by=Auth::id();
                        $vehicaloan->save();
                        $vehicaloan->delete();
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

    public function check_vehicle_loan(Request $request)
    {
        $staff_id = $request->id;
        $vehicle_loan = VehicalLoanModelTrans::where('staff_central_id', $staff_id)->exists();
        if ($vehicle_loan) {
            return response()->json('1');
        } else {
            return response()->json('0');
        }
    }

}
