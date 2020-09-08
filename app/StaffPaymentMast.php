<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StaffPaymentMast extends Model
{
    use SoftDeletes;
    protected $table = 'staff_payment_mast';

    protected static function boot()
    {
        parent::boot();
        if (!Session::get('role')) {
            static::addGlobalScope('staff_central_id', function (Builder $builder) {
                $branch_id = Auth::user()->branch_id;
                $staff_ids = StafMainMastModel::where('branch_id', $branch_id)->pluck('id')->toArray();
                $builder->whereIn('staff_payment_mast.staff_central_id', $staff_ids);
            });
        }

    }

    public function allowance()
    {
        return $this->belongsTo(AllowanceModelMast::class, 'allow_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
