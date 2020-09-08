<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransDashainPayment extends Model
{
    protected $table = 'trans_dashain_payments';

    public function staff()
    {
        return $this->belongsTo('\App\StafMainMastModel', 'staff_central_id');
    }
}
