<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingDetailFile extends Model
{
    protected $fillable = [
        'training_detail_id',
        'staff_file_id'
    ];

    public function staffFile()
    {
        return $this->belongsTo(StaffFileModel::class, 'staff_file_id');
    }
}
