<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;

class StaffTransferModel extends Model
{
    //
    use SearchableTrait;
    use SoftDeletes;
    protected $table = 'staff_transefer_mast';
    protected $primaryKey = 'transfer_id';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        if (!Session::get('role')) {
            static::addGlobalScope('staff_central_id', function (Builder $builder) {
                $user = Auth::user();
                if (!empty($user)) {
                    $branch_id = Auth::user()->branch_id;
                    $staff_ids = StafMainMastModel::where('branch_id', $branch_id)->pluck('id')->toArray();
                    $builder->whereIn('staff_transefer_mast.staff_central_id', $staff_ids);
                }
            });
        }
    }

    public function office()
    {
        return $this->belongsTo('App\SystemOfficeMastModel', 'office_id');

    }

    //used in showing history of staff
    public function office_from_get()
    {
        return $this->hasOne('App\SystemOfficeMastModel', 'office_id', 'office_from');

    }

    public function staff()
    {
        return $this->belongsTo('App\StafMainMastModel', 'staff_central_id');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'autho_id');
    }

    protected $searchable = [
        'columns' => [
            'staff_transefer_mast.staff_central_id' => 10,
            'staff_main_mast.name_eng' => 10,
            'system_office_mast.office_name' => 10
        ],
        /*search by join table ->use table name*/
        'joins' => [
            'staff_main_mast' => ['staff_transefer_mast.staff_central_id', 'staff_main_mast.id'],
            'system_office_mast' => ['staff_transefer_mast.office_id', 'system_office_mast.office_id'],
        ],

    ];
}
