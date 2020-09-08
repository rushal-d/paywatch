<?php

namespace App;

use App\Helpers\SyncHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;

class StafMainMastModel extends Model
{
    //
    use SearchableTrait;
    use SoftDeletes;

    protected $table = 'staff_main_mast';
    protected $fillable = ['staff_central_id', 'main_id', 'branch_id', 'payroll_branch_id'];

    const sync = SyncHelper::sync;
    const unSync = SyncHelper::unSync;

    public const STAFF_STATUS_FOR_WORKING_OR_SUSPENSE = 1;
    public const STAFF_STATUS_FOR_RESIGN_OR_FIRED = 2;

    public const MANUAL_ATTENDANCE_ENABLED = 1;
    public const MANUAL_ATTENDANCE_DISABLED = 0;

    public const STAFF_TYPE_OPTION_FOR_BBSM = 0;
    public const STAFF_TYPE_OPTION_FOR_GUARD_BBSM = 1;
    public const STAFF_TYPE_OPTION_FOR_COMPANY = 2;
    public const STAFF_TYPE_OPTION_FOR_COMPANY_GUARD = 4;
    public const STAFF_TYPE_OPTION_FOR_BBSM_NOT_IN_PAYROLL = 5;

//    protected $primaryKey = 'salary_primary';

    protected static function boot()
    {
        parent::boot();
        if (!Session::get('role')) {
            static::addGlobalScope('branch_id', function (Builder $builder) {
                $user = Auth::user();
                if (!empty($user)) {
                    $branch_id = Auth::user()->branch_id;
                    $builder->where('staff_main_mast.branch_id', '=', $branch_id);
                }
            });

            static::addGlobalScope('id', function (Builder $builder) {
                $user = Auth::user();
                if (!empty($user) && Session::get('isEmployee')) {
                    $builder->where('id', '=', $user->staff_central_id);
                }
            });

        }

        static::addGlobalScope('orderByDesignation', function (Builder $builder) {
            /*$builder->join('system_post_mast', 'system_post_mast.post_id', '=', 'staff_main_mast.post_id')
                ->orderBy('system_post_mast.order');*/
        });
    }


    protected $searchable = [
        'columns' => [
            'name_eng' => 10,
            'main_id' => 10
        ]
    ];


    public function appooffice()
    {
        return $this->belongsTo('App\SystemOfficeMastModel', 'appo_office');
    }

    public function branch()
    {
        return $this->belongsTo('App\SystemOfficeMastModel', 'branch_id');
    }

