<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class AppVersion extends Model
{
    use SearchableTrait, SoftDeletes;

    protected $searchable = [
        'columns' => [
            'app_version_name' => 1
        ]
    ];
}
