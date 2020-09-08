<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveRequestFile extends Model
{
    public function staffFile()
    {
        return $this->belongsTo(StaffFileModel::class, 'staff_file_id');
    }
}
