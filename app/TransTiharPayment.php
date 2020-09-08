<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransTiharPayment extends Model
{
    protected $table = 'trans_tihar_payments';

    public function staff()
    {
        return $this->belongsTo('\App\StafMainMastModel', 'staff_central_id');
    }
}
