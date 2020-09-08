<?php

namespace App\Http\Controllers;

use App\Helpers\BSDateHelper;
use App\StaffJobPosition;
use App\StafMainMastModel;
use App\SystemPostMastModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffJobPositionController extends Controller
{
    public function index($staff_central_id)
    {
        $staffmain = StafMainMastModel::with(['staffPosts' => function ($query) {
            $query->orderBy('effective_from_date');
            $query->with('post');
        }])->find($staff_central_id);
        $designations = SystemPostMastModel::where('active', 1)->pluck('post_title', 'post_id');
        return view('staffmain.jobposition.create', [
            'title' => 'Staff Position',
            'staffmain' => $staffmain,
            'designations' => $designations,
        ]);

    }

    public function store(Request $request, $staff_central_id)
    {
        $status_mesg = false;
        $effective_from = BSDateHelper::BsToAd('-', $request->effective_from_date_np);
        //check if staff position exists for the given date

        $checkStaffPosition = StaffJobPosition::where('effective_from_date', '<=', $effective_from)->where('effective_to_date', '>=', $effective_from)->where('staff_central_id', $staff_central_id)->exists();
        if ($checkStaffPosition) {
            $status = 'error';
            $mesg = 'Staff Position Already Exists for the given date!';
            return redirect()->back()->withInput()->with('flash', array('status' => $status, 'mesg' => $mesg));
        }

        $post = SystemPostMastModel::where('post_id', $request->post_id)->first();
        //check if inactive post -- return redirect back or similar options

        $staffPosition = StaffJobPosition::whereDate('effective_from_date', $effective_from)->where('effective_to_date', null)->where('staff_central_id', $staff_central_id)->first();
        if (empty($staffPosition)) {
            $staffPosition = new StaffJobPosition();
        }
        try {
            DB::beginTransaction();
            //if the effective from date of the post is before that of the Post Edit(Revised) date then the inactive records of post should also be inserted in staff record
            //eg if POST Assistant had Rs 15000 in 2074-04-01 and revised to 25000 in 2076-12-01
            // and if the POST is to be set at 2075-10-11 then the salary should be 15000 from 2075-10-11 to 2076-11-30 and Rs 25000  2076-12-01 onwards
            if (strtotime($effective_from) < strtotime($post->effect_date)) {
                $all_parent_posts = $post->parents;
                // all the parent inactive posts are taken in the base record of the effective date

                //inserting the first eligible inactive record- as date may be in between of the effective date
                $eligibleRecord = $all_parent_posts->where('effect_date', '<=', $effective_from)
                    ->where('effective_to_date', '>=', $effective_from)->first();
                $staffPosition->staff_central_id = $staff_central_id;
                $staffPosition->post_id = $eligibleRecord->post_id;
                $staffPosition->effective_from_date_np = $request->effective_from_date_np;
                $staffPosition->effective_from_date = $effective_from;
                $staffPosition->effective_to_date_np = $eligibleRecord->effective_to_date_np;
                $staffPosition->effective_to_date = $eligibleRecord->effective_to_date;
                $staffPosition->created_by = Auth::id();
                $staffPosition->is_system_created = 1;
                $staffPosition->parent_id = null;
                $staffPosition->save();
                $staffPreviousPosition = StaffJobPosition::orderBy('effective_from_date', 'DESC')
                    ->whereDate('effective_from_date', '<', $staffPosition->effective_from_date)
                    ->where('staff_central_id', $staff_central_id)->first();

                //setting previous job position effective upto date
                if (!empty($staffPreviousPosition)) {
                    $staffPosition->parent_id = $staffPreviousPosition->id;
                    $staffPreviousPosition->effective_to_date = date('Y-m-d', strtotime('-1 day', strtotime($staffPosition->effective_from_date)));
                    $staffPreviousPosition->effective_to_date_np = BSDateHelper::AdToBs('-', $staffPreviousPosition->effective_to_date);
                    $staffPreviousPosition->updated_by = Auth::id();
                    $staffPreviousPosition->save();
                }

                $parent_id = $staffPosition->id;
                //getting all other inactive eligible records and inserting
                $nextRecords = $all_parent_posts->where('effect_date', '>', $effective_from)->sortBy('effect_date');
                foreach ($nextRecords as $record) {
                    $staffPosition->staff_central_id = $staff_central_id;
                    $staffPosition->post_id = $record->post_id;
                    $staffPosition->effective_from_date_np = $record->effect_date_np;
                    $staffPosition->effective_from_date = $record->effect_date;
                    $staffPosition->effective_to_date_np = $record->effective_to_date_np;
                    $staffPosition->effective_to_date = $record->effective_to_date;
                    $staffPosition->created_by = Auth::id();
                    $staffPosition->parent_id = $parent_id;
                    $staffPosition->is_system_created = 1;
                    $staffPosition->save();
                    $parent_id = $staffPosition->id;
                }
                //current record
                $staffPosition = new StaffJobPosition();
                $staffPosition->staff_central_id = $staff_central_id;
                $staffPosition->post_id = $post->post_id;
                $staffPosition->effective_from_date_np = $post->effect_date_np;
                $staffPosition->effective_from_date = $post->effect_date;
                $staffPosition->effective_to_date_np = $post->effective_to_date_np;
                $staffPosition->effective_to_date = $post->effective_to_date;
                $staffPosition->created_by = Auth::id();
                $staffPosition->parent_id = $parent_id;
                $status_mesg = $staffPosition->save();

            } else {
                //if no past records to be inserted!!!
                $staffPosition = new StaffJobPosition();
                $staffPosition->staff_central_id = $staff_central_id;
                $staffPosition->post_id = $request->post_id;
                $staffPosition->effective_from_date_np = $request->effective_from_date_np;
                $staffPosition->effective_from_date = $effective_from;
                $staffPosition->created_by = Auth::id();
                $staffPosition->parent_id = null;
                $staffPreviousPosition = StaffJobPosition::orderBy('effective_from_date', 'DESC')->whereDate('effective_from_date', '<', $staffPosition->effective_from_date)->where('staff_central_id', $staff_central_id)->first();
                if (!empty($staffPreviousPosition)) {
                    $staffPosition->parent_id = $staffPreviousPosition->id;
                    $staffPreviousPosition->effective_to_date = date('Y-m-d', strtotime('-1 day', strtotime($staffPosition->effective_from_date)));
                    $staffPreviousPosition->effective_to_date_np = BSDateHelper::AdToBs('-', $staffPreviousPosition->effective_to_date);
                    $staffPreviousPosition->updated_by = Auth::id();
                    $staffPreviousPosition->save();
                }
                $status_mesg = $staffPosition->save();
            }
            $currentJobPosition = StaffJobPosition::where('effective_to_date', null)->where('staff_central_id', $staff_central_id)->latest()->first();
            $staff = StafMainMastModel::find($staff_central_id);
            if ($staff->post_id != $currentJobPosition->post_id) {
                $staff->post_id = $currentJobPosition->post_id;
                $staff->save();
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        if ($status_mesg) {
            DB::commit();
        }

        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            $id = $request->id;
            $staffPosition = StaffJobPosition::find($id);
            $staffPreviousPosition = StaffJobPosition::orderBy('effective_from_date', 'DESC')->whereDate('effective_from_date', '<', $staffPosition->effective_from_date)->where('staff_central_id', $staffPosition->staff_central_id)->first();
            if (!empty($staffPreviousPosition)) {
                $staffPreviousPosition->effective_to_date = null;
                $staffPreviousPosition->effective_to_date_np = null;
                $staffPreviousPosition->save();
            }
            $staffPosition->deleted_by = Auth::id();
            $staffPosition->save();
            $success = $staffPosition->delete();
            if ($success) {
                echo 'Successfully Deleted';
            } else {
                echo "Error deleting!";
            }
        } else {
            echo "Error deleting!";
        }
    }

    public function transferMainRecordToStaffJobPosition()
    {
        $staffs = StafMainMastModel::where('post_id', '<>', null)->get();
        $staff_job_position = [];
        $i = 0;
        foreach ($staffs as $staff) {
            $staff_job_position[$i]['staff_central_id'] = $staff->id;
            $staff_job_position[$i]['post_id'] = $staff->post_id;
            $effective_from = date('Y-m-d');
            if (!empty($staff->appo_date)) {
                $effective_from = $staff->appo_date;
            }
            $effective_from_np = BSDateHelper::AdToBs('-', $effective_from);
            $staff_job_position[$i]['effective_from_date'] = '2019-07-17';
            $staff_job_position[$i]['effective_from_date_np'] = '2076-04-01';
            $staff_job_position[$i]['effective_to_date'] = null;
            $staff_job_position[$i]['effective_to_date_np'] = null;
            $staff_job_position[$i]['created_by'] = $staff->created_by;
            $staff_job_position[$i]['updated_by'] = $staff->updated_by;
            $i++;
        }
        foreach (array_chunk($staff_job_position, 10) as $job_position) {
            StaffJobPosition::insert($job_position);
        }
    }
}
