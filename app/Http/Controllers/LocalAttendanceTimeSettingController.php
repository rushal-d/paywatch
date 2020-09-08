<?php

namespace App\Http\Controllers;

use App\LocalAttendanceTimeSetting;
use App\Shift;
use App\SystemOfficeMastModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class LocalAttendanceTimeSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $local_attendance_settings = LocalAttendanceTimeSetting::with('shift')->search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('localattendancesetting.index', [
            'title' => 'Loacl Attendance Seting',
            'local_attendance_settings' => $local_attendance_settings,
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
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        return view('localattendancesetting.create',
            [
                'title' => 'Add Local Attendance Time Settings',
                'branches' => $branches
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
            'shift_id' => 'required',
            'branch_id' => 'required',
            'punch_in' => 'required',
            'punch_out' => 'required',
            'tiffin_duration' => 'required',
            'lunch_duration' => 'required',
        ],
            [
                'shift_id.required' => 'You must enter the shift name!',
                'branch_id.required' => 'You must enter the branch!',
                'punch_in.required' => 'You must enter punch in!',
                'punch_out.required' => 'You must enter punch out!',
                'tiffin_duration.required' => 'You must enter tiffin duration!',
                'lunch_duration.required' => 'You must enter lunch duration!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('localattendace-setting-index')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $input = Input::all();
                $status_mesg = LocalAttendanceTimeSetting::create($input);
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
        return redirect()->route('localattendace-setting-index')->with('flash', array('status' => $status, 'mesg' => $mesg));


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LocalAttendanceTimeSetting $localAttendanceTimeSetting
     * @return \Illuminate\Http\Response
     */
    public function show(LocalAttendanceTimeSetting $localAttendanceTimeSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LocalAttendanceTimeSetting $localAttendanceTimeSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(LocalAttendanceTimeSetting $localAttendanceTimeSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\LocalAttendanceTimeSetting $localAttendanceTimeSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LocalAttendanceTimeSetting $localAttendanceTimeSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LocalAttendanceTimeSetting $localAttendanceTimeSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(LocalAttendanceTimeSetting $localAttendanceTimeSetting)
    {
        //
    }
}
