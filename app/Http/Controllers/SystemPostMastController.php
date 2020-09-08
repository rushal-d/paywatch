<?php

namespace App\Http\Controllers;

use App\GradeModel;
use App\Helpers\BSDateHelper;
use App\StaffJobPosition;
use App\StafMainMastModel;
use App\SystemPostMastModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Config;

class SystemPostMastController extends Controller
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
        $search_term = $request->search;
        $systempost = SystemPostMastModel::withCount('staffs')->orderBy('active', 'desc')->orderBy('effect_date', 'desc')->search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('systempost.index', [
            'title' => 'Post Name',
            'systempost' => $systempost,
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
        $grades = GradeModel::select('id', 'value')->get();
        return view('systempost.create',
            [
                'title' => 'Add Post',
                'grades' => $grades
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
            'post_title' => 'required',
            'basic_salary' => 'required',
            'effect_date' => 'required',
        ],
            [
                'post_title.required' => 'You must enter the Post Title!',
                'basic_salary.required' => 'You must enter the basic salary for the post!',
                'effect_date.required' => 'You must enter the effective date!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('system-post-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $post = new SystemPostMastModel();
                $post->post_title = $request->post_title;
                $post->basic_salary = $request->basic_salary;
                $post->effect_date_np = $request->effect_date;
                $post->effect_date = !empty($request->effect_date_np) ? BSDateHelper::BsToAd('-', $request->effect_date_np) : null;
                $post->grade_id = $request->grade_id;
                $post->grade_amount = $request->grade_amount;
                $post->active = 1;
                $post->parent_id = null;
                $post->created_by = \Auth::user()->id;
                if ($post->save()) {
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
        return redirect()->route('system-post-create')->with('flash', array('status' => $status, 'mesg' => $mesg));
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
        $grades = GradeModel::select('id', 'value')->get();
        $post = SystemPostMastModel::find($id);
        return view('systempost.edit', [
            'title' => 'Edit Post',
            'post' => $post,
            'grades' => $grades
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
            'post_title' => 'required',
            'basic_salary' => 'required',
            'effect_date' => 'required',
        ],
            [
                'post_title.required' => 'You must enter the Post Title!',
                'basic_salary.required' => 'You must enter the basic salary for the post!',
                'effect_date.required' => 'You must enter the effective date!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('system-post-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info
                $effective_date_np = $request->effect_date;
                $effective_date_en = !empty($effective_date_np) ? BSDateHelper::BsToAd('-', $effective_date_np) : null;

                $oneDayBeforeEffective = date('Y-m-d', strtotime('-1 day', strtotime($effective_date_en)));
                $oneDayBeforeEffective_np = BSDateHelper::AdToBs('-', $oneDayBeforeEffective);

                //check if there is any other post with the id in between the given effective date

                $post = SystemPostMastModel::find($id);

                if ($post->active == 0) {
                    $status = 'error';
                    $mesg = 'You can not edit inactive post!';
                    return redirect()->route('system-post-edit', [$id])->withInput()->with('flash', array('status' => $status, 'mesg' => $mesg));
                }

                $parent_posts = $post->parents;
                if (!empty($parent_posts->where('effect_date', '<=', $effective_date_en)->where('effective_to_date', '>=', $effective_date_en)->first())) {
                    $status = 'error';
                    $mesg = 'The Post already has a record with the given date!';
                    return redirect()->route('system-post-edit', [$id])->withInput()->with('flash', array('status' => $status, 'mesg' => $mesg));
                }
                if ($parent_posts->count() > 0) {
                    if ($parent_posts->where('effect_date', '<=', $effective_date_en)->count() == 0) {
                        $status = 'error';
                        $mesg = 'The Effective Date is back date than of the initial record';
                        return redirect()->route('system-post-edit', [$id])->withInput()->with('flash', array('status' => $status, 'mesg' => $mesg));
                    }
                }

                $redirect_post_id = $id;
                //if previous post is equal or less the the input date then update the record.
                if ($post->effect_date == $effective_date_en || strtotime($post->effect_date) > strtotime($effective_date_en) ||
                    (($post->effect_date < $effective_date_en) && ($post->basic_salary == $request->basic_salary) && ($post->grade_id == $request->grade_id) && ($post->grade_amount == $request->grade_amount))
                ) {

                    //if input effective date is less than the previous post effective date then update the previous record's effective to date
                    if (strtotime($post->effect_date) > strtotime($effective_date_en) || (($post->effect_date < $effective_date_en) && ($post->basic_salary == $request->basic_salary) && ($post->grade_id == $request->grade_id) && ($post->grade_amount == $request->grade_amount))) {
                        $parentPost = $post->parentPost;
                        if (!empty($parentPost)) {
                            $parentPost->effective_to_date = $oneDayBeforeEffective;
                            $parentPost->effective_to_date_np = $oneDayBeforeEffective_np;
                            $parentPost->save();

                            $parentStaffPositionIds = StaffJobPosition::whereHas('childJobPosition', function ($query) use ($post, $id) {
                                $query->where('post_id', $id)->whereDate('effective_from_date', $post->effect_date)->where('is_system_created', 1);
                            })->pluck('id');

                            $staffPosition = StaffJobPosition::where('post_id', $id)
                                ->whereDate('effective_from_date', $post->effect_date)
                                ->where('is_system_created', 1)->update([
                                    'effective_from_date' => $effective_date_en,
                                    'effective_from_date_np' => $effective_date_np
                                ]);


                            StaffJobPosition::whereIn('id', $parentStaffPositionIds)->update([
                                'effective_to_date' => $oneDayBeforeEffective,
                                'effective_to_date_np' => $oneDayBeforeEffective_np
                            ]);
                        }
                    }

                    $post->post_title = $request->post_title;
                    $post->basic_salary = $request->basic_salary;
                    $post->effect_date = $effective_date_en;
                    $post->effect_date_np = $effective_date_np;
                    $post->grade_amount = $request->grade_amount;
                    $post->grade_id = $request->grade_id;
                    $post->updated_by = \Auth::user()->id;

                } else {
                    $new_post = new SystemPostMastModel();
                    $new_post->post_title = $request->post_title;
                    $new_post->basic_salary = $request->basic_salary;
                    $new_post->effect_date = $effective_date_en;
                    $new_post->effect_date_np = $effective_date_np;
                    $new_post->grade_id = $request->grade_id;
                    $new_post->grade_amount = $request->grade_amount;
                    $new_post->active = 1;
                    $new_post->parent_id = $id;
                    $new_post->created_by = \Auth::user()->id;
                    $new_post->save();
                    $redirect_post_id = $new_post->post_id;
                    $post->active = 0;
                    $post->effective_to_date = $oneDayBeforeEffective;
                    $post->effective_to_date_np = $oneDayBeforeEffective_np;
                    StafMainMastModel::where('post_id', $id)->update(['post_id' => $new_post->post_id]);

                    //get the staff position record in which the record has the effective date of the post in between as of the staff effective from and effective to and split the record in the staff position
                    $staffJobPositionsToSplit = StaffJobPosition::with('childJobPosition')
                        ->where(function ($query) use ($effective_date_en, $id) {
                            $query->where('post_id', $id);
                            $query->where('effective_from_date', '<', $effective_date_en);
                            $query->where('effective_to_date', '>=', $effective_date_en);
                        })
                        ->orWhere(function ($query) use ($effective_date_en, $id) {
                            $query->where('post_id', $id);
                            $query->where('effective_from_date', '<', $effective_date_en);
                            $query->where('effective_to_date', '=', null);
                        })->get();
                    $jobPositionBulk = [];
                    $job_position_count = 0;
                    StaffJobPosition::
                    where(function ($query) use ($effective_date_en, $id) {
                        $query->where('post_id', $id);
                        $query->where('effective_from_date', '<', $effective_date_en);
                        $query->where('effective_to_date', '>=', $effective_date_en);
                    })
                        ->orWhere(function ($query) use ($effective_date_en, $id) {
                            $query->where('post_id', $id);
                            $query->where('effective_from_date', '<', $effective_date_en);
                            $query->where('effective_to_date', '=', null);
                        })
                        ->update([
                            'effective_to_date' => $oneDayBeforeEffective,
                            'effective_to_date_np' => $oneDayBeforeEffective_np
                        ]);
                    $parentIdsToResolve = [];
                    foreach ($staffJobPositionsToSplit as $jobPosition) {
                        $nextJobPosition = $jobPosition->childJobPosition;

                        $jobPositionBulk[$job_position_count]['staff_central_id'] = $jobPosition->staff_central_id;
                        $jobPositionBulk[$job_position_count]['post_id'] = $new_post->post_id;
                        $jobPositionBulk[$job_position_count]['effective_from_date'] = $effective_date_en;
                        $jobPositionBulk[$job_position_count]['effective_from_date_np'] = $effective_date_np;
                        $jobPositionBulk[$job_position_count]['effective_to_date'] = null;
                        $jobPositionBulk[$job_position_count]['effective_to_date_np'] = null;
                        $jobPositionBulk[$job_position_count]['is_system_created'] = 1;
                        $jobPositionBulk[$job_position_count]['parent_id'] = $jobPosition->id;

                        if (!empty($nextJobPosition)) {
                            //parent id needs to be resolved because during bulk insert the id is not created on the instance so that the child of the system created entry cannot map the child so it takes the ids to resolve in the code below
                            $parentIdsToResolve[] = $jobPosition->id;
                            $jobPositionBulk[$job_position_count]['effective_to_date'] = date('Y-m-d', strtotime('-1 day', strtotime($nextJobPosition->effective_from_date)));
                            $jobPositionBulk[$job_position_count]['effective_to_date_np'] = BSDateHelper::AdToBs('-', $jobPositionBulk[$job_position_count]['effective_to_date']);
                        }
                        $jobPositionBulk[$job_position_count]['created_at'] = Carbon::now();
                        $jobPositionBulk[$job_position_count]['updated_at'] = Carbon::now();
                        $job_position_count++;
                    }
                    StaffJobPosition::insert($jobPositionBulk);
                    StaffJobPosition::where('post_id', $id)->where('effective_from_date', '>=', $effective_date_en)->update(['post_id' => $new_post->post_id]);
                    $collectionToResolve = StaffJobPosition::whereIn('parent_id', $parentIdsToResolve)->get();
                    foreach ($parentIdsToResolve as $parentIdToResolve) {
                        $parentIdCollection = $collectionToResolve->where('parent_id', $parentIdToResolve);
                        if ($parentIdCollection->count() == 2) {
                            $systemCreatedRecord = $parentIdCollection->where('is_system_created', 1)->first();
                            $nextCollection = $parentIdCollection->where('is_system_created', 0)->first();
                            $nextCollection->parent_id = $systemCreatedRecord->id;
                            $nextCollection->save();
                        }
                    }
                }

                if ($post->save()) {
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
        return redirect()->route('system-post-edit', [$redirect_post_id])->with('flash', array('status' => $status, 'mesg' => $mesg));

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
            $systempost = SystemPostMastModel::with('staffs')->find($request->id);
            if ($systempost->staffs->count() > 0) {
                echo "Post cannot be deleted because the post is associated with staffs!";
                exit;
            }
            $systempost->deleted_by = Auth::user()->id;
            $systempost->save();

            if ($systempost->delete()) {
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
                        $systempost = SystemPostMastModel::with('staffs')->find($id);
                        if ($systempost->staffs->count() == 0) {
                            $systempost->deleted_by = Auth::user()->id;
                            $systempost->save();
                            $systempost->delete();
                        }
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

    public function orderPost()
    {
        $systemposts = SystemPostMastModel::orderBy('active', 'desc')->orderBy('order')->get();
        return view('systempost.order', [
            'title' => 'Designation Order',
            'systemposts' => $systemposts
        ]);
    }

    public function orderPostSave(Request $request)
    {
        $systemposts = SystemPostMastModel::orderBy('active', 'desc')->orderBy('order')->get();
        foreach ($request->posts as $order => $post) {
            $systemPost = $systemposts->where('post_id', $post['item_id'])->first();
            if (!empty($systemPost)) {
                $systemPost->order = $order;
                $systemPost->save();
            }
        }
        return response()->json(true);
    }

}
