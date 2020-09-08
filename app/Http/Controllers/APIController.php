<?php

namespace App\Http\Controllers;

use App\AppVersion;
use App\FetchAttendance;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\Helpers\MenuHelper;
use App\Helpers\SyncHelper;
use App\Permission;
use App\PermissionRole;
use App\RoleUser;
use App\Shift;
use App\StaffFingerprint;
use App\StaffTransferModel;
use App\StafMainMastModel;
use App\SyncRecord;
use App\SystemHolidayMastModel;
use App\SystemOfficeMastModel;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class APIController extends Controller
{

    public function __construct(Request $request)
    {
        /* if (md5('secret') != $request->md5) {
             dd('Unauthorized!');
         }*/
    }

    public function fetchAttendance(Request $request)
    {
        $status_mesg = false;
        $datas = json_decode($request->getContent());
        $response_data = array();
        try {
            DB::beginTransaction();
            foreach ($datas as $data) {
                $fetch_attendance = null;

                if (!empty($data->server_id)) {
                    $fetch_attendance = FetchAttendance::where('id', (int)$data->server_id)->orWhereDate('punchin_datetime', $data->punchinDatetimeDB)->first();
                }
                if (empty($fetch_attendance)) { //create new record
                    $fetch_attendance = new FetchAttendance();
                }
                $fetch_attendance->staff_central_id = (int)$data->staffCentralID;
                $fetch_attendance->branch_id = (int)$data->branch_id;
                $fetch_attendance->shift_id = (int)$data->shift_id;
                $fetch_attendance->punchin_datetime = $data->punchinDatetimeDB;
                $fetch_attendance->punchin_datetime_np = $data->punchinDatetimeNP;
                $fetch_attendance->punchout_datetime_np = $data->punchoutDatetimeNP;
                $fetch_attendance->punchout_datetime = $data->punchoutDatetimeDB;
                $fetch_attendance->tiffinin_datetime_np = $data->tiffininDatetimeNP;
                $fetch_attendance->tiffinin_datetime = $data->tiffininDatetimeDB;
                $fetch_attendance->tiffinout_datetime_np = $data->tiffinoutDatetimeNP;
                $fetch_attendance->tiffinout_datetime = $data->tiffinoutDatetimeDB;
                $fetch_attendance->lunchin_datetime_np = $data->lunchinDatetimeNP;
                $fetch_attendance->lunchin_datetime = $data->lunchinDatetimeDB;
                $fetch_attendance->lunchout_datetime_np = $data->lunchoutDatetimeNP;
                $fetch_attendance->lunchout_datetime = $data->lunchoutDatetimeDB;
                $fetch_attendance->personalin_datetime_np = $data->personalinDatetimeNP;
                $fetch_attendance->personalin_datetime = $data->personalinDatetimeDB;
                $fetch_attendance->personalout_datetime_np = $data->personaloutDatetimeNP;
                $fetch_attendance->personalout_datetime = $data->personaloutDatetimeDB;
                $fetch_attendance->total_work_hour = $data->totalWorkHour;
                $fetch_attendance->status = $data->status;

                if ($fetch_attendance->save()) {
                    $r_data = new \stdClass();
                    $r_data->id = $data->ID;
                    $r_data->server_id = $fetch_attendance->id;
                    $response_data[] = $r_data;
                    $status_mesg = true;
                }
            }
        } catch (\Exception $e) {
            $status_mesg = false;
            unset($response_data);
            $response_data = [];
            DB::rollback();
        }
        if ($status_mesg) {
            DB::commit();
        }
        return response()->json(['response_data' => $response_data, 'status' => $status_mesg]);
    }

    public function getStaffsByBranchID($id)
    {
        $staffs = \App\StafMainMastModel::where('branch_id', $id)->get();
        return response()->json($staffs);
    }

    public function getShiftsByBranchID($id)
    {
        $shifts = Shift::where('branch_id', $id)->get();
        return response()->json($shifts);
    }

    public function getAttendance(Request $request)
    {
        $fetchAttendances = null;
        $status_mesg = false;
        if ($request->has('branch_id') && !empty($request->branch_id)) {
            $fetchAttendances = FetchAttendance::where('sync', 1)->where('branch_id', $request->branch_id)->get();
            $status_mesg = true;
            $sync_record = new SyncRecord();
            $sync_record->sync_time = date('Y-m-d H:i:s');
            $sync_record->branch_id = $request->branch_id;
            $sync_record->save();
        }

        return response()->json(['response_data' => $fetchAttendances, 'status' => $status_mesg]);
    }

    public function updateGetAttendance(Request $request)
    {
        $status_mesg = false;
        $datas = json_decode($request->getContent());
        $response_data = array();
        try {
            foreach ($datas as $data) {
                $fetch_attendance = null;
                if (!empty($data->server_id)) {
                    $fetch_attendance = FetchAttendance::find((int)$data->server_id);
                    $fetch_attendance->sync = 0;
                    if ($fetch_attendance->save()) {
                        $r_data = new \stdClass();
                        $r_data->id = $data->ID;
                        $r_data->server_id = $fetch_attendance->id;
                        $response_data[] = $r_data;
                        $status_mesg = true;
                    }
                }
            }
        } catch (\Exception $e) {
            $status_mesg = false;
            unset($response_data);
            $response_data = [];
            DB::rollback();
        }
        if ($status_mesg) {
            DB::commit();
        }
        return response()->json(['response_data' => $response_data, 'status' => $status_mesg]);
    }

    public function saveStaffFingerprint(Request $request)
    {
        $status_mesg = false;
        $datas = json_decode($request->getContent());
        $response_data = array();
        try {
            DB::beginTransaction();
            foreach ($datas as $data) {
                $staff_fingerprint = null;
                if (empty($data->server_id)) { //create new record
                    $staff_fingerprint = new StaffFingerprint();
                } else { //model from existing id
                    $staff_fingerprint = StaffFingerprint::find((int)$data->server_id);
                }
                $staff_fingerprint->staff_central_id = (int)$data->staffCentralID;
                $staff_fingerprint->status = $data->status;
                $staff_fingerprint->sync = 0;
                $staff_fingerprint->fingerprint = $data->fingerPrint;
                $staff_fingerprint->fingerprint2 = $data->fingerPrint2;
                $staff_fingerprint->branch_id = (int)$data->branch_id;
                $staff_fingerprint->created_at = Carbon::parse($data->createdAt);
                $staff_fingerprint->updated_at = Carbon::parse($data->updatedAt);

                if ($staff_fingerprint->save()) {
                    $r_data = new \stdClass();
                    $r_data->id = $data->ID;
                    $r_data->server_id = $staff_fingerprint->id;
                    $response_data[] = $r_data;
                    $status_mesg = true;
                }
            }
        } catch (\Exception $e) {
            $status_mesg = false;
            unset($response_data);
            $response_data = [];
            DB::rollback();
        }
        if ($status_mesg) {
            DB::commit();
        }
        return response()->json(['response_data' => $response_data, 'status' => $status_mesg]);
    }

    public function getStaffFingerprint(Request $request)
    {
        $status = false;
        $fingerprints = null;
        if ($request->has('branch_id') && !empty($request->branch_id)) {
            $fingerprints = StaffFingerprint::sync()
                ->where('branch_id', $request->branch_id)
                ->get();
            $status = true;
        }

        return response()->json([
            'response_data' => $fingerprints,
            'status' => $status
        ]);
    }

    public function updateStaffFingerprint(Request $request)
    {
        $status_mesg = false;
        $datas = json_decode($request->getContent());
        $response_data = array();

        try {
            foreach ($datas as $data) {
                $localAttendance = null;

                if (!empty($data->server_id)) {

                    $staffFingerprint = StaffFingerprint::find((int)$data->server_id);
                    $staffFingerprint->sync = 0;

                    if ($staffFingerprint->save()) {

                        $r_data = new \stdClass();
                        $r_data->id = $data->ID;
                        $r_data->server_id = $staffFingerprint->id;
                        $response_data[] = $r_data;
                        $status_mesg = true;
                    }
                }
            }
        } catch (\Exception $e) {
            $status_mesg = false;
            unset($response_data);
            $response_data = [];
            DB::rollback();
        }
        if ($status_mesg) {
            DB::commit();
        }
        return response()->json(['response_data' => $response_data, 'status' => $status_mesg]);
    }

    public function getDeletedLocalAttendance(Request $request)
    {
        $status = false;

        $deletedLocalAttendances = null;

        if ($request->has('branch_id') && !empty($request->branch_id)) {
            $deletedLocalAttendances = FetchAttendance::onlyTrashed()->select('id', 'staff_central_id')
                ->where('sync', FetchAttendance::sync)
                ->where('branch_id', $request->branch_id)
                ->get();
            $status = true;
        }

        return response()->json([
            'response_data' => $deletedLocalAttendances,
            'status' => $status
        ]);

    }

    public function updatedDeletedLocalAttendance(Request $request)
    {
        $status_mesg = false;
        $datas = json_decode($request->getContent());
        $response_data = array();
        try {
            foreach ($datas as $data) {
                $fetch_attendance = null;
                if (!empty($data->server_id)) {
                    $fetch_attendance = FetchAttendance::onlyTrashed()->find((int)$data->server_id);
                    $fetch_attendance->sync = 0;
                    if ($fetch_attendance->save()) {
                        $r_data = new \stdClass();
                        $r_data->id = $data->ID;
                        $r_data->server_id = $fetch_attendance->id;
                        $response_data[] = $r_data;
                        $status_mesg = true;
                    }
                }
            }
        } catch (\Exception $e) {
            $status_mesg = false;
            unset($response_data);
            $response_data = [];
            DB::rollback();
        }
        if ($status_mesg) {
            DB::commit();
        }
        return response()->json(['response_data' => $response_data, 'status' => $status_mesg]);
    }

    public function getBranchUsers($branch_id)
    {
        $users = DB::table('users')->where('branch_id', $branch_id)->get();
        return response()->json($users);
    }

    public function getFiscalYear()
    {
        $fiscal_years = DB::table('fiscal_year')->get();
        return response()->json($fiscal_years);
    }

    public function attendanceServerGetUnsyncedAttendance(Request $request)
    {
        $fetchAttendances = '';
        $status_mesg = false;
        $message = 'Branch Id not found';
        if ($request->has('branch_id') && !empty($request->branch_id)) {
            $fetchAttendances = FetchAttendance::where('sync', 1)->where('branch_id', $request->branch_id)->get();
            $status_mesg = true;
            $message = 'Retrieved successfully';
        }
        return response()->json(['response_data' => $fetchAttendances, 'status' => $status_mesg]);
    }

    public function attendanceServerUpdateSyncStatus(Request $request)
    {
        $status_mesg = false;
        $datas = json_decode($request->getContent());
        $response_data = array();
        $message = null;
        if (empty($datas) || count($datas) < 1) {
            return response()->json(['response_data' => [], 'status' => $status_mesg, 'message' => 'No Unsync data']);
        }
        try {
            DB::beginTransaction();
            foreach ($datas as $data) {
                $fetch_attendance = null;
                if (!empty($data->server_id)) {
                    $fetch_attendance = FetchAttendance::find((int)$data->server_id);
                    $fetch_attendance->sync = 0;
                    if ($fetch_attendance->save()) {
                        $r_data = new \stdClass();
                        $r_data->id = $data->id;
                        $r_data->server_id = $fetch_attendance->id;
                        $response_data[] = $r_data;
                    }
                }
            }
            $status_mesg = true;
        } catch (\Exception $e) {
            $status_mesg = false;
            $message = 'Error occurred in the server';
            unset($response_data);
            $response_data = [];
            DB::rollback();
        }
        if ($status_mesg) {
            $message = 'Fetch Attendance sync status changed successfully';
            DB::commit();
        }
        return response()->json(['response_data' => $response_data, 'status' => $status_mesg, 'message' => $message]);
    }

    public function attendanceServerUpdateAttendances(Request $request)
    {

        $status_mesg = false;
        $message = null;
        $unsyncedDatas = json_decode($request->getContent());

        if (count($unsyncedDatas) < 1) {
            return response()->json(['response_data' => [], 'status' => $status_mesg, 'message' => 'No Unsync data']);
        }

        $response_data = array();
        $branch_id = $unsyncedDatas[0]->branch_id;
        $branch = SystemOfficeMastModel::find($branch_id);
        if ($branch->sync_lock_attendance == 1) {
            return response()->json(['response_data' => [], 'status' => $status_mesg, 'message' => 'Previous Sync Operation Still Running']);
        } else {
            $branch->sync_lock_attendance = 1;
            $branch->save();
        }
        try {
            DB::beginTransaction();

            foreach ($unsyncedDatas as $unsyncedData) {
                $fetch_attendance = null;

                $fetch_attendance = FetchAttendance::where('id', (int)$unsyncedData->server_id)->orWhere(function ($query) use ($unsyncedData) {
                    $query->where('staff_central_id', $unsyncedData->staff_central_id)->whereDate('punchin_datetime', date('Y-m-d', strtotime($unsyncedData->punchin_datetime)))->first();
                })->first();

                if (empty($fetch_attendance)) { //create new record
                    $fetch_attendance = new FetchAttendance();
                }

                $fetch_attendance->staff_central_id = (int)$unsyncedData->staff_central_id;
                $fetch_attendance->branch_id = (int)$unsyncedData->branch_id;
                $fetch_attendance->shift_id = (int)$unsyncedData->shift_id;
                $fetch_attendance->punchin_datetime = $unsyncedData->punchin_datetime;
                $fetch_attendance->punchin_datetime_np = $unsyncedData->punchin_datetime_np;
                $fetch_attendance->punchout_datetime = $unsyncedData->punchout_datetime;
                $fetch_attendance->punchout_datetime_np = $unsyncedData->punchout_datetime_np;
                $fetch_attendance->tiffinin_datetime_np = $unsyncedData->tiffinin_datetime_np;
                $fetch_attendance->tiffinin_datetime = $unsyncedData->tiffinin_datetime;
                $fetch_attendance->tiffinout_datetime_np = $unsyncedData->tiffinout_datetime_np;
                $fetch_attendance->tiffinout_datetime = $unsyncedData->tiffinout_datetime;
                $fetch_attendance->lunchin_datetime_np = $unsyncedData->lunchin_datetime_np;
                $fetch_attendance->lunchin_datetime = $unsyncedData->lunchin_datetime;
                $fetch_attendance->lunchout_datetime_np = $unsyncedData->lunchout_datetime_np;
                $fetch_attendance->lunchout_datetime = $unsyncedData->lunchout_datetime;
                $fetch_attendance->personalin_datetime_np = $unsyncedData->personalin_datetime_np;
                $fetch_attendance->personalin_datetime = $unsyncedData->personalin_datetime;
                $fetch_attendance->personalout_datetime_np = $unsyncedData->personalout_datetime_np;
                $fetch_attendance->personalout_datetime = $unsyncedData->personalout_datetime;
                $fetch_attendance->total_work_hour = $unsyncedData->total_work_hour;
                $fetch_attendance->status = $unsyncedData->status;
                $fetch_attendance->is_force = $unsyncedData->is_force;
                $fetch_attendance->sync = FetchAttendance::unSync;
                $fetch_attendance->created_by = $unsyncedData->created_by;
                $fetch_attendance->updated_by = $unsyncedData->updated_by;
                $fetch_attendance->remarks = $unsyncedData->remarks;

                if ($fetch_attendance->save()) {
                    $r_data = new \stdClass();
                    $r_data->server_id = $fetch_attendance->id;
                    $r_data->local_id = $unsyncedData->id;
                    $response_data[] = $r_data;
                    $status_mesg = true;
                }
            }
        } catch (\Exception $e) {
            $message = $e->getMessage() . ' ' . $e->getLine();
            $status_mesg = false;
            unset($response_data);
            $response_data = [];
            DB::rollback();
        }
        if ($status_mesg) {
            $message = "Updated successfully in server";
            DB::commit();
        }
        $branch->sync_lock_attendance = 0;
        $branch->save();
        return response()->json(['response_data' => $response_data, 'status' => $status_mesg, 'message' => $message]);
    }

    public function serverGetUnsyncedShift(Request $request)
    {
        $shifts = null;
        $status = false;
        $message = 'No Shifts Found';
        if ($request->has('branch_id') && !empty($request->branch_id)) {
            $shifts = Shift::query()->where('sync', 1)->where('branch_id', $request->branch_id)->get();
            if ($shifts->count() < 1) {
                return response()->json(['response_data' => $shifts, 'status' => $status, 'message' => $message]);
            }

            $message = 'Shift retrieved successfully';
            $status = true;
        }
        return response()->json(['response_data' => $shifts, 'status' => $status, 'message' => $message]);
    }


    public function serverUpdateSyncStatusShift(Request $request)
    {
        $savedShifts = null;
        //DB commit is dependent upon the status in attendance local. Hence even if there are no data, the status is set to true.
        $status = true;
        $message = 'No Shifts Found';

        $unsyncedDatas = json_decode($request->getContent());

        if (count($unsyncedDatas) < 1) {
            return response()->json(['response_data' => [], 'status' => $status, 'message' => 'No Unsync data']);
        }

        try {
            DB::beginTransaction();
            foreach ($unsyncedDatas as $unsyncedData) {
                if (!empty($unsyncedData->shift_id)) {
                    $shift = Shift::where('id', $unsyncedData->shift_id)->first();
                    if (!empty($shift)) {
                        $shift->sync = SyncHelper::unSync;
                        $shift->save();
                    }
                }
            }

            DB::commit();

            $status = true;
            $message = 'Shift synced completed';
        } catch (\Exception $exception) {
            $status = false;
            $message = 'Error occurred!';
            DB::rollback();
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function serverGetUnsyncedStaff(Request $request)
    {
        $staffs = null;
        $status = false;
        $message = 'No Staffs Found';
        if ($request->has('branch_id') && !empty($request->branch_id)) {
            $message = 'No branch found';
            $staffs = StafMainMastModel::query()->with(['fingerprint', 'latestWorkSchedule' => function ($query) {
                $query->select('max_work_hour', 'staff_central_id', 'weekend_day');
            }])->where('sync', 1)
                ->where('branch_id', $request->branch_id)
                ->get();
            if ($staffs->count() < 1) {
                return response()->json(['response_data' => $staffs, 'status' => $status, 'message' => $message]);
            }

            $message = 'Staffs retrieved successfully';
            $status = true;
        } else {
            return response()->json([
                'response_data' => [],
                'status' => $status,
                'message' => 'No branch found'
            ]);
        }
        return response()->json(['response_data' => $staffs, 'status' => $status, 'message' => $message]);
    }

    public function serverAddSyncStatusStaff(Request $request)
    {
        $savedStaffs = null;
        $status = false;
        $message = 'No branch id found';
        $responseData = [];
        $branch_id = null;

        if (empty($request->branch_id)) {
            return response()->json([
                'response_data' => [],
                'status' => $status,
                'message' => 'No branch found'
            ]);
        }

        $branch_id = $request->branch_id;

        $unsyncedStaffs = $request->staffs;

        if (empty($unsyncedStaffs) || count($unsyncedStaffs) < 1) {
            $message = 'No Staffs Found';
            return response()->json([
                'response_data' => $responseData,
                'status' => $status,
                'message' => $message
            ]);
        }
        try {
            DB::beginTransaction();
            foreach ($unsyncedStaffs as $unsyncedStaff) {
                $staff = StafMainMastModel::where('main_id', $unsyncedStaff['main_id'])->where('branch_id', $branch_id)->first();
                if (empty($staff)) {
                    $staff = new StafMainMastModel();
                    $staff->main_id = $unsyncedStaff['main_id'];
                    $staff->branch_id = $branch_id;
                }
                $staff->payroll_branch_id = $branch_id;
                $staff->name_eng = $unsyncedStaff['fullname'];
                $staff->shift_id = $unsyncedStaff['shift'];
                //Saleskeeper by default
                $staff->post_id = 2;

                if ($staff->save()) {
                    $r_data = new \stdClass();
                    $r_data->server_id = $staff->id;
                    $r_data->local_id = $unsyncedStaff['id'];
                    $responseData[] = $r_data;
                    $status = true;
                }
            }
            DB::commit();
            $message = 'Synced Successfully';
        } catch (\Exception $exception) {
            DB::rollback();
            $responseData = [];
            $status = false;
            $message = 'Error occurred!';
        }

        return response()->json([
            'response_data' => $responseData,
            'status' => $status,
            'message' => $message
        ]);
    }

    public function serverUpdateSyncStatusStaff(Request $request)
    {
        $savedStaffs = null;
        //DB commit is dependent upon the status in attendance local. Hence even if there are no data, the status is set to true.
        $status = true;
        $message = 'No Staffs Found';

        $unsyncedDatas = json_decode($request->getContent());
        if (empty($unsyncedDatas) || count($unsyncedDatas) < 1) {
            return response()->json(['response_data' => [], 'status' => $status, 'message' => 'No Unsync data']);
        }

        try {
            DB::beginTransaction();
            foreach ($unsyncedDatas as $unsyncedData) {
                if (!empty($unsyncedData->staff_id)) {
                    $staff = StafMainMastModel::where('id', $unsyncedData->staff_id)->first();
                    if (!empty($staff)) {
                        $staff->sync = SyncHelper::unSync;
                        $staff->save();
                    }
                }
            }

            DB::commit();

            $status = true;
            $message = 'Staff synced completed';
        } catch (\Exception $exception) {
            $status = false;
            $message = 'Error occurred!';
            DB::rollback();
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function serverGetUnsyncedTransferStaff(Request $request)
    {
        $staffTransfers = null;
        $status = false;
        $message = 'No Staff Transfer Found';
        $response_data = [];

        if (!$request->has('branch_id') || empty($request->branch_id)) {
            $message = 'No branch found';
            return response()->json([
                'response_data' => $response_data,
                'status' => $status,
                'message' => $message
            ]);
        }

        $staffTransfers = StaffTransferModel::query()->select(['transfer_id', 'staff_central_id', 'sync'])->orderBy('transfer_id', 'ASC')->where('sync', SyncHelper::sync)
            ->where('office_from', $request->branch_id)
            ->get();

        if ($staffTransfers->count() < 1) {
            $message = 'No Staff transfer found';
            return response()->json(['response_data' => $staffTransfers, 'status' => $status, 'message' => $message]);
        }

        $status = true;
        $message = 'Staff transfer retrieved successfully';
        return response()->json([
            'response_data' => $staffTransfers,
            'status' => $status,
            'message' => $message
        ]);
    }

    public function serverUpdateSyncStatusTransferStaff(Request $request)
    {
        $savedStaffs = null;
        //DB commit is dependent upon the status in attendance local. Hence even if there are no data, the status is set to true.
        $status = true;
        $message = 'No Staff Transfer Found';

        $unsyncedDatas = json_decode($request->getContent());

        if (count($unsyncedDatas) < 1) {
            return response()->json(['response_data' => [], 'status' => $status, 'message' => 'No Unsync data']);
        }

        try {
            DB::beginTransaction();
            foreach ($unsyncedDatas as $unsyncedData) {
                if (!empty($unsyncedData->transfer_id)) {
                    $staffTransfer = StaffTransferModel::where('transfer_id', $unsyncedData->transfer_id)->first();
                    if (!empty($staffTransfer)) {
                        $staffTransfer->sync = SyncHelper::unSync;
                        $staffTransfer->save();
                    }
                }
            }

            DB::commit();

            $status = true;
            $message = 'Staff Transfer synced completed';
        } catch (\Exception $exception) {
            $status = false;
            $message = 'Error occurred!';
            DB::rollback();
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function checkForUpdate(Request $request)
    {
        $versionCode = $request->version_code;

        $existingVersion = AppVersion::where('app_version_name', $versionCode)->first();
        $newVersion = AppVersion::latest()->first();

        if (empty($newVersion)) {
            return response()->json([
                'status' => false,
                'message' => 'No App Version Available!'
            ]);
        }

        if (empty($existingVersion)) {
            return response()->json([
                'status' => true,
                'version_code' => $newVersion->app_version_name,
                'version_filename' => $newVersion->path_name,
                'version_description' => $newVersion->description,
                'version_updated_at' => $newVersion->updated_at
            ]);
        }

        if ($existingVersion->id >= $newVersion->id) {
            return response()->json([
                'status' => false,
                'message' => 'The system is sync to the latest update!',
            ]);
        }

        return response()->json([
            'status' => true,
            'version_code' => $newVersion->app_version_name,
            'version_filename' => $newVersion->path_name,
            'version_description' => $newVersion->description,
            'version_updated_at' => $newVersion->updated_at
        ]);
    }

    public function downloadUpdate()
    {
        $appVersion = AppVersion::latest()->first();

        if (empty($appVersion))

            if (empty($appVersion->path_name)) {
                abort('403', 'This app version does not contain zip file');
            }

        if (!Storage::exists($appVersion->path_name)) {
            abort('403', 'File not found of this version');
        }

        return Storage::download($appVersion->path_name, $appVersion->app_version_name . ' Update');
    }

    public function attendanceServerUpdateFingerprint($branch_id, Request $request)
    {
        $status_mesg = false;
        $message = null;
        $unsyncedDatas = json_decode($request->getContent());

        if (count($unsyncedDatas) < 1) {
            return response()->json(['response_data' => [], 'status' => $status_mesg, 'message' => 'No Unsync data']);
        }

        $response_data = array();
        try {
            DB::beginTransaction();

            foreach ($unsyncedDatas as $unsyncedData) {
                $fingerprint = null;

                $fingerprint = StaffFingerprint::where('staff_central_id', (int)$unsyncedData->staff_central_id)->first();

                if (empty($fingerprint)) { //create new record
                    $fingerprint = new StaffFingerprint();
                }

                $fingerprint->staff_central_id = (int)$unsyncedData->staff_central_id;
                $fingerprint->fingerprint = $unsyncedData->fingerprint;
                $fingerprint->fingerprint_image = $unsyncedData->fingerprint_image;
                $fingerprint->fingerprint2 = $unsyncedData->fingerprint2;
                $fingerprint->fingerprint_image2 = $unsyncedData->fingerprint_image2;
                $fingerprint->branch_id = $branch_id;


                if ($fingerprint->save()) {
                    $r_data = new \stdClass();
                    $r_data->server_id = $fingerprint->id;
                    $r_data->local_id = $unsyncedData->id;
                    $response_data[] = $r_data;
                    $status_mesg = true;
                }
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $status_mesg = false;
            unset($response_data);
            $response_data = [];
            DB::rollback();
        }
        if ($status_mesg) {
            $message = "Updated successfully in server";
            DB::commit();
        }
        return response()->json(['response_data' => $response_data, 'status' => $status_mesg, 'message' => $message]);
    }

    public function getPublicHoliday(Request $request)
    {
        $public_holidays = SystemHolidayMastModel::query();
        $currentFiscalYear = FiscalYearModel::where('fiscal_status', 1)->first()->id ?? null;
        if (!empty($request->branch_id)) {
            $public_holidays = $public_holidays->whereHas('branch', function ($query) use ($request) {
                $query->where('branch_id', $request->branch_id);
            });
        }

        $public_holidays = $public_holidays->where('fy_year',$currentFiscalYear)->get();
        return response()->json(['response_data' => $public_holidays]);

    }

    public function getActions($userID){
        //get actions only by the permissions
        $user_roles = RoleUser::where('user_id', $userID)->pluck('role_id')->toArray();
        $per = PermissionRole::whereIn('role_id', $user_roles)->pluck('permission_id')->toArray();
        $allpermissions = Permission::whereIn('id', $per)->with("childPs")->orderBy('order', 'asc')->get();
        $permissions = $allpermissions->where('parent_id', 0);
        $user = User::find($userID);
        config(['role' => $user->hasRole('Administrator')]);

        //
        $actions = [];
        foreach($permissions as $permission){
            if(count($permission->childPs) > 0 && substr($permission->name, 0, 1) == "#"){
                //dont include the heading menu
                //['name'] = ($permission->name);
                //$action['title'] = $permission->display_name;
                //$actions[] = $action;
            }
            else{
                $action['title'] = $permission->display_name;
                $action['link'] = route($permission->name);
                $actions[] = $action;
            }

             if(count($permission->childPs)>0 && substr($permission->name, 0, 1) == "#"){

                if ($permission->id > 0) {
                    $childs = $allpermissions->where('parent_id', $permission->id)
                        ->sortBy('order');
                }

                foreach ($childs as $child){
                    //$action['title'] = $permission->display_name .$child->display_name;
                    $action['title'] = $child->display_name;
                    $action['link'] = route($child->name);
                    $actions[] = $action;
                }
            }
        }

        /*$actions = collect($actions);
        $search = 'dash';
        $actions = $actions->filter(function ($item) use ($search) {
            // replace stristr with your choice of matching function
            return false !== stristr($item['title'], $search);
        })->values()->all();;*/

        return response()->json($actions);
    }
}
