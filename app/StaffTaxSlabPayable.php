<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffTaxSlabPayable extends Model
{
    public function taxDetail()
    {
        return $this->belongsTo(SystemTdsMastModel::class, 'tds_detail_id');
    }
}
