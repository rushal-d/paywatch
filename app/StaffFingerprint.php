<?php

namespace App;

use App\Helpers\SyncHelper;
use Illuminate\Database\Eloquent\Model;

class StaffFingerprint extends Model
{
    protected $fillable = ['branch_id', 'sync'];

    public function staff()
    {
        return $this->belongsTo(StafMainMastModel::class, 'staff_central_id');
    }

    public function branch()
    {
        return $this->belongsTo(SystemOfficeMastModel::class, 'branch_id');
    }

    public function scopeSync($query)
    {
        return $query->where('sync', SyncHelper::sync);
    }

}
