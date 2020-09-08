<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalenderHolidaySplitMonth extends Model
{
    use SoftDeletes;

    public function calenderholiday()
    {
        return $this->belongsTo(CalenderHolidayModel::class, 'calender_holiday_id');
    }
}
