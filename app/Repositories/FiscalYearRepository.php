<?php

namespace App\Repositories;

use App\FiscalYearModel;

class FiscalYearRepository
{
    public function getAllFiscalYears()
    {
        $fiscalYears = FiscalYearModel::get();
        return $fiscalYears;
    }

    public function getCurrentFiscalYear()
    {
        $currentFiscalYear = FiscalYearModel::isActiveFiscalYear()->get();
        return $currentFiscalYear;
    }

}
