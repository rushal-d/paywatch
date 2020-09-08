<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PayrollConfirm extends Model
{
    protected $fillable = [
        'payroll_id',
        'staff_central_id',
        'min_work_hour',
        'tax_code',
        'total_worked_hours',
        'days_absent_on_holiday',
        'weekend_work_hours',
        'public_holiday_work_hours',
        'present_days',
        'absent_days',
        'redeem_home_leave',
        'redeem_sick_leave',
        'salary_hour_payable',
        'ot_hour_payable',
        'basic_salary',
        'dearness_allowance',
        'special_allowance',
        'extra_allowance',
        'gratuity_amount',
        'social_security_fund_amount',
        'incentive',
        'outstation_facility_amount',
        'pro_fund',
        'pro_fund_contribution',
        'home_sick_redeem_amount',
        'ot_amount',
        'gross_payable',
        'loan_payment',
        'sundry_dr',
        'sundry_cr',
        'tax',
        'net_payable',
        'levy_amount',
        'home_leave_taken',
        'sick_leave_taken',
        'maternity_leave_taken',
        'maternity_care_leave_taken',
        'funeral_leave_taken',
        'substitute_leave_taken',
        'unpaid_leave_taken',
        'suspended_days',
        'useable_home_leave',
        'useable_sick_leave',
        'useable_substitute_leave',
        'remarks',
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
                $builder->whereIn('payroll_confirms.staff_central_id', $staff_ids);
            });
        }
    }

    public function staff()
    {
        return $this->belongsTo('App\StafMainMastModel', 'staff_central_id');
    }

    public function jobposition()
    {
        return $this->belongsTo('App\SystemPostMastModel', 'post_id');
    }

    public function jobtype()
    {
        return $this->belongsTo('App\SystemJobTypeMastModel', 'jobtype_id');
    }

    public function leave_payrolls()
    {
        return $this->belongsTo('App\LeavePayrollConfirm', 'id', 'payroll_confirm_id');
    }

    public function payroll()
    {
        return $this->belongsTo('App\PayrollDetailModel', 'payroll_id');
    }

    /*public function leavePayrollConfirm()
    {
        return $this->hasOne('App\LeavePayrollConfirm', 'payroll_confirm_id', 'id');
    }*/

    public function payrollConfirmAllowances()
    {
        return $this->hasMany('\App\PayrollConfirmAllowance', 'payroll_confirm_id');
    }

    public function payrollConfirmLeaveInfos()
    {
        return $this->hasMany('\App\PayrollConfirmLeaveInfo', 'payroll_confirm_id');
    }


}
