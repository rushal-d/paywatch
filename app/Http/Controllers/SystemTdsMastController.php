<?php

namespace App\Http\Controllers;

use App\FiscalYearModel;
use App\SystemTdsMastModel;
use Illuminate\Http\Request;
use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SystemTdsMastController extends Controller
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
        $tdss = SystemTdsMastModel::with('fiscal')->search($search_term)->orderByDesc('fy')->orderBy('type')->orderBy('slab')->paginate($records_per_page);
        $status_options = Config::get('constants.status_options');
        $tds_options = Config::get('constants.tds_options');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('tds.index', [
            'title' => 'TDS',
            'tdss' => $tdss,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'status_options' => $status_options,
            'tds_options' => $tds_options
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fiscalyear = FiscalYearModel::orderBy('id', 'DESC')->pluck('fiscal_code', 'id');
        $status_options = Config::get('constants.status_options');
        $tds_options = Config::get('constants.tds_options');
        $tds_slabs = Config::get('constants.tds_slabs');
        return view('tds.create',
            [
                'title' => 'Add TDS',
                'fiscalyear' => $fiscalyear,
                'status_options' => $status_options,
                'tds_options' => $tds_options,
                'tds_slabs' => $tds_slabs,
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
            'fy' => 'required',
            'amount' => 'required',
            'percent' => 'required',
            'type' => 'required',
        ],
            [
                'fy.required' => 'You must enter the Description!',
                'type.required' => 'You must select single or couple!',
                'amount.required' => 'You must enter amount!',
                'percent.required' => 'You must enter percent!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('system-tds-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $tds = new SystemTdsMastModel();
                $tds->amount = $request->amount;
                $tds->fy = $request->fy;
                $tds->slab = $request->slab;
                $tds->percent = $request->percent;
                $tds->type = $request->type;
                $tds->status = $request->status;
                $tds->created_by = \Auth::user()->id;
                if ($tds->save()) {
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
        return redirect()->route('system-tds-create')->with('flash', array('status' => $status, 'mesg' => $mesg));

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
        $tds = SystemTdsMastModel::find($id);
        $fiscalyear = FiscalYearModel::orderBy('id', 'DESC')->pluck('fiscal_code', 'id');
        $status_options = Config::get('constants.status_options');
        $tds_options = Config::get('constants.tds_options');
        $tds_slabs = Config::get('constants.tds_slabs');
        return view('tds.edit', [
            'title' => 'Edit TDS',
            'tds' => $tds,
            'fiscalyear' => $fiscalyear,
            'status_options' => $status_options,
            'tds_options' => $tds_options,
            'tds_slabs' => $tds_slabs,
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
            'fy' => 'required',
            'amount' => 'required',
            'percent' => 'required',
            'type' => 'required',
        ],
            [
                'fy.required' => 'You must enter the Description!',
                'type.required' => 'You must select single or couple!',
                'amount.required' => 'You must enter amount!',
                'percent.required' => 'You must enter percent!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('system-tds-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();

                $tds = SystemTdsMastModel::find($id);
                $tds->amount = $request->amount;
                $tds->fy = $request->fy;
                $tds->slab = $request->slab;
                $tds->percent = $request->percent;
                $tds->type = $request->type;
                $tds->status = $request->status;
                $tds->updated_by = \Auth::user()->id;
                if ($tds->save()) {
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
        return redirect()->route('system-tds-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

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
            $tds = SystemTdsMastModel::find($request->id);
            $tds->deleted_by = Auth::id();
            $tds->save();
            if ($tds->delete()) {
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
                        $tds = SystemTdsMastModel::find($id);
                        $tds->deleted_by = Auth::id();
                        $tds->save();
                        $tds->delete();
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
