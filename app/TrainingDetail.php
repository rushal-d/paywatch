<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingDetail extends Model
{
    use SoftDeletes;

    public function staff()
    {
        return $this->belongsTo(StafMainMastModel::class, 'staff_central_id');
    }

    public function trainingDetailFiles()
    {
        return $this->hasMany(TrainingDetailFile::class, 'training_detail_id');
    }
}
