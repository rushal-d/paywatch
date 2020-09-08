<?php

namespace App;

use App\Traits\LeaveableScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Nicolaslopezj\Searchable\SearchableTrait;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';

    protected $guarded = [];

    use SearchableTrait;
    use SoftDeletes;
    use LeaveableScope;

    protected $searchable = [
        'columns' => [
            'staff_main_mast.id' => 1,
            'staff_main_mast.name_eng' => 10
        ],
        /*search by join table ->use table name*/
        'joins' => [
            'staff_main_mast' => ['leave_requests.staff_central_id', 'staff_main_mast.id'],
        ],

    ];

    protected static function boot()
    {
        parent::boot();
        if (!Session::get('role')) {
            static::addGlobalScope('staff_central_id', function (Builder $builder) {
                $branch_id = Auth::user()->branch_id;
                $staff_ids = StafMainMastModel::where('branch_id', $branch_id)->pluck('id')->toArray();
                $builder->whereIn('leave_requests.staff_central_id', $staff_ids);
            });
        }
    }

    public function staff()
    {
        return $this->belongsTo('App\StafMainMastModel', 'staff_central_id');
    }

    public function leave()
    {
        return $this->belongsTo('App\SystemLeaveMastModel', 'leave_id');
    }

    public function getTotalLeaveDaysAttribute()
    {
        return $this->holiday_days + $this->weekend_days + $this->public_holidays - $this->public_weekend;
    }

    public function leaveRequestFiles()
    {
        return $this->hasMany(LeaveRequestFile::class, 'leave_request_id');
    }
}
