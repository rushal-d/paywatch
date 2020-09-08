<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffFileModel extends Model
{
    use SoftDeletes;
    protected $table = 'staff_file';
//    protected $primaryKey = 'salary_primary';
}
