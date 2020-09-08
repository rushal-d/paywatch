<?php

namespace App\Http\Controllers;

use App\Helpers\BSDateHelper;
use App\Helpers\SyncHelper;
use App\Shift;
use App\StaffShiftHistory;
use App\StaffTransferModel;
use App\StaffWorkScheduleMastModel;
use App\StafMainMastModel;
use App\SystemOfficeMastModel;
use App\SystemPostMastModel;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class StaffTransferMastController extends Controller
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
        //
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $staffTransfer = StaffTransferModel::withoutGlobalScopes()->with(['office' => function ($query) {
            $query->withoutGlobalScopes();
        }, 'office_from_get' => function ($query) {
            $query->withoutGlobalScopes();
        }, 'staff' => function ($query) {
            $query->withoutGlobalScopes();
        }])->where('office_id', '<>', null)->where(function ($query) {
            if (!Auth::user()->hasRole('Administrator')) {
                $query->where('office_from', Auth::user()->branch_id);
                $query->orWhere('office_id', Auth::user()->branch_id);
            }

        });

        if (!empty($from_branch = $request->from_branch)) {
            $staffTransfer = $staffTransfer->where('staff_transefer_mast.office_from', $from_branch);
        }

        if (!empty($staff_central_id = $request->staff_central_id)) {
            $staffTransfer = $staffTransfer->where('staff_transefer_mast.staff_central_id', $staff_central_id);
        }

        if (!empty($to_branch = $request->to_branch)) {
            $staffTransfer = $staffTransfer->where('staff_transefer_mast.office_id', $to_branch);
        }

        if (!empty($date_from = $request->date_from)) {
            $staffTransfer = $staffTransfer->where('staff_transefer_mast.from_date', '>=', BSDateHelper::BsToAd('-', $date_from));
        }

        if (!empty($date_to = $request->date_to)) {
            $staffTransfer = $staffTransfer->where('staff_transefer_mast.transfer_date', '<=', BSDateHelper::BsToAd('-', $date_to));
        }

        $staffTransfer = $staffTransfer->latest()->paginate($records_per_page);

        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');

        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('stafftransfer.index', [
            'title' => 'Staff Transfer',
            'stafftransfers' => $staffTransfer,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'branches' => $branches,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $offices = SystemOfficeMastModel::withoutGlobalScopes()->select('office_id', 'office_name')->get();
        $weekend_days = Config::get('constants.weekend_days');
        return view('stafftransfer.create',
            [
                'title' => 'Add Staff Transfer',
                'offices' => $offices,
                'weekend_days' => $weekend_days

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
        //
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'staff_central_id' => 'required',
            'office_id' => 'required',
            'main_id' => 'required',
            'from_date' => 'required'
        ],
            [
                'staff_central_id.required' => 'You must select the staff!',
                'office_id.required' => 'You must enter the Office Name!',
                'main_id.required' => 'You must enter the Branch Staff ID!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('staff-transfer-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                $staff = StafMainMastModel::withoutGlobalScopes()->where('id', $request->staff_central_id)->first();

                if (!$staff->exists()) {
                    return redirect()->route('staff-transfer-create')
                        ->withInput()
                        ->withErrors(['Staff does not exists']);
                }

                if ($staff->branch_id == $request->office_id) {
                    return redirect()->route('staff-transfer-create')
                        ->withInput()
                        ->withErrors(['Staff is already in the transfer branch.']);
                }

                $checkMainIdExists = StafMainMastModel::withoutGlobalScopes()->where('branch_id', $request->office_id)->where('main_id', $request->main_id)->exists();
                if ($checkMainIdExists) {
                    return redirect()->route('staff-transfer-create')
                        ->withInput()
                        ->withErrors(['Branch ID Already Exists in the transfer branch']);
                }

                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $previousStaffTransfer = StaffTransferModel::withoutGlobalScopes()->where('staff_central_id', $staff->id)->whereNull('office_id')->where('office_from', $staff->branch_id)
                    ->whereNull('transfer_date')->whereNull('transfer_date_np')->first();

                $oneDayPreviousOfTransferDate = date('Y-m-d', strtotime('-1 day', strtotime(BSDateHelper::BsToAd('-', $request->from_date))));
                if (!empty($previousStaffTransfer)) {
                    $previousStaffTransfer->update([
                        'office_from' => $staff->payroll_branch_id,
                        'office_id' => $request->office_id,
                        'transfer_date' => $oneDayPreviousOfTransferDate,
                        'transfer_date_np' => BSDateHelper::AdToBs('-', $oneDayPreviousOfTransferDate),
                        'sync' => 1,
                        'autho_id' => auth()->id()
                    ]);
                }
                $shift_id = null;
                if (!empty($request->shift_id)) {
                    $shift_id = $request->shift_id;
                } else {
                    $randomActiveShift = Shift::withoutGlobalScopes()->where('branch_id', $request->office_id)->where('active', 1)->first();
                    if (!empty($randomActiveShift)) {
                        $shift_id = $randomActiveShift->id;
                    }
                }

                if (!empty($shift_id)) {
                    $previous_staff_history = StaffShiftHistory::withoutGlobalScopes()->where('staff_central_id', $staff->id)->latest()->first();
                    if (!empty($previous_staff_history)) {
                        $previous_staff_history->effective_to = date('Y-m-d', strtotime('-1 day', strtotime(BSDateHelper::BsToAd('-', $request->from_date))));
                        $previous_staff_history->save();
                    }

                    $staff_shift_history = new StaffShiftHistory();
                    $staff_shift_history->staff_central_id = $staff->id;
                    $staff_shift_history->shift_id = $shift_id;
                    $staff_shift_history->effective_from = BSDateHelper::BsToAd('-', $request->from_date);
                    $staff_shift_history->save();

                    if (BSDateHelper::BsToAd('-', $request->from_date) == date('Y-m-d')) {
                        $staff->shift_id = $shift_id;
                    }
                } else {
                    $staff->shift_id = null;
                }

                $stafftransfer = new StaffTransferModel();
                $stafftransfer->from_date_np = $request->from_date;
                $stafftransfer->from_date = BSDateHelper::BsToAd('-', $request->from_date);

                $stafftransfer->staff_central_id = $request->staff_central_id;
                $stafftransfer->office_from = $request->office_id;
                $stafftransfer->autho_id = Auth::user()->id;

                if (!empty($request->weekend)) {
                    $previous_workschedule = StaffWorkScheduleMastModel::where('staff_central_id', $staff->id)->latest()->first();
                    if (!empty($previous_workschedule)) {
                        $staff_workschedule = new StaffWorkScheduleMastModel();
                        $staff_workschedule->staff_central_id = $staff->id;
                        $staff_workschedule->work_hour = $previous_workschedule->work_hour;
                        $staff_workschedule->max_work_hour = $previous_workschedule->max_work_hour;
                        $staff_workschedule->weekend_day = $request->weekend;
                        $staff_workschedule->effect_day = BSDateHelper::BsToAd('-', $request->from_date);
                        $staff_workschedule->effect_date_np = $request->from_date;
                        $staff_workschedule->work_status = $previous_workschedule->work_status;
                        $staff_workschedule->save();
                    }

                }
                //changes in staff master table
                $input['payroll_branch_id'] = $request->office_id;
                $input['branch_id'] = $request->office_id;
                $input['main_id'] = $request->main_id;
                $input['sync'] = 1;
                $staff->payroll_branch_id = $request->office_id;
                $staff->branch_id = $request->office_id;
                $staff->main_id = $request->main_id;
                $staff->sync = 1;
                $staff->save();

                $staffFingerprint = $staff->fingerprint;

                if (!empty($staffFingerprint)) {
                    $fingerPrintUpdateSstatus = $staffFingerprint->update([
                        'branch_id' => $request->office_id,
                        'sync' => SyncHelper::sync
                    ]);
                }

                if ($stafftransfer->save()) {
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
        return redirect()->route('staff-transfer-create')->with('flash', array('status' => $status, 'mesg' => $mesg));
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
        //edit disabled
        /*  $offices = SystemOfficeMastModel::select('office_id', 'office_name')->get();
          $staffs = StafMainMastModel::select('id', 'name_eng', 'main_id')->get();
          $stafftransfer = StaffTransferModel::find($id);
          return view('stafftransfer.edit', [
              'title' => 'Edit Staff Transfer',
              'offices' => $offices,
              'staffs' => $staffs,
              'stafftransfer' => $stafftransfer
          ]);*/
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
            'office_id' => 'required',
        ],
            [
                'office_id.required' => 'You must Select Office!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('staff-transfer-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info
                $stafftransfer = StaffTransferModel::find($id);
                $stafftransfer->from_date = $request->from_date;
                $stafftransfer->staff_central_id = $request->staff_central_id;
                $stafftransfer->transfer_date = $request->to_date;
                $stafftransfer->office_id = $request->office_id;
                $stafftransfer->created_by = Auth::user()->id;
                $stafftransfer->sync = SyncHelper::sync;

                $staff = StafMainMastModel::find($request->staff_central_id);
                $staff->branch_id = $request->office_id;
                $staff->payroll_branch_id = $request->office_id;
                $staff->main_id = $request->main_id;
                $staff->sync = StafMainMastModel::sync;
                $staff->save();
                if ($stafftransfer->save()) {
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
        return redirect()->route('staff-transfer-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

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
            $stafftransfer = StaffTransferModel::find($request->id);
            if ($stafftransfer->delete()) {
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
                        $stafftransfer = StaffTransferModel::find($id);
                        $stafftransfer->delete();
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
