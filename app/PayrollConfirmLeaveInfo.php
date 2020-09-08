<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollConfirmLeaveInfo extends Model
{
    protected $fillable = [
        'payroll_confirm_id',
        'leave_id',
        'used',
        'earned',
        'balance',
    ];

    public function leaveMast()
    {
        return $this->belongsTo('\App\SystemLeaveMastModel', 'leave_id');
    }
}
