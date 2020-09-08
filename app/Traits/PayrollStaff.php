<?php


namespace App\Traits;


use App\EmployeeStatus;
use App\Helpers\BSDateHelper;
use App\StaffTransferModel;
use App\StafMainMastModel;
use Illuminate\Support\Facades\DB;

trait PayrollStaff
{
    public function getPayrollStaffs($branch_id, $department_id, $from_date_np, $to_date_np)
    {
        $staffs = StafMainMastModel::OfPayrollStaffFilter($branch_id)->where('staff_status', 1);

        $excludeStaffCentralIdsFromStaffTransfer = [];
        if (!empty($department_id)) {
            $staffs = $staffs->where('department', $department_id);
        }
        if (!empty($from_date_np) && !empty($to_date_np)) {
            $to_date_np_array = explode('-', $to_date_np);
            $to_date_np_array[2] = BSDateHelper::getLastDayByYearMonth($to_date_np_array[0], (int)$to_date_np_array[1]);
            $to_date_np = implode('-', $to_date_np_array);
            $startDate = BSDateHelper::BsToAd('-', $from_date_np);
            $endDate = BSDateHelper::BsToAd('-', $to_date_np);

            $staffs = $staffs->whereDate('temporary_con_date', '<=', $endDate);
            $staffCentralIdsFromStaffTransfer = [];

            //including the dismissed staffs from the payroll as they tend to get the salary for the working days before dismiss and redeem the leave balances
            //get the staff with the status of dismiss and retire of the given branch for the given payroll month and include them in the payroll staff list.

            $dismissed_retired_staffs_this_month = EmployeeStatus::whereHas('staff', function ($query) use ($branch_id) {
                $query->where('payroll_branch_id', $branch_id);
                $query->whereHas('jobtype', function ($query) {
                    $query->where('jobtype_code', '<>', 'T');
                });
            })
//                ->whereIn('status', [EmployeeStatus::STATUS_DISMISS, EmployeeStatus::STATUS_FIRE, EmployeeStatus::STATUS_RESIGN])
                ->whereIn(DB::raw('MONTH(date_from)'), [date('m', strtotime($startDate)), date('m', strtotime($endDate))])->pluck('staff_central_id')->toArray();

            if (count($dismissed_retired_staffs_this_month) > 0) {
                $staffs = $staffs->orWhereIn('id', $dismissed_retired_staffs_this_month);
            }


            $staffTransfers = StaffTransferModel::whereNotNull('office_id')
                ->where(function ($query) use ($endDate) {
                    $query->where('from_date', '<=', $endDate)
                        ->where('transfer_date', '>=', $endDate);
                });


            $excludeStaffTransfer = clone($staffTransfers);
            $staffTransfers = $staffTransfers->where('office_from', $branch_id);
            $excludeStaffCentralIdsFromStaffTransfer = $excludeStaffTransfer->where('office_id', $branch_id)->pluck('staff_central_id');
            if (!empty($staffTransfers)) {
                $staffCentralIdsFromStaffTransfer = $staffTransfers->pluck('staff_central_id');
            }
            if (count($staffCentralIdsFromStaffTransfer) > 0) {
                $staffs = $staffs->orWhereIn('id', $staffCentralIdsFromStaffTransfer);
            }
            $staffs = $staffs->whereDate('appo_date', '<=', $endDate);

        }


        $staffs = $staffs->select('id', 'name_eng', 'main_id', 'staff_central_id')->orderBy('staff_central_id', 'ASC')->get();

        if (count($excludeStaffCentralIdsFromStaffTransfer) > 0) {
            $staffs = $staffs->whereNotIn('id', $excludeStaffCentralIdsFromStaffTransfer);
        }

        return $staffs;
    }
}