    public function payrollBranch()
    {
        return $this->belongsTo('App\SystemOfficeMastModel', 'payroll_branch_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }

    public function getSection()
    {
        return $this->belongsTo('App\Section', 'section');
    }

    public function getDepartment()
    {
        return $this->belongsTo('App\Department', 'department');
    }

    public function education()
    {
        return $this->belongsTo('App\Education', 'edu_id');
    }

    public function jobposition()
    {
        return $this->belongsTo('App\SystemPostMastModel', 'post_id');
    }

    public function jobtype()
    {
        return $this->belongsTo('App\SystemJobTypeMastModel', 'jobtype_id');
    }

    public function bankInformation()
    {
        return $this->belongsTo(BankMastModel::class, 'bank_id');
    }

    public function salary()
    {
        return $this->hasMany('App\StaffSalaryModel', 'staff_central_id');
    }

    public function latestsalary()
    {
        return $this->hasOne('App\StaffSalaryModel', 'staff_central_id')->orderByDesc('salary_effected_date')->latest();
    }

    public function latestWorkSchedule()
    {
        return $this->hasOne('App\StaffWorkScheduleMastModel', 'staff_central_id')->orderByDesc('effect_day');
    }

    public function payment()
    {
        return $this->hasMany('App\StaffPaymentMast', 'staff_central_id');
    }

    public function workschedule()
    {
        return $this->hasMany('App\StaffWorkScheduleMastModel', 'staff_central_id');
    }

    public function nominee()
    {
        return $this->hasMany('App\StaffNomineeMastModel', 'staff_central_id');
    }

    public function additionalSalary()
    {
        return $this->hasMany('App\StaffSalaryMastModel', 'staff_central_id', 'id');
    }

    public function district()
    {
        return $this->hasOne('App\DistrictModel', 'id', 'district_id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function caste()
    {
        return $this->belongsTo('App\Caste', 'caste_id');
    }

    public function religion()
    {
        return $this->belongsTo('App\Religion', 'religion_id');
    }

    public function attendanceDetails()
    {
        return $this->hasMany('App\AttendanceDetailModel', 'staff_central_id', 'id');
    }

    public function vehicleLoan()
    {
        return $this->hasMany('App\VehicalLoanModelTrans', 'staff_central_id', 'id');
    }

    public function houseLoan()
    {
        return $this->hasMany('App\HouseLoanModelMast', 'staff_central_id', 'id');
    }

    public function sundryLoan()
    {
        return $this->hasMany('App\SundryTransaction', 'staff_central_id', 'id');
    }

    public function staffStatus()
    {
        return $this->hasMany('App\EmployeeStatus', 'staff_central_id', 'id');
    }

    public function staffTransfer()
    {
        return $this->hasMany('App\StaffTransferModel', 'staff_central_id', 'id');
    }

    public function grantLeave()
    {
        return $this->hasMany('App\CalenderHolidayModel', 'staff_central_id', 'id');
    }

    public function grantLeaveSplits()
    {
        return $this->hasManyThrough('App\CalenderHolidaySplitMonth', 'App\CalenderHolidayModel', 'staff_central_id', 'calender_holiday_id');
    }

    public function shift()
    {
        return $this->hasOne('App\Shift', 'id', 'shift_id');
    }

    public function latestShift()
    {
        return $this->hasOne('App\StaffShiftHistory', 'staff_central_id', 'id')->latest();
    }

    public function shiftHistory()
    {
        return $this->hasMany('App\StaffShiftHistory', 'staff_central_id', 'id');
    }

    public function fetchAttendances()
    {
        return $this->hasMany(FetchAttendance::class, 'staff_central_id', 'id');
    }

    public function leaveBalance()
    {
        return $this->hasMany(LeaveBalance::class, 'staff_central_id');
    }

    public function latestLeaveBalanceWithID($leave_id)
    {
        return $this->hasOne(LeaveBalance::class, 'staff_central_id')->where('leave_id', $leave_id)->latest()->first();
    }

    public function fingerprint()
    {
        return $this->hasOne(StaffFingerprint::class, 'staff_central_id', 'id');
    }

    public function payrolls()
    {
        return $this->hasMany(PayrollDetailModel::class, 'staff_central_id', 'id');
    }

    public function homeLeaveBalanceLast()
    {
        return $this->hasOne(LeaveBalance::class, 'staff_central_id')->whereHas('leave', function ($query) {
            $query->where('leave_code', 3);
        })->latest();
    }

    public function sickLeaveBalanceLast()
    {
        return $this->hasOne(LeaveBalance::class, 'staff_central_id')->whereHas('leave', function ($query) {
            $query->where('leave_code', 4);
        })->latest();
    }

    public function substituteLeaveBalanceLast()
    {
        return $this->hasOne(LeaveBalance::class, 'staff_central_id')->whereHas('leave', function ($query) {
            $query->where('leave_code', 8);
        })->latest();
    }

    public function maternityLeaveBalanceLast()
    {
        return $this->hasOne(LeaveBalance::class, 'staff_central_id')->whereHas('leave', function ($query) {
            $query->where('leave_code', 5);
        })->latest();
    }

    public function maternityCareLeaveBalanceLast()
    {
        return $this->hasOne(LeaveBalance::class, 'staff_central_id')->whereHas('leave', function ($query) {
            $query->where('leave_code', 6);
        })->latest();
    }

    public function funeralLeaveBalanceLast()
    {
        return $this->hasOne(LeaveBalance::class, 'staff_central_id')->whereHas('leave', function ($query) {
            $query->where('leave_code', 7);
        })->latest();
    }

    public function houseLoanToDeduct()
    {
        return $this->hasOne(HouseLoanModelMast::class, 'staff_central_id')->whereIn('account_status', [0, 1])->latest();
    }

    public function vehicleLoanToDeduct()
    {
        return $this->hasOne(VehicalLoanModelTrans::class, 'staff_central_id')->whereIn('account_status', [0, 1])->latest();
    }

    public function fiscalYearAttendanceSum()
    {
        return $this->hasMany(FiscalYearAttendanceSum::class, 'staff_central_id');
    }

    public function loanDeducation()
    {
        return $this->hasMany(LoanDeduct::class, 'staff_central_id');
    }

    public function staffFiles()
    {
        return $this->hasMany(StaffFileModel::class, 'staff_central_id');
    }

    public function staffAlternativeShifts()
    {
        return $this->hasOne(AlternativeDayShift::class, 'staff_central_id');
    }

    public function AllstaffAlternativeShifts()
    {
        return $this->hasMany(AlternativeDayShift::class, 'staff_central_id');
    }

    public function staffCitDeductions()
    {
        return $this->hasMany(StaffCitDeduction::class, 'staff_central_id');
    }

    public function staffType()
    {
        return $this->belongsTo(StaffType::class, 'staff_type', 'staff_type_code');
    }

    public function houseLoanDiffIncome()
    {
        return $this->hasManyThrough(HouseLoanDiffIncome::class, HouseLoanModelMast::class, 'staff_central_id', 'house_loan_id');
    }

    public function vehicleLoanDiffIncome()
    {
        return $this->hasManyThrough(VehicleLoanDiffIncome::class, VehicalLoanModelTrans::class, 'staff_central_id', 'vehicle_loan_id');
    }

    public function staffBonuses()
    {
        return $this->hasMany(Bonuses::class, 'staff_central_id');
    }

    public function staffInsurance()
    {
        return $this->hasMany(StaffInsurancePremium::class, 'staff_central_id');
    }

    public function taxPayable()
    {
        return $this->hasMany(StaffTaxSlabPayable::class, 'staff_central_id');
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)
            ->with([$relation => $constraint]);
    }

    //warningNoWorkSchedule
    public function scopeWarningNoWorkSchedule($query)
    {
        return $query->whereDoesntHave('workschedule');
    }

    //warningNoWeekend
    public function scopeWarningNoWeekend($query)
    {
        return $query->whereHas('workschedule', function ($innerInnerQuery) {
            $innerInnerQuery->whereNull('weekend_day');
        });
    }

    //warningNoWorkHour
    public function scopeWarningNoWorkHour($query)
    {
        return $query->whereHas('workschedule', function ($innerInnerQuery) {
            $innerInnerQuery->whereNull('work_hour');
        });
    }

    //warningNoPostId
    public function scopeWarningNoPostId($query)
    {
        return $query->where('post_id', 0)
            ->orWhereNull('post_id');
    }

    //warningNoJobType
    public function scopeWarningNoJobType($query)
    {
        return $query->whereNull('jobtype_id')
            ->orWhere('jobtype_id', 0);
    }

    //warningNoBranch
    public function scopeWarningNoBranch($query)
    {
        return $query->whereNull('branch_id')
            ->orWhere('branch_id', 0);
    }

    //warningNoDateOfBirth
    public function scopeWarningNoDateOfBirth($query)
    {
        return $query->whereNull('staff_dob');
    }

    //warningNoAppoDate
    public function scopeWarningNoAppoDate($query)
    {
        return $query->whereNull('appo_date');
    }

    //warningNoStaffCentralId
    public function scopeWarningNoStaffCentralId($query)
    {
        return $query->whereNull('staff_central_id');
    }

    //warningNoPermanentDateForPermanentStaff
    public function scopeWarningNoPermanentDateForPermanentStaff($query)
    {
        return $query->where('jobtype_id', SystemJobTypeMastModel::JOB_TYPE_FOR_PERMANENT)->whereNull('permanent_date');
    }

    //warningNoBankForBankAccountStaff
    public function scopeWarningNoBankForBankAccountStaff($query)
    {
        return $query->whereNotNull('acc_no')->whereNull('bank_id');
    }

    //warningNoTemporaryConDate
    public function scopeWarningNoTemporaryConDate($query)
    {
        return $query->whereNull('temporary_con_date');
    }

    //dateOfBirthOlderThanGivenNumberOfYearsOld
    public function scopeDateOfBirthOlderThanGivenNumberOfYearsOld($query, $yearNumber)
    {
//        return $query;
        return $query->whereNotNull('staff_dob')->where('staff_dob', '<=', date('Y-m-d', strtotime('-' . $yearNumber . ' years')))
            ->whereIn('staff_type', [0, 1])
            ->whereNotNull('post_id')
            ->where(function ($query) {
                $query->where('jobtype_id', '<>', 4)
                    ->orWhereNull('jobtype_id');
            });
    }

    //nonPermanentToPermanent
    public function scopeNonPermanentToPermanent($query)
    {
        return $query
            ->whereNotNull('appo_date')
            ->whereIn('staff_type', [0, 1])
            ->where('jobtype_id', 2)->where('appo_date', '<>', null);
    }

    //traineeToNonPermanent
    public function scopeTraineeToNonPermanent($query)
    {
        return $query
            ->whereNotNull('appo_date')
            ->whereIn('staff_type', [0, 1])
            ->where(function ($subQuery) {
                $subQuery->where('jobtype_id', 5);
            })
            ->where('appo_date', '<>', null);
    }

    public function staffGrades()
    {
        return $this->hasMany(StaffGrade::class, 'staff_central_id');
    }

    public function staffPosts()
    {
        return $this->hasMany(StaffJobPosition::class, 'staff_central_id');
    }

    //ofPayrollStaffFilter
    public function scopeOfPayrollStaffFilter($query, $branch_id)
    {
        return $query->whereHas('jobtype', function ($query) {
            $query->where('jobtype_code', '<>', 'T');
        })
            ->where('payroll_branch_id', $branch_id)->where(function ($innerQuery) {
                $innerQuery->whereHas('workschedule', function ($innerInnerQuery) {
                    $innerInnerQuery->whereNotNull('weekend_day')->whereNotNull('work_hour');
                })->where(function ($innerInnerQuery) {
                    $innerInnerQuery->where('post_id', '<>', 0)
                        ->whereNotNull('post_id')
                        ->whereNotNull('jobtype_id')
                        ->where('jobtype_id', '<>', 0);
                });
            })
            ->whereIn('staff_type', [0, 1])->orderBy('main_id');
    }

}
