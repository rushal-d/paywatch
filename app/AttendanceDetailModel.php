<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class AttendanceDetailModel extends Model
{
    //
    use SearchableTrait;
    protected $table = 'attendance_details';
    protected $searchable = [
        'columns' => [
            'attendance_details.staff_central_id' => 10,
            'staff_main_mast.name_eng' => 10
        ],
        /*search by join table ->use table name*/
        'joins' => [
            'staff_main_mast' => ['attendance_details.staff_central_id', 'staff_main_mast.id'],
        ],

    ];

    public function payroll()
    {
        return $this->belongsTo('App\PayrollDetailModel', 'payroll_id');
    }

    public function staff()
    {
        return $this->belongsTo('App\StafMainMastModel', 'staff_central_id');
    }

    public function leave()
    {
        return $this->belongsTo('\App\SystemLeaveMastModel', 'leave_id');
    }


}
