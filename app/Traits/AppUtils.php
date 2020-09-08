<?php

namespace App\Traits;

use App\AllowanceModelMast;
use App\StaffGrade;
use App\StafMainMastModel;
use Carbon\Carbon;

trait AppUtils
{

    /** Get allowance by id
     * @param $id
     *
     * @return int
     */
    public function getAllowanceByID($id)
    {
        $allowance = AllowanceModelMast::where('allow_title', $id)->where('effect_date', '<=', Carbon::now())->where('status_id', 1)->latest()->first();
        if (!empty($allowance)) {
            return $allowance->allow_amt;
        }
        return 0;
    }


    public function gradeChangeDivisionByID($staff_central_id, $payroll_from_en, $payroll_to_en)
    {
        //get collection from the staff central id
        $staffGradesInInterval = StaffGrade::with('grade')->orderBy('effective_from_date')->where('staff_central_id', $staff_central_id)->where(function ($query) use ($payroll_from_en, $payroll_to_en) {
            $query->where([['effective_from_date', '>=', $payroll_from_en], ['effective_to_date', '<=', $payroll_to_en]]);
            $query->orWhere([['effective_from_date', '<=', $payroll_from_en], ['effective_to_date', '>=', $payroll_from_en]]);
            $query->orWhere([['effective_from_date', '<=', $payroll_to_en], ['effective_to_date', '>=', $payroll_to_en]]);
            $query->orWhere([['effective_from_date', '<=', $payroll_from_en], ['effective_to_date', '>=', $payroll_to_en]]);
        })->orWhere(function ($query) use ($payroll_from_en, $payroll_to_en) {
            $query->where([['effective_from_date', '<=', $payroll_from_en], ['effective_to_date', '=', null]]);
            $query->orWhere([['effective_from_date', '<=', $payroll_to_en], ['effective_to_date', '=', null]]);
        })->get();
        $response = $this->gradeChangeDivisionByCollection($staffGradesInInterval, $payroll_from_en, $payroll_to_en);
        return $response;
    }

    public function gradeChangeDivisionByCollection($staffGrade, $payroll_from_en, $payroll_to_en, $basic_salary = 0, &$grade_payable = 0)
    {
        $number_of_days = $this->daysDifference($payroll_from_en, $payroll_to_en);
        $splitRecords = [];
        $i = 0;
        foreach ($staffGrade as $interval) {
            //filter the collection - get the data within the from date and to date
            if (strtotime($interval->effective_to_date) >= strtotime($payroll_from_en)
                || (strtotime($interval->effective_from_date) <= strtotime($payroll_from_en) && $interval->effective_to_date == null)
                || (strtotime($interval->effective_from_date) <= strtotime($payroll_to_en) && $interval->effective_to_date == null)
            ) {
                $splitRecords[$i]['grade'] = $interval->grade->value;
                // check from date
                if ($interval->effective_from_date <= $payroll_from_en) {
                    $splitRecords[$i]['from'] = $payroll_from_en;
                } else {
                    $splitRecords[$i]['from'] = $interval->effective_from_date;
                }
                //check to date
                if ($interval->effective_to_date >= $payroll_to_en) {
                    $splitRecords[$i]['to'] = $payroll_to_en;
                } else {
                    $splitRecords[$i]['to'] = $interval->effective_to_date ?? $payroll_to_en;
                }
                //find the days difference in split
                $days_in_split = $this->daysDifference($splitRecords[$i]['from'], $splitRecords[$i]['to']);
                $splitRecords[$i]['days'] = $days_in_split;
                $splitRecords[$i]['percentage'] = round(($days_in_split / $number_of_days) * 100, 3);
                $splitRecords[$i]['grade_payable'] = ($basic_salary / 30) * $splitRecords[$i]['grade'] * ($splitRecords[$i]['percentage'] / 100);
                $grade_payable += $splitRecords[$i]['grade_payable'];
                $i++;
            }
        }
        return $splitRecords;
    }

    public function allowanceChangeDivisionByID($staff_central_id, $allow_id, $payroll_from_en, $payroll_to_en)
    {
        $staffPayment = StafMainMastModel::with(['payment' => function ($query) use ($allow_id) {
            $query->where('allow_id', $allow_id);
        }])->where('id', $staff_central_id)->first()->payment;
        $response = $this->allowanceChangeDivisionByCollection($staffPayment, $payroll_from_en, $payroll_to_en);

        return $response;
    }

