<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Caste extends Model
{

    use SoftDeletes;
    protected $fillable = [
        'caste_name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'caste_name' => 10,
            'description' => 5
        ]
    ];
}
