<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;

class Department extends Model
{
    use SoftDeletes;
    use SearchableTrait;
    protected $table = 'departments';
    protected $primaryKey = 'id';

    protected $fillable = ['department_name', 'created_by'];

    protected $searchable = [
        'columns' => [
            'department_name' => 10
        ]
    ];
}
