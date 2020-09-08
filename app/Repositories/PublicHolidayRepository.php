<?php

namespace App\Repositories;

use App\PublicHoliday;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PublicHolidayRepository
{
    protected $genders = [
        PublicHoliday::holidayForAllGenders => 'All',
        PublicHoliday::holidayForMaleGender => 'Male',
        PublicHoliday::holidayForFemaleGender => 'Female',
        PublicHoliday::holidayForOthersGender => 'Others'
    ];

    public function __construct()
    {
        $this->model = PublicHoliday::query();
    }

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
            $publicHoliday = $this->findById($id)->first();
            $request->updated_by = auth()->id();
            $request->created_by = $publicHoliday->created_by;
        } else {
            $request->created_by = auth()->id();
            $request->updated_by = null;
            $publicHoliday = new PublicHoliday;
        }
        return $this->save($publicHoliday, $request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function saveMany(Request $request)
    {
        DB::beginTransaction();
        $saveStatus= false;
        try {
            foreach ($request->branch_id as $branchId) {
                $publicHoliday = new PublicHoliday;
                $publicHoliday->fy_year = $request->fy_year;
                $publicHoliday->holiday_descri = $request->holiday_descri;
                $publicHoliday->holiday_stat = $request->holiday_stat;

                $publicHoliday->branch_id = $branchId;
                $publicHoliday->name = $request->name;
                $publicHoliday->from_date_np = $request->from_date_np;
                $publicHoliday->from_date = $request->from_date;
                $publicHoliday->to_date_np = $request->to_date_np;
                $publicHoliday->to_date = $request->to_date;

                if (!empty($request->from_date) && !empty($request->to_date)) {
                    $toDate = Carbon::parse($request->to_date);
                    $fromDate = Carbon::parse($request->from_date);

                    $publicHoliday->holiday_days = $toDate->diffInDays($fromDate);
                }

                $publicHoliday->gender_id = $request->gender_id;

                $publicHoliday->autho_id = auth()->id();
                if ($saveStatus = $publicHoliday->save()) {
                    $publicHoliday->religions()->sync($request->religion_id);
                    $publicHoliday->castes()->sync($request->caste_id);
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $saveStatus = false;
        }
        return $saveStatus;
    }


    public function save(PublicHoliday $publicHoliday, Request $request)
    {
        DB::beginTransaction();
        try {
            $publicHoliday->fy_year = $request->fy_year;
            $publicHoliday->holiday_descri = $request->holiday_descri;
            $publicHoliday->holiday_stat = $request->holiday_stat;

            $publicHoliday->branch_id = $request->branch_id;
            $publicHoliday->name = $request->name;
            $publicHoliday->from_date_np = $request->from_date_np;
            $publicHoliday->from_date = $request->from_date;
            $publicHoliday->to_date_np = $request->to_date_np;
            $publicHoliday->to_date = $request->to_date;

            if (!empty($request->from_date) && !empty($request->to_date)) {
                $toDate = Carbon::parse($request->to_date);
                $fromDate = Carbon::parse($request->from_date);

                $publicHoliday->holiday_days = $toDate->diffInDays($fromDate);
            }

            $publicHoliday->gender_id = $request->gender_id;

            $publicHoliday->autho_id = auth()->id();
            if ($saveStatus = $publicHoliday->save()) {
                $publicHoliday->religions()->sync($request->religion_id);
                $publicHoliday->castes()->sync($request->caste_id);
            }

            $publicHoliday->branch_id = $request->branch_id;
            $publicHoliday->name = $request->name;
            $publicHoliday->description = $request->description;
            $publicHoliday->gender = $request->gender;
            $publicHoliday->from_date_np = $request->from_date_np;
            $publicHoliday->from_date = $request->from_date;
            $publicHoliday->to_date_np = $request->to_date_np;
            $publicHoliday->to_date = $request->to_date;
            $publicHoliday->created_by = $request->created_by;
            $publicHoliday->updated_by = $request->updated_by;
            $saveStatus = $publicHoliday->save();
            $publicHoliday->religions()->sync($request->religion_id);
            $publicHoliday->castes()->sync($request->caste_id);
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
