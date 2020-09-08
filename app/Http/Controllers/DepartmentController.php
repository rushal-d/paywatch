<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
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
        $departments = Department::search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('department.index', [
            'title' => 'Department',
            'departments' => $departments,
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
        return view('department.create',
            [
                'title' => 'Add Department'
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
            'department_name' => 'required',
        ],
            [
                'department_name.required' => 'You must enter the title!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('department-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $department = new Department();
                $department->department_name = $request->department_name;
                $department->created_by = Auth::user()->id;
                if ($department->save()) {
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
        return redirect()->route('department-create')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Department $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Department $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $department = Department::find($id);
        return view('department.edit', [
            'title' => 'Edit Department',
            'department' => $department
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Department $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $department = Department::find($id);
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'department_name' => 'required',
        ],
            [
                'department_name.required' => 'You must enter the department_name!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('department-edit', [$department->id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info

                $department->department_name = $request->department_name;
                $department->updated_by = Auth::id();

                if ($department->save()) {
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
        return redirect()->route('department-edit', [$department->id])->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Department $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $department = Department::find($request->id);
            $department->deleted_by = Auth::id();
            $department->save();
            if ($department->delete()) {
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
                        $department = Department::find($id);
                        $department->deleted_by = Auth::id();
                        $department->save();
                        $department->delete();
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
