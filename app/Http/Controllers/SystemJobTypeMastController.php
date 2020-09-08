<?php

namespace App\Http\Controllers;

use App\SystemJobTypeMastModel;
use Illuminate\Http\Request;
use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SystemJobTypeMastController extends Controller
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
        $systemjobtypes = SystemJobTypeMastModel::search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('systemjobtype.index', [
            'title' => 'Job Type Description',
            'systemjobtypes' => $systemjobtypes,
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
        return view('systemjobtype.create',
            [
                'title' => 'Add Job Type'
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
            'jobtype_name' => 'required',
        ],
            [
                'jobtype_name.required' => 'You must enter the job type!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('system-jobtype-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $jobtype = new SystemJobTypeMastModel();
                $jobtype->jobtype_name = $request->jobtype_name;
                $jobtype->jobtype_code = $request->jobtype_code;
                $jobtype->effect_date = $request->effect_date;
                $jobtype->profund_per = $request->profund_per;
                $jobtype->profund_contri_per = $request->profund_contri_per;
                $jobtype->social_security_fund_per = $request->social_security_fund_per;
                $jobtype->gratuity = $request->gratuity;
                $jobtype->created_by = Auth::user()->id;
                if ($jobtype->save()) {
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
        return redirect()->route('system-jobtype-create')->with('flash', array('status' => $status, 'mesg' => $mesg));

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
//        dd("working");
        $jobtype = SystemJobTypeMastModel::find($id);
        return view('systemjobtype.edit', [
            'title' => 'Edit JobType',
            'jobtype' => $jobtype
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
            'jobtype_name' => 'required',
        ],
            [
                'jobtype_name.required' => 'You must enter the Job Type!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('system-jobtype-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info

                $jobtype = SystemJobTypeMastModel::find($id);
                $jobtype->jobtype_name = $request->jobtype_name;
                $jobtype->jobtype_code = $request->jobtype_code;
                $jobtype->effect_date = $request->effect_date;
                $jobtype->profund_per = $request->profund_per;
                $jobtype->profund_contri_per = $request->profund_contri_per;
                $jobtype->social_security_fund_per = $request->social_security_fund_per;
                $jobtype->gratuity = $request->gratuity;
                $jobtype->updated_by = Auth::id();
                if ($jobtype->save()) {
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
        return redirect()->route('system-jobtype-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));
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
            $systemjobtype = SystemJobTypeMastModel::find($request->id);
            $systemjobtype->deleted_by = Auth::id();
            $systemjobtype->save();
            if ($systemjobtype->delete()) {
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
                        $systemjobtype = SystemJobTypeMastModel::find($id);
                        $systemjobtype->deleted_by = Auth::id();
                        $systemjobtype->save();
                        $systemjobtype->delete();
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
