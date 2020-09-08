<?php
/**
 * Created by PhpStorm.
 * User: Shree Sthapit
 * Date: 17-May-2018
 * Time: 11:54 AM
 */

namespace App\Helpers;


use App\Permission;
use App\PermissionRole;
use App\RoleUser;
use Illuminate\Support\Facades\Auth;

class MenuHelper
{

    public static function allMenu()
    {
        $user_roles = RoleUser::where('user_id', Auth::user()->id)->pluck('role_id')->toArray();
        $per = PermissionRole::whereIn('role_id', $user_roles)->pluck('permission_id')->toArray();
        $allpermission = Permission::whereIn('id', $per)->with("childPs")->orderBy('order', 'asc');
        return $allpermission;
    }
}