<?php

namespace App\Repositories;

use App\SystemHolidayMastModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemHolidayMastRepository
{
    private $model;

    public function __construct()
    {
        $this->model = SystemHolidayMastModel::query();
    }

    /**
     * @param $date
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getPublicHolidaysByDate($date)
    {
        $systemHolidays = $this->model->get()->filter(function ($systemHolidays) use ($date) {
            return $systemHolidays->from_date <= $date && $systemHolidays->to_date >= $date;
        });

        return $systemHolidays;
    }

    protected $genders = [
        SystemHolidayMastModel::holidayForAllGenders => 'All',
        SystemHolidayMastModel::holidayForMaleGender => 'Male',
        SystemHolidayMastModel::holidayForFemaleGender => 'Female',
        SystemHolidayMastModel::holidayForOthersGender => 'Others'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function all()
    {
        return $this->model;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function findById($id)
    {
        return $this->model->where('id', $id);
    }

    public function getGenders()
    {
        return collect($this->genders);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function store(Request $request)
    {
        if (isset($request->id)) {
            $id = $request->id;
            $systemHoliday = $this->findById($id)->first();
            $request->updated_by = auth()->id();
            $request->created_by = $systemHoliday->created_by;
        } else {
            $request->created_by = auth()->id();
            $request->updated_by = null;
            $systemHoliday = new SystemHolidayMastModel;
        }
        return $this->save($systemHoliday, $request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function saveMany(Request $request)
    {
        DB::beginTransaction();
        $saveStatus= true;
        try {
            foreach ($request->branch_id as $branchId) {
                $systemHoliday = new SystemHolidayMastModel;
                $systemHoliday->fy_year = $request->fy_year;
                $systemHoliday->holiday_descri = $request->holiday_descri;
                $systemHoliday->holiday_stat = $request->holiday_stat;

                $systemHoliday->branch_id = $branchId;
                $systemHoliday->name = $request->name;
                $systemHoliday->from_date_np = $request->from_date_np;
                $systemHoliday->from_date = $request->from_date;
                $systemHoliday->to_date_np = $request->to_date_np;
                $systemHoliday->to_date = $request->to_date;

                if (!empty($request->from_date) && !empty($request->to_date)) {
                    $toDate = Carbon::parse($request->to_date);
                    $fromDate = Carbon::parse($request->from_date);

                    $systemHoliday->holiday_days = $toDate->diffInDays($fromDate);
                }

                $systemHoliday->gender_id = $request->gender_id;

                $systemHoliday->autho_id = auth()->id();
                if ($saveStatus = $systemHoliday->save()) {
                    $systemHoliday->religions()->sync($request->religion_id);
                    $systemHoliday->castes()->sync($request->caste_id);
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
            $saveStatus = false;
        }
        return $saveStatus;
    }


    public function save(SystemHolidayMastModel $systemHoliday, Request $request)
    {
        DB::beginTransaction();
        try {
            $systemHoliday->fy_year = $request->fy_year;
            $systemHoliday->holiday_descri = $request->holiday_descri;
            $systemHoliday->holiday_stat = $request->holiday_stat;

            $systemHoliday->branch_id = $request->branch_id;
            $systemHoliday->name = $request->name;
            $systemHoliday->from_date_np = $request->from_date_np;
            $systemHoliday->from_date = $request->from_date;
            $systemHoliday->to_date_np = $request->to_date_np;
            $systemHoliday->to_date = $request->to_date;

            if (!empty($request->from_date) && !empty($request->to_date)) {
                $toDate = Carbon::parse($request->to_date);
                $fromDate = Carbon::parse($request->from_date);

                $systemHoliday->holiday_days = $toDate->diffInDays($fromDate);
            }

            $systemHoliday->gender_id = $request->gender_id;

            $systemHoliday->autho_id = auth()->id();
            if ($saveStatus = $systemHoliday->save()) {
                $systemHoliday->religions()->sync($request->religion_id);
                $systemHoliday->castes()->sync($request->caste_id);
            }

            $systemHoliday->branch_id = $request->branch_id;
            $systemHoliday->name = $request->name;
            $systemHoliday->description = $request->description;
            $systemHoliday->gender = $request->gender;
            $systemHoliday->from_date_np = $request->from_date_np;
            $systemHoliday->from_date = $request->from_date;
            $systemHoliday->to_date_np = $request->to_date_np;
            $systemHoliday->to_date = $request->to_date;
            $systemHoliday->created_by = $request->created_by;
            $systemHoliday->updated_by = $request->updated_by;
            $saveStatus = $systemHoliday->save();
            $systemHoliday->religions()->sync($request->religion_id);
            $systemHoliday->castes()->sync($request->caste_id);
            DB::commit();
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            DB::rollBack();
            $saveStatus = false;
        }
        return $saveStatus;
    }

    public function retrieveOnlyNecessaryRequestInputs(Request $request)
    {
        return $request->only(['id', 'branch_id', 'religion_id', 'name', 'description', 'gender', 'from_date_np', 'from_date', 'to_date_np', 'to_date']);
    }

    public function getSaveStatusMessage(bool $saveStatus)
    {
        return $saveStatus ? 'Added Successfully' : 'Error Occured! Try Again!';
    }
}
