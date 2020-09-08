<?php

namespace App\Http\Controllers;

use App\AllowanceModelMast;
use App\Helpers\BSDateHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Config;

class AllowanceControllerMast extends Controller
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
        $allowances = AllowanceModelMast::search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $allowance_options = Config::get('constants.allowance_options');
        return view('allowancemast.index', [
            'title' => 'Allowance',
            'allowances' => $allowances,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'allowance_options' => $allowance_options
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allowance_types = Config::get('constants.allowance_types');
        return view('allowancemast.create',
            [
                'title' => 'Add Allowance',
                'allowance_types' => $allowance_types
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
            'allow_title' => 'required',
            'allow_code' => 'required|unique:system_allwance_mast',
        ],
            [
                'allow_title.required' => 'You must enter the title!',
                'allow_code' => 'Allowance Code Must be Unique!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('allowance-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $allowance = new AllowanceModelMast();
                $allowance->allow_title = $request->allow_title;
                $allowance->allow_amt = $request->allow_amt;
                $allowance->effect_date_np = $request->effect_date_np;
                $allowance->effect_date = $request->effect_date;
                $allowance->allow_code = $request->allow_code;
                $allowance->allowance_type = $request->allowance_type;
                $allowance->include_in_payroll = !empty($request->include_in_payroll) ? 1 : 0;
                $allowance->show_on_form = !empty($request->show_on_form) ? 1 : 0;
                $allowance->status_id = $request->status_id;
                $allowance->created_by = Auth::user()->id;
                if ($allowance->save()) {
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
        return redirect()->route('allowance-create')->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $allowance_types = Config::get('constants.allowance_types');
        $allowance = AllowanceModelMast::find($id);
        return view('allowancemast.edit', [
            'allowance_types' => $allowance_types,
            'title' => 'Edit Allowance',
            'allowance' => $allowance
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
            'allow_amt' => 'required',
            'allow_code' => 'required|unique:system_allwance_mast,allow_code,' . $id . ',allow_id',
        ],
            [
                'allow_amt.required' => 'You must enter the title!',
                'allow_code' => 'Allowance Code Must be Unique!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('allowance-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info

                $allowance = AllowanceModelMast::find($id);
                $allowance->allow_title = $request->allow_title;
                $allowance->allow_amt = $request->allow_amt;
                $allowance->effect_date_np = $request->effect_date_np;
                $allowance->effect_date = BSDateHelper::BsToAd('-', $request->effect_date_np);
                $allowance->allow_code = $request->allow_code;
                $allowance->allowance_type = $request->allowance_type;
                $allowance->include_in_payroll = !empty($request->include_in_payroll) ? 1 : 0;
                $allowance->show_on_form = !empty($request->show_on_form) ? 1 : 0;
                $allowance->status_id = $request->status_id;
                $allowance->updated_by = Auth::id();
                if ($allowance->save()) {
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
        return redirect()->route('allowance-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

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
            $allowance = AllowanceModelMast::find($request->id);
            $allowance->deleted_by = Auth::id();
            $allowance->save();
            if ($allowance->delete()) {
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
                        $allowance = AllowanceModelMast::find($id);
                        $allowance->deleted_by = Auth::id();
                        $allowance->save();
                        $allowance->delete();
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
