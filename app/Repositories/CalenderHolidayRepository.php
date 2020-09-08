<?php

namespace App\Repositories;

use App\CalenderHolidayFile;
use App\CalenderHolidayModel;
use App\CalenderHolidaySplitMonth;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\LeaveBalance;
use App\OrganizationSetup;
use App\StafMainMastModel;
use App\SystemHolidayMastModel;
use App\SystemLeaveMastModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CalenderHolidayRepository
{
    public function save($inputs)
    {
        //start transaction to save the data
        try {
            $organization = OrganizationSetup::first();
            if ($organization->organization_structure == 2) {
                $response = $this->check_conditions($inputs['leave_id'], $inputs['staff_central_id'], $inputs['from_leave_day_np'], $inputs['to_leave_day_np'], ($inputs['holiday_days'] == 0.5) ? 1 : 0,null,$organization);
                if (!$response['status']) {
                    return redirect()->back()->with('flash', array('status' => 'error', 'mesg' => $response['message']));
                }
            }
            $leaveBalance = LeaveBalance::where('staff_central_id', $inputs['staff_central_id'])->where('leave_id', $inputs['leave_id'])->orderBy('id', 'desc')->latest()->first();
            $fiscal_years = FiscalYearModel::get();
//            $fiscal_year = $fiscal_years->where('fiscal_start_date', '<=', $inputs['from_leave_day'])->where('fiscal_end_date', '>=', $inputs['to_leave_day'])->first();
            $fiscal_year = $fiscal_years->where('fiscal_start_date', '<=', $inputs['from_leave_day'])->where('fiscal_end_date', '>=', $inputs['from_leave_day'])->first();
            $holidayDays = $this->daysDifference($inputs['from_leave_day'], $inputs['to_leave_day']);

            if ($inputs['holiday_days'] == 0.5) {
                $holidayDays = $holidayDays - 0.5;
            }

            //start transaction for rolling back if some problem occurs
            DB::beginTransaction();
            $calenderholiday = new CalenderHolidayModel();
            $calenderholiday->staff_central_id = $inputs['staff_central_id'];
            $calenderholiday->fiscal_year_id = $fiscal_year->id;
            $calenderholiday->leave_id = $inputs['leave_id'];
            $calenderholiday->from_leave_day_np = $inputs['from_leave_day_np'];
            $calenderholiday->from_leave_day = $inputs['from_leave_day'];
            $calenderholiday->to_leave_day_np = $inputs['to_leave_day_np'];
            $calenderholiday->to_leave_day = $inputs['to_leave_day'];
            $calenderholiday->authorized_by = \Auth::user()->id;
            $calenderholiday->created_by = \Auth::user()->id;
            $calenderholiday->leave_days = $holidayDays;
            if ($calenderholiday->save()) {
                if (!empty($inputs['upload']) && isset($inputs['upload'])) {
                    foreach ($inputs['upload'] as $staff_file_id) {
                        $calender_holiday_file = new CalenderHolidayFile();
                        $calender_holiday_file->calender_holiday_id = $calenderholiday->id;
                        $calender_holiday_file->staff_file_id = $staff_file_id['staff_file_id'];
                        $calender_holiday_file->save();
                    }
                }
                $month_days = BSDateHelper::getDaysInMonthOfDateRange($inputs['from_leave_day_np'], $inputs['to_leave_day_np']);
                foreach ($month_days as $leaveMonth => $month_day) {
                    $fiscal_year = $fiscal_years->where('fiscal_start_date', '<=', $month_day['from'])->where('fiscal_end_date', '>=', $month_day['from'])->first();
                    $calenderholiday_split = new CalenderHolidaySplitMonth();
                    $calenderholiday_split->calender_holiday_id = $calenderholiday->id;
                    $calenderholiday_split->fiscal_year_id = $fiscal_year->id;
                    $calenderholiday_split->leave_month = $leaveMonth;
                    if ($inputs['holiday_days'] == 0.5) {
                        $month_day['days'] = $month_day['days'] - 0.5;
                    }
                    $calenderholiday_split->leave_days = $month_day['days'];
                    $calenderholiday_split->save();
                }

                $leavebalance = new LeaveBalance();
                $leavebalance->staff_central_id = $inputs['staff_central_id'];
                $leavebalance->leave_id = $inputs['leave_id'];
                $leavebalance->date_np = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $leavebalance->date = date('Y-m-d');
                $leavebalance->fy_id = FiscalYearModel::IsActiveFiscalYear()->value('id');
                $leavebalance->description = Config::get('constants.balance_description')[2];
                $leavebalance->consumption = $holidayDays;
                $leavebalance->earned = 0;
                $leavebalance->balance = (($leaveBalance->balance ?? 0) - $holidayDays);
                $leavebalance->authorized_by = auth()->id();
                if ($leavebalance->save()) {
                    $status_mesg = true;
                }
            }
        } catch (Exception $e) {
//            dd($e);
            DB::rollback();
            $status_mesg = false;
        }

        if ($status_mesg) {
            DB::commit();
        }

        return $status_mesg;
    }

    public function check_conditions($leave_id, $staff_id, $leave_from_np, $leave_to_np, $is_half_day, $calender_holiday = null,$organization=null)
    {
        $leave_from = BSDateHelper::BsToAd('-', $leave_from_np);
        $leave_to = BSDateHelper::BsToAd('-', $leave_to_np);
        if (strtotime($leave_to) < strtotime($leave_from)) {
            return [
                'status' => false,
                'message' => 'Date To is before than Date From',
            ];
        }
        $leave = SystemLeaveMastModel::find($leave_id);
        $staff = StafMainMastModel::with('workschedule')->where('id', $staff_id)->first();
        $fiscal_year = FiscalYearModel::where('fiscal_start_date', '<=', $leave_from)->where('fiscal_end_date', '>=', $leave_from)->first();
        $system_public_holiday = SystemHolidayMastModel::with('branch')->where('fy_year', $fiscal_year->id)->get();
        $holidayDays = $this->daysDifference($leave_from, $leave_to);
        $leaveBalance = LeaveBalance::where('staff_central_id', $staff_id)->where('leave_id', $leave_id)->latest()->first();
        $month_names = Config::get('constants.month_name');
        $checkIfAlreadyTaken = $this->checkIfLeaveAlreadyTakenOnDays($staff_id, $leave_from, $leave_to, $calender_holiday);

        if (!$checkIfAlreadyTaken) {
            return [
                'status' => false,
                'message' => 'Leave already taken on (some) days given',
            ];
        }
        if (!empty($calender_holiday)) {
            $calender_holiday = CalenderHolidayModel::with('calenderHolidaySplits')->where('id', $calender_holiday)->first();
        }
        if (!empty($leave->job_type_id)) {
            if ($leave->job_type_id != $staff->jobtype_id) {
                return [
                    'status' => false,
                    'message' => 'Leave Not Applicable for the staff! Job Type Limitation',
                ];
            }
        }
        //check half day
        if ($is_half_day == 1) {
            if ($leave->allow_half_day != 1) {
                //return false - no half day allowed
                return [
                    'status' => false,
                    'message' => 'Half Day Leave Not Allowed on the leave selected.',
                ];
            } else {
                $holidayDays -= 0.5;
            }
        }

        $weekend_days = 0;
        $public_holidays = 0;
        $public_holiday_weekend = 0;
        for ($timestamp = strtotime($leave_from); $timestamp <= strtotime($leave_to); $timestamp = strtotime("+1 day", $timestamp)) {
            $date = date('Y-m-d', $timestamp);
            $weekend_on_this_date = $staff->workschedule->where('effect_day', '<=', $date)->last();
            if ($this->checkIfWeekendDay($date, $weekend_on_this_date->weekend_day ?? null)) {
                $weekend_days++;
            }
            if ($this->checkIfPublicHoliday($date, $system_public_holiday, $staff)) {
                $public_holidays++;
                //if weekend on public holiday
                if ($this->checkIfWeekendDay($date, $weekend_on_this_date->weekend_day ?? null)) {
                    $weekend_days--;
                    $public_holiday_weekend++;
                }
            }
        }

        //check if public holiday and weekend counted on leave
        if ($leave->inclusive_public_holiday_weekend == 0) {
            $holidayDays = $holidayDays - $weekend_days - $public_holidays;
        }
        //for edit case.. leave balance must contain the balance of including the previous leave taken
        $previous_leave_taken = 0;
        if (!empty($calender_holiday)) {
            $previous_leave_taken = $calender_holiday->leave_days;
        }
        if (empty($leave->allow_negative)) {
            //check if has optimum leave balance
            if (($leaveBalance->balance ?? 0) + $previous_leave_taken < $holidayDays) {
                return [
                    'status' => false,
                    'message' => 'Negative Leave Balance Not Allowed',
                ];
            }
        }


        //check gender applicable
        if (!empty($leave->applicable_gender)) {
            if ($leave->applicable_gender != $staff->Gender) {
                //return false - not application gender
                return [
                    'status' => false,
                    'message' => 'The leave is gender specific! The leave is not allowed for the staff.',
                ];
            }
        }

        //check useability conditions
        if ($leave->leaveUseability->count() > 0) {
            foreach ($leave->leaveUseability as $useabilityCases) {
                switch ($useabilityCases->useability_count_unit) {
                    case 1:
                        // Days in Fiscal Year
                        if(!empty($organization) && $organization->organization_code=="NEPALRE"){
                            $leave_days_taken = CalenderHolidayModel::where('staff_central_id', $staff_id)->where('leave_id', $leave_id)->whereYear('from_leave_day_np', BSDateHelper::getYear($leave_from_np));
                        }else{
                            $leave_days_taken = CalenderHolidayModel::where('staff_central_id', $staff_id)->where('leave_id', $leave_id)->where('fiscal_year_id', $fiscal_year->id);
                        }
                        if (!empty($calender_holiday)) {
                            $leave_days_taken = $leave_days_taken->where('id', '<>', $calender_holiday->id);
                        }
                        $leave_days_taken = $leave_days_taken->sum('leave_days');
                        if (($leave_days_taken + $holidayDays) > $useabilityCases->useability_count) {
                            //return false
                            return [
                                'status' => false,
                                'message' => 'Max ' . $useabilityCases->useability_count . ' days in fiscal year is allowed. The Staff tends to take ' . ($leave_days_taken + $holidayDays) . ' days leave in fiscal year.',
                            ];
                        }
                        break;
                    case 2:
                        //Time(s) in fiscal year
                        if(!empty($organization) && $organization->organization_code=="NEPALRE"){
                            $leave_times_taken = CalenderHolidayModel::where('staff_central_id', $staff_id)->where('leave_id', $leave_id)->whereYear('from_leave_day_np', BSDateHelper::getYear($leave_from_np));
                        }else{
                            $leave_times_taken = CalenderHolidayModel::where('staff_central_id', $staff_id)->where('leave_id', $leave_id)->where('fiscal_year_id', $fiscal_year->id);
                        }
                        if (!empty($calender_holiday)) {
                            $leave_times_taken = $leave_times_taken->where('id', '<>', $calender_holiday->id);
                        }
                        $leave_times_taken = $leave_times_taken->count();
                        if (($leave_times_taken + 1) > $useabilityCases->useability_count) {
                            //return false
                            return [
                                'status' => false,
                                'message' => 'Max ' . $useabilityCases->useability_count . ' times in fiscal year is allowed. The Staff tends to take leave for ' . $this->ordinal($leave_times_taken + 1) . ' time.',
                            ];
                        }
                        break;
                    case 3:
                        //days in month
                        $month_days = BSDateHelper::getDaysInMonthOfDateRange($leave_from_np, $leave_to_np);
                        foreach ($month_days as $month => $month_day) {
                            $leave_times_days_in_month = CalenderHolidayModel::where('staff_central_id', $staff_id)->where('leave_id', $leave_id)->whereHas('calenderHolidaySplits', function ($query) use ($fiscal_year, $month) {
                                $query->where('fiscal_year_id', $fiscal_year->id);
                                $query->where('leave_month', $month);
                            });
                            if (!empty($calender_holiday)) {
                                $leave_times_days_in_month = $leave_times_days_in_month->where('id', '<>', $calender_holiday->id);
                            }
                            $leave_times_days_in_month = $leave_times_days_in_month->sum('leave_days');

                            if (($leave_times_days_in_month + $month_day['days']) > $useabilityCases->useability_count) {
                                // return false
                                return [
                                    'status' => false,
                                    'message' => 'Max ' . $useabilityCases->useability_count . ' days in a month is allowed. The Staff tends to take ' . ($leave_times_days_in_month + $month_day['days']) . ' days leave in the month of ' . $month_names[$month],
                                ];
                            }
                        }
                        break;
                    case 4:
                        //times in month
                        $month_days = BSDateHelper::getDaysInMonthOfDateRange($leave_from_np, $leave_to);
                        foreach ($month_days as $month => $month_day) {
                            $leave_times_taken_in_from_month = CalenderHolidayModel::where('staff_central_id', $staff_id)->where('leave_id', $leave_id)->whereHas('calenderHolidaySplits', function ($query) use ($fiscal_year, $month) {
                                $query->where('fiscal_year_id', $fiscal_year->id);
                                $query->where('leave_month', $month);
                            });
                            if (!empty($calender_holiday)) {
                                $leave_times_taken_in_from_month = $leave_times_taken_in_from_month->where('id', '<>', $calender_holiday->id);
                            }
                            $leave_times_taken_in_from_month = $leave_times_taken_in_from_month->count();
                            if (($leave_times_taken_in_from_month + 1) > $useabilityCases->useability_count) {
                                // return false
                                return [
                                    'status' => false,
                                    'message' => 'Max ' . $useabilityCases->useability_count . ' time(s) in a month is allowed. The Staff tends to take leave for the ' . $this->ordinal($leave_times_taken_in_from_month + 1) . ' time in the month of ' . $month_names[$month],
                                ];
                            }
                        }

                        break;
                    case 5:
                        //times in service Period
                        $leave_times_taken_in_service_period = CalenderHolidayModel::where('staff_central_id', $staff_id)->where('leave_id', $leave_id);
                        if (!empty($calender_holiday)) {
                            $leave_times_taken_in_service_period = $leave_times_taken_in_service_period->where('id', '<>', $calender_holiday->id);
                        }
                        $leave_times_taken_in_service_period = $leave_times_taken_in_service_period->count();
                        if (($leave_times_taken_in_service_period + 1) > $useabilityCases->useability_count) {
                            // return false
                            return [
                                'status' => false,
                                'message' => 'Max ' . $useabilityCases->useability_count . ' time(s) in the service period is allowed. The Staff tends to take leave for the ' . $this->ordinal($leave_times_taken_in_service_period + 1) . ' time in the service period.',
                            ];
                        }
                        break;
                    default:
                        break;
                }
            }
        }


        // check min days and max days
        if ($leave->min_no_of_days_allowed_at_time > $holidayDays) {
            //return false
            return [
                'status' => false,
                'message' => 'The leave days does not meet the minimum leave days that should be taken.',
            ];
        }
        if (!empty($leave->max_no_of_days_allowed_at_time)) {
            if ($leave->max_no_of_days_allowed_at_time < $holidayDays) {
                //return false
                return [
                    'status' => false,
                    'message' => 'The leave days exceed the maximum leave days that can be taken.',
                ];
            }
        }

        return [
            'status' => true,
            'message' => 'All Ok!',
            'holiday_days' => $holidayDays,
            'weekend_day' => $weekend_days,
            'public_holiday' => $public_holidays,
            'public_holiday_weekend' => $public_holiday_weekend
        ];
    }

    public function daysDifference($date_from, $date_to)
    {
        $dateFrom = date_create($date_from);
        $dateTo = date_create($date_to);
        $holidayDays = date_diff($dateFrom, $dateTo)->format('%a') + 1;
        return $holidayDays;
    }

    public function checkIfWeekendDay($date, $weekend_day)
    {
        $wday = date('N', strtotime($date));
        if ($wday == $weekend_day) {
            return true;
        }
        return false;
    }

    public function checkIfPublicHoliday($date, $publicHolidays, $staff = null, $branch_id = null)
    {
        $date = date('Y-m-d', strtotime($date));

        $isHoliday = $publicHolidays->filter(function ($publicHolidays) use ($date, $staff, $branch_id) {
            $checkDateStatus = true;
            if (!($publicHolidays->from_date <= $date && $publicHolidays->to_date >= $date)) {
                $checkDateStatus = false;
            }

            $checkGenderStatus = true;
            if (!empty($staff)) {
                if (empty($publicHolidays->gender_id)) {
                    $checkGenderStatus = true;
                } elseif ($publicHolidays->gender_id == $staff->Gender) {
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
                if ($publicHolidays->branch->where('office_id', $branch_id_to_check)->count() > 0) {
                    $checkBranchStatus = true;
                }
            }
            return ($checkDateStatus && $checkGenderStatus && $checkBranchStatus);
        });

        if (!empty($isHoliday->count())) {
            return true;
        }
        return false;
    }

    public function checkIfLeaveAlreadyTakenOnDays($staff_central_id, $date_from_en, $date_to_en, $calenderholiday_id)
    {
        $holiday_count = CalenderHolidayModel::where('staff_central_id', $staff_central_id)->where(function ($query) use ($date_from_en, $date_to_en) {
            $query->where([['from_leave_day', '>=', $date_from_en], ['to_leave_day', '<=', $date_to_en]]);
            $query->orWhere([['from_leave_day', '<=', $date_from_en], ['to_leave_day', '>=', $date_from_en]]);
            $query->orWhere([['from_leave_day', '<=', $date_to_en], ['to_leave_day', '>=', $date_to_en]]);
            $query->orWhere([['from_leave_day', '<=', $date_from_en], ['to_leave_day', '>=', $date_to_en]]);
        });
        if (!empty($calenderholiday_id)) {
            $holiday_count = $holiday_count->where('id', '<>', $calenderholiday_id);
        }
        $holiday_count = $holiday_count->count();
        if ($holiday_count == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function ordinal($number)
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . 'th';
        else
            return $number . $ends[$number % 10];
    }

}
