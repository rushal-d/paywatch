<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Section extends Model
{
    use SearchableTrait;
    use SoftDeletes;
    protected $table = 'sections';
    protected $primaryKey = 'id';

    protected $searchable = [
        'columns' => [
            'section_name' => 1
        ]
    ];
}
