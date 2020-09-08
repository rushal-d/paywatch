<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffSalaryMastModel extends Model
{
    use SoftDeletes;
    protected $table = 'staff_salary_mast';
    protected $primaryKey = 'salary_id';

    // donot use this model --s
}
