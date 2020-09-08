<?php

namespace App\Http\Controllers;

use App\FiscalYearAttendanceSum;
use App\FiscalYearModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Config;

class FiscalYearController extends Controller
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
        $fiscalyears = FiscalYearModel::search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('fiscalyear.index', [
            'title' => 'Fiscal Year',
            'fiscalyears' => $fiscalyears,
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
        $status_options = Config::get('constants.status_options');
        return view('fiscalyear.create',
            [
                'title' => 'Add Fiscal Year',
                'status_options' => $status_options
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
            'fiscal_start_date_np' => 'required',
            'fiscal_code' => 'required|unique:fiscal_year',
        ],
            [
                'fiscal_start_date.required' => 'You must enter the title!',
                'fiscal_code.required' => 'You must enter Unique Code!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('fiscal-year-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $fiscal = new FiscalYearModel();
                $fiscal->fiscal_start_date_np = $request->fiscal_start_date_np;
                $fiscal->fiscal_end_date_np = $request->fiscal_end_date_np;
                $fiscal->fiscal_start_date = $request->fiscal_start_date;
                $fiscal->fiscal_end_date = $request->fiscal_end_date;
                $fiscal->fiscal_code = $request->fiscal_code;
                $fiscal->present_days = $request->present_days;
                $fiscal->fiscal_status = $request->fiscal_status;
                $fiscal->created_by = Auth::id();
                if ($request->fiscal_status == 1) {
                    $active_fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
                    if (!empty($active_fiscal_year)) {
                        $active_fiscal_year->fiscal_status = 0;
                        $active_fiscal_year->save();
                    }

                }
                if ($fiscal->save()) {
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
        return redirect()->route('fiscal-year-create')->with('flash', array('status' => $status, 'mesg' => $mesg));

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
        $status_options = Config::get('constants.status_options');
        $fiscalyear = FiscalYearModel::find($id);
        return view('fiscalyear.edit', [
            'title' => 'Edit Fiscal Year',
            'fiscalyear' => $fiscalyear,
            'status_options' => $status_options
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
            'fiscal_start_date_np' => 'required',
            'fiscal_code' => 'required:fiscal_year',
        ],
            [
                'fiscal_start_date.required' => 'You must enter the title!',
                'fiscal_code.required' => 'You must enter Unique Code!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('fiscal-year-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info

                $fiscal = FiscalYearModel::find($id);
                $fiscal->fiscal_start_date_np = $request->fiscal_start_date_np;
                $fiscal->fiscal_end_date_np = $request->fiscal_end_date_np;
                $fiscal->fiscal_start_date = $request->fiscal_start_date;
                $fiscal->fiscal_end_date = $request->fiscal_end_date;
                $fiscal->fiscal_code = $request->fiscal_code;
                $fiscal->present_days = $request->present_days;
                $fiscal->fiscal_status = $request->fiscal_status;
                $fiscal->updated_by = Auth::id();
                if ($request->fiscal_status == 1) {
                    $active_fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
                    if (!empty($active_fiscal_year)) {
                        $active_fiscal_year->fiscal_status = 0;
                        $active_fiscal_year->save();
                    }
                }

                if ($fiscal->save()) {
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
        return redirect()->route('fiscal-year-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

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
            $fiscalyear = FiscalYearModel::find($request->id);
            $fiscalyear->deleted_by = Auth::id();
            $fiscalyear->save();
            if ($fiscalyear->delete()) {
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
                        $fiscalyear = FiscalYearModel::find($id);
                        $fiscalyear->deleted_by = Auth::id();
                        $fiscalyear->save();
                        $fiscalyear->delete();
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
