<?php

namespace App\Http\Controllers;

use App\OrganizationSetup;
use App\SystemJobTypeMastModel;
use App\SystemLeaveMastModel;
use App\SystemLeaveMastUseability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SystemLeaveMastController extends Controller
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
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $systemleaves = SystemLeaveMastModel::paginate($records_per_page);

        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('systemleave.index', [
            'title' => 'Leave Description',
            'systemleaves' => $systemleaves,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page
        ]);


//    return view("systemleave.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization = OrganizationSetup::first();
        $leave_types = Config::get('constants.leave_types');
        $leave_earnable_types = Config::get('constants.leave_earnable_types');
        $leave_earnable_periods = Config::get('constants.leave_earnable_periods');
        $useability_count_units = Config::get('constants.useability_count_units');
        $gender = Config::get('constants.gender');
        $job_types = SystemJobTypeMastModel::pluck('jobtype_name', 'jobtype_id');
        return view('systemleave.create',
            [
                'organization' => $organization,
                'leave_types' => $leave_types,
                'leave_earnable_types' => $leave_earnable_types,
                'leave_earnable_periods' => $leave_earnable_periods,
                'useability_count_units' => $useability_count_units,
                'gender' => $gender,
                'title' => 'Add Leave',
                'job_types' => $job_types,

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
            'leave_name' => 'required',
            'leave_code' => 'required|unique:system_leave_mast',
            'max_days' => 'required',
            'no_of_days' => 'required',
        ],
            [
                'leave_name.required' => 'You must enter the Leave Name!',
                'leave_code' => 'Leave Code Must be Unique!',
                'max_days' => 'Max Days Required',
                'no_of_days' => 'No of Days Required',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('systemleavecreate')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $leave = new SystemLeaveMastModel();
                $leave->leave_name = $request->leave_name;
                $leave->leave_code = $request->leave_code;
                $leave->max_days = $request->max_days;
                $leave->no_of_days = $request->no_of_days;
                $leave->initial_setup = $request->initial_setup ?? 0;
                $leave->job_type_id = $request->job_type_id ?? null;
                $leave->allow_negative = $request->allow_negative ?? 0;
                $leave->created_by = Auth::user()->id;

                $organization = OrganizationSetup::first();
                if ($organization->organization_structure == 2) {
                    $leave->leave_type = $request->leave_type;
                    $leave->leave_earnability = $request->leave_earnability;
                    if ($leave->leave_earnability == 1) {
                        $leave->leave_earnable_balance = $request->leave_earnable_balance;
                        $leave->leave_earnable_period = $request->leave_earnable_period;
                        $leave->leave_earnable_type = $request->leave_earnable_type;
                        $leave->threshold_for_earnability = $request->threshold_for_earnability;
                        $leave->threshold_for_present_days = $request->threshold_for_present_days;
                    } else {
                        $leave->leave_earnable_balance = 0;
                        $leave->leave_earnable_period = 1;
                        $leave->leave_earnable_type = 1;
                        $leave->threshold_for_earnability = null;
                        $leave->threshold_for_present_days = 0;
                    }

                    if (isset($request->allow_half_day)) {
                        $leave->allow_half_day = 1;
                    } else {
                        $leave->allow_half_day = 0;
                    }
                    if (isset($request->inclusive_public_holiday_weekend)) {
                        $leave->inclusive_public_holiday_weekend = 1;
                    } else {
                        $leave->inclusive_public_holiday_weekend = 0;
                    }
                    if (isset($request->act_as_present_days)) {
                        $leave->act_as_present_days = 1;
                    } else {
                        $leave->act_as_present_days = 0;
                    }
                    if (isset($request->is_paid)) {
                        $leave->is_paid = 1;
                    } else {
                        $leave->is_paid = 0;
                    }
                    $leave->min_no_of_days_allowed_at_time = $request->min_no_of_days_allowed_at_time;
                    $leave->max_no_of_days_allowed_at_time = $request->max_no_of_days_allowed_at_time;
                    $leave->applicable_gender = $request->applicable_gender;

                    if ($leave->is_paid == 1) {
                        $leave->leave_extra_payment_amount = $request->leave_extra_payment_amount;
                        $leave->basic_salary_ratio = $request->basic_salary_ratio;
                        $leave->grade_ratio = $request->grade_ratio;
                        $leave->allowance_ratio = $request->allowance_ratio;
                    } else {
                        $leave->leave_extra_payment_amount = 0;
                        $leave->basic_salary_ratio = 0;
                        $leave->grade_ratio = 0;
                        $leave->allowance_ratio = 0;
                    }
                }
                if ($leave->save()) {
                    if ($organization->organization_structure == 2) {
                        foreach ($request->useability_count as $key => $useability_count) {
                            if (!empty($useability_count)) {
                                $leave_useability = new SystemLeaveMastUseability();
                                $leave_useability->system_leave_id = $leave->leave_id;
                                $leave_useability->useability_count = $useability_count;
                                $leave_useability->useability_count_unit = $request->useability_count_unit[$key];
                                $leave_useability->save();
                            }
                        }
                    }
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
        return redirect()->route('systemleavecreate')->with('flash', array('status' => $status, 'mesg' => $mesg));
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
        $leave = SystemLeaveMastModel::find($id);
        $organization = OrganizationSetup::first();
        $leave_types = Config::get('constants.leave_types');
        $leave_earnable_types = Config::get('constants.leave_earnable_types');
        $leave_earnable_periods = Config::get('constants.leave_earnable_periods');
        $useability_count_units = Config::get('constants.useability_count_units');
        $gender = Config::get('constants.gender');
        $job_types = SystemJobTypeMastModel::pluck('jobtype_name', 'jobtype_id');
        return view('systemleave.edit', [
            'organization' => $organization,
            'leave_types' => $leave_types,
            'leave_earnable_types' => $leave_earnable_types,
            'leave_earnable_periods' => $leave_earnable_periods,
            'useability_count_units' => $useability_count_units,
            'gender' => $gender,
            'title' => 'Edit Leave',
            'leave' => $leave,
            'job_types' => $job_types,
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
            'leave_name' => 'required',
            'leave_code' => 'required|unique:system_leave_mast,leave_code,' . $id . ',leave_id',
            'max_days' => 'required',
            'no_of_days' => 'required',
        ],
            [
                'leave_name.required' => 'You must enter the Leave Name!',
                'leave_code' => 'Leave Code Must be Unique!',
                'max_days' => 'Max Days Required',
                'no_of_days' => 'No of Days Required',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('systemleaveedit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {

                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info
                $leave = SystemLeaveMastModel::find($id);
                $leave->leave_name = $request->leave_name;
                $leave->leave_code = $request->leave_code;
                $leave->max_days = $request->max_days;
                $leave->no_of_days = $request->no_of_days;
                $leave->initial_setup = $request->initial_setup ?? 0;
                $leave->updated_by = Auth::id();
                $leave->job_type_id = $request->job_type_id ?? null;
                $leave->allow_negative = $request->allow_negative ?? 0;

                $organization = OrganizationSetup::first();
                if ($organization->organization_structure == 2) {
                    $leave->leave_type = $request->leave_type;
                    $leave->leave_earnability = $request->leave_earnability;
                    if ($leave->leave_earnability == 1) {
                        $leave->leave_earnable_balance = $request->leave_earnable_balance;
                        $leave->leave_earnable_period = $request->leave_earnable_period;
                        $leave->leave_earnable_type = $request->leave_earnable_type;
                        $leave->threshold_for_earnability = $request->threshold_for_earnability;
                        $leave->threshold_for_present_days = $request->threshold_for_present_days;
                    } else {
                        $leave->leave_earnable_balance = 0;
                        $leave->leave_earnable_period = 1;
                        $leave->leave_earnable_type = 1;
                        $leave->threshold_for_earnability = null;
                        $leave->threshold_for_present_days = 0;
                    }

                    if (isset($request->allow_half_day)) {
                        $leave->allow_half_day = 1;
                    } else {
                        $leave->allow_half_day = 0;
                    }
                    if (isset($request->inclusive_public_holiday_weekend)) {
                        $leave->inclusive_public_holiday_weekend = 1;
                    } else {
                        $leave->inclusive_public_holiday_weekend = 0;
                    }
                    if (isset($request->act_as_present_days)) {
                        $leave->act_as_present_days = 1;
                    } else {
                        $leave->act_as_present_days = 0;
                    }
                    if (isset($request->is_paid)) {
                        $leave->is_paid = 1;
                    } else {
                        $leave->is_paid = 0;
                    }
                    $leave->min_no_of_days_allowed_at_time = $request->min_no_of_days_allowed_at_time;
                    $leave->max_no_of_days_allowed_at_time = $request->max_no_of_days_allowed_at_time;
                    $leave->applicable_gender = $request->applicable_gender;

                    if ($leave->is_paid == 1) {
                        $leave->leave_extra_payment_amount = $request->leave_extra_payment_amount;
                        $leave->basic_salary_ratio = $request->basic_salary_ratio;
                        $leave->grade_ratio = $request->grade_ratio;
                        $leave->allowance_ratio = $request->allowance_ratio;
                    } else {
                        $leave->leave_extra_payment_amount = 0;
                        $leave->basic_salary_ratio = 0;
                        $leave->grade_ratio = 0;
                        $leave->allowance_ratio = 0;
                    }
                }
                if ($leave->save()) {
                    if ($organization->organization_structure == 2) {
                        SystemLeaveMastUseability::where('system_leave_id', $id)->delete();
                        foreach ($request->useability_count as $key => $useability_count) {
                            if (!empty($useability_count)) {
                                $leave_useability = new SystemLeaveMastUseability();
                                $leave_useability->system_leave_id = $leave->leave_id;
                                $leave_useability->useability_count = $useability_count;
                                $leave_useability->useability_count_unit = $request->useability_count_unit[$key];
                                $leave_useability->save();
                            }
                        }
                    }
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
        return redirect()->route('systemleaveedit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

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
            $leave = SystemLeaveMastModel::find($request->id);
            $leave->deleted_by = Auth::id();
            $leave->save();
            if ($leave->delete()) {
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
                        $leave = SystemLeaveMastModel::find($id);
                        $leave->deleted_by = Auth::id();
                        $leave->save();
                        $leave->delete();
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
