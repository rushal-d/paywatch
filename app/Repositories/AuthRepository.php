<?php

namespace App\Repositories;

class AuthRepository
{
    public static function isEmployee()
    {
        $count = auth()->user()->roles()->where('name', 'Employee')->count();
        return self::isCountGreaterThanZero($count);
    }

    public static function isEditor()
    {
        $count = auth()->user()->roles()->where('name', 'Editor')->count();
        return self::isCountGreaterThanZero($count);
    }

    public static function isPayrollConfirm()
    {
        $count = auth()->user()->roles()->where('name', 'Payroll Confirm')->count();
        return self::isCountGreaterThanZero($count);
    }

    public static function isAdministrator()
    {
        $count = auth()->user()->roles()->where('name', 'Administrator')->count();
        return self::isCountGreaterThanZero($count);
    }

    /**
     * @param $count
     * @return bool
     */
    private static function isCountGreaterThanZero($count): bool
    {
        if ($count > 0)
            return true;
        else
            return false;
    }

}
