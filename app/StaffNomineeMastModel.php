<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffNomineeMastModel extends Model
{
    use SoftDeletes;
    protected $table = 'staff_nominee_mast';
    protected $primaryKey = 'nominee_id';

    public function staff()
    {
        return $this->belongsTo(StafMainMastModel::class, 'staff_central_id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
}
