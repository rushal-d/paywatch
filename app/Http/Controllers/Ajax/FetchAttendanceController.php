<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\FetchAttendanceRepository;
use Illuminate\Http\Request;

class FetchAttendanceController
{
    protected $fetchAttendanceRepository;

    public function __construct(FetchAttendanceRepository $fetchAttendanceRepository)
    {
        $this->fetchAttendanceRepository = $fetchAttendanceRepository;
    }

    public function getUserAttendanceByAttendanceDateAndStaffCentralId(Request $request)
    {
        $staff_central_id = $request->staff_central_id;
        $attendanceDate = $request->attendance_date;
        $fetchAttendance = $this->fetchAttendanceRepository->getUserAttendanceByAttendanceDateAndStaffCentralId($attendanceDate, $staff_central_id);

        return response()->json([
            'status' => 'true',
            'data' => $fetchAttendance
        ]);
    }
}
