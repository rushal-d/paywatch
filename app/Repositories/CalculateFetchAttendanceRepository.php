<?php

namespace App\Repositories;

use App\CalenderHolidayModel;
use App\EmployeeStatus;
use App\Helpers\BSDateHelper;
use App\Helpers\DateHelper;
use App\OrganizationSetup;
use App\StafMainMastModel;
use App\SystemHolidayMastModel;
use App\SystemLeaveMastModel;
use App\SystemOfficeMastModel;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CalculateFetchAttendanceRepository
{
    /**
     * Always include workschedule', 'grantLeave', 'staffStatus', 'branch', 'fetchAttendances before using this function for staff
     * @param $staff
     * @param $public_holidays
     * @param $date_from_en
     * @param $date_to_en
     * @param null $organization
     * @return array
     * @throws \Exception
     */
    public function getCalculatedAttendanceInformation($staff, $public_holidays, $date_from_en, $date_to_en, $organization = null)
    {
        if (empty($organization)) {
            $organization = OrganizationSetup::first();
        }

        if (empty($staff)) {
            return [];
        }

        $dStart = new DateTime($date_from_en);
        $dEnd = new DateTime($date_to_en);

        $attendance_data = array();

        $dDiff = $dStart->diff($dEnd);
        $total_days = $dDiff->days + 1;

        $public_holiday_count = 0;
        $suspense_days = 0;
        $present_on_public_holiday = 0;
        $weekend_count = 0;
        $public_holiday_on_weekend = 0;//check if it is public holiday on weekend
        $present_weekend_count = 0;
        $present_on_leave = 0;
        $present_days = 0;
        $absent_days = 0;
        $grant_leave = 0;
        $total_work_hour = 0;
        $date_en = $date_from_en;
        $end_date_en = $date_to_en;
        $public_holiday_work_hour = 0;
        $weekend_holiday_work_hour = 0;
        $absent_on_pubic_holiday_on_weekend = 0;
        $original_date_from = $date_en;
        while (strtotime($date_en) <= strtotime($end_date_en)) {
            $isTodayPublicHoliday = false;
            $isTodayWeekend = false;
            $isTodayGrantLeave = false;

            $check_if_public_holiday = $this->getPublicHolidayCollectionBasedOnDateStaff($public_holidays, $date_en, $staff);

            if ($check_if_public_holiday->count() > 0) {
                $public_holiday_count++;
            }

            $suspenses = $staff->staffStatus;

            $checkResignDays = $suspenses->whereNotIn('status', [EmployeeStatus::STATUS_WORKING, EmployeeStatus::STATUS_SUSPENSE])->where('date_from', '<=', $date_en);

            if ($checkResignDays->count() > 0) {
                $absent_days++;
                $suspense_days++;
                $date_en = date("Y-m-d", strtotime("+1 day", strtotime($date_en)));
                continue;
            }

            $checkSuspenseDays = $suspenses->whereIn('status', [EmployeeStatus::STATUS_SUSPENSE])->where('date_from', '<=', $date_en)->where('date_to', '>=', $date_en);

            if ($checkSuspenseDays->count() >= 1) {
                $suspense_days++;
                $absent_days++;
                $date_en = date("Y-m-d", strtotime("+1 day", strtotime($date_en)));
                continue;
            }

            $weekend_on_this_date = $staff->workschedule->where('effect_day', '<=', $date_en)->last();

            if ($weekend_on_this_date) {
                if ((date('N', strtotime($date_en)) == $weekend_on_this_date->weekend_day) && ($check_if_public_holiday->count() > 0)) {
                    $public_holiday_on_weekend++;
                }
            } else {
                $public_holiday_on_weekend += 0;
            }

            $detail = $staff->fetchAttendances->where('punchin_datetime', '>', $date_en . ' 00:00:00')
                ->where('punchin_datetime', '<', $date_en . ' 23:59:00')->first();

            if (!empty($detail)) {
                $present_days++;

                $work_hour_day = $detail->total_work_hour;
                if ($check_if_public_holiday->count() > 0) {
                    $present_on_public_holiday++;
//                            $roundedPublicTotalWorkHour = DateHelper::getMaximumWorkHourForNonNormalDays($detail->total_work_hour);
                    $roundedPublicTotalWorkHour = $detail->total_work_hour;
                    $work_hour_day = $roundedPublicTotalWorkHour;
                    $public_holiday_work_hour += $roundedPublicTotalWorkHour;
                }
                if (!empty($weekend_on_this_date) && date('N', strtotime($date_en)) == $weekend_on_this_date->weekend_day) {
                    $weekend_count++;
                    $present_weekend_count++;
//                            $roundedWeekendTotalWorkHour = DateHelper::getMaximumWorkHourForNonNormalDays($detail->total_work_hour);
                    $roundedWeekendTotalWorkHour = $detail->total_work_hour;
                    $weekend_holiday_work_hour += $roundedWeekendTotalWorkHour;
                }

                //If present and present on leave and is leave half day then deduct present days - .5
                //Else present and present on leave but not half day then - 1

                $check_if_grant_leave = $staff->grantLeave->where('from_leave_day', '<=', $date_en)
                    ->where('to_leave_day', '>=', $date_en)
                    ->first();

                if (!empty($check_if_grant_leave)) {
                    if ($check_if_grant_leave->leave_days == CalenderHolidayModel::HALF_LEAVE_DAY) {
                        $present_days = $present_days - 1 + 0.5;
                    }
                    $present_on_leave++;
                    $grant_leave++;
                    $absent_days++;
                }
                $total_work_hour += $work_hour_day;

            } else {
                $absent_days++;

                /*absent on weekend with public holiday*/

                $weekend_on_this_date = $staff->workschedule->where('effect_day', '<=', $date_en)->last();
                if ($weekend_on_this_date) {
                    if ((date('N', strtotime($date_en)) == $weekend_on_this_date->weekend_day) && ($check_if_public_holiday->count() > 0)) {
                        $absent_on_pubic_holiday_on_weekend++;
                    }
                }

                if ($check_if_public_holiday->count() > 0) {
                    $isTodayPublicHoliday = true;
                }

                /*end*/
                if ($weekend_on_this_date) {
                    if (date('N', strtotime($date_en)) == $weekend_on_this_date->weekend_day) {
                        $weekend_count++;
                        if ($organization->absent_weekend_on_cons_absent) {
                            $prevDay = date("Y-m-d", strtotime("-1 day", strtotime($date_en)));
                            $nextDay = date("Y-m-d", strtotime("+1 day", strtotime($date_en)));

                            $check_if_public_holiday_prev_day = $public_holidays->filter(function ($public_holidays) use ($prevDay) {
                                return $public_holidays->from_date <= $prevDay && $public_holidays->to_date >= $prevDay;
                            });
                            $check_if_public_holiday_next_day = $public_holidays->filter(function ($public_holidays) use ($nextDay) {
                                return $public_holidays->from_date <= $nextDay && $public_holidays->to_date >= $nextDay;
                            });

                            if ($check_if_public_holiday_prev_day->count() == 0 && $check_if_public_holiday_next_day->count() == 0
                                && ($original_date_from != $date_en) && ($date_en != $date_to_en)) {
                                $prevDayWorkHour = $staff->fetchAttendances->where('punchin_datetime', '>', $prevDay . ' 00:00:00')
                                        ->where('punchin_datetime', '<', $prevDay . ' 23:59:00')->first()->total_work_hour ?? 0;

                                $nextDayWorkHour = $staff->fetchAttendances->where('punchin_datetime', '>', $nextDay . ' 00:00:00')
                                        ->where('punchin_datetime', '<', $nextDay . ' 23:59:00')->first()->total_work_hour ?? 0;

                                if ($prevDayWorkHour == 0 && $nextDayWorkHour == 0) {
                                    $weekend_count--;
                                    $isTodayWeekend = false;
                                } else {
                                    $isTodayWeekend = true;
                                }
                            }
                        }
                    }
                }

                $check_if_grant_leave = $staff->grantLeave->where('from_leave_day', '<=', $date_en)->where('to_leave_day', '>=', $date_en)->first();

                if (!empty($check_if_grant_leave)) {
                    $grant_leave++;

                    //if grant leave is regarded as present days and there is no public holiday and weekend, add a present days by 1
                    if ($check_if_grant_leave->act_as_present_days == 1 && !$isTodayPublicHoliday && !$isTodayWeekend) {
                        $present_days++;
                    }
                }
            }
            $date_en = date("Y-m-d", strtotime("+1 day", strtotime($date_en)));
        }
        $absent_on_weekend = $weekend_count - $present_weekend_count;
        $absent_on_public_holiday = $public_holiday_count - $present_on_public_holiday;
        $total_working_day = $total_days + $public_holiday_on_weekend - $weekend_count - $public_holiday_count;
//            $actual_absent_days = $absent_days + $public_holiday_on_weekend - $weekend_count - $public_holiday_count + $present_weekend_count + $present_on_public_holiday;
        $paid_leave = ($absent_on_weekend + $absent_on_public_holiday + $grant_leave - $absent_on_pubic_holiday_on_weekend);
        $unpaid_leave = $total_days - $present_days - $paid_leave;
        $actual_absent_days = $paid_leave + $unpaid_leave;
        $absent_days_without_public_holiday = $absent_days + $public_holiday_on_weekend - $absent_on_public_holiday;

        if ($organization->code == 'BBSM') {

            if ($staff->branch->manual_weekend_enable == SystemOfficeMastModel::MANUAL_WEEKEND_ENABLE) {
                if ($absent_days_without_public_holiday >= $weekend_count) {
                    $absent_on_weekend = $weekend_count;
                    $present_weekend_count = 0;
                } else {
                    $absent_on_weekend = $absent_days_without_public_holiday;
                    $present_weekend_count = $weekend_count - $absent_days_without_public_holiday;
                }
                $weekendsForManualWeekendEnabledBranch = $staff->fetchAttendances->sortByDesc('total_work_hour')->take($present_weekend_count);
                $weekend_holiday_work_hour = 0;

                foreach ($weekendsForManualWeekendEnabledBranch as $weekendForManualWeekendEnabledBranch) {
                    $weekend_holiday_work_hour += DateHelper::getMaximumWorkHourForNonNormalDays($weekendForManualWeekendEnabledBranch->total_work_hour);
                }
            }

            if ($staff->manual_attendance_enable == StafMainMastModel::MANUAL_ATTENDANCE_ENABLED) {
                $weekend_count = 4;
                if ($absent_days_without_public_holiday >= 4) {
                    $absent_on_weekend = 4;
                    $present_weekend_count = 0;
                } else {
                    $absent_on_weekend = $absent_days_without_public_holiday;
                    $present_weekend_count = 4 - $absent_days_without_public_holiday;
                }
                $weekend_holiday_work_hour = $present_weekend_count * DateHelper::getMaximumWorkHourForNonNormalDays();
            }
        }
        $attendance_data['CID'] = $staff->id;
        $attendance_data['name'] = $staff->name_eng;
        $attendance_data['total_days'] = $total_days;
        $attendance_data['total_working_days'] = $total_working_day;
        $attendance_data['present_days'] = $present_days;
        $attendance_data['absent_days'] = $actual_absent_days;
        $attendance_data['weekend_days'] = $weekend_count;
        $attendance_data['public_holidays'] = $public_holiday_count;
        $attendance_data['public_holidays_on_weekend'] = $public_holiday_on_weekend;
        $attendance_data['present_on_public_holidays'] = $present_on_public_holiday;


        $attendance_data['total_working_days_in_all_days'] = $present_days + $grant_leave;

        $attendance_data['present_on_weekend'] = $present_weekend_count;
        $attendance_data['paid_leave'] = $paid_leave;
        $attendance_data['unpaid_leave'] = $unpaid_leave;
        $attendance_data['absent_on_weekend'] = $absent_on_weekend;
        $attendance_data['grant_leave'] = $grant_leave;
        $attendance_data['total_work_hour'] = $total_work_hour;
        $attendance_data['public_holiday_work_hour'] = $public_holiday_work_hour;
        $attendance_data['weekend_holiday_work_hour'] = $weekend_holiday_work_hour;
        $attendance_data['suspense_days'] = $suspense_days;

        return $attendance_data;
    }

    protected function getPublicHolidayCollectionBasedOnDateStaff($public_holidays, $date, $staff = null, $branch_id = null)
    {
        $check_if_public_holiday = $public_holidays->filter(function ($public_holidays) use ($date, $staff, $branch_id) {
            $checkDateStatus = true;
            if (!($public_holidays->from_date <= $date && $public_holidays->to_date >= $date)) {
                $checkDateStatus = false;
            }
            $checkGenderStatus = true;
            if (!empty($staff)) {
                if (empty($public_holidays->gender_id)) {
                    $checkGenderStatus = true;
                } elseif ($public_holidays->gender_id == $staff->Gender) {
                    $checkGenderStatus = true;
                } else {
                    $checkGenderStatus = false;
                }
            }
            $checkBranchStatus = true;
            if (!empty($staff) || !empty($branch_id)) {
                $branch_id_to_check = !empty($staff) ? $staff->branch_id : null;
                if (empty($branch_id_to_check)) {
                    $branch_id_to_check = $branch_id;
                }
                $checkBranchStatus = false;
                if ($public_holidays->branch->where('office_id', $branch_id_to_check)->count() > 0) {
                    $checkBranchStatus = true;
                }
            }

            return ($checkDateStatus && $checkGenderStatus && $checkBranchStatus);
        });

        return $check_if_public_holiday;
    }
}
