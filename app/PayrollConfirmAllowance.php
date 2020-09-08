<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollConfirmAllowance extends Model
{
    protected $fillable = [
        'payroll_confirm_id',
        'allow_id',
        'amount',
    ];

    public function allowanceMast()
    {
        return $this->belongsTo('\App\AllowanceModelMast', 'allow_id');
    }
}
