<?php

namespace App\Repositories;

use App\FetchAttendance;
use App\Helpers\DateHelper;
use App\StafMainMastModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FetchAttendanceRepository extends BaseRepository
{
    public function save($inputs, $fetchAttendance)
    {
        $saveStatus = false;
        try {
            DB::beginTransaction();

            $fetchAttendance->branch_id = $inputs['branch_id'];
            $fetchAttendance->status = $inputs['status'];
            $fetchAttendance->sync = $inputs['sync'];
            $fetchAttendance->shift_id = $inputs['shift_id'];

            $fetchAttendance->total_work_hour = $inputs['total_work_hour'];

            $fetchAttendance->staff_central_id = $inputs['staff_central_id'];
            $fetchAttendance->punchin_datetime_np = $inputs['punchin_datetime_np'];
            $fetchAttendance->punchin_datetime = $inputs['punchin_datetime'];

            $fetchAttendance->punchout_datetime_np = $inputs['punchout_datetime_np'];
            $fetchAttendance->punchout_datetime = $inputs['punchout_datetime'];

            $fetchAttendance->tiffinin_datetime_np = $inputs['tiffinin_datetime_np'];
            $fetchAttendance->tiffinin_datetime = $inputs['tiffinin_datetime'];

            $fetchAttendance->tiffinout_datetime_np = $inputs['tiffinout_datetime_np'];
            $fetchAttendance->tiffinout_datetime = $inputs['tiffinout_datetime'];

            $fetchAttendance->personalin_datetime_np = $inputs['personalin_datetime_np'];
            $fetchAttendance->personalin_datetime = $inputs['personalin_datetime'];

            $fetchAttendance->personalout_datetime_np = $inputs['personalout_datetime_np'];
            $fetchAttendance->personalout_datetime = $inputs['personalout_datetime'];

            $fetchAttendance->lunchin_datetime_np = $inputs['lunchin_datetime_np'];
            $fetchAttendance->lunchin_datetime = $inputs['lunchin_datetime'];

            $fetchAttendance->lunchout_datetime_np = $inputs['lunchout_datetime_np'];
            $fetchAttendance->lunchout_datetime = $inputs['lunchout_datetime'];

            if ($fetchAttendance->save()) {
                $saveStatus = true;
            }

        } catch (\Exception $e) {
            DB::rollback();
            $saveStatus = false;
        }

        if ($saveStatus == true) {
            DB::commit();
        }

        return $saveStatus;
    }

    public function store($inputs)
    {
        $fetchAttendance = new FetchAttendance();

        $saveStatus = $this->save($inputs, $fetchAttendance);

        return $saveStatus;
    }

    public function update($inputs, $id)
    {
        $fetchAttendance = FetchAttendance::where('id', $id)->first();

        $saveStatus = $this->save($inputs, $fetchAttendance);

        return $saveStatus;
    }


    public function getStoreInputs($request)
    {
        $inputs = [];

        $staff = StafMainMastModel::where('id', $request->staff_central_id)->first();

        $inputs['punchin_datetime_np'] = $request->punchin_datetime_np ? $request->attendance_date_np . ' ' . $request->punchin_datetime_np . ':00' : null;
        $inputs['punchout_datetime_np'] = $request->punchout_datetime_np ? $request->attendance_date_np . ' ' . $request->punchout_datetime_np . ':00' : null;
        $inputs['tiffinin_datetime_np'] = $request->tiffinin_datetime_np ? $request->attendance_date_np . ' ' . $request->tiffinin_datetime_np . ':00' : null;
        $inputs['tiffinout_datetime_np'] = $request->tiffinout_datetime_np ? $request->attendance_date_np . ' ' . $request->tiffinout_datetime_np . ':00' : null;
        $inputs['personalin_datetime_np'] = $request->personalin_datetime_np ? $request->attendance_date_np . ' ' . $request->personalin_datetime_np . ':00' : null;
        $inputs['personalout_datetime_np'] = $request->personalout_datetime_np ? $request->attendance_date_np . ' ' . $request->personalout_datetime_np . ':00' : null;
        $inputs['lunchin_datetime_np'] = $request->lunchin_datetime_np ? $request->attendance_date_np . ' ' . $request->lunchin_datetime_np . ':00' : null;
        $inputs['lunchout_datetime_np'] = $request->lunchout_datetime_np ? $request->attendance_date_np . ' ' . $request->lunchout_datetime_np . ':00' : null;

        $inputs['punchin_datetime'] = $request->punchin_datetime_np ? $request->attendance_date_en . ' ' . $request->punchin_datetime_np . ':00' : null;
        $inputs['punchout_datetime'] = $request->punchout_datetime_np ? $request->attendance_date_en . ' ' . $request->punchout_datetime_np . ':00' : null;
        $inputs['tiffinin_datetime'] = $request->tiffinin_datetime_np ? $request->attendance_date_en . ' ' . $request->tiffinin_datetime_np . ':00' : null;
        $inputs['tiffinout_datetime'] = $request->tiffinout_datetime_np ? $request->attendance_date_en . ' ' . $request->tiffinout_datetime_np . ':00' : null;
        $inputs['personalin_datetime'] = $request->personalin_datetime_np ? $request->attendance_date_en . ' ' . $request->personalin_datetime_np . ':00' : null;
        $inputs['personalout_datetime'] = $request->personalout_datetime_np ? $request->attendance_date_en . ' ' . $request->personalout_datetime_np . ':00' : null;
        $inputs['lunchin_datetime'] = $request->lunchin_datetime_np ? $request->attendance_date_en . ' ' . $request->lunchin_datetime_np . ':00' : null;
        $inputs['lunchout_datetime'] = $request->lunchout_datetime_np ? $request->attendance_date_en . ' ' . $request->lunchout_datetime_np . ':00' : null;

        $inputs['status'] = FetchAttendance::forceLeave;
        $inputs['branch_id'] = $request->branch_id;
        $inputs['staff_central_id'] = $request->staff_central_id;
        $inputs['shift_id'] = $staff->shift_id;

        $inputs['sync'] = 1;

        $inputs['total_work_hour'] = 0;

        if ($this->validatePunchInOutDateTime($request)) {
            $punchInDate = Carbon::parse($inputs['punchin_datetime']);
            $punchOutDate = Carbon::parse($inputs['punchout_datetime']);

            $inputs['total_work_hour'] = $punchOutDate->diffInMinutes($punchInDate) / 60;
        }

        return $inputs;
    }

    /**
     * @param $request
     * @return bool
     */
    public function validatePunchInOutDateTime($request): bool
    {
        return $request->punchout_datetime_np ? true : false;
    }

    public function calculateTotalHoursWork($datesArray)
    {
        if (empty($datesArray['punchIn']) || empty($datesArray['punchOut'])) {
            return 0;
        }
        $punchOut = Carbon::parse($datesArray['punchOut']);
        $punchIn = Carbon::parse($datesArray['punchIn']);
        $totalWorkHour = $punchOut->diffInMinutes($punchIn) / 60;
        if (!empty($datesArray['personalOut']) && !empty($datesArray['personalIn'])) { //check if has personal in out
            $totalPersonalTakenTime = Carbon::parse($datesArray['personalOut'])->diffInMinutes(Carbon::parse($datesArray['personalIn'])) / 60;
            $totalWorkHour = $totalWorkHour - $totalPersonalTakenTime;
        }
        return $totalWorkHour;
    }

    public function getInputsForDateTimeFields($inputs)
    {
        $newInputs['punchin_datetime_np'] = isset($inputs['punchin_datetime_np']) ? $inputs['attendance_date_np'] . ' ' . $inputs['punchin_datetime_np'] . ':00' : null;
        $newInputs['punchout_datetime_np'] = isset($inputs['punchout_datetime_np']) ? $inputs['attendance_date_np'] . ' ' . $inputs['punchout_datetime_np'] . ':00' : null;
        $newInputs['tiffinin_datetime_np'] = isset($inputs['tiffinin_datetime_np']) ? $inputs['attendance_date_np'] . ' ' . $inputs['tiffinin_datetime_np'] . ':00' : null;
        $newInputs['tiffinout_datetime_np'] = isset($inputs['tiffinout_datetime_np']) ? $inputs['attendance_date_np'] . ' ' . $inputs['tiffinout_datetime_np'] . ':00' : null;
        $newInputs['personalin_datetime_np'] = isset($inputs['personalin_datetime_np']) ? $inputs['attendance_date_np'] . ' ' . $inputs['personalin_datetime_np'] . ':00' : null;
        $newInputs['personalout_datetime_np'] = isset($inputs['personalout_datetime_np']) ? $inputs['attendance_date_np'] . ' ' . $inputs['personalout_datetime_np'] . ':00' : null;
        $newInputs['lunchin_datetime_np'] = isset($inputs['lunchin_datetime_np']) ? $inputs['attendance_date_np'] . ' ' . $inputs['lunchin_datetime_np'] . ':00' : null;
        $newInputs['lunchout_datetime_np'] = isset($inputs['lunchout_datetime_np']) ? $inputs['attendance_date_np'] . ' ' . $inputs['lunchout_datetime_np'] . ':00' : null;

        $newInputs['punchin_datetime'] = isset($inputs['punchin_datetime_np']) ? $inputs['attendance_date_en'] . ' ' . $inputs['punchin_datetime_np'] . ':00' : null;
        $newInputs['punchout_datetime'] = isset($inputs['punchout_datetime_np']) ? $inputs['attendance_date_en'] . ' ' . $inputs['punchout_datetime_np'] . ':00' : null;
        $newInputs['tiffinin_datetime'] = isset($inputs['tiffinin_datetime_np']) ? $inputs['attendance_date_en'] . ' ' . $inputs['tiffinin_datetime_np'] . ':00' : null;
        $newInputs['tiffinout_datetime'] = isset($inputs['tiffinout_datetime_np']) ? $inputs['attendance_date_en'] . ' ' . $inputs['tiffinout_datetime_np'] . ':00' : null;
        $newInputs['personalin_datetime'] = isset($inputs['personalin_datetime_np']) ? $inputs['attendance_date_en'] . ' ' . $inputs['personalin_datetime_np'] . ':00' : null;
        $newInputs['personalout_datetime'] = isset($inputs['personalout_datetime_np']) ? $inputs['attendance_date_en'] . ' ' . $inputs['personalout_datetime_np'] . ':00' : null;
        $newInputs['lunchin_datetime'] = isset($inputs['lunchin_datetime_np']) ? $inputs['attendance_date_en'] . ' ' . $inputs['lunchin_datetime_np'] . ':00' : null;
        $newInputs['lunchout_datetime'] = isset($inputs['lunchout_datetime_np']) ? $inputs['attendance_date_en'] . ' ' . $inputs['lunchout_datetime_np'] . ':00' : null;

        return $newInputs;
    }

    public function hasPreviousFetchAttendance($currentStaffCentralId, $previousStaffCentralIdsArray)
    {
        if (in_array($currentStaffCentralId, $previousStaffCentralIdsArray)) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserAttendanceByAttendanceDateAndStaffCentralId($attendance_date, $staff_central_id)
    {
        $fetchAttendance = FetchAttendance::whereDate('punchin_datetime', $attendance_date)
            ->where('staff_central_id', $staff_central_id)
            ->first();

        if (empty($fetchAttendance)) {
            return [
                'punchin_datetime_np' => null,
                'tiffinout_datetime_np' => null,
                'tiffinin_datetime_np' => null,
                'personalout_datetime_np' => null,
                'personalin_datetime_np' => null,
                'lunchout_datetime_np' => null,
                'lunchin_datetime_np' => null,
                'punchout_datetime_np' => null,
                'remarks' => null,
            ];
        }

        return [
            'punchin_datetime_np' => DateHelper::toCustomDateFormat($fetchAttendance->punchin_datetime, 'H:i'),
            'tiffinout_datetime_np' => DateHelper::toCustomDateFormat($fetchAttendance->tiffinout_datetime, 'H:i'),
            'tiffinin_datetime_np' => DateHelper::toCustomDateFormat($fetchAttendance->tiffinin_datetime, 'H:i'),
            'personalout_datetime_np' => DateHelper::toCustomDateFormat($fetchAttendance->personalout_datetime, 'H:i'),
            'personalin_datetime_np' => DateHelper::toCustomDateFormat($fetchAttendance->personalin_datetime, 'H:i'),
            'lunchout_datetime_np' => DateHelper::toCustomDateFormat($fetchAttendance->lunchout_datetime, 'H:i'),
            'lunchin_datetime_np' => DateHelper::toCustomDateFormat($fetchAttendance->lunchin_datetime, 'H:i'),
            'punchout_datetime_np' => DateHelper::toCustomDateFormat($fetchAttendance->punchout_datetime, 'H:i'),
            'remarks' => $fetchAttendance->remarks
        ];
    }
}
