<?php

namespace App\Http\Controllers;

use App\BranchSystemHoliday;
use App\Caste;
use App\CasteSystemHoliday;
use App\FiscalYearModel;
use App\Religion;
use App\ReligionSystemHoliday;
use App\SystemHolidayMastModel;
use App\SystemOfficeMastModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SystemHolidayMastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $holidays = SystemHolidayMastModel::query()->with('fiscal');
        $records_per_page_options = Config::get('constants.records_per_page_options');

        if (!empty($request->fiscal_year_id)) {
            $holidays = $holidays->where('fy_year', $request->fiscal_year_id);
        }

        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');

        $holidays = $holidays->search($search_term)->orderBy('from_date', 'DESC')->paginate($records_per_page);

        return view('holiday.index', [
            'title' => 'Holiday',
            'holidays' => $holidays,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'fiscal_years' => $fiscal_years
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fiscalyear = FiscalYearModel::ascOrder()->pluck('fiscal_code', 'id');
        $genders = Config::get('constants.gender');
        $status_options = Config::get('constants.status_options');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
        $branches = SystemOfficeMastModel::get();
        $castes = Caste::get();
        $religions = Religion::get();
        return view('holiday.create',
            [
                'title' => 'Add Holiday',
                'status_options' => $status_options,
                'fiscalyear' => $fiscalyear,
                'genders' => $genders,
                'current_fiscal_year_id' => $current_fiscal_year_id,
                'branches' => $branches,
                'castes' => $castes,
                'religions' => $religions,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'holiday_descri' => 'required',
            'from_date_np' => 'required',
            'to_date_np' => 'required',
            'branch' => 'required',
        ],
            [
                'holiday_descri.required' => 'You must enter Holiday Detail!',
                'from_date_np.required' => 'You must enter from date!',
                'to_date_np.required' => 'You must enter to date!',
                'branch.required' => 'Please Select at least on Branch'
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('system-holiday-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $holiday = new SystemHolidayMastModel();
                $holiday->fy_year = $request->fy_year;
                $holiday->holiday_descri = $request->holiday_descri;
                $holiday->holiday_stat = $request->holiday_stat;
                $holiday->holiday_days = $request->holiday_days;
                $holiday->from_date = $request->from_date;
                $holiday->from_date_np = $request->from_date_np;
                $holiday->to_date = $request->to_date;
                $holiday->to_date_np = $request->to_date_np;
                $holiday->gender_id = $request->gender_id;
                $holiday->autho_id = \Auth::user()->id;
                if ($holiday->save()) {
                    $status_mesg = true;

                    foreach ($request->branch as $branch_id) {
                        $branch_holiday = new BranchSystemHoliday();
                        $branch_holiday->branch_id = $branch_id;
                        $branch_holiday->system_holiday_id = $holiday->holiday_id;
                        $branch_holiday->save();
                    }

                    if (!empty($request->caste)) {
                        foreach ($request->caste as $caste_id) {
                            $caste_holiday = new CasteSystemHoliday();
                            $caste_holiday->caste_id = $caste_id;
                            $caste_holiday->system_holiday_id = $holiday->holiday_id;
                            $caste_holiday->save();
                        }
                    }

                    if (!empty($request->religion)) {
                        foreach ($request->religion as $religion_id) {
                            $religion_holiday = new ReligionSystemHoliday();
                            $religion_holiday->religion_id = $religion_id;
                            $religion_holiday->system_holiday_id = $holiday->holiday_id;
                            $religion_holiday->save();
                        }
                    }
                }
            } catch (Exception $e) {
                DB::rollback();
                $status_mesg = false;
            }
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('system-holiday-create')->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fiscalyear = FiscalYearModel::ascOrder()->pluck('fiscal_code', 'id');
        $holiday = SystemHolidayMastModel::with('branch', 'castes', 'religions')->where('holiday_id', $id)->first();
        $status_options = Config::get('constants.status_options');
        $genders = Config::get('constants.gender');
        $branches = SystemOfficeMastModel::get();
        $castes = Caste::get();
        $religions = Religion::get();
        return view('holiday.edit', [
            'title' => 'Edit Holiday',
            'holiday' => $holiday,
            'fiscalyear' => $fiscalyear,
            'genders' => $genders,
            'status_options' => $status_options,
            'branches' => $branches,
            'castes' => $castes,
            'religions' => $religions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'holiday_descri' => 'required',
            'from_date_np' => 'required',
            'to_date_np' => 'required',
            'branch' => 'required',
        ],
            [
                'holiday_descri.required' => 'You must enter Holiday Detail!',
                'from_date_np.required' => 'You must enter from date!',
                'to_date_np.required' => 'You must enter to date!',
                'branch.required' => 'Select at least one branch'
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('system-holiday-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info

                $holiday = SystemHolidayMastModel::find($id);
                $holiday->fy_year = $request->fy_year;
                $holiday->holiday_descri = $request->holiday_descri;
                $holiday->holiday_stat = $request->holiday_stat;
                $holiday->holiday_days = $request->holiday_days;
                $holiday->from_date = $request->from_date;
                $holiday->from_date_np = $request->from_date_np;
                $holiday->to_date = $request->to_date;
                $holiday->to_date_np = $request->to_date_np;
                $holiday->gender_id = $request->gender_id;
                $holiday->autho_id = \Auth::user()->id;

                if ($holiday->save()) {
                    $status_mesg = true;
                    BranchSystemHoliday::where('system_holiday_id', $id)->delete();
                    CasteSystemHoliday::where('system_holiday_id', $id)->delete();
                    ReligionSystemHoliday::where('system_holiday_id', $id)->delete();

                    foreach ($request->branch as $branch_id) {
                        $branch_holiday = new BranchSystemHoliday();
                        $branch_holiday->branch_id = $branch_id;
                        $branch_holiday->system_holiday_id = $holiday->holiday_id;
                        $branch_holiday->save();
                    }
                    if (!empty($request->caste)) {
                        foreach ($request->caste as $caste_id) {
                            $caste_holiday = new CasteSystemHoliday();
                            $caste_holiday->caste_id = $caste_id;
                            $caste_holiday->system_holiday_id = $holiday->holiday_id;
                            $caste_holiday->save();
                        }
                    }


                    if (!empty($request->religion)) {
                        foreach ($request->religion as $religion_id) {
                            $religion_holiday = new ReligionSystemHoliday();
                            $religion_holiday->religion_id = $religion_id;
                            $religion_holiday->system_holiday_id = $holiday->holiday_id;
                            $religion_holiday->save();
                        }
                    }

                }
            } catch (Exception $e) {
                DB::rollback();
                $status_mesg = false;
            }
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('system-holiday-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $holiday = SystemHolidayMastModel::find($request->id);
            if ($holiday->delete()) {
                $success = true;
            }
            if ($success) {
                echo 'Successfully Deleted';
            } else {
                echo "Error deleting!";
            }
        } else {
            echo "Error deleting!";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroySelected(Request $request)
    {
        $status_mesg = false;
        if (!empty($request->ids)) {
            $ids = $request->ids;
            //only soft delete
            try {
                //start transaction to prevent unsuccessful deletion
                $exception = DB::transaction(function () use ($ids) {
                    foreach ($ids as $id) {
                        $holiday = SystemHolidayMastModel::find($id);
                        $holiday->delete();
                    }
                });
                $status_mesg = is_null($exception) ? true : $exception;
            } catch (Exception $e) {
                $status_mesg = false;
            }
        }
        $mesg = ($status_mesg) ? 'Successfully Deleted' : 'Error deleting!';
        echo $mesg;
    }

}
