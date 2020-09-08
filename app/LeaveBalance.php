<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;

class LeaveBalance extends Model
{
    //
    use SearchableTrait;
    use SoftDeletes;
    protected $table = 'leave_balance';
    protected $searchable = [
        'columns' => [
            'staff_main_mast.id' => 1,
            'staff_main_mast.name_eng' => 10
        ],
        /*search by join table ->use table name*/
        'joins' => [
            'staff_main_mast' => ['leave_balance.staff_central_id', 'staff_main_mast.id'],
        ],

    ];

    protected static function boot()
    {
        parent::boot();
        if (!Session::get('role')) {
            static::addGlobalScope('staff_central_id', function (Builder $builder) {
                $branch_id = Auth::user()->branch_id;
                $staff_ids = StafMainMastModel::where('branch_id', $branch_id)->pluck('id')->toArray();
                $builder->whereIn('staff_central_id', $staff_ids);
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

    public function fiscal()
    {
        return $this->belongsTo('App\FiscalYearModel', 'fy_id');
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

    public function scopeSubstituteLeave($query)
    {
        $query->whereHas('leave', function ($query) {
            $query->where('leave_code', 8);
        });
    }

}
