<?php

namespace App\Traits;

use App\EmployeeStatus;
use App\Helpers\BSDateHelper;
use App\OrganizationMastShift;
use App\SystemHolidayMastModel;

trait OvertimeCalculation
{
    public function calcuateOvertimeData($date, $end_date, $staff, $employeeStatus, $local_attendances, &$total_overtime_hours, $organization, $public_holidays = null, $organization_shifts = null)
    {
        if (empty($public_holidays)) {
            $public_holidays = SystemHolidayMastModel::with('branch')->get();
        }
        if (empty($organization_shifts)) {
            $organization_shifts = OrganizationMastShift::where('effective_from', '<=', $end_date)->orderBy('effective_from', 'desc')->get();
        }
        $overtime_data = array();
        $i = 0;
        while (strtotime($date) <= strtotime($end_date)) {
            $checkResignDays = $employeeStatus->whereNotIn('status', [EmployeeStatus::STATUS_WORKING, EmployeeStatus::STATUS_SUSPENSE])
                ->where('date_from', '<=', $date);

            $overtime_data[$i]['date'] = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($date)));

            if ($checkResignDays->count() >= 1) {
                $overtime_data[$i]['status'] = 'Resignation';
                $overtime_data[$i]['overtime_hour'] = 0;
                $i++;
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                continue;
            }

            $checkSuspenseDays = $employeeStatus->whereIn('status', [EmployeeStatus::STATUS_SUSPENSE])
                ->where('date_from', '<=', $date)
                ->where('date_to', '>=', $date);

            if ($checkSuspenseDays->count() >= 1) {
                $overtime_data[$i]['status'] = 'Suspense';
                $overtime_data[$i]['overtime_hour'] = 0;

                $i++;
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                continue;
            }

            $check_if_grant_leave = $staff->grantLeave->where('from_leave_day', '<=', $date)->where('to_leave_day', '>=', $date)->first();

            $detail = $local_attendances->where('punchin_datetime', '>=', $date . ' 00:00:00')
                ->where('punchin_datetime', '<=', $date . ' 23:59:59')->first();

