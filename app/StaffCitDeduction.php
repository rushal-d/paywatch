<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffCitDeduction extends Model
{
    use SoftDeletes;

    public function staff()
    {
        return $this->belongsTo(StafMainMastModel::class, 'staff_central_id');
    }

    public function branch()
    {
        return $this->belongsTo(SystemOfficeMastModel::class, 'branch_id', 'office_id');
    }

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYearModel::class, 'fiscal_year_id');
    }

    public function payroll()
    {
        return $this->belongsTo(PayrollDetailModel::class, 'payroll_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
