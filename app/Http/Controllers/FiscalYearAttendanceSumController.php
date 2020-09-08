<?php

namespace App\Http\Controllers;

use App\FiscalYearAttendanceSum;
use App\FiscalYearModel;
use App\StafMainMastModel;
use App\SystemOfficeMastModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class FiscalYearAttendanceSumController extends Controller
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

        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');

        $fiscalyearattendancesums = FiscalYearAttendanceSum::query()->with('staff');

        if (!empty($request->fiscal_year_id)) {
            $fiscalyearattendancesums = $fiscalyearattendancesums->where('fiscal_year', $request->fiscal_year_id);
        }

        $fiscalyearattendancesums = $fiscalyearattendancesums->search($search_term)->latest()->paginate($records_per_page);

        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('fiscalyearattendancesum.index', [
            'title' => 'Fiscal Year Attendance Sum',
            'fiscalyearattendancesums' => $fiscalyearattendancesums,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'fiscal_years' => $fiscal_years,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $fiscal_years = FiscalYearModel::all();
        $branches = SystemOfficeMastModel::all();
        return view('fiscalyearattendancesum.create', [
            'title' => 'Create Fiscal Year Attendance',
            'fiscal_years' => $fiscal_years,
            'branches' => $branches
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
        $staff_details = StafMainMastModel::where('branch_id', $request->branch_id)->get();
        foreach ($staff_details as $staff_detail) {
            FiscalYearAttendanceSum::firstOrCreate(['fiscal_year' => $request->fiscal_year, 'staff_central_id' => $staff_detail->id], ['branch_id' => $request->branch_id]);
        }
        $fiscal_year_attendance_sum = FiscalYearAttendanceSum::where('fiscal_year', $request->fiscal_year)->where('branch_id', $request->branch_id)->first();
        $next = FiscalYearAttendanceSum::where('id', '>', $fiscal_year_attendance_sum->id)->min('id');
        return view('fiscalyearattendancesum.edit', [
            'title' => 'Attendance Details',
            'fiscal_year_attendance_sum' => $fiscal_year_attendance_sum,
            'next' => $next
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\FiscalYearAttendanceSum $fiscalYearAttendanceSum
     * @return \Illuminate\Http\Response
     */
    public function show(FiscalYearAttendanceSum $fiscalYearAttendanceSum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\FiscalYearAttendanceSum $fiscalYearAttendanceSum
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fiscalYearAttendanceSum = FiscalYearAttendanceSum::find($id);
        $previous = FiscalYearAttendanceSum::where('id', '<', $fiscalYearAttendanceSum->id)->where('branch_id', $fiscalYearAttendanceSum->branch_id)->where('fiscal_year', $fiscalYearAttendanceSum->fiscal_year)->max('id');
        return view('fiscalyearattendancesum.edit', [
            'title' => 'Attendance Details',
            'fiscal_year_attendance_sum' => $fiscalYearAttendanceSum,
            'previous' => $previous
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\FiscalYearAttendanceSum $fiscalYearAttendanceSum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $fiscalYearAttendanceSum = FiscalYearAttendanceSum::find($id);
        $fiscalYearAttendanceSum->total_attendance = $request->total_attendance;
        $fiscalYearAttendanceSum->save();
        $next = FiscalYearAttendanceSum::where('id', '>', $fiscalYearAttendanceSum->id)->where('branch_id', $fiscalYearAttendanceSum->branch_id)->where('fiscal_year', $fiscalYearAttendanceSum->fiscal_year)->min('id');
        if (empty($next)) {
            return redirect()->route('fiscalyearattendancesum');
        }
        return redirect()->route('fiscalyearattendancesum-edit', $next);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\FiscalYearAttendanceSum $fiscalYearAttendanceSum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $fiscalattendance = FiscalYearAttendanceSum::find($request->id);
            if ($fiscalattendance->delete()) {
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
                        $fiscalattendance = FiscalYearAttendanceSum::find($id);
                        $fiscalattendance->delete();
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

    public function import()
    {
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        return view('fiscalyearattendancesum.import', [
            'title' => 'Fiscal Year Attendance Sum Import',
            'branches' => $branches,
            'fiscal_years' => $fiscal_years,
        ]);
    }

    public function importStore(Request $request)
    {
        $path = $request->file('excel_file');
        $datas = Excel::load($path, function ($reader) {
        })->all();
        $status = false;
        $branch_id = $request->branch_id;
        $fiscal_year_id = $request->fiscal_year_id;

        $staffmains = StafMainMastModel::whereIn('staff_type', [0, 1])->where('payroll_branch_id', $branch_id)->get();
        $alreadyFiscalYearAttendance = FiscalYearAttendanceSum::where('fiscal_year', $fiscal_year_id)->whereIn('staff_central_id', $staffmains->pluck('id')->toArray())->get();
        $fiscalyearattendances = [];
        $i = 0;
        try {
            DB::beginTransaction();
            foreach ($datas as $data) {
                if (strcasecmp($data['sn'], 'end') == 0) {
                    break;
                }
                $main_id = $data['main_id'];
                $staff_central_id = $data['staff_central_id'];
                $staffmain = $staffmains->where('main_id', $main_id)->first();
                if (empty($staffmain)) {
                    $staffmain = StafMainMastModel::where('staff_central_id', $staff_central_id)->orWhere(function ($query) use ($branch_id, $main_id) {
                        $query->where('branch_id', $branch_id)->where('main_id', $main_id);
                    })->orWhere(function ($query) use ($branch_id, $main_id) {
                        $query->where('payroll_branch_id', $branch_id)->where('main_id', $main_id);
                    })->first();
                }
                if (empty($staffmain)) {
                    $status = 'error';
                    $mesg = 'Error Occured! Try Again! The Staff with Branch ID ' . $main_id . ' doesnot exist.';
                    return redirect()->route('fiscalyearattendancesum')->with('flash', array('status' => $status, 'mesg' => $mesg));
                } else {
                    $check_if_attendance_exists = $alreadyFiscalYearAttendance->where('staff_central_id', $staffmain->id)->first();
                    if (!empty($check_if_attendance_exists)) {
                        $check_if_attendance_exists->total_attendance = $data['total_attendance'];
                        $check_if_attendance_exists->branch_id = $branch_id;
                        $check_if_attendance_exists->save();

                    } else {
                        $fiscalyearattendances[$i]['staff_central_id'] = $staffmain->id;
                        $fiscalyearattendances[$i]['fiscal_year'] = $fiscal_year_id;
                        $fiscalyearattendances[$i]['branch_id'] = $staffmain->branch_id;
                        $fiscalyearattendances[$i]['total_attendance'] = $data['total_attendance'];
                        $fiscalyearattendances[$i]['created_at'] = Carbon::now();
                        $fiscalyearattendances[$i]['updated_at'] = Carbon::now();
                        $i++;
                    }

                }
            }
            $status = FiscalYearAttendanceSum::insert($fiscalyearattendances);
        } catch (\Exception $e) {
            DB::rollBack();
        }

        if ($status) {
            DB::commit();
        }
        $status = ($status) ? 'success' : 'error';
        $mesg = ($status) ? 'Imported Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('fiscalyearattendancesum')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }
}
