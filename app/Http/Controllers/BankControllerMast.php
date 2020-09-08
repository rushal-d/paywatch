<?php

namespace App\Http\Controllers;

use App\BankMastModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Config;
class BankControllerMast extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page') ;
        $search_term = $request->search;
        $banks = BankMastModel::paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('bank.index', [
            'title' => 'Bank',
            'banks' => $banks,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('bank.create',
            [
                'title' => 'Add Bank'
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(),[
            'bank_name' => 'required',
        ],
            [
                'bank_name.required' => 'You must enter the Bank Name!',
            ]
        );

        if($validator->fails()){
            return redirect()->route('bank-create')
                ->withInput()
                ->withErrors($validator);
        }
        else{
            //start transaction to save the data
            try{
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $bank = new BankMastModel();
                $bank->bank_name = $request->bank_name;
                $bank->created_by = Auth::user()->id;
                if($bank->save()){
                    $status_mesg = true;
                }
            }
            catch (Exception $e){
                DB::rollback();
                $status_mesg = false;
            }
        }

        if($status_mesg){
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('bank-create')->with('flash',array('status'=>$status,'mesg' => $mesg));

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        dd("working");
        $bank = BankMastModel::find($id);
        return view('bank.edit', [
            'title' => 'Edit Bank',
            'bank' => $bank
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(),[
            'bank_name' => 'required',
        ],
            [
                'bank_name.required' => 'You must enter Bank Name!',
            ]
        );
        if($validator->fails()){
            return redirect()->route('bank-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        }
        else{
            //start transaction to save the data
            try{
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info

                $bank = BankMastModel::find($id);
                $bank->bank_name = $request->bank_name;
                $bank->updated_by = Auth::id();
                $bank->save();

                if($bank->save()){
                    $status_mesg = true;
                }
            }
            catch (Exception $e){
                DB::rollback();
                $status_mesg = false;
            }
        }

        if($status_mesg){
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('bank-edit', [$id])->with('flash',array('status'=>$status,'mesg' => $mesg));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!empty($request->id)){
            //only soft delete
            $bank = BankMastModel::find($request->id);
            $bank->deleted_by = Auth::id();
            $bank->save();
            if($bank->delete()){
                $success = true;
            }
            if($success){
                echo 'Successfully Deleted';
            }
            else{
                echo "Error deleting!";
            }
        }
        else{
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
        if(!empty($request->ids)){
            $ids = $request->ids;
            //only soft delete
            try{
                //start transaction to prevent unsuccessful deletion
                $exception = DB::transaction(function() use ($ids) {
                    foreach ($ids as $id) {
                        $bank = BankMastModel::find($id);
                        $bank->deleted_by = Auth::id();
                        $bank->save();
                        $bank->delete();
                    }
                });
                $status_mesg = is_null($exception) ? true : $exception;
            }
            catch(Exception $e) {
                $status_mesg = false;
            }
        }
        $mesg = ($status_mesg) ? 'Successfully Deleted' : 'Error deleting!';
        echo $mesg;
    }

}
