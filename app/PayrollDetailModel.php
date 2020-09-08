<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;

class PayrollDetailModel extends Model
{
    use SearchableTrait, SoftDeletes;
    protected $searchable = [
        'columns' => [
            'branch_id' => 10
        ],

    ];

    protected $table = 'payroll_details';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('branch_id', function (Builder $builder) {
            if (!Session::get('role')) {
                $branch_id = Auth::user()->branch_id;
                $builder->where('payroll_details.branch_id', '=', $branch_id);
            }
        });


        /* static::addGlobalScope('has_bonus', function (Builder $builder) {
             $builder->where('has_bonus', '=', null);
         });*/

    }

    public function branch()
    {
        return $this->belongsTo('App\SystemOfficeMastModel', 'branch_id');
    }

    public function fiscalyear()
    {
        return $this->belongsTo('App\FiscalYearModel', 'fiscal_year');
    }

    public function attendanceSummary()
    {
        return $this->hasMany('\App\AttendanceDetailSumModel', 'payroll_id');
    }

    public function payrollConfirm()
    {
        return $this->hasMany(PayrollConfirm::class, 'payroll_id');
    }

    public function dashainPayment()
    {
        return $this->hasMany(TransDashainPayment::class, 'payroll_id');
    }

    public function tiharPayment()
    {
        return $this->hasMany(TransTiharPayment::class, 'payroll_id');
    }

    public function socalSecurityTaxPayment()
    {
        return $this->hasMany(SocialSecurityTaxStatement::class, 'payroll_id');
    }

    public function incomeTaxPayment()
    {
        return $this->hasMany(TaxStatement::class, 'payroll_id');
    }

    public function citLedger()
    {
        return $this->hasMany(CitLedger::class, 'payroll_id');
    }

    public function profundLedger()
    {
        return $this->hasMany(ProFund::class, 'payroll_id');
    }

    public function dashainTaxPayment()
    {
        return $this->hasMany(DashainTaxStatement::class, 'payroll_id');
    }

    public function grantLeaves()
    {
        return $this->hasMany('\App\CalenderHolidayModel', 'payroll_id');
    }

    public function leaveBalances()
    {
        return $this->hasMany('\App\LeaveBalance', 'payroll_id');
    }

    public function houseLoanTransactionLogs()
    {
        return $this->hasMany('\App\HouseLoanTransactionLog', 'payroll_id');
    }

    public function vehicleLoanTransactionLogs()
    {
        return $this->hasMany('\App\VehicleLoanTransactionLog', 'payroll_id');
    }

    public function sundryLoanTransactionLogs()
    {
        return $this->hasMany('\App\SundryTransactionLog', 'payroll_id');
    }

    public function staffCitDeductions()
    {
        return $this->hasMany(StaffCitDeduction::class, 'payroll_id');
    }

    public function payrollConfirmAllowances()
    {
        return $this->hasManyThrough('\App\PayrollConfirmAllowance', 'App\PayrollConfirm', 'payroll_id', 'payroll_confirm_id');
    }

    public function payrollConfirmLeaveInfos()
    {
        return $this->hasManyThrough('\App\PayrollConfirmLeaveInfo', 'App\PayrollConfirm', 'payroll_id', 'payroll_confirm_id');
    }
}
