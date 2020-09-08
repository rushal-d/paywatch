<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function convertHourToHourAndMinutesFormat($inputHour)
    {
        //inputHour would be 14.5 format

        $minutes = static::convertHourToMinute($inputHour);

        $hours = floor($inputHour);
        $minutes = ($minutes % 60);
        return $hours . ' ' . str_plural('hrs', $hours) . ' ' . $minutes . ' ' . str_plural('mins', $minutes);
    }

    public static function convertHourToMinute($inputHour)
    {
        return $inputHour * 60;
    }

    public static function toCustomDateFormat($date, $format = 'Y-m-d H:i:s')
    {
        if (empty($date)) {
            return null;
        }

        return Carbon::parse($date)->format($format);

    }

    public static function getDiffHoursFromTwoTime($lesserTime, $greaterTime)
    {
        $lesserTime = Carbon::parse($lesserTime);
        $greaterTime = Carbon::parse($greaterTime);

        return $greaterTime->diffInHours($lesserTime);
    }

    public static function getAgeToday($date)
    {
        if (empty($date)) {
            return null;
        }

        return Carbon::parse($date)->diffInYears(Carbon::now());
    }

    public static function getMaximumWorkHourForNonNormalDays($time = null)
    {
        if (empty($time)) {
            return config('constants.max_working_hour_for_holiday');
        }

        if ($time > config('constants.max_working_hour_for_holiday')) {
            return config('constants.max_working_hour_for_holiday');
        }

        return round($time);
    }
}
