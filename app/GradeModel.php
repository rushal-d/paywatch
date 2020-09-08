<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeModel extends Model
{
    //
    use SoftDeletes;
    protected $table = 'grades';

}
