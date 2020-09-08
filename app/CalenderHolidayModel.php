<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;

class CalenderHolidayModel extends Model
{
    //
    use SearchableTrait;
    use SoftDeletes;
    protected $table = 'calender_holiday';
    protected $searchable = [
        'columns' => [
            'staff_main_mast.id' => 1,
            'staff_main_mast.name_eng' => 10
        ],
        /*search by join table ->use table name*/
        'joins' => [
            'staff_main_mast' => ['calender_holiday.staff_central_id', 'staff_main_mast.id'],
        ],
    ];

    public const HALF_LEAVE_DAY = 0.5;

    protected static function boot()
    {
        parent::boot();
        if (!Session::get('role')) {
            static::addGlobalScope('staffScope', function (Builder $builder) {
                $branch_id = Auth::user()->branch_id;
                $staff_ids = StafMainMastModel::where('branch_id', $branch_id)->pluck('id')->toArray();
                $builder->whereIn('calender_holiday.staff_central_id', $staff_ids);
            });
        }

    }

    public function staff()
    {
        return $this->belongsTo('App\StafMainMastModel', 'staff_central_id');
    }

    public function leave()
    {
        return $this->belongsTo('App\SystemLeaveMastModel', 'leave_id');
    }

    public function scopeWeekendHolidayLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', 1); //weekend holiday leave
        });
    }

    public function scopePublicHolidayLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', 2); //weekend holiday leave
        });
    }

    public function scopeHomeLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', 3);
        });
    }

    public function scopeSickLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', 4);
        });
    }

    public function scopeMaternityLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', 5);
        });
    }

    public function scopeMaternityCareLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', 6);
        });
    }

    public function scopeFuneralLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', 7);
        });
    }

    public function scopeSubstituteLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', 8);
        });
    }

    public function scopeWithOutPayLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', 9);
        });
    }

    public function scopeMaternityWithOutPayLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', 10);
        });
    }

    public function calenderHolidaySplits()
    {
        return $this->hasMany(CalenderHolidaySplitMonth::class, 'calender_holiday_id');
    }

    public function calenderHolidayFiles(){
        return $this->hasMany(CalenderHolidayFile::class, 'calender_holiday_id');
    }

}
