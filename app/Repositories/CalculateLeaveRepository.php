<?php

namespace App\Repositories;

use App\SystemLeaveMastModel;
use Illuminate\Support\Collection;

class CalculateLeaveRepository
{
    /**
     * Repository data contains = > present_days, presentable_days, staff_jobtype_id
     * @param SystemLeaveMastModel $systemHolidayMastModel
     * @param $repositoryDataCollect
     * @return float|int|mixed
     */
    public function getLeaveEarnable(SystemLeaveMastModel $systemHolidayMastModel, $repositoryDataCollect)
    {
        if (!empty($systemHolidayMastModel->job_type_id) && $systemHolidayMastModel->job_type_id !== $repositoryDataCollect->staff_jobtype_id) {
            return 0;
        }
        if($systemHolidayMastModel->threshold_for_present_days > $repositoryDataCollect->present_days){
            return 0;
        }
        switch ($systemHolidayMastModel->leave_earnable_type) {
            case SystemLeaveMastModel::EARN_ABLE_TYPE_FOR_FLAT:
                return $this->flatEarnable($systemHolidayMastModel);
                break;
            case SystemLeaveMastModel::EARN_ABLE_TYPE_FOR_PRESENT_DAYS_RATIO:
                return $this->pdrEarnable($systemHolidayMastModel, $repositoryDataCollect);
            default:
                return 0;
        }
//        if ($systemHolidayMastModel->leave_earnable_type == SystemLeaveMastModel::EARN_ABLE_TYPE_FOR_FLAT) {
//            return $this->flatEarnable($systemHolidayMastModel);
//        } elseif ($systemHolidayMastModel->leave_earnable_type == SystemLeaveMastModel::EARN_ABLE_TYPE_FOR_PRESENT_DAYS_RATIO) {
//            return $this->pdrEarnable($systemHolidayMastModel, $repositoryDataCollect);
//        } else {
//            return 0;
//        }
    }

    public function flatEarnable(SystemLeaveMastModel $systemHolidayMastModel)
    {
        if ($systemHolidayMastModel->leave_earnable_balance != 0) {
            return $systemHolidayMastModel->leave_earnable_balance;
        } else {
            return 0;
        }
//        return $repositoryDataCollect->present_days;
    }

    public function pdrEarnable(SystemLeaveMastModel $systemHolidayMastModel, $repositoryDataCollect)
    {
        if (!isset($repositoryDataCollect->present_days) || empty($repositoryDataCollect->present_days) || $repositoryDataCollect->present_days == 0) {
            return 0;
        }

        $earnable = ($repositoryDataCollect->present_days / $systemHolidayMastModel->threshold_for_earnability) * $systemHolidayMastModel->leave_earnable_balance;
        if ($earnable > $systemHolidayMastModel->leave_earnable_balance) {
            return $systemHolidayMastModel->leave_earnable_balance;
        }

        return $earnable;
    }
}
