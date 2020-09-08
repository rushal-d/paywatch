<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;

class EmployeeStatus extends Model
{
    use SearchableTrait;
    use SoftDeletes;
    protected $searchable = [
        'columns' => [
            'staff_central_id' => 1,
            'staff_main_mast.name_eng' => 9
        ],
        'joins' => [
            'staff_main_mast' => ['employee_statuses.staff_central_id', 'staff_main_mast.id']
        ]
    ];

    public const STATUS_WORKING = 0;
    public const STATUS_RESIGN = 1;
    public const STATUS_DISMISS = 2;
    public const STATUS_FIRE = 3;
    public const STATUS_SUSPENSE = 4;

    protected static function boot()
    {
        parent::boot();
        if (!Session::get('role')) {
            static::addGlobalScope('staff_central_id', function (Builder $builder) {
                $user = Auth::user();
                if (!empty($user)) {
                    $branch_id = $user->branch_id;
                    $staff_ids = StafMainMastModel::where('branch_id', $branch_id)->pluck('id')->toArray();
                    $builder->whereIn('employee_statuses.staff_central_id', $staff_ids);
                }
            });
        }
    }

    public function staff()
    {
        return $this->belongsTo('\App\StafMainMastModel', 'staff_central_id', 'id');
    }

    public function created_name()
    {
        return $this->belongsTo('\App\User', 'created_by', 'id');
    }

    public function updated_name()
    {
        return $this->belongsTo('\App\User', 'updated_by', 'id');
    }
}
