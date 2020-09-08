<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffInsurancePremium extends Model
{
    use SoftDeletes;
    public $table = "staff_insurance_premium";

    public function staff()
    {
        return $this->belongsTo('App\StafMainMastModel', 'staff_central_id');
    }

    public function fiscal_year()
    {
        return $this->belongsTo('\App\FiscalYearModel', 'fiscal_year_id');
    }

    public function branch()
    {
        return $this->belongsTo('\App\SystemOfficeMastModel', 'branch_id');
    }
}
