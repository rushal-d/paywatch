<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class LocalAttendanceTimeSetting extends Model
{
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'shift_id' => 10
        ]
    ];

    protected $fillable = [
        'shift_id',
        'punch_in',
        'punch_in_threshold',
        'punch_out',
        'punch_out_threshold',
        'min_tiffin_out',
        'max_tiffin_in',
        'tiffin_duration',
        'tiffin_duration',
        'min_lunch_out',
        'max_lunch_in',
        'lunch_duration',
        'lunch_threshold',
        'personal_in_out_duration',
        'personal_in_out_threshold',

    ];
    public function shift()
    {
        return $this->belongsTo(Shift::class,'shift_id');
    }
}
