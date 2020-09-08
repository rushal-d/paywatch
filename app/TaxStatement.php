<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TaxStatement extends Model
{
    protected static function boot()
    {
        parent::boot();
        if (!Session::get('role')) {

            static::addGlobalScope('staff_central_id', function (Builder $builder){
                $branch_id = Auth::user()->branch_id;
                $staff_ids = StafMainMastModel::where('branch_id', $branch_id)->pluck('id')->toArray();
                $builder->whereIn('tax_statements.staff_central_id', $staff_ids);
            });
        }

    }

    public function staff()
    {
        return $this->belongsTo('\App\StafMainMastModel', 'staff_central_id');
    }

    public function branch()
    {
        return $this->belongsTo('\App\SystemOfficeMastModel', 'branch_id');
    }

    public function payroll()
    {
        return $this->belongsTo('\App\PayrollDetailModel', 'payroll_id')->withoutGlobalScope('has_bonus');
    }

    public function post()
    {
        return $this->belongsTo('\App\SystemPostMastModel', 'post_id');
    }

}
