<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffGrade extends Model
{
    use SoftDeletes;

    public function grade()
    {
        return $this->belongsTo(GradeModel::class, 'grade_id');
    }
}
