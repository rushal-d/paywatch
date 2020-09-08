<?php

namespace App\Http\Controllers;

use App\Helpers\BSDateHelper;
use App\SystemOfficeMastModel;
use Illuminate\Http\Request;
use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SystemOfficeMastController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;

        $systemoffice = SystemOfficeMastModel::search($search_term)->paginate($records_per_page);
//        dd($systemoffice);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('systemoffice.index', [
            'title' => 'Office Name',
            'systemoffice' => $systemoffice,
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
        return view('systemoffice.create',
            [
                'title' => 'Add Office'
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
            'office_name' => 'required',
        ],
            [
                'office_name.required' => 'You must enter the Office Name!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('systemofficecreate')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $office = new SystemOfficeMastModel();
                $office->office_name = $request->office_name;
                $office->sync = SystemOfficeMastModel::sync;
                $office->office_location = $request->office_location;
                $office->estd_date = $request->estd_date;
                $office->estd_date_np = $request->estd_date_np;
                $office->paywatch_implementation_date_np = $request->paywatch_implementation_date_np;
                $office->manual_weekend_enable = $request->manual_weekend_enable;
                $office->created_by = Auth::id();
                if (!empty($request->paywatch_implementation_date_np)) {
                    $office->paywatch_implementation_date = BSDateHelper::BsToAd('-', $office->paywatch_implementation_date_np);
                    $office->schedule_run_date = date('Y-m-d', strtotime("-1 day", strtotime($office->paywatch_implementation_date)));
                }

                if ($office->save()) {
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
        return redirect()->route('systemofficecreate')->with('flash', array('status' => $status, 'mesg' => $mesg));
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
        $office = SystemOfficeMastModel::find($id);
        return view('systemoffice.edit', [
            'title' => 'Edit Office',
            'office' => $office
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
            'office_name' => 'required',
        ],
            [
                'office_name.required' => 'You must enter the Leave Name!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('systemofficeedit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info

                $office = SystemOfficeMastModel::find($id);
                $office->office_name = $request->office_name;
                $office->sync = SystemOfficeMastModel::sync;
                $office->office_location = $request->office_location;
                $office->estd_date = $request->estd_date;
                $office->estd_date_np = $request->estd_date_np;
                $office->updated_by = Auth::id();
                $office->order_staff_ids = $request->order_staff_ids;
                if (!empty($request->paywatch_implementation_date_np)) {
                    $office->paywatch_implementation_date_np = $request->paywatch_implementation_date_np;
                    $office->paywatch_implementation_date = BSDateHelper::BsToAd('-', $office->paywatch_implementation_date_np);
                    $office->schedule_run_date = date('Y-m-d', strtotime("-1 day", strtotime($office->paywatch_implementation_date)));
                } else {
                    $office->paywatch_implementation_date_np = null;
                    $office->paywatch_implementation_date = null;
                    $office->schedule_run_date = null;
                }
                $office->manual_weekend_enable = $request->manual_weekend_enable;
                if ($office->save()) {
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
        return redirect()->route('systemofficeedit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

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
            $office = SystemOfficeMastModel::find($request->id);
            $office->deleted_by = Auth::id();
            $office->save();
            if ($office->delete()) {
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
                        $office = SystemOfficeMastModel::find($id);
                        $office->deleted_by = Auth::id();
                        $office->save();
                        $office->delete();
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

    public function toogleAlternativeShift(Request $request)
    {
        $office = SystemOfficeMastModel::find($request->office_id);
        if (!empty($office)) {
            $office->enable_alternative_shift = $request->enable_alternative_shift;
            $office->save();
        }
        return response()->json(true);
    }
}
