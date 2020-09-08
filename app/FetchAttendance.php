<?php

namespace App;

use App\Traits\EloquentGetTableNameTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;

class FetchAttendance extends Model
{
    use SearchableTrait, EloquentGetTableNameTrait;

    use SoftDeletes;

    public static function boot()
    {
        parent::boot();

        self::updating(function ($model) {
            if (auth()->check()) {
                //TODO:: replace this
                $model->updated_by = auth()->id();
            }
        });

        self::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
        /*if (!Session::get('roles')) {
            $user = Auth::user();
            if (!empty($user)) {
                static::addGlobalScope('staff_central_id', function (Builder $builder) use($user){
                    $builder->where('staff_central_id', $user->staff_central_id);
                });
            }
        }*/

    }

    const forceLeave = 99;
    const forceDelete = 88;
    const manualAttendance = 77;

    const sync = 1;
    const unSync = 0;

    const forced = 1;
    const notForced = 0;

    protected $guarded = [];

    protected $searchable = [

        'columns' => [
            'staff_main_mast.name_eng' => 10,

        ],
        'joins' => [
            'staff_main_mast' => ['staff_main_mast.id', 'fetch_attendances.staff_central_id'],
        ],
    ];

    public function staff()
    {
        return $this->belongsTo('\App\StafMainMastModel', 'staff_central_id');
    }

    public function branch()
    {
        return $this->belongsTo('App\SystemOfficeMastModel', 'branch_id');
    }

    public function shift()
    {
        return $this->belongsTo('App\Shift', 'shift_id');
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)
            ->with([$relation => $constraint]);
    }

    //last_modified_by
    public function getLastModifiedByAttribute()
    {
        if (is_null($this->created_by) && is_null($this->updated_by)) {
            return '';
        }

        if (!is_null($this->updated_by)) {
            $updatedByUser = User::where('id', $this->updated_by)->first();

            if ($updatedByUser->exists()) {
                return $updatedByUser->name;
            }
        }

        if (!is_null($this->created_by)) {
            $createdByUser = User::where('id', $this->created_by)->first();

            if ($createdByUser->exists()) {
                return $createdByUser->name;
            }
        }
        return '';
    }
}
