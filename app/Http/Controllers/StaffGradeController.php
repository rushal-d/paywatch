<?php

namespace App\Http\Controllers;

use App\GradeModel;
use App\Helpers\BSDateHelper;
use App\StaffGrade;
use App\StafMainMastModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($staff_central_id)
    {
        $staffmain = StafMainMastModel::find($staff_central_id);
        $staff_grades = StaffGrade::with('grade')->orderBy('effective_from_date')->where('staff_central_id', $staff_central_id)->get();
        $grades = GradeModel::pluck('value', 'id');
        return view('staffmain.staffgrade', [
            'title' => 'Staff Grade',
            'staffmain' => $staffmain,
            'staff_grades' => $staff_grades,
            'grades' => $grades,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $staff_central_id)
    {
        $effective_from = BSDateHelper::BsToAd('-', $request->effective_from_date_np);

        //check if staff grade exists for the given date
        $checkStaffGradeExists = StaffGrade::where('effective_from_date', '<=', $effective_from)->where('effective_to_date', '>=', $effective_from)->where('staff_central_id',$staff_central_id)->exists();
        if ($checkStaffGradeExists) {
            $status = 'error';
            $mesg = 'Staff Grade Already Exists for the given date!';
            return redirect()->back()->withInput()->with('flash', array('status' => $status, 'mesg' => $mesg));
        }

        $staffGrade = new StaffGrade();
        $staffGrade->staff_central_id = $staff_central_id;
        $staffGrade->grade_id = $request->grade_id;
        $staffGrade->effective_from_date_np = $request->effective_from_date_np;
        $staffGrade->effective_from_date = $effective_from;
        $staffGrade->created_by = Auth::id();

        $staffPreviousGrade = StaffGrade::orderBy('effective_from_date', 'DESC')->whereDate('effective_from_date', '<', $staffGrade->effective_from_date)->where('staff_central_id',$staff_central_id)->first();
        if (!empty($staffPreviousGrade)) {
            $staffPreviousGrade->effective_to_date = date('Y-m-d', strtotime('-1 day', strtotime($staffGrade->effective_from_date)));
            $staffPreviousGrade->effective_to_date_np = BSDateHelper::AdToBs('-', $staffPreviousGrade->effective_to_date);
            $staffPreviousGrade->updated_by = Auth::id();
            $staffPreviousGrade->save();
        }

        $status_mesg = $staffGrade->save();

        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\StaffGrade $staffGrade
     * @return \Illuminate\Http\Response
     */
    public function show(StaffGrade $staffGrade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\StaffGrade $staffGrade
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*$staffGrade = StaffGrade::find($id);
        $staffmain = StafMainMastModel::find($staffGrade->staff_central_id);
        $staff_grades = StaffGrade::with('grade')->orderBy('effective_from_date')->where('staff_central_id', $staffGrade->staff_central_id)->get();
        $grades = GradeModel::pluck('value', 'id');
        return view('staffmain.staffgrade-edit', [
            'title' => 'Staff Grade Edit',
            'staffmain' => $staffmain,
            'staff_grades' => $staff_grades,
            'grades' => $grades,
            'staff_grade' => $staffGrade
        ]);*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\StaffGrade $staffGrade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*$effective_from = BSDateHelper::BsToAd('-', $request->effective_from_date_np);

        $staffGrade = StaffGrade::find($id);


        $staffPreviousGrade = StaffGrade::orderBy('effective_from_date', 'DESC')->whereDate('effective_from_date', '<', $staffGrade->effective_from_date)->first();
        if (!empty($staffPreviousGrade)) {
            $staffPreviousGrade->effective_to_date = date('Y-m-d', strtotime('-1 day', strtotime($effective_from)));
            $staffPreviousGrade->effective_to_date_np = BSDateHelper::AdToBs('-', $staffPreviousGrade->effective_to_date);
            $staffPreviousGrade->save();
        }

        $staffGrade->grade_id = $request->grade_id;
        $staffGrade->effective_from_date_np = $request->effective_from_date_np;
        $staffGrade->effective_from_date = $effective_from;
        $staffGrade->created_by = Auth::id();
        $status_mesg = $staffGrade->save();

        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('staff-grade', $staffGrade->staff_central_id)->with('flash', array('status' => $status, 'mesg' => $mesg));*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\StaffGrade $staffGrade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            $id = $request->id;
            $staffGrade = StaffGrade::find($id);
            $staffPreviousGrade = StaffGrade::orderBy('effective_from_date', 'DESC')->whereDate('effective_from_date', '<', $staffGrade->effective_from_date)->where('staff_central_id',$staffGrade->staff_central_id)->first();
            if (!empty($staffPreviousGrade)) {
                $staffPreviousGrade->effective_to_date = null;
                $staffPreviousGrade->effective_to_date_np = null;
                $staffPreviousGrade->save();
            }
            $staffGrade->deleted_by = Auth::id();
            $staffGrade->save();
            $success = $staffGrade->delete();
            if ($success) {
                echo 'Successfully Deleted';
            } else {
                echo "Error deleting!";
            }
        } else {
            echo "Error deleting!";
        }
    }

}
