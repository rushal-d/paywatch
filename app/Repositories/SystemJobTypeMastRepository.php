<?php

namespace App\Repositories;

use App\SystemJobTypeMastModel;

class SystemJobTypeMastRepository
{
    public function getAllJobTypes()
    {
        $jobTypes = SystemJobTypeMastModel::get();
        return $jobTypes;
    }
}
