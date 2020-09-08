<?php

namespace App\Http\Controllers;

use App\GradeModel;
use App\Helpers\BSDateHelper;
use App\StaffJobPosition;
use App\StaffType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StaffTypeController extends Controller
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
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : config('constants.records_per_page');
        $search_term = $request->search;
        $staffTypes = StaffType::withCount('staffs')->search($search_term)->paginate($records_per_page);
        $records_per_page_options = config('constants.records_per_page_options');

        return view('stafftype.index', [
            'title' => 'Staff Type Name',
            'staffTypes' => $staffTypes,
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
        return view('stafftype.create',
            [
                'title' => 'Add Staff Type',
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
            'staff_type_title' => ['required', Rule::unique('staff_types', 'staff_type_title')->whereNull('deleted_at')],
            'staff_type_code' => ['required', Rule::unique('staff_types', 'staff_type_code')->whereNull('deleted_at')],
        ],
            [
                'staff_type_title.required' => 'You must enter the Staff Type Title!',
                'staff_type_code.required' => 'You must enter the Staff Type Code!',
                'staff_type_title.unique' => 'Title already exists. You must enter unique staff type title!',
                'staff_type_code.unique' => 'Code already exists. You must enter unique staff type code!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('staff-type-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $staffType = new StaffType();
                $staffType->staff_type_title = $request->staff_type_title;
                $staffType->staff_type_code = $request->staff_type_code;
                $staffType->created_by = \Auth::user()->id;
                if ($staffType->save()) {
                    $status_mesg = true;
                }
            } catch (\Exception $e) {
                DB::rollback();
                $status_mesg = false;
            }
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('staff-type-edit', $staffType->id)->with('flash', array('status' => $status, 'mesg' => $mesg));
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
        $staffType = StaffType::find($id);
        return view('stafftype.edit', [
            'title' => 'Edit Staff Type',
            'staffType' => $staffType,
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
        $staffType = StaffType::where('id', $id)->firstOrFail();

        $validator = \Validator::make($request->all(), [
            'staff_type_title' => Rule::unique('staff_types', 'staff_type_title')->ignore($staffType->id)->whereNull('deleted_at'),
            'staff_type_code' => Rule::unique('staff_types', 'staff_type_code')->ignore($staffType->id)->whereNull('deleted_at'),
        ],
            [
                'staff_type_title.required' => 'You must enter the Staff Type Title!',
                'staff_type_code.required' => 'You must enter the Staff Type Code!',
                'staff_type_title.unique' => 'Title already exists. You must enter unique staff type title!',
                'staff_type_code.unique' => 'Code already exists. You must enter unique staff type code!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('staff-type-edit', $id)
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $staffType->staff_type_title = $request->staff_type_title;
                $staffType->staff_type_code = $request->staff_type_code;
                $staffType->updated_by = \Auth::user()->id;
                if ($staffType->save()) {
                    $status_mesg = true;
                }
            } catch (\Exception $e) {
                DB::rollback();
                $status_mesg = false;
            }
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('staff-type-edit', $staffType->id)->with('flash', array('status' => $status, 'mesg' => $mesg));

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
            $staffType = StaffType::withCount('staffs')->find($request->id);
            if ($staffType->staffs_count > 0) {
                echo "Post cannot be deleted because the post is associated with {$staffType->staffs_count} staffs!";
                exit;
            }
            $staffType->deleted_by = Auth::user()->id;
            $staffType->save();

            if ($staffType->delete()) {
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
                DB::beginTransaction();
                $staffTypes = StaffType::withCount('staffs')->whereIn('id', $ids)->get();
                foreach ($staffTypes as $staffType) {
                    if ($staffType->staffs_count == 0) {
                        $staffType->deleted_by = Auth::user()->id;

                        $staffType->delete();
                    }
                }
                DB::commit();
                $status_mesg = true;
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e);
                $status_mesg = false;
            }
        }
        $mesg = ($status_mesg) ? 'Successfully Deleted' : 'Error deleting!';
        echo $mesg;
    }

}
