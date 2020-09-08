<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Education extends \Eloquent
{
    use SearchableTrait,SoftDeletes;
    protected $table = 'system_edu_mast';
	protected $primaryKey = 'edu_id';

    protected $searchable = [
        'columns' => [
            'edu_description' => 1
        ]
    ];
}
