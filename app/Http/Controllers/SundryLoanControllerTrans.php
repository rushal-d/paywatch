<?php

namespace App\Http\Controllers;

use App\Helpers\BSDateHelper;
use App\StafMainMastModel;
use App\SundryBalance;
use App\SundryTransaction;
use App\SundryTransactionLog;
use App\SundryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Config;

class SundryLoanControllerTrans extends Controller
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
        $sundryloans = SundryBalance::with(['staff'=>function($query){
            $query->with('payrollBranch');
        }])->search($search_term)->latest()->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('sundryloantrans.index', [
            'title' => 'Sundry Loan',
            'sundryloans' => $sundryloans,
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
        $sundry_types = SundryType::pluck('title', 'id');
        $staffs = StafMainMastModel::with(['branch' => function ($query) {
            $query->select('office_id', 'office_name');
        }])->select('id', 'name_eng', 'main_id', 'branch_id','staff_central_id')->take(15)->get();
        return view('sundryloantrans.create',
            [
                'title' => 'Add Sundry Loan',
                'staffs' => $staffs,
                'sundry_types' => $sundry_types
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
            'amount' => 'required',
            'sundry_type' => 'required',
            'no_installment' => 'required',
            'trans_date' => 'required',
        ],
            [
                'amount.required' => 'You must enter Amount!',
                'sundry_type.required' => 'You must select sundry type!',
                'no_installment.required' => 'You must enter no. of installment!',
                'trans_date.required' => 'Sundry Date is required!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('houseloan-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //please check to payroll difference payrollDifferenceSingleConfirm section if any changes in this module

                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $sundry = new SundryTransaction();
                $staff_central_id = $request->staff_central_id;
                $sundry->staff_central_id = $staff_central_id;
                $sundry_type = $request->sundry_type;
                $no_installment = $request->no_installment;
                $installment_amount = $request->installment_amount;
                $amount = $request->amount;
                $is_cr = SundryType::isCR($sundry_type);
                $sundry->transaction_type_id = $sundry_type;
                if ($is_cr) { //cr
                    $sundry->cr_installment = $no_installment;
                    $sundry->cr_amount = $installment_amount; // installment amount
                    $sundry->cr_balance = $amount;
                } else { //dr
                    $sundry->dr_installment = $no_installment;
                    $sundry->dr_amount = $installment_amount; // installment amount
                    $sundry->dr_balance = $amount;
                }
                $sundry->transaction_date = $request->trans_date;
                $sundry->transaction_date_en = BSDateHelper::BsToAd('-', $request->trans_date);
                $sundry->notes = $request->notes;
                $user_id = \Auth::user()->id;
                $sundry->created_by = $user_id;
                if ($sundry->save()) {
                    //record of the transactions
                    $sundry_transaction_log = new SundryTransactionLog();
                    $sundry_transaction_log->sundry_id = $sundry->id;
                    $sundry_transaction_log->staff_central_id = $staff_central_id;
                    $sundry_transaction_log->transaction_date = $request->trans_date;
                    $sundry_transaction_log->transaction_date_en = BSDateHelper::BsToAd('-', $request->trans_date);
                    $sundry_transaction_log->notes = 'Begining Transaction';
                    $is_cr = SundryType::isCR($sundry_type);
                    $sundry_transaction_log->transaction_type_id = $sundry_type;
                    if ($is_cr) { //cr
                        $sundry_transaction_log->cr_installment = $no_installment;
                        $sundry_transaction_log->cr_amount = $installment_amount; // installment amount
                        $sundry_transaction_log->cr_balance = $amount;
                    } else { //dr
                        $sundry_transaction_log->dr_installment = $no_installment;
                        $sundry_transaction_log->dr_amount = $installment_amount; // installment amount
                        $sundry_transaction_log->dr_balance = $amount;
                    }
                    $sundry_transaction_log->save();

                    //also update the main master account of that same employee
                    $sundry_balance = SundryBalance::where('staff_central_id', $staff_central_id)->first();
                    if (!empty($sundry_balance)) { //check is empty that means first transaction
                        if ($is_cr) { //cr
                            $sundry_balance->cr_installment = $sundry_balance->cr_installment + $no_installment;
                            $sundry_balance->cr_amount = $sundry_balance->cr_amount + $installment_amount; // installment amount
                            $sundry_balance->cr_balance = $sundry_balance->cr_balance + $amount;
                        } else { //dr
                            $sundry_balance->dr_installment = $sundry_balance->dr_installment + $no_installment;
                            $sundry_balance->dr_amount = $sundry_balance->dr_amount + $installment_amount; // installment amount
                            $sundry_balance->dr_balance = $sundry_balance->dr_balance + $amount;
                        }
                    } else { //make new master account for the employee
                        $sundry_balance = new SundryBalance();
                        $sundry_balance->dr_installment = $sundry->dr_installment;
                        $sundry_balance->dr_amount = $sundry->dr_amount;
                        $sundry_balance->dr_balance = $sundry->dr_balance;
                        $sundry_balance->cr_installment = $sundry->cr_installment;
                        $sundry_balance->cr_amount = $sundry->cr_amount;
                        $sundry_balance->cr_balance = $sundry->cr_balance;
                    }
                    $sundry_balance->staff_central_id = $sundry->staff_central_id;
                    $sundry_balance->transaction_date = $sundry->transaction_date;
                    $sundry_balance->transaction_date_en = $sundry->transaction_date_en;
                    $sundry_balance->transaction_type_id = $sundry->transaction_type_id;
                    $sundry_balance->notes = $sundry->notes;
                    $sundry_balance->status = 0;
                    $sundry_balance->created_by = $user_id;
                    if ($sundry_balance->save()) {
                        $status_mesg = true;
                    }
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
        return redirect()->route('sundryloan-create')->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $sundryloans = SundryTransactionLog::with('staff','sundryType')->where('sundry_transaction_logs.staff_central_id', $id)->with('staff')->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('sundryloantrans.detail', [
            'title' => 'Sundry Loan',
            'sundryloans' => $sundryloans,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $sundryloan = SundryTransaction::find($id);
        if ($sundryloan->status == 2) {
            $status = 'error';
            $mesg = 'The Sundry Loan has already been paid!';
            return redirect()->route('sundryloan-detail', [$sundryloan->staff_central_id])->with('flash', array('status' => $status, 'mesg' => $mesg));
        }
        $is_cr = SundryType::isCR($sundryloan->transaction_type_id);
        if ($is_cr) {
            $sundryloan->installment_amount = $sundryloan->cr_amount;
            $sundryloan->no_installment = $sundryloan->cr_installment;
            $sundryloan->amount = $sundryloan->cr_balance;
        } else {
            $sundryloan->installment_amount = $sundryloan->dr_amount;
            $sundryloan->no_installment = $sundryloan->dr_installment;
            $sundryloan->amount = $sundryloan->dr_balance;
        }
        $sundry_types = SundryType::pluck('title', 'id');

        if ($sundryloan->status == 1) {
            return view('sundryloantrans.edit_inprogress', [
                'title' => 'Edit Pay in Progress Sundry Loan',
                'sundryloan' => $sundryloan,
                'sundry_types' => $sundry_types
            ]);
        }

        return view('sundryloantrans.edit', [
            'title' => 'Edit Sundry Loan',
            'sundryloan' => $sundryloan,
            'sundry_types' => $sundry_types
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
            'amount' => 'required',
            'sundry_type' => 'required',
            'no_installment' => 'required',
            'trans_date' => 'required',
        ],
            [
                'amount.required' => 'You must enter Amount!',
                'sundry_type.required' => 'You must select sundry type!',
                'no_installment.required' => 'You must enter no. of installment!',
                'trans_date.required' => 'Sundry Date is required!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('sundryloan-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $sundry = SundryTransaction::find($id);
                $was_cr = SundryType::isCR($sundry->transaction_type_id);
                if ($was_cr) {
                    $previous_installment_amount = $sundry->cr_amount;
                    $previous_no_installment = $sundry->cr_installment;
                    $previous_balance = $sundry->cr_balance;
                } else {
                    $previous_installment_amount = $sundry->dr_amount;
                    $previous_no_installment = $sundry->dr_installment;
                    $previous_balance = $sundry->dr_balance;
                }

                $staff_central_id = $request->staff_central_id;
                $sundry->staff_central_id = $staff_central_id;
                $sundry_type = $request->sundry_type;
                $no_installment = $request->no_installment;
                $installment_amount = $request->installment_amount;
                $amount = $request->amount;
                $is_cr = SundryType::isCR($sundry_type);
                $sundry->transaction_type_id = $sundry_type;
                //amounts to be deduccted or added if sundry type changes from dr to cr and vice versa
                $deduct_dr_installment = 0;
                $deduct_dr_amount = 0;
                $deduct_dr_balance = 0;
                $deduct_cr_installment = 0;
                $deduct_cr_amount = 0;
                $deduct_cr_balance = 0;
                $add_dr_installment = 0;
                $add_dr_amount = 0;
                $add_dr_balance = 0;
                $add_cr_installment = 0;
                $add_cr_amount = 0;
                $add_cr_balance = 0;
                if ($is_cr != $was_cr) {
                    //check if the type has been changed
                    if ($is_cr) {
                        //debit amounts to be deducted and credit amount added if sundry type changes from dr to cr
                        $deduct_dr_installment = $sundry->dr_installment;
                        $deduct_dr_amount = $sundry->dr_amount;
                        $deduct_dr_balance = $sundry->dr_balance;
                        $add_cr_installment = $sundry->dr_installment;
                        $add_cr_amount = $sundry->dr_amount;
                        $add_cr_balance = $sundry->dr_balance;
                    } else {
                        //credit amounts to be deducted and debit amount added if sundry type changes from cr to dr
                        $deduct_cr_installment = $sundry->cr_installment;
                        $deduct_cr_amount = $sundry->cr_amount;
                        $deduct_cr_balance = $sundry->cr_balance;
                        $add_dr_installment = $sundry->cr_installment;
                        $add_dr_amount = $sundry->cr_amount;
                        $add_dr_balance = $sundry->cr_balance;
                    }
                    $sundry->cr_installment = 0;
                    $sundry->cr_amount = 0; // installment amount
                    $sundry->cr_balance = 0;
                    $sundry->dr_installment = 0;
                    $sundry->dr_amount = 0; // installment amount
                    $sundry->dr_balance = 0;
                }

                if ($is_cr) { //cr
                    $sundry->cr_installment = $no_installment;
                    $sundry->cr_amount = $installment_amount; // installment amount
                    $sundry->cr_balance = $amount;
                } else { //dr
                    $sundry->dr_installment = $no_installment;
                    $sundry->dr_amount = $installment_amount; // installment amount
                    $sundry->dr_balance = $amount;
                }
                $sundry->transaction_date = $request->trans_date;
                $sundry->transaction_date_en = BSDateHelper::BsToAd('-', $request->trans_date);
                $sundry->notes = $request->notes;
                $user_id = \Auth::user()->id;
                $sundry->created_by = $user_id;
                if ($sundry->save()) {
                    //updating sundry transaction logs
                    $old_sundry_transaction = SundryTransactionLog::where('sundry_id', $sundry->id)->latest()->first();
                    $is_cr = SundryType::isCR($old_sundry_transaction->transaction_type_id);
                    $updated_sundry_transaction = new SundryTransactionLog();

                    $updated_sundry_transaction->sundry_id = $old_sundry_transaction->sundry_id;
                    $updated_sundry_transaction->staff_central_id = $old_sundry_transaction->staff_central_id;
                    $updated_sundry_transaction->transaction_date = BSDateHelper::AdToBs('-', date('Y-m-d'));
                    $updated_sundry_transaction->transaction_date_en = date('Y-m-d');
                    $updated_sundry_transaction->transaction_type_id = $old_sundry_transaction->transaction_type_id;
                    $updated_sundry_transaction->dr_installment = $old_sundry_transaction->cr_installment;
                    $updated_sundry_transaction->dr_amount = $old_sundry_transaction->cr_amount;
                    $updated_sundry_transaction->dr_balance = $old_sundry_transaction->cr_balance;
                    $updated_sundry_transaction->cr_installment = $old_sundry_transaction->dr_installment;
                    $updated_sundry_transaction->cr_amount = $old_sundry_transaction->dr_amount;
                    $updated_sundry_transaction->cr_balance = $old_sundry_transaction->dr_balance;
                    $updated_sundry_transaction->notes = "Reverse Transaction";
                    $updated_sundry_transaction->save();

                    $sundry_transaction_log = new SundryTransactionLog();
                    $sundry_transaction_log->sundry_id = $sundry->id;
                    $sundry_transaction_log->staff_central_id = $staff_central_id;
                    $sundry_transaction_log->transaction_date = $request->trans_date;
                    $sundry_transaction_log->transaction_date_en = BSDateHelper::BsToAd('-', $request->trans_date);
                    $sundry_transaction_log->notes = 'Begining Transaction';
                    $is_cr = SundryType::isCR($sundry_type);
                    $sundry_transaction_log->transaction_type_id = $sundry_type;
                    if ($is_cr) { //cr
                        $sundry_transaction_log->cr_installment = $no_installment;
                        $sundry_transaction_log->cr_amount = $installment_amount; // installment amount
                        $sundry_transaction_log->cr_balance = $amount;
                    } else { //dr
                        $sundry_transaction_log->dr_installment = $no_installment;
                        $sundry_transaction_log->dr_amount = $installment_amount; // installment amount
                        $sundry_transaction_log->dr_balance = $amount;
                    }
                    $sundry_transaction_log->save();

                    //also update the sundry balance table account of that same employee
                    $sundry_balance = SundryBalance::where('staff_central_id', $staff_central_id)->first();
                    if ($is_cr) { //cr
                        $sundry_balance->cr_installment = $sundry_balance->cr_installment - $deduct_cr_installment + $add_cr_installment - $previous_no_installment + $no_installment;
                        $sundry_balance->cr_amount = $sundry_balance->cr_amount - $deduct_cr_amount + $add_cr_amount - $previous_installment_amount + $installment_amount; // installment amount
                        $sundry_balance->cr_balance = $sundry_balance->cr_balance - $deduct_cr_balance + $add_cr_balance - $previous_balance + $amount;
                        $sundry_balance->dr_installment = $sundry_balance->dr_installment - $deduct_dr_installment + $add_dr_installment;
                        $sundry_balance->dr_amount = $sundry_balance->dr_amount - $deduct_dr_amount + $add_dr_amount;
                        $sundry_balance->dr_balance = $sundry_balance->dr_balance - $deduct_dr_balance + $add_dr_balance;

                    } else { //dr
                        $sundry_balance->cr_installment = $sundry_balance->cr_installment - $deduct_cr_installment + $add_cr_installment;
                        $sundry_balance->cr_amount = $sundry_balance->cr_amount - $deduct_cr_amount + $add_cr_amount;
                        $sundry_balance->cr_balance = $sundry_balance->cr_balance - $deduct_cr_balance + $add_cr_balance;
                        $sundry_balance->dr_installment = $sundry_balance->dr_installment - $deduct_dr_installment + $add_dr_installment - $previous_no_installment + $no_installment;
                        $sundry_balance->dr_amount = $sundry_balance->dr_amount - $deduct_dr_amount + $add_dr_amount - $previous_installment_amount + $installment_amount; // installment amount
                        $sundry_balance->dr_balance = $sundry_balance->dr_balance - $deduct_dr_balance + $add_dr_balance - $previous_balance + $amount;
                    }
                    $sundry_balance->staff_central_id = $sundry->staff_central_id;
                    $sundry_balance->transaction_date = $sundry->transaction_date;
                    $sundry_balance->transaction_date_en = $sundry->transaction_date_en;
                    $sundry_balance->transaction_type_id = $sundry->transaction_type_id;
                    $sundry_balance->notes = $sundry->notes;
                    $sundry_balance->status = 0;
                    $sundry_balance->created_by = $user_id;
                    if ($sundry_balance->save()) {
                        $status_mesg = true;
                    }
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
        return redirect()->route('sundryloan-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    public function update_inprogress(Request $request, $id)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'new_amount' => 'required',
        ],
            [
                'new_amount.required' => 'You must enter the Amount!',
            ]
        );

        if ($validator->fails()) {
            return redirect()->route('sundryloan-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            try {
                DB::beginTransaction();
                $sundry = SundryTransaction::find($id);
                $is_cr = SundryType::isCR($sundry->transaction_type_id);
                $staff_central_id = $sundry->staff_central_id;
                $sundry_balance = SundryBalance::where('staff_central_id', $staff_central_id)->first();
                $sundry_log = new SundryTransactionLog();
                if ($is_cr) {
                    $sundry_balance->cr_installment = $sundry_balance->cr_installment - ($sundry->cr_installment - $sundry->dr_installment);
                    $sundry_balance->cr_amount = $sundry_balance->cr_amount - ($sundry->cr_amount - $sundry->dr_amount);
                    $sundry_balance->cr_balance = $sundry_balance->cr_balance - ($sundry->cr_balance - $sundry->dr_balance);
//keeping log record of the changed amount as dr=cr is being made in cr case
                    $sundry_log->dr_installment = $sundry->cr_installment - $sundry->dr_installment;
                    $sundry_log->dr_amount = ($sundry->cr_balance - $sundry->dr_balance) / ($sundry->cr_installment - $sundry->dr_installment);
                    $sundry_log->dr_balance = $sundry->cr_balance - $sundry->dr_balance;
//equating the value i.e dr=cr for the previous edited record
                    $sundry->dr_installment = $sundry->cr_installment;
                    $sundry->dr_amount = $sundry->cr_amount;
                    $sundry->dr_balance = $sundry->cr_balance;

                } else {
                    $sundry_balance->dr_installment = $sundry_balance->dr_installment - ($sundry->dr_installment - $sundry->cr_installment);
                    $sundry_balance->dr_amount = $sundry_balance->dr_amount - ($sundry->dr_amount - $sundry->cr_installment);
                    $sundry_balance->dr_balance = $sundry_balance->dr_balance - ($sundry->dr_balance - $sundry->cr_installment);
//keeping log record of the changed amount as dr=cr is being made in dr case
                    $sundry_log->cr_installment = $sundry->dr_installment - $sundry->cr_installment;
                    $sundry_log->cr_amount = ($sundry->dr_balance - $sundry->cr_balance) / ($sundry->dr_installment - $sundry->cr_installment);
                    $sundry_log->cr_balance = $sundry->dr_balance - $sundry->cr_balance;
//equating the value i.e dr=cr for the previous edited record
                    $sundry->cr_installment = $sundry->dr_installment;
                    $sundry->cr_amount = $sundry->dr_amount;
                    $sundry->cr_balance = $sundry->dr_balance;

                }
                $sundry->status = 2;

                $sundry_log->sundry_id = $sundry->id;
                $sundry_log->staff_central_id = $staff_central_id;
                $sundry_log->transaction_date = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $sundry_log->transaction_date_en = date('Y-m-d');
                $sundry_log->transaction_type_id = $sundry->transaction_type_id;
                if ($is_cr) {
                    $msg = "Debited";
                } else {
                    $msg = "Credited";
                }
                $sundry_log->notes = "System Generated Note:: The Transaction has been reversed/edited As result of reconciliation the remaining balance
                has been" . $msg . "and new entry has been created.";
                $sundry_log->save();

                if ($sundry->save()) {
                    $new_sundry = new SundryTransaction();
                    $staff_central_id = $sundry->staff_central_id;
                    $new_sundry->staff_central_id = $staff_central_id;
                    $sundry_type = $sundry->transaction_type_id;
                    $no_installment = $request->new_no_installment;
                    $installment_amount = $request->new_installment_amount;
                    $amount = $request->new_amount;
                    $is_cr = SundryType::isCR($sundry_type);
                    $new_sundry->transaction_type_id = $sundry_type;
                    if ($is_cr) { //cr
                        $new_sundry->cr_installment = $no_installment;
                        $new_sundry->cr_amount = $installment_amount; // installment amount
                        $new_sundry->cr_balance = $amount;
                    } else { //dr
                        $new_sundry->dr_installment = $no_installment;
                        $new_sundry->dr_amount = $installment_amount; // installment amount
                        $new_sundry->dr_balance = $amount;
                    }
                    $new_sundry->transaction_date = $sundry->transaction_date;
                    $new_sundry->transaction_date_en = BSDateHelper::BsToAd('-', $sundry->transaction_date);
                    $new_sundry->notes = $request->notes;
                    $user_id = \Auth::user()->id;
                    $new_sundry->created_by = $user_id;
                    $new_sundry->created_at = $sundry->created_at;
                    if ($new_sundry->save()) {

                        $sundry_transaction_log = new SundryTransactionLog();
                        $sundry_transaction_log->sundry_id = $sundry->id;
                        $sundry_transaction_log->staff_central_id = $staff_central_id;
                        $sundry_transaction_log->transaction_date = $request->trans_date;
                        $sundry_transaction_log->transaction_date_en = BSDateHelper::BsToAd('-', $request->trans_date);
                        $sundry_transaction_log->notes = 'Begining Transaction';
                        $is_cr = SundryType::isCR($sundry_type);
                        $sundry_transaction_log->transaction_type_id = $sundry_type;
                        if ($is_cr) { //cr
                            $sundry_transaction_log->cr_installment = $no_installment;
                            $sundry_transaction_log->cr_amount = $installment_amount; // installment amount
                            $sundry_transaction_log->cr_balance = $amount;
                        } else { //dr
                            $sundry_transaction_log->dr_installment = $no_installment;
                            $sundry_transaction_log->dr_amount = $installment_amount; // installment amount
                            $sundry_transaction_log->dr_balance = $amount;
                        }
                        $sundry_transaction_log->save();

                        if ($is_cr) { //cr
                            $sundry_balance->cr_installment = $sundry_balance->cr_installment + $no_installment;
                            $sundry_balance->cr_amount = $sundry_balance->cr_amount + $installment_amount; // installment amount
                            $sundry_balance->cr_balance = $sundry_balance->cr_balance + $amount;

                        } else { //dr
                            $sundry_balance->dr_installment = $sundry_balance->dr_installment + $no_installment;
                            $sundry_balance->dr_amount = $sundry_balance->dr_amount + $installment_amount; // installment amount
                            $sundry_balance->dr_balance = $sundry_balance->dr_balance + $amount;
                        }
                        if ($sundry_balance->save()) {
                            $status_mesg = true;
                        }
                    }
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
        return redirect()->route('sundryloan-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

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
            $sundryloan = SundryTransaction::find($request->id);
            if ($sundryloan->status == 2 || $sundryloan->status = 1) {
                $status = 'error';
                $mesg = 'The Sundry Loan has already been paid or is being paid!';
                return redirect()->route('sundryloan-detail', [$sundryloan->staff_central_id])->with('flash', array('status' => $status, 'mesg' => $mesg));
            }
            $is_cr = SundryType::isCR($sundryloan->transaction_type_id);
            if ($is_cr) {
                $deleted_installment_amount = $sundryloan->cr_amount;
                $deleted_no_installment = $sundryloan->cr_installment;
                $deleted_balance = $sundryloan->cr_balance;
            } else {
                $deleted_installment_amount = $sundryloan->dr_amount;
                $deleted_no_installment = $sundryloan->dr_installment;
                $deleted_balance = $sundryloan->dr_balance;
            }
            $staff_central_id = $sundryloan->staff_central_id;
            if ($sundryloan->delete()) {

                $sundry_balance = SundryBalance::where('staff_central_id', $staff_central_id)->first();
                if ($is_cr) { //cr
                    $sundry_balance->cr_installment = $sundry_balance->cr_installment - $deleted_no_installment;
                    $sundry_balance->cr_amount = $sundry_balance->cr_amount - $deleted_installment_amount;  // installment amount
                    $sundry_balance->cr_balance = $sundry_balance->cr_balance - $deleted_balance;
                } else { //dr
                    $sundry_balance->dr_installment = $sundry_balance->dr_installment - $deleted_no_installment;
                    $sundry_balance->dr_amount = $sundry_balance->dr_amount - $deleted_installment_amount; // installment amount
                    $sundry_balance->dr_balance = $sundry_balance->dr_balance - $deleted_balance;
                }
                if ($sundry_balance->save()) {
                    $success = true;
                }
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
                        $sundryloan = SundryTransaction::find($id);
                        if ($sundryloan->status == 2 || $sundryloan->status = 1) {
                            continue;
                        }
                        $sundryloan->delete();
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
