<?php

namespace App\Repositories;

use App\Department;
use App\SystemOfficeMastModel;

class DepartmentRepository extends BaseRepository
{
    public function getAllDepartments()
    {
        $departments = Department::pluck('department_name', 'id');

        $departments = $departments->prepend('Please select a department', '');

        return $departments;
    }

}
