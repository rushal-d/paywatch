<?php

namespace App\Http\Controllers;

use App\OrganizationMastShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class OrganizationMastShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $organizationShifts = OrganizationMastShift::query();
        $organizationShifts = $organizationShifts->orderBy('effective_from', 'desc')->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');

        return view('orgnaizationshift.index', [
            'title' => 'Shift',
            'organizationShifts' => $organizationShifts,
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

        return view('orgnaizationshift.create', [
            'title' => 'Organization Shift Create'
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
            'effective_from' => 'required',
            'effective_from_np' => 'required',
        ],
            [
                'effective_from.required' => 'Effective From (AD) is required!',
                'effective_from_np.required' => 'Effective From (BS) is required!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            try {
                DB::beginTransaction();
                $organizationShift = new OrganizationMastShift();
                $organizationShift->effective_from = $request->effective_from;
                $organizationShift->effective_from_np = $request->effective_from_np;
                $organizationShift->sunday_punch_in = (!empty($request->sunday_punch_out) && !empty($request->sunday_punch_in)) ? $request->sunday_punch_in : null;
                $organizationShift->sunday_punch_out = (!empty($request->sunday_punch_out) && !empty($request->sunday_punch_in)) ? $request->sunday_punch_out : null;
                $organizationShift->monday_punch_in = (!empty($request->monday_punch_out) && !empty($request->monday_punch_in)) ? $request->monday_punch_in : null;
                $organizationShift->monday_punch_out = (!empty($request->monday_punch_out) && !empty($request->monday_punch_in)) ? $request->monday_punch_out : null;
                $organizationShift->tuesday_punch_in = (!empty($request->tuesday_punch_out) && !empty($request->tuesday_punch_in)) ? $request->tuesday_punch_in : null;
                $organizationShift->tuesday_punch_out = (!empty($request->tuesday_punch_out) && !empty($request->tuesday_punch_in)) ? $request->tuesday_punch_out : null;
                $organizationShift->wednesday_punch_in = (!empty($request->wednesday_punch_out) && !empty($request->wednesday_punch_in)) ? $request->wednesday_punch_in : null;
                $organizationShift->wednesday_punch_out = (!empty($request->wednesday_punch_out) && !empty($request->wednesday_punch_in)) ? $request->wednesday_punch_out : null;
                $organizationShift->thursday_punch_in = (!empty($request->thursday_punch_out) && !empty($request->thursday_punch_in)) ? $request->thursday_punch_in : null;
                $organizationShift->thursday_punch_out = (!empty($request->thursday_punch_out) && !empty($request->thursday_punch_in)) ? $request->thursday_punch_out : null;
                $organizationShift->friday_punch_in = (!empty($request->friday_punch_out) && !empty($request->friday_punch_in)) ? $request->friday_punch_in : null;
                $organizationShift->friday_punch_out = (!empty($request->friday_punch_out) && !empty($request->friday_punch_in)) ? $request->friday_punch_out : null;
                $organizationShift->saturday_punch_in = (!empty($request->saturday_punch_out) && !empty($request->saturday_punch_in)) ? $request->saturday_punch_in : null;
                $organizationShift->saturday_punch_out = (!empty($request->saturday_punch_out) && !empty($request->saturday_punch_in)) ? $request->saturday_punch_out : null;
                $organizationShift->created_by = Auth::id();
                $status_mesg = $organizationShift->save();
            } catch (\Exception $e) {
                DB::rollBack();
            }
            if ($status_mesg) {
                DB::commit();
            }

            $status = ($status_mesg) ? 'success' : 'error';
            $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
            return redirect()->route('organization-shift-create')->with('flash', array('status' => $status, 'mesg' => $mesg));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\OrganizationMastShift $organizationMastShift
     * @return \Illuminate\Http\Response
     */
    public function show(OrganizationMastShift $organizationMastShift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\OrganizationMastShift $organizationMastShift
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $organizationShift = OrganizationMastShift::find($id);
        return view('orgnaizationshift.edit', [
            'title' => 'Organization Shift Update',
            'organizationShift' => $organizationShift
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\OrganizationMastShift $organizationMastShift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'effective_from' => 'required',
            'effective_from_np' => 'required',
        ],
            [
                'effective_from.required' => 'Effective From (AD) is required!',
                'effective_from_np.required' => 'Effective From (BS) is required!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            try {
                DB::beginTransaction();
                $organizationShift = OrganizationMastShift::find($id);
                $organizationShift->effective_from = $request->effective_from;
                $organizationShift->effective_from_np = $request->effective_from_np;
                $organizationShift->sunday_punch_in = (!empty($request->sunday_punch_out) && !empty($request->sunday_punch_in)) ? $request->sunday_punch_in : null;
                $organizationShift->sunday_punch_out = (!empty($request->sunday_punch_out) && !empty($request->sunday_punch_in)) ? $request->sunday_punch_out : null;
                $organizationShift->monday_punch_in = (!empty($request->monday_punch_out) && !empty($request->monday_punch_in)) ? $request->monday_punch_in : null;
                $organizationShift->monday_punch_out = (!empty($request->monday_punch_out) && !empty($request->monday_punch_in)) ? $request->monday_punch_out : null;
                $organizationShift->tuesday_punch_in = (!empty($request->tuesday_punch_out) && !empty($request->tuesday_punch_in)) ? $request->tuesday_punch_in : null;
                $organizationShift->tuesday_punch_out = (!empty($request->tuesday_punch_out) && !empty($request->tuesday_punch_in)) ? $request->tuesday_punch_out : null;
                $organizationShift->wednesday_punch_in = (!empty($request->wednesday_punch_out) && !empty($request->wednesday_punch_in)) ? $request->wednesday_punch_in : null;
                $organizationShift->wednesday_punch_out = (!empty($request->wednesday_punch_out) && !empty($request->wednesday_punch_in)) ? $request->wednesday_punch_out : null;
                $organizationShift->thursday_punch_in = (!empty($request->thursday_punch_out) && !empty($request->thursday_punch_in)) ? $request->thursday_punch_in : null;
                $organizationShift->thursday_punch_out = (!empty($request->thursday_punch_out) && !empty($request->thursday_punch_in)) ? $request->thursday_punch_out : null;
                $organizationShift->friday_punch_in = (!empty($request->friday_punch_out) && !empty($request->friday_punch_in)) ? $request->friday_punch_in : null;
                $organizationShift->friday_punch_out = (!empty($request->friday_punch_out) && !empty($request->friday_punch_in)) ? $request->friday_punch_out : null;
                $organizationShift->saturday_punch_in = (!empty($request->saturday_punch_out) && !empty($request->saturday_punch_in)) ? $request->saturday_punch_in : null;
                $organizationShift->saturday_punch_out = (!empty($request->saturday_punch_out) && !empty($request->saturday_punch_in)) ? $request->saturday_punch_out : null;
                $organizationShift->created_by = Auth::id();
                $status_mesg = $organizationShift->save();
            } catch (\Exception $e) {
                DB::rollBack();
            }
            if ($status_mesg) {
                DB::commit();
            }

            $status = ($status_mesg) ? 'success' : 'error';
            $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
            return redirect()->route('organization-shift-edit', $id)->with('flash', array('status' => $status, 'mesg' => $mesg));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\OrganizationMastShift $organizationMastShift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $shift = OrganizationMastShift::find($request->id);
            $shift->deleted_by = Auth::id();
            $shift->save();
            if ($shift->delete()) {
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
                        $shift = OrganizationMastShift::find($id);
                        $shift->deleted_by = Auth::id();
                        $shift->save();
                        $shift->delete();
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
