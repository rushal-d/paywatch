<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffJobPosition extends Model
{
    use SoftDeletes;

    public function post()
    {
        return $this->belongsTo(SystemPostMastModel::class, 'post_id');
    }

    public function childJobPosition()
    {
        return $this->hasOne(StaffJobPosition::class, 'parent_id');
    }

    public function parentJobPosition()
    {
        return $this->belongsTo(StaffJobPosition::class, 'parent_id');
    }

    public function staff()
    {
        return $this->belongsTo(StafMainMastModel::class, 'staff_central_id');
    }
}
