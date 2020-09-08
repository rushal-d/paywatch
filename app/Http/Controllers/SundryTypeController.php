<?php

namespace App\Http\Controllers;

use App\SundryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Config;

class SundryTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    /** Index Function to show all data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $types = Config::get('constants.sundry_types');
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $sundryTypes = SundryType::search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('sundryType.index', [
            'title' => 'SundryType',
            'sundryTypes' => $sundryTypes,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'types' => $types
        ]);
    }

    /** To create a new
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $types = Config::get('constants.sundry_types');
        return view('sundryType.create',
            [
                'title' => 'Add SundryType',
                'types' => $types
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
            'title' => 'required|unique:sundry_types',
            'type' => 'required',
        ],
            [
                'title.required' => 'You must enter the title!',
                'type.required' => 'You must enter the type!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('sundry-type-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $sundryType = new SundryType();
                $sundryType->title = $request->title;
                $sundryType->description = $request->description;
                $sundryType->type = $request->type;
                $sundryType->created_by = Auth::id();
                if ($sundryType->save()) {
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
        return redirect()->route('sundry-type-create')->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Display the specified resource.
     *
     * @param \App\SundryType $sundryType
     * @return \Illuminate\Http\Response
     */
    public function show(SundryType $sundryType)
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
        $types = Config::get('constants.sundry_types');
        $sundryType = SundryType::find($id);
        return view('sundryType.edit', [
            'title' => 'Edit SundryType',
            'sundryType' => $sundryType,
            'types' => $types
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
            'title' => 'required',
            'type' => 'required',
        ],
            [
                'title.required' => 'You must enter the title!',
                'type.required' => 'You must enter the type!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('sundry-type-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info

                $sundryType = SundryType::find($id);
                $sundryType->title = $request->title;
                $sundryType->description = $request->description;
                $sundryType->type = $request->type;
                $sundryType->updated_by = Auth::id();

                if ($sundryType->save()) {
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
        return redirect()->route('sundry-type-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $sundryType = SundryType::find($request->id);
            $sundryType->deleted_by = Auth::id();
            $sundryType->save();
            if ($sundryType->delete()) {
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
                        $sundryType = SundryType::find($id);
                        $sundryType->deleted_by = Auth::id();
                        $sundryType->save();
                        $sundryType->delete();
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
