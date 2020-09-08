<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class StaffType extends Model
{
    use SoftDeletes, SearchableTrait;

    protected $searchable = [
        'columns' => [
            'staff_type_title' => 1,
            'staff_type_code' => 1
        ]
    ];

    public function staffs()
    {
        return $this->hasMany(StafMainMastModel::class, 'staff_type', 'staff_type_code');
    }
}
