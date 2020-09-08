<?php

namespace App\Http\Controllers;

use App\MaxHomeSickLeave;
use Illuminate\Http\Request;

class MaxHomeSickLeaveController extends Controller
{
    public function index()
    {
        $max_leaves = MaxHomeSickLeave::first();
        if (empty($max_leaves)) {
            $max_leaves = new MaxHomeSickLeave();
            $max_leaves->max_home_leave = 0;
            $max_leaves->max_sick_leave = 0;
            $max_leaves->save();
        }
        return view('maxhomesickleave.index', [
            'title' => 'Max Home / Sick Leave',
            'max_leaves' => $max_leaves,

        ]);
    }

    public function update(Request $request)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'max_home_leave' => 'required',
            'max_sick_leave' => 'required',
        ],
            [
                'max_home_leave.required' => 'You must enter the Max Home Leave!',
                'max_home_sick.required' => 'You must enter the Max Sick Leave!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('staff-main-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            $max_leaves = MaxHomeSickLeave::first();
            $max_leaves->max_home_leave = $request->max_home_leave;
            $max_leaves->max_sick_leave = $request->max_sick_leave;
            if ($max_leaves->save()) {
                $status_mesg = true;
            }
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('max-home-sick-leave-index')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }
}
