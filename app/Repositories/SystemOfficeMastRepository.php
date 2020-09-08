<?php

namespace App\Repositories;

use App\SystemOfficeMastModel;

class SystemOfficeMastRepository
{
    public function retrieveAllBranchList()
    {
        $branchesList = $this->retrieveAllBranchListWithoutDefaultPlaceholder();

        if ($branchesList->count() > 1) {
            $branchesList = $branchesList->prepend('Please select a branch', '');
        }

        return $branchesList;
    }

    public function retrieveAllBranchListWithoutDefaultPlaceholder()
    {
        $branchesList = SystemOfficeMastModel::pluck('office_name', 'office_id');
        return $branchesList;
    }

    public function retrieveAllBranchListWithPlaceHolder($placeHolder = 'All Branches', $value = '')
    {
        $branchesList = $this->retrieveAllBranchListWithoutDefaultPlaceholder();
        if ($branchesList->count() > 1) {
            $branchesList = $branchesList->prepend($placeHolder, $value);
        }
        return $branchesList;
    }


}
