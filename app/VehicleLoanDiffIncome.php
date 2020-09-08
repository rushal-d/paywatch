<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleLoanDiffIncome extends Model
{
    use SoftDeletes;

    public function vehicleLoan()
    {
        return $this->belongsTo(VehicalLoanModelTrans::class, 'vehicle_loan_id', 'vehical_id');
    }

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYearModel::class, 'fiscal_year_id');
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
