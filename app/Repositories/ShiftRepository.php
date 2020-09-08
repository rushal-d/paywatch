<?php

namespace App\Repositories;

use App\Shift;

class ShiftRepository
{
    public function getAllShiftsByBranch($branch_id)
    {
        $shifts = new Shift();
        if (!empty($branch_id)) {
            $shifts = $shifts->where('branch_id', $branch_id);
        }
        $shifts = $shifts->get();
        return $shifts;

    }

    public function getAllActiveShiftsByBranch($branch_id)
    {
        $shifts = new Shift();
        if (!empty($branch_id)) {
            $shifts = $shifts->where('branch_id', $branch_id);
        }
        $shifts = $shifts->where('active', 1)->get();
        return $shifts;
    }

    public function getAllShifts()
    {
        $shifts = Shift::get();
        return $shifts;
    }


}
