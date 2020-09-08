<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Province extends Model
{
    use SoftDeletes;
    protected $fillable=[
        'religion_name',
        'description',
    ];
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'religion_name' => 10,
            'description' => 5
        ]
    ];
}
