<?php

namespace App\Repositories;

use App\FiscalYearModel;
use App\LeaveRequest;
use App\LeaveRequestFile;
use App\StafMainMastModel;
use Illuminate\Support\Facades\DB;


class LeaveRequestRepository
{
    private $calenderHolidayRepository;

    public function __construct(CalenderHolidayRepository $calenderHolidayRepository)
    {
        $this->calenderHolidayRepository = $calenderHolidayRepository;
    }

    public function save($inputs)
    {
        $leaveRequest = new LeaveRequest();

        $leaveRequest['staff_central_id'] = $inputs['staff_central_id'];
        $leaveRequest['leave_id'] = $inputs['leave_id'];
        $leaveRequest['fy_id'] = FiscalYearModel::isActiveFiscalYear()->first()->id;
        $leaveRequest['authorized_by'] = auth()->id();
        $leaveRequest['leave_balance'] = $inputs['leave_balance'];
        $leaveRequest['description'] = $inputs['description'];
        $leaveRequest['from_leave_day_np'] = $inputs['from_leave_day_np'];
        $leaveRequest['from_leave_day'] = $inputs['from_leave_day'];
        $leaveRequest['to_leave_day_np'] = $inputs['to_leave_day_np'];
        $leaveRequest['to_leave_day'] = $inputs['to_leave_day'];
        $leaveRequest['public_holidays'] = $inputs['public_holidays'];
        $leaveRequest['weekend_days'] = $inputs['weekend_days'];
        $leaveRequest['public_weekend'] = $inputs['public_weekend'];
        $leaveRequest['holiday_days'] = $inputs['holiday_days'];
        $leaveRequest['status'] = 0;

        $status = $leaveRequest->save();

        if ($status) {
            if (!empty($inputs['upload']) && isset($inputs['upload'])) {
                foreach ($inputs['upload'] as $staff_file_id) {
                    $training_detail_file = new LeaveRequestFile();
                    $training_detail_file->leave_request_id = $leaveRequest->id;
                    $training_detail_file->staff_file_id = $staff_file_id;
                    $training_detail_file->save();
                }
            }
        }


        return $status;
    }

    public function retrieveLeaveStatus()
    {
        if (AuthRepository::isAdministrator()) {
            return config('constants.leave_request_status.approved');
        } else {
            return config('constants.leave_request_status.not_approved');
        }
    }

    public function setApprove($id)
    {
        try {
            DB::beginTransaction();
            $leaveRequest = LeaveRequest::find($id);

            $leaveRequest->status = config('constants.leave_request_status.approved');
            $leaveRequest->authorized_by = auth()->id();

            $status = $leaveRequest->save();

            $inputs = [];

            $inputs['staff_central_id'] = $leaveRequest->staff_central_id;
            $inputs['leave_id'] = $leaveRequest->leave_id;
            $inputs['from_leave_day_np'] = $leaveRequest->from_leave_day_np;
            $inputs['from_leave_day'] = $leaveRequest->from_leave_day;
            $inputs['to_leave_day_np'] = $leaveRequest->to_leave_day_np;
            $inputs['to_leave_day'] = $leaveRequest->to_leave_day;
            $inputs['holiday_days'] = $leaveRequest->holiday_days;
            $inputs['leave_balance'] = $leaveRequest->leave_balance;
            $inputs['upload'] = $leaveRequest->leaveRequestFiles->toArray();
            $status_mesg = $this->calenderHolidayRepository->save($inputs);
        } catch (\Exception $e) {
            DB::rollBack();
        }
        if ($status_mesg) {
            DB::commit();
        }
        return $status_mesg;
    }

    public function setReject($id)
    {
        $leaveRequest = LeaveRequest::find($id);

        $leaveRequest->status = config('constants.leave_request_status.rejected');
        $leaveRequest->authorized_by = auth()->id();

        $status = $leaveRequest->save();

        return $status;
    }

    public function isInsufficientDays()
    {
        return request()->insufficient_days_status;
    }

    public function retrieveStaffIdBasedOnRole()
    {
        if (AuthRepository::isAdministrator()) {
            $staff_extract = StafMainMastModel::pluck('id')->toArray();
        } else {
            $staff_extract[] = auth()->user()->staff_central_id;
        }
        return $staff_extract;
    }

    public function retrieveStaffsBasedOnRole()
    {
        if (AuthRepository::isAdministrator()) {
            $staff_extract = StafMainMastModel::select('id', 'name_eng')->get();
        } else {
            $staff_extract = StafMainMastModel::where('id', auth()->user()->staff_central_id)->select('id', 'name_eng')->get();
        }
        return $staff_extract;
    }


}
