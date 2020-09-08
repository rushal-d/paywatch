<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;

class Shift extends Model
{
    use SearchableTrait;
    use SoftDeletes;
    const sync = 1;
    const unSync = 0;
    protected $searchable = [
        'columns' => [
            'shift_name' => 10
        ]
    ];

    protected static function boot()
    {
        parent::boot();
        if (!Session::get('role')) {
            static::addGlobalScope('shift_id', function (Builder $builder) {
                $user = Auth::user();
                if (!empty($user)) {
                    $branch_id = $user->branch_id;
                    $builder->where('shifts.branch_id', '=', $branch_id);
                }
            });
        }
    }

    protected $fillable = [
        'shift_name',
        'branch_id',
        'punch_in',
        'before_punch_in_threshold',
        'after_punch_in_threshold',
        'before_punch_out_threshold',
        'after_punch_out_threshold',
        'punch_out',
        'min_tiffin_out',
        'max_tiffin_in',
        'tiffin_duration',
        'before_tiffin_threshold',
        'after_tiffin_threshold',
        'min_lunch_out',
        'max_lunch_in',
        'lunch_duration',
        'before_lunch_threshold',
        'after_lunch_threshold',
        'personal_in_out_duration',
        'personal_in_out_threshold',
        'parent_id',
        'sync',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function branch()
    {
        return $this->belongsTo(SystemOfficeMastModel::class, 'branch_id');
    }

    public function staff()
    {
        return $this->hasMany('App\StafMainMastModel', 'shift_id');
    }
}