            $check_if_public_holiday = $this->getPublicHolidayCollectionBasedOnDateStaff($public_holidays, $date, $staff);
            $weekend_on_this_date = $staff->workschedule->where('effect_day', '<=', $date)->last();
            $overtime_data[$i]['add_classes'] = [];
            $overtime_data[$i]['lateIn'] = '';
            $overtime_data[$i]['earlyOut'] = '';
            $overtime_data[$i]['total_work_hour'] = $detail->total_work_hour ?? 0;
            if (!empty($detail)) {
                $overtime_data[$i]['lateIn'] = 0;
                $overtime_data[$i]['earlyOut'] = 0;
                if ($check_if_public_holiday->count() > 0) {
                    $overtime_data[$i]['status'] = 'Public Holiday Work';
                    $overtime_data[$i]['overtime_hour'] = $detail->total_work_hour;
                    $overtime_data[$i]['punchin'] = !empty($detail->punchin_datetime) ? date('h:i a', strtotime($detail->punchin_datetime)) : null;
                    $overtime_data[$i]['punch_out'] = !empty($detail->punchout_datetime) ? date('h:i a', strtotime($detail->punchout_datetime)) : null;
                    $total_overtime_hours += $detail->total_work_hour;
                    array_push($overtime_data[$i]['add_classes'], 'present-on-public-holiday');
                    $i++;
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                    continue;
                }

                if (!empty($weekend_on_this_date) && (date('N', strtotime($date)) == $weekend_on_this_date->weekend_day)) {
                    $overtime_data[$i]['status'] = 'Weekend Work';
                    $overtime_data[$i]['overtime_hour'] = $detail->total_work_hour;
                    $total_overtime_hours += $detail->total_work_hour;
                    $overtime_data[$i]['punchin'] = !empty($detail->punchin_datetime) ? date('h:i a', strtotime($detail->punchin_datetime)) : null;
                    $overtime_data[$i]['punch_out'] = !empty($detail->punchout_datetime) ? date('h:i a', strtotime($detail->punchout_datetime)) : null;
                    array_push($overtime_data[$i]['add_classes'], 'present-on-weekend');
                    $i++;
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                    continue;
                }
                $minimum_work_hour = $weekend_on_this_date->work_hour ?? 7;
                $overtime_hour = 0;
                //conditions
                if ($detail->total_work_hour > $minimum_work_hour) {
                    $overtime_hour = $detail->total_work_hour - $minimum_work_hour;
                }

                $effective_organization_shift = $organization_shifts->where('effective_from', '<=', $date)->first();

                if ($organization->overtime_calculation_type == 2 && !empty($effective_organization_shift)) {
                    $response = $this->calculateOTFromOrganizationShift($effective_organization_shift, $date, $detail);
                    $overtime_hour = 0;

                    if (!empty($detail->total_work_hour)) {
                        $overtime_hour = $response['total_ot_hour'];
                    }

                    $overtime_data[$i]['lateIn'] = !empty($detail->punchin_datetime) ? $response['lateIn'] : 0;
                    $overtime_data[$i]['earlyOut'] = !empty($detail->punchout_datetime) ? $response['earlyOut'] : 0;
                }
                $overtime_data[$i]['status'] = 'Regular';
                $overtime_data[$i]['overtime_hour'] = $overtime_hour;
                $overtime_data[$i]['punchin'] = !empty($detail->punchin_datetime) ? date('h:i a', strtotime($detail->punchin_datetime)) : null;
                $overtime_data[$i]['punch_out'] = !empty($detail->punchout_datetime) ? date('h:i a', strtotime($detail->punchout_datetime)) : null;
                $total_overtime_hours += $overtime_hour;
                if (!empty($check_if_grant_leave)) {
                    array_push($overtime_data[$i]['add_classes'], 'present-on-leave');

                    if ($check_if_grant_leave->leave_days == 0.5) {
                        $overtime_data[$i]['status'] .= " Half Day Leave";
                    }
                }
                $i++;

            } else {
                if ($check_if_public_holiday->count() > 0) {
                    $overtime_data[$i]['status'] = 'Absent-Public Holiday Work';
                    $overtime_data[$i]['overtime_hour'] = 0;
                    $overtime_data[$i]['punchin'] = '';
                    $overtime_data[$i]['punch_out'] = '';
                    array_push($overtime_data[$i]['add_classes'], 'Public Holiday');
                    $i++;
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                    continue;
                }
                if (!empty($weekend_on_this_date) && (date('N', strtotime($date)) == $weekend_on_this_date->weekend_day)) {
                    $overtime_data[$i]['status'] = 'Absent - Weekend Work';
                    $overtime_data[$i]['overtime_hour'] = 0;
                    $overtime_data[$i]['punchin'] = '';
                    $overtime_data[$i]['punch_out'] = '';
                    array_push($overtime_data[$i]['add_classes'], 'Weekend Day');
                    $i++;
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                    continue;
                }

                $overtime_data[$i]['status'] = 'Absent Day';
                if (!empty($check_if_grant_leave)) {
                    $overtime_data[$i]['status'] = 'Approved Leave. ' . $check_if_grant_leave->leave->leave_name ?? '';
                }
                $overtime_data[$i]['overtime_hour'] = 0;
                $overtime_data[$i]['punchin'] = '';
                $overtime_data[$i]['punch_out'] = '';
                array_push($overtime_data[$i]['add_classes'], 'Absent');
                $i++;
            }
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }
        return $overtime_data;
    }

    public function calculateOTFromOrganizationShift($effective_organization_shift, $date, $attendanceDetail)
    {
        $punchin = date('H:i', strtotime($attendanceDetail->punchin_datetime));
        $punchout = date('H:i', strtotime($attendanceDetail->punchout_datetime));
        $shiftPunchin = $shiftPunchout = null;
        $weekday = date('N', strtotime($date));
        switch ($weekday) {
            case 1:
                //monday
                $shiftPunchin = $effective_organization_shift->monday_punch_in;
                $shiftPunchout = $effective_organization_shift->monday_punch_out;
                break;
            case 2:
                //tuesday
                $shiftPunchin = $effective_organization_shift->tuesday_punch_in;
                $shiftPunchout = $effective_organization_shift->tuesday_punch_out;
                break;
            case 3:
                //wednesday
                $shiftPunchin = $effective_organization_shift->wednesday_punch_in;
                $shiftPunchout = $effective_organization_shift->wednesday_punch_out;
                break;
            case 4:
                //thursday
                $shiftPunchin = $effective_organization_shift->thursday_punch_in;
                $shiftPunchout = $effective_organization_shift->thursday_punch_out;
                break;
            case 5:
                //friday
                $shiftPunchin = $effective_organization_shift->friday_punch_in;
                $shiftPunchout = $effective_organization_shift->friday_punch_out;
                break;
            case 6:
                //saturday
                $shiftPunchin = $effective_organization_shift->saturday_punch_in;
                $shiftPunchout = $effective_organization_shift->saturday_punch_out;
                break;
            case 7:
                //sunday
                $shiftPunchin = $effective_organization_shift->sunday_punch_in;
                $shiftPunchout = $effective_organization_shift->sunday_punch_out;
                break;
            default:
                break;
        }
        //in minutes
        $beforeShitOT = $lateIn = $afterShiftOT = $earlyOut = $total_ot_hours = 0;
        if (empty($shiftPunchin) || empty($shiftPunchin)) {
            $total_ot_hours = $attendanceDetail->total_work_hour;
        } else {
            $time1 = strtotime($punchin);
            $time2 = strtotime($shiftPunchin);
            //in minutes
            $timeDifferencePunchIn = round(abs($time2 - $time1) / 60, 2);
            if ($shiftPunchin > $punchin) {
                //overtime payble before shift work;
                $beforeShitOT = $timeDifferencePunchIn;
            } else {
                $lateIn = $timeDifferencePunchIn;
            }
            $time1 = strtotime($shiftPunchout);
            $time2 = strtotime($punchout);
            //in minutes
            $timeDifferencePunchOut = round(abs($time2 - $time1) / 60, 2);
            if ($shiftPunchout < $punchout) {
                //overtime payble after shift work;
                $afterShiftOT = $timeDifferencePunchOut;
            } else {
                $earlyOut = $timeDifferencePunchOut;
            }
            $total_ot_hours = ($beforeShitOT + $afterShiftOT - $lateIn - $earlyOut) / 60;
        }
        return [
            'total_ot_hour' => ($total_ot_hours > 0) ? $total_ot_hours : 0,
            'lateIn' => $lateIn,
            'earlyOut' => $earlyOut,
        ];
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
