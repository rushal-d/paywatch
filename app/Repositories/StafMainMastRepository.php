<?php

namespace App\Repositories;

use App\StafMainMastModel;
use phpDocumentor\Reflection\Types\Integer;

class StafMainMastRepository
{
    public function getListByBranch($branchId)
    {
        $staffsList = $this->getByBranch($branchId)->pluck('name_eng', 'id');
        return $staffsList;
    }

    public function getByBranch($branchId)
    {
        $staffs = StafMainMastModel::where('branch_id', $branchId);
        return $staffs;
    }

    public function canStaffViewableByAuthenticatedUser($staff_central_id)
    {
        if (!auth()->user()->hasRole('Employee')) {
            return true;
        } else {
            return auth()->user()->staff_central_id == $staff_central_id;
        }
    }

    /**
     * @param $request_staff_central_id
     * @return mixed
     */
    public function getViewableStaffCentralIdFromRequestedStaffCentralId($request_staff_central_id)
    {
        return $this->canStaffViewableByAuthenticatedUser($request_staff_central_id) ? $request_staff_central_id : auth()->user()->staff_central_id;
    }

    public function getListsOfWarning()
    {
        return [
            'no_work_schedule' => 'No Work Schedule',
            'no_weekend' => 'No weekend',
            'no_work_hour' => 'No Work Hour',
            'no_post_id' => 'No Posts',
            'no_job_type' => 'No Job Type',
            'no_branch' => 'No Branch',
            'no_date_of_birth' => 'No Date of Birth',
            'no_appo_date' => 'No Appo Date',
            'no_central_id' => 'No Central ID',
            'no_permanent_date_for_permanent_staff' => 'No Permanent Date For Permanent Staff',
            'no_bank_for_bank_account_staff' => 'No Bank Selected For Bank Account Staff',
            'no_temporary_con_date' => 'No Temporary Contract Date',
        ];
    }

}
