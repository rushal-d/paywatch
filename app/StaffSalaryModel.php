<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffSalaryModel extends Model
{
    use SoftDeletes;
    protected $table = 'staff_salary_mast';
    protected $primaryKey = 'salary_id';

    public function post()
    {
        return $this->belongsTo('App\SystemPostMastModel', 'post_id');
    }

    public function staff()
    {
        return $this->belongsTo('\App\StafMainMastModel', 'staff_central_id');
    }

    public function created_get()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updated_get()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function fiscalyear()
    {
        return $this->belongsTo('\App\FiscalYearModel', 'fiscal_year_id');
    }
}
