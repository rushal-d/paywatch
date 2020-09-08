<?php

namespace App\Http\Controllers;

use App\EmployeeStatus;
use App\Helpers\BSDateHelper;
use App\StafMainMastModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class EmployeeStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = Config::get('constants.employee_status');
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');

        $employee_status = EmployeeStatus::query();

        if (!empty($staff_central_id = $request->staff_central_id)) {
            $employee_status = $employee_status->where('staff_central_id', $staff_central_id);
        }

        if (!empty($date_from = $request->date_from)) {
            $employee_status = $employee_status->where('date_from', '>=', BSDateHelper::BsToAd('-', $date_from));
        }

        if (!empty($date_to = $request->date_to)) {
            $employee_status = $employee_status->where('date_to', '<=', BSDateHelper::BsToAd('-', $date_to));
        }

        if (isset($request->status_type)) {
            $employee_status = $employee_status->where('status', $request->status_type);
        }

        $employee_status = $employee_status->latest()->paginate($records_per_page);

        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('employeeStatus.index', [
            'title' => 'Staff Status',
            'employee_statuses' => $employee_status,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'status' => $status,
            'statusTypes' => config('constants.employee_status')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $staffs = StafMainMastModel::with('branch')->select('id', 'name_eng', 'main_id','branch_id','staff_central_id')->take(15)->get();
        $status = Config::get('constants.employee_status');
        return view('employeeStatus.create',
            [
                'title' => 'Add Employee Status',
                'status' => $status,
                'staffs' => $staffs,
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
            'staff_central_id' => 'required',
            'date_from_np' => 'required',
            'date_from' => 'required',
            'status' => 'required',
        ],
            [
                'staff_central_id.required' => 'You must enter the staff details!',
                'date_from_np.required' => 'You must enter the date from!',
                'date_from.required' => 'You must enter the date from!',
                'status.required' => 'You must enter the type of status!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('staff-status-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $staff = StafMainMastModel::find($request->staff_central_id);

                if ($staff->staff_status == 1 && $request->status == 0) {
                    return redirect()->back()->withInput()->withErrors(['The staff ' . $staff->name_eng . ' is already in working status.']);
                }

                $previousEmployeeStatus = EmployeeStatus::where('staff_central_id', $request->staff_central_id)->latest()->first();

                if (!empty($previousEmployeeStatus) && $previousEmployeeStatus->status != EmployeeStatus::STATUS_WORKING && $previousEmployeeStatus->date_from < $request->date_from) {
                    $oneDayBefore = date('Y-m-d', strtotime('-1 day', strtotime($request->date_from)));
                    $previousEmployeeStatus->date_to = $oneDayBefore;
                    $previousEmployeeStatus->date_to_np = BSDateHelper::AdToBs('-', $oneDayBefore);
                    $previousEmployeeStatus->save();
                }

                $employee_status = new EmployeeStatus();
                $employee_status->staff_central_id = $request->staff_central_id;
                $employee_status->date_from_np = $request->date_from_np;
                $employee_status->date_from = $request->date_from;
                $employee_status->date_to_np = $request->date_to_np;
                $employee_status->date_to = !empty($request->date_to_np) ? BSDateHelper::BsToAd('-', $request->date_to_np) : null;
                $employee_status->status = $request->status;
                $employee_status->created_by = Auth::user()->id;

                if ($request->status != 0) { // if the staff is resign or fired deactivate the user .. the values are in constants
                    $staff->staff_status = 2;
                } else { //if the status is changed to working
                    $staff->staff_status = 1;
                }
                $staff->sync = StafMainMastModel::sync;
                $staff->save();
                if ($employee_status->save()) {
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
        return redirect()->route('staff-status-create')->with('flash', array('status' => $status, 'mesg' => $mesg));

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
        $employee_status = EmployeeStatus::findorfail($id);
        $staffs = StafMainMastModel::select('id', 'name_eng', 'main_id')->get();
        $status = Config::get('constants.employee_status');
        return view('employeeStatus.edit',
            [
                'title' => 'Add Employee Status',
                'status' => $status,
                'staffs' => $staffs,
                'employee_status' => $employee_status
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
            'staff_central_id' => 'required',
            'date_from_np' => 'required',
            'date_from' => 'required',
            'status' => 'required',
        ],
            [
                'staff_central_id.required' => 'You must enter the staff details!',
                'date_from_np.required' => 'You must enter the date from!',
                'date_from.required' => 'You must enter the date from!',
                'status.required' => 'You must enter the type of status!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('staff-status-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();

                $previousEmployeeStatus = EmployeeStatus::where('staff_central_id', $request->staff_central_id)->whereNull('date_to')->first();
                if (!empty($previousEmployeeStatus)) {
                    $oneDayBefore = date('Y-m-d', strtotime('-1 day', strtotime($request->date_from)));
                    $oneDayBefore = $oneDayBefore < $previousEmployeeStatus->date_from ? $previousEmployeeStatus->date_from : $oneDayBefore;
                    $previousEmployeeStatus->date_to = $oneDayBefore;

                    $previousEmployeeStatus->date_to_np = BSDateHelper::AdToBs('-', $oneDayBefore);
                    $previousEmployeeStatus->save();
                }

                $employee_status = EmployeeStatus::findorfail($id);
                $employee_status->staff_central_id = $request->staff_central_id;
                $employee_status->date_from_np = $request->date_from_np;
                $employee_status->date_from = $request->date_from;
                $employee_status->date_to = !empty($request->date_to_np) ? BSDateHelper::BsToAd('-', $request->date_to_np) : null;
                $employee_status->date_to_np = $request->date_to_np;
                $employee_status->status = $request->status;
                $employee_status->updated_by = Auth::user()->id;
                $staff = StafMainMastModel::find($request->staff_central_id);
                if ($request->status != 0) { // if the staff is resign or fired deactivate the user .. the values are in constants
                    $staff->staff_status = 2;
                } else { //if the status is changed to working or suspense
                    $staff->staff_status = 1;
                }
                $staff->sync = StafMainMastModel::sync;
                $staff->save();
                if ($employee_status->save()) {
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
        return redirect()->route('staff-status-edit', $id)->with('flash', array('status' => $status, 'mesg' => $mesg));

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
            $employeeStatus = EmployeeStatus::find($request->id);
            $employeeStatus->deleted_by = Auth::id();
            $employeeStatus->save();
            if ($employeeStatus->delete()) {
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
                        $employeeStatus = EmployeeStatus::find($id);
                        $employeeStatus->deleted_by = Auth::id();
                        $employeeStatus->save();
                        $employeeStatus->delete();
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
