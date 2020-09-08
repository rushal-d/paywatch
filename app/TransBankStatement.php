<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TransBankStatement extends Model
{


    protected $table = 'trans_bank_statement';

    protected static function boot()
    {
        parent::boot();

        if (!Session::get('role')) {
            static::addGlobalScope('branch_id', function (Builder $builder) {
                $branch_id = Auth::user()->branch_id;
                $builder->where(' trans_bank_statement.branch_id', '=', $branch_id);
            });
        }
    }

    public function staff()
    {
        return $this->belongsTo('\App\StafMainMastModel', 'staff_central_id');
    }

    public function branch()
    {
        return $this->belongsTo('\App\SystemOfficeMastModel', 'branch_id');
    }

    public function bank()
    {
        return $this->belongsTo('\App\BankMastModel', 'bank_id');
    }

    public function payroll()
    {
        return $this->belongsTo('\App\PayrollDetailModel', 'payroll_id')->withoutGlobalScope('has_bonus');
    }
}
