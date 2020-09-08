<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class AllowanceModelMast extends Model
{
    //
    use SearchableTrait;
    use SoftDeletes;
    protected $table = 'system_allwance_mast';
    protected $primaryKey = 'allow_id';

    protected $searchable = [
        'columns' => [
            'allow_title' => 1
        ]
    ];
}
