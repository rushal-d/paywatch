<?php

namespace App\Http\Controllers;

use App\AlternativeDayShift;
use App\Shift;
use App\StafMainMastModel;
use App\SystemOfficeMastModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AlternativeDayShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $staffs = StafMainMastModel::search($search_term)->whereHas('AllstaffAlternativeShifts')->with(['AllstaffAlternativeShifts' => function ($query) {
            $query->with('shift');
        }])->paginate(100);

        $days = Config::get('constants.weekend_days');


        return view('shifts.alternative-shift-index', [
            'title' => 'Alternative Staff Shift',
            'branches' => $branches,
            'staffs' => $staffs,
            'days' => $days,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'staff_central_id' => 'required',
        ],
            [
                'staff_central_id.required' => 'Please select staff!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            $previous_alternative_shift = AlternativeDayShift::where('staff_central_id', $request->staff_central_id)->get();
            $staff = StafMainMastModel::find($request->staff_central_id);
            $days = Config::get('constants.weekend_days');
            $days_key = [7, 1, 2, 3, 4, 5, 6];
            $shifts = Shift::where('active', 1)->where('branch_id', $staff->branch_id)->pluck('shift_name', 'id');
            return view('shifts.alternative-shift-create', [
                'title' => 'Alternative Day Shift Create',
                'previous_alternative_shift' => $previous_alternative_shift,
                'staff' => $staff,
                'days' => $days,
                'days_key' => $days_key,
                'shifts' => $shifts
            ]);
        }
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
        try {
            DB::beginTransaction();
            $staff_central_id = $request->staff_central_id;
            AlternativeDayShift::where('staff_central_id', $staff_central_id)->delete();
            $staff = StafMainMastModel::find($staff_central_id);
            foreach ($request->shift_id as $day => $shift) {
                $alternative_day_shift = new AlternativeDayShift();
                $alternative_day_shift->staff_central_id = $staff_central_id;
                $alternative_day_shift->day = $day;
                $alternative_day_shift->shift_id = $shift;
                $alternative_day_shift->created_by = Auth::id();
                $alternative_day_shift->save();
            }
            $status_mesg = true;
        } catch (\Exception $e) {
            DB::rollBack();
            $status_mesg = false;
        }
        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Data Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('alternative-shift-index')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\AlternativeDayShift $alternativeDayShift
     * @return \Illuminate\Http\Response
     */
    public function show(AlternativeDayShift $alternativeDayShift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\AlternativeDayShift $alternativeDayShift
     * @return \Illuminate\Http\Response
     */
    public function edit(AlternativeDayShift $alternativeDayShift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\AlternativeDayShift $alternativeDayShift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlternativeDayShift $alternativeDayShift)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\AlternativeDayShift $alternativeDayShift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            AlternativeDayShift::where('staff_central_id', $request->id)->update(['deleted_by'=> Auth::id()]);

            if (AlternativeDayShift::where('staff_central_id', $request->id)->delete()) {
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
                        AlternativeDayShift::where('staff_central_id', $id)->update(['deleted_by'=> Auth::id()]);
                        AlternativeDayShift::where('staff_central_id', $id)->delete();
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
