<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemLeaveMastModel extends Model
{

    //
    use SoftDeletes;
    protected $table = 'system_leave_mast';
    protected $primaryKey = 'leave_id';

    public const EARN_ABLE_TYPE_FOR_FLAT = 1;
    public const EARN_ABLE_TYPE_FOR_PRESENT_DAYS_RATIO = 2;
//    public const EARN_ABLE_TYPE_FOR_MIN_PRESENT_THRESHOLD = 3;
    public const EARN_ABLE_TYPE_FOR_EVERY_SPECIFIC_NUMBER_OF_DAYS_PRESENT = 4;
    public const EARN_ABLE_TYPE_FOR_DAYS_FROM_APPOINTMENT = 5;
    public const EARN_ABLE_TYPE_FOR_YEAR_FROM_APPOINTMENT = 6;

    public const LEAVE_EARNABLILITY_ENABLED = 1;
    public const LEAVE_EARNABLILITY_NOT_ENABLED = 0;

    public const LEAVE_EARNABLE_PERIOD_FOR_MONTHLY = 1;
    public const LEAVE_EARNABLE_PERIOD_FOR_YEARLY = 2;

    //home leave
    public function scopeGetHomeLeave($query)
    {
        $query->where('leave_code', 3);
    }

    //home leave
    public function scopeGetSickLeave($query)
    {
        $query->where('leave_code', 4);
    }

    public function scopeGetLeaveWithoutPay($query)
    {
        return $query->where('leave_code', config('constants.leave_code.without_pay_leave'));
    }

    public function leaveUseability()
    {
        return $this->hasMany(SystemLeaveMastUseability::class, 'system_leave_id');
    }

    //leaveEarnabilityEnabled
    public function scopeLeaveEarnabilityEnabled($query)
    {
        return $query->where('leave_earnability', static::LEAVE_EARNABLILITY_ENABLED);
    }

    //leaveEarnableMonthlyType
    public function scopeLeaveEarnableMonthlyType($query)
    {
        return $query->where('leave_earnable_period', static::LEAVE_EARNABLE_PERIOD_FOR_MONTHLY);
    }
}
