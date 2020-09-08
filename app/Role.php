<?php

namespace App;

use Nicolaslopezj\Searchable\SearchableTrait;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $table = "roles";
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'name' => 1
        ]
    ];

    public function users()
    {
        return $this->belongsToMany('\App\User', 'role_user', 'role_id', 'user_id');

    }

    public function permission()
    {
        return $this->belongsToMany('\App\Permission', 'permission_role', 'role_id', 'permission_id');
    }
}
