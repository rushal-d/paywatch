<?php

namespace App\Http\Controllers;

use App\Department;
use App\Helpers\BSDateHelper;
use App\Shift;
use App\StaffWorkScheduleMastModel;
use App\StafMainMastModel;
use App\SystemJobTypeMastModel;
use App\SystemOfficeMastModel;
use App\SystemPostMastModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ChangeWeekendController extends Controller
{
    public function index()
    {

        $data['branches'] = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $data['job_types'] = SystemJobTypeMastModel::pluck('jobtype_name', 'jobtype_id');
        $data['designations'] = SystemPostMastModel::pluck('post_title', 'post_id');
        $data['departments'] = Department::pluck('department_name', 'id');
        $data['title'] = "Filter Staff";
        return view('changeweekend.index', $data);
    }

    public function staffList(Request $request)
    {
        $staffs = new StafMainMastModel();
        if (!empty($request->branch_id)) {
            $staffs = $staffs->where('branch_id', $request->branch_id);
        }
        if (!empty($request->job_type)) {
            $staffs = $staffs->where('jobtype_id', $request->job_type);
        }
        if (!empty($request->designation)) {
            $staffs = $staffs->where('post_id', $request->designation);
        }

        if (!empty($request->department)) {
            $staffs = $staffs->where('department', $request->department);
        }

        if (!empty($request->main_ids)) {
            $array = explode(',', $request->main_ids);
            $staffs = $staffs->whereIn('main_id', $array);
        }
        $staffs = $staffs->orderby('main_id')->get();

        $data['staffs'] = $staffs;
        $data['i'] = 1;
        if ($staffs->count() > 100) {
            $data['break_count'] = (ceil($staffs->count() / 3));
        } else {
            $data['break_count'] = $staffs->count();
        }
        $data['branches'] = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $data['job_types'] = SystemJobTypeMastModel::pluck('jobtype_name', 'jobtype_id');
        $data['designations'] = SystemPostMastModel::pluck('post_title', 'post_id');
        $data['departments'] = Department::pluck('department_name', 'id');
        $data['title'] = 'Change Staff Weekend';

        $data['weekends'] = Config::get('constants.weekend_days');
        return view('changeweekend.stafflist', $data);
    }

    public function store(Request $request)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'staff_central_id' => 'required',
            'weekend' => 'required',
        ],
            [
                'staff_central_id.required' => 'You must select atleast on Staf!',
                'weekend.required' => 'You must select the weekend!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            try {
                DB::beginTransaction();
                $staffs = StafMainMastModel::with('latestWorkSchedule')->whereIn('id', $request->staff_central_id)->get();
                foreach ($staffs as $staff) {
                    $staff_workschedule = new StaffWorkScheduleMastModel();
                    $staff_workschedule->staff_central_id = $staff->id;
                    $staff_workschedule->work_hour = $staff->latestWorkSchedule->work_hour ?? 8;
                    $staff_workschedule->max_work_hour = $staff->latestWorkSchedule->max_work_hour ?? 8;
                    $staff_workschedule->weekend_day = $request->weekend;
                    $staff_workschedule->effect_day = BSDateHelper::BsToAd('-', $request->effective_from);
                    $staff_workschedule->effect_date_np = $request->effective_from;
                    $staff_workschedule->work_status = 'A';
                    $staff_workschedule->created_by = Auth::id();
                    $status_mesg = $staff_workschedule->save();
                    $status_mesg = true;
                }
            } catch (\Exception $e) {
                DB::rollBack();
            }

            if ($status_mesg) {
                DB::commit();
            }
            $status = ($status_mesg) ? 'success' : 'error';
            $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';
            return redirect()->route('change-weekend')->with('flash', array('status' => $status, 'mesg' => $mesg));
        }
    }
}
