<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SundryTransactionLog extends Model
{
    protected static function boot()
    {
        parent::boot();

        if (!Session::get('role')) {
            static::addGlobalScope('staff_central_id', function (Builder $builder) {
                $branch_id = Auth::user()->branch_id;
                $staff_ids = StafMainMastModel::where('branch_id', $branch_id)->pluck('id')->toArray();
                $builder->whereIn('sundry_transaction_logs.staff_central_id', $staff_ids);
            });
        }
    }


    public function staff()
    {
        return $this->belongsTo('App\StafMainMastModel', 'staff_central_id');
    }

    public function sundryType()
    {
        return $this->belongsTo('\App\SundryType', 'transaction_type_id');
    }

    public function sundryTransaction()
    {
        return $this->belongsTo('\App\SundryTransaction', 'sundry_id');
    }

}
