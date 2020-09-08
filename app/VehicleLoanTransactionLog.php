<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VehicleLoanTransactionLog extends Model
{
    protected static function boot()
    {
        parent::boot();

        if (!Session::get('role')) {
            static::addGlobalScope('staff_central_id', function (Builder $builder) {
                $branch_id = Auth::user()->branch_id;
                $staff_ids = StafMainMastModel::where('branch_id', $branch_id)->pluck('id')->toArray();
                $builder->whereIn('vehicle_loan_transaction_logs.staff_central_id', $staff_ids);
            });
        }


    }

    public function staff()
    {
        return $this->belongsTo(StafMainMastModel::class, 'staff_central_id');
    }

    public function vehicleLoanMasterRecord(){
        return $this->belongsTo(HouseLoanModelMast::class,'vehical_id');
    }

    public function payroll(){
        return $this->belongsTo(PayrollDetailModel::class,'payroll_id');
    }

}
