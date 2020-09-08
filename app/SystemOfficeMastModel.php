<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;

class SystemOfficeMastModel extends Model
{

    use SearchableTrait;
    use SoftDeletes;
    protected $table = 'system_office_mast';
    protected $primaryKey = 'office_id';

    const sync = 1;
    const unSync = 0;

    const MANUAL_WEEKEND_ENABLE = 1;
    const MANUAL_WEEKEND_DISABLE = 0;


    protected $searchable = [
        'columns' => [
            'office_name' => 1
        ]
    ];

    protected static function boot()
    {
        parent::boot();

        if (!Session::get('role')) {
            static::addGlobalScope('office_id', function (Builder $builder) {
                $user = Auth::user();
                if (!empty($user)) {
                    $branch_id = $user->branch_id;
                    $builder->where('system_office_mast.office_id', '=', $branch_id);
                }

            });
        }
    }

    public function branch_shifts()
    {
        return $this->hasMany(Shift::class, 'branch_id');
    }

    public function staffCitDeductions()
    {
        return $this->hasMany(StaffCitDeduction::class, 'branch_id');
    }
}
