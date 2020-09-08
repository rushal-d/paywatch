<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffWorkScheduleMastModel extends Model
{
    use SoftDeletes;
    protected $table = 'staff_workschedule_mast';
    protected $primaryKey = 'work_id';
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
}
