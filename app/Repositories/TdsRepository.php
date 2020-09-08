<?php

namespace App\Repositories;

use App\SystemTdsMastModel;

class TdsRepository
{
    /**
     * @param $martialStatus
     * @param $totalYearlySalary
     * @return array
     */
    public function getTdsDeductionAmountBySlabNumber($martialStatus, $totalYearlySalary)
    {
        $numberOfSlabs = config('constants.tds_slabs');
        $tds = [];
        $lastSlab = false;
        $remaining_amount = $totalYearlySalary;

        foreach ($numberOfSlabs as $slabNumber) {
            $tds_details_slab = $this->getTdsDetailsBySlab($martialStatus, $slabNumber);
            $tds_deduction_amount = $tds_details_slab->amount;
            $tds_deduction_percent = ($tds_details_slab->percent / 100);

            // Last slab being 5
            if ($remaining_amount - $tds_deduction_amount > 0 && ($slabNumber < config('constants.tds_last_slab_number'))) {
                $remaining_amount = $remaining_amount - $tds_deduction_amount;
            } else {
                $tds_deduction_amount = $remaining_amount;
                $lastSlab = true;
            }

            if ($slabNumber == 1 || $remaining_amount > 0 || $lastSlab == true) {
                $tds[$slabNumber] = $tds_deduction_amount * $tds_deduction_percent;
                if ($lastSlab == true) {
                    break;
                }
            }
        }
        return $tds;
    }

    public function getTdsDetailsBySlab($maritalStatus, $slab)
    {
        return SystemTdsMastModel::where('type', $maritalStatus)->where('slab', $slab)->first();
    }

}
