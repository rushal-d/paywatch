<?php

namespace App;

use Nicolaslopezj\Searchable\SearchableTrait;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $table = "permissions";

    protected $fillable = [
        'name',
        'display_name',
        'uri',
        'parent_id',
        'order',
        'icon',
        'isURL',
    ];
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'name' => 1
        ]
    ];

    public function scopeRootPermission($query)
    {
        return $query->where('parent_id', 0)->orderBy('order', 'asc');
    }

    public function scopeNextLevel($query, $id)
    {
        return $query->where('parent_id', $id)->orderBy('order', 'asc');
    }

    public function childPs()
    {
        return $this->hasMany('\App\Permission', 'parent_id', 'id');
    }

    public function scopeRootMenu($query)
    {
        return $query->where('parent_id', 0)->orderBy('order', 'asc');
    }

    public function roles()
    {
        return $this->belongsToMany('\App\Role', 'permission_role', 'permission_id', 'role_id');
    }

    public function parents()
    {
        return $this->hasOne('\App\Permission', 'id', 'parent_id');
    }
}