    public function allowanceChangeDivisionByCollection($staffPayment, $payroll_from_en, $payroll_to_en, &$allowance_payable = 0)
    {
        $number_of_days = $this->daysDifference($payroll_from_en, $payroll_to_en);
        $splitRecords = [];
        $i = 0;
        foreach ($staffPayment as $interval) {
            //filter the collection - get the data within the from date and to date
            if (strtotime($interval->effective_to) >= strtotime($payroll_from_en)
                || (strtotime($interval->effective_from) <= strtotime($payroll_from_en) && $interval->effective_to == null)
                || (strtotime($interval->effective_from) <= strtotime($payroll_to_en) && $interval->effective_to == null)
            ) {
                $splitRecords[$i]['allow_id'] = $interval->allow_id;
                $splitRecords[$i]['amount'] = ($interval->allow == 1) ? $interval->amount : 0;
                // check from date
                if ($interval->effective_from <= $payroll_from_en) {
                    $splitRecords[$i]['from'] = $payroll_from_en;
                } else {
                    $splitRecords[$i]['from'] = $interval->effective_from;
                }
                //check to date
                if ($interval->effective_to >= $payroll_to_en) {
                    $splitRecords[$i]['to'] = $payroll_to_en;
                } else {
                    $splitRecords[$i]['to'] = $interval->effective_to ?? $payroll_to_en;
                }
                //find the days difference in split
                $days_in_split = $this->daysDifference($splitRecords[$i]['from'], $splitRecords[$i]['to']);
                $splitRecords[$i]['days'] = $days_in_split;
                $splitRecords[$i]['percentage'] = round(($days_in_split / $number_of_days) * 100, 3);
                $splitRecords[$i]['allowance_payable'] = $splitRecords[$i]['amount'] * ($splitRecords[$i]['percentage'] / 100);
                $allowance_payable += $splitRecords[$i]['allowance_payable'];
                $i++;
            }
        }
        return $splitRecords;
    }

    public function basicSalaryChangeDivisionByID($staff_central_id, $payroll_from_en, $payroll_to_en)
    {
        $staff = StafMainMastModel::with(['staffPosts' => function ($query) {
            $query->with('post');
        }])->find($staff_central_id);
        $response = $this->basicSalaryChangeDivisionByCollection($staff->staffPosts, $payroll_from_en, $payroll_to_en);

        return $response;
    }

    public function basicSalaryChangeDivisionByCollection($staffJobPositions, $payroll_from_en, $payroll_to_en, &$total_payable = 0)
    {
        $number_of_days = $this->daysDifference($payroll_from_en, $payroll_to_en);
        $splitRecords = [];
        $i = 0;
        foreach ($staffJobPositions as $interval) {
            //filter the collection - get the data within the from date and to date
            if (strtotime($interval->effective_to_date) >= strtotime($payroll_from_en)
                || (strtotime($interval->effective_from_date) <= strtotime($payroll_from_en) && $interval->effective_to_date == null)
                || (strtotime($interval->effective_from_date) <= strtotime($payroll_to_en) && $interval->effective_to_date == null)
            ) {
                $splitRecords[$i]['basic_salary'] = $interval->post->basic_salary ?? 0;
                $splitRecords[$i]['post_id'] = $interval->post_id;
                // check from date
                if ($interval->effective_from_date <= $payroll_from_en) {
                    $splitRecords[$i]['from'] = $payroll_from_en;
                } else {
                    $splitRecords[$i]['from'] = $interval->effective_from_date;
                }
                //check to date
                if ($interval->effective_to_date >= $payroll_to_en) {
                    $splitRecords[$i]['to'] = $payroll_to_en;
                } else {
                    $splitRecords[$i]['to'] = $interval->effective_to_date ?? $payroll_to_en;
                }
                //find the days difference in split
                $days_in_split = $this->daysDifference($splitRecords[$i]['from'], $splitRecords[$i]['to']);
                $splitRecords[$i]['days'] = $days_in_split;
                $splitRecords[$i]['percentage'] = round(($days_in_split / $number_of_days) * 100, 3);
                $splitRecords[$i]['basic_salary_payable'] = $splitRecords[$i]['basic_salary'] * ($splitRecords[$i]['percentage'] / 100);
                $total_payable += $splitRecords[$i]['basic_salary_payable'];
                $i++;
            }
        }
        return $splitRecords;
    }


    public function daysDifference($date_from, $date_to)
    {
        $dateFrom = date_create($date_from);
        $dateTo = date_create($date_to);
        $holidayDays = date_diff($dateFrom, $dateTo)->format('%a') + 1;
        return $holidayDays;
    }
}
