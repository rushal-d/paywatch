<?php

namespace App;

use App\Repositories\AuthRepository;
use App\Traits\CheckHasParticularRoleTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;
    use SearchableTrait;
    use CheckHasParticularRoleTrait;


    protected $searchable = [
        'columns' => [
            'name' => 1
        ]
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'branch_id', 'staff_central_id', 'department_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();
            static::addGlobalScope('deleted_at_users', function (Builder $builder) {
                $builder->where('deleted_at','=', null);
            });
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function branch()
    {
        return $this->hasOne('\App\SystemOfficeMastModel', 'office_id', 'branch_id');
    }

    public function staff()
    {
        return $this->belongsTo(StafMainMastModel::class, 'staff_central_id');
    }

    //authCurrentBranch
    public function scopeAuthCurrentBranch($query)
    {
        if (!auth()->check()) {
            return $query;
        }

        if (AuthRepository::isAdministrator()) {
            return $query;
        }

        if(empty(auth()->user()->branch_id)){
            return $query;
        }
        return $query->where('branch_id', auth()->user()->branch_id);
    }

}
