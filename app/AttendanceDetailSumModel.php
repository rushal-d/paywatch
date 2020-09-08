<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;

class AttendanceDetailSumModel extends Model
{
    //
    protected $table = 'attendance_details_sum';
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'attendance_details_sum.staff_central_id' => 10,
            'staff_main_mast.name_eng' => 10
        ],
        /*search by join table ->use table name*/
        'joins' => [
            'staff_main_mast' => ['attendance_details_sum.staff_central_id', 'staff_main_mast.id'],
        ],

    ];
    protected static function boot()
    {
        parent::boot();

        if (!Session::get('role')) {
            static::addGlobalScope('branch_id', function (Builder $builder) {
                $branch_id = Auth::user()->branch_id;
                $builder->where('branch_id', '=', $branch_id);
            });
        }

    }

    public function payroll()
    {
        return $this->belongsTo('App\PayrollDetailModel', 'payroll_id');
    }

    public function staff()
    {
        return $this->belongsTo('App\StafMainMastModel', 'staff_central_id');
    }

    public function homeleavebalance()
    {
        $instance = $this->hasMany('\App\LeaveBalance', 'staff_central_id', 'id');
        $instance->where('leave_id', '=', 7)->latest()->first();
        return $instance;
    }
    public function sickleavebalance()
    {
        $instance = $this->hasMany('\App\LeaveBalance', 'staff_central_id', 'id');
        $instance->where('leave_id', '=', 8)->latest()->first();
        return $instance;
    }

}
