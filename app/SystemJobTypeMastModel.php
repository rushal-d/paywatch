<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class SystemJobTypeMastModel extends Model
{
    public const JOB_TYPE_FOR_PERMANENT = 1;
    public const JOB_TYPE_FOR_NON_PERMANENT = 2;
    public const JOB_TYPE_FOR_CONTRACT = 3;
    public const JOB_TYPE_FOR_CONTRACT_1 = 4;
    public const JOB_TYPE_FOR_TRAINEE = 5;
    //
    use SearchableTrait;
    use SoftDeletes;
    protected $table = 'system_jobtype_mast';
    protected $primaryKey = 'jobtype_id';
    protected $searchable = [
        'columns' => [
            'jobtype_name' => 1
        ]
    ];

}
