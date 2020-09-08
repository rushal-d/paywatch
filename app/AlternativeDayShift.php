<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlternativeDayShift extends Model
{
    use SoftDeletes;
    public function staff()
    {
        return $this->belongsTo('App\StafMainMastModel', 'staff_central_id');
    }

    public function shift()
    {
        return $this->belongsTo('App\Shift', 'shift_id');
    }
}
