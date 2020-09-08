<?php

namespace App\Console\Commands;

use App\AttendanceDetailModel;
use App\FetchAttendance;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\StafMainMastModel;
use App\SystemHolidayMastModel;
use App\SystemOfficeMastModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ManageAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manage:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $branch = SystemOfficeMastModel::first();

            $branch_id = $branch->office_id;
            $staffs = StafMainMastModel::with('workschedule')->where('branch_id', $branch_id)->get();

            if ($branch->schedule_run_date < date('Y-m-d')) {
                $date = date("Y-m-d", strtotime("+1 day", strtotime($branch->schedule_run_date)));

                $fetch_attendances = FetchAttendance::whereDate('punchin_datetime', '>=', $date)->where('branch_id', $branch_id)->get();

                $currentFiscalYear = FiscalYearModel::IsActiveFiscalYear()->value('id');
                $public_holidays = SystemHolidayMastModel::where('fy_year', $currentFiscalYear)->get();

                foreach ($staffs as $staff) {
                    $staff_workschedule = $staff->workschedule->last();
                    $local_attendance = $fetch_attendances->where('staff_central_id', $staff->id)->where('punchin_datetime', '>', $date . ' 00:00:00')
                        ->where('punchin_datetime', '<', $date . ' 23:59:00')->first();

                    $attendance_detail_model = AttendanceDetailModel::where('staff_central_id', $staff->id)->where('date', $date)->first();
                    if (empty($attendance_detail_model)) {
                        $attendance_detail_model = new AttendanceDetailModel();
                    }

                    $attendance_detail_model->staff_central_id = $staff->id;
                    $attendance_detail_model->payroll_id = null;
                    $attendance_detail_model->date = $date;
                    $attendance_detail_model->date_np = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($attendance_detail_model->date)));
                    $attendance_detail_model->total_work_hour = empty($local_attendance->total_work_hour) ? 0 : $local_attendance->total_work_hour;

                    $attendance_detail_model->weekend_holiday = (($this->checkIfWeekendDay($attendance_detail_model->date, $staff_workschedule->weekend_day))) ? 1 : 0;
                    $attendance_detail_model->public_holiday = (($this->checkIfPublicHoliday($attendance_detail_model->date, $public_holidays))) ? 1 : 0;
                    //calculate OT
                    $total_OT = 0;
                    if ($attendance_detail_model->total_work_hour > $staff_workschedule->work_hour) {
                        $total_OT = $attendance_detail_model->total_work_hour - $staff_workschedule->work_hour;
                    }
                    $attendance_detail_model->total_ot_hour = $total_OT;

                    $attendance_detail_model->status = (empty($attendance_detail_model->total_work_hour)) ? 0 : 1; //0 is absent and 1 is present
                    $attendance_detail_model->save();
                }
                $branch->schedule_run_date = $date;
                $branch->save();
            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
        }
        DB::commit();


    }

    /** Check if the staff has attendance on weekend or not
     * @param $date
     * @param $weekend_day
     */
    public
    function checkIfWeekendDay($date, $weekend_day)
    {
        $wday = date('N', strtotime($date));
        if ($wday == $weekend_day) {
            return true;
        }
        return false;
    }

    /** Check if the staff has attendance on public holiday or not
     * @param string $date
     * @param SystemHolidayMastModel $publicHoliday
     *
     * @return bool
     */

    public
    function checkIfPublicHoliday($date, $publicHolidays)
    {
        $date = date('Y-m-d', strtotime($date));
        $isHoliday = $publicHolidays->filter(function ($publicHoliday) use ($date) {
            return $publicHoliday->from_date <= $date && $publicHoliday->to_date >= $date;
        })->first();
        if (!empty($isHoliday)) {
            return true;
        }
        return false;
    }
}
