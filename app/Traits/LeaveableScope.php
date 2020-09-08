<?php


namespace App\Traits;


trait LeaveableScope
{
    public function scopeWeekendHolidayLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', config('constants.leave_code.weekend_holiday_leave')); //weekend holiday leave
        });
    }

    public function scopePublicHolidayLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', config('constants.leave_code.public_holiday_leave')); //weekend holiday leave
        });
    }

    public function scopeHomeLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', config('constants.leave_code.home_leave'));
        });
    }

    public function scopeSickLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', config('constants.leave_code.sick_leave'));
        });
    }

    public function scopeMaternityLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', config('constants.leave_code.maternity_leave'));
        });
    }

    public function scopeMaternityCareLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', config('constants.leave_code.maternity_care_leave'));
        });
    }

    public function scopeFuneralLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', config('constants.leave_code.funeral_leave'));
        });
    }

    public function scopeSubstituteLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', config('constants.leave_code.substitute_leave'));
        });
    }

    public function scopeWithOutPayLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', config('constants.leave_code.without_pay_leave'));
        });
    }

    public function scopeMaternityWithOutPayLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', config('constants.leave_code.without_pay_maternity_leave'));
        });
    }
}
