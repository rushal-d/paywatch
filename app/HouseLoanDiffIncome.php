<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseLoanDiffIncome extends Model
{
    use SoftDeletes;

    public function houseLoan()
    {
        return $this->belongsTo(HouseLoanModelMast::class, 'house_loan_id', 'house_id');
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
