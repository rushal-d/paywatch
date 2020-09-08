<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class FileType extends Model
{
    use SoftDeletes;
    use SearchableTrait;

    protected $searchable = [
        'columns' => [
            'file_type' => 1,
            'file_section' => 1
        ]
    ];
}
