<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PayrollCalculationData extends Model
{
    protected $fillable = [
        'payroll_id',
        'staff_central_id',
        'redeem_home_leave',
        'redeem_sick_leave',
        'check_house_loan',
        'check_vehicle_loan',
        'check_sundry_loan',
        'house_loan_installment',
        'vehicle_loan_installment',
        'misc_amount',
        'remarks',
        'grant_home_leave',
        'grant_sick_leave',
        'grant_substitute_leave',
        'grant_maternity_leave',
        'grant_maternity_care_leave',
        'grant_funeral_leave',
        'prepared_by',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        if (!Session::get('role')) {
            static::addGlobalScope('staff_central_id', function (Builder $builder) {
                $branch_id = Auth::user()->branch_id;
                $staff_ids = StafMainMastModel::where('branch_id', $branch_id)->pluck('id')->toArray();
                $builder->whereIn('payroll_calculation_datas.staff_central_id', $staff_ids);
            });
        }

    }
}
