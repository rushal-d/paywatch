<?php

namespace App\Traits;

trait CheckHasParticularRoleTrait
{
    /**
     * It checks where it is super admin
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->roles->where('name', config('constants.roles.names.administrator'))->first() ? true : false;
    }

    /**
     * It checks where it is employee
     * @return bool
     */
    public function isEmployee()
    {
        return $this->roles->where('name', config('constants.roles.names.employee'))->first() ? true : false;
    }

    /**
     * It checks where it is editor
     * @return bool
     */
    public function isEditor()
    {
        return $this->roles->where('name', config('constants.roles.names.editor'))->first() ? true : false;
    }
}
