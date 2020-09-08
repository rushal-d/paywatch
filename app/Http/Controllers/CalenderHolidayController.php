<?php

namespace App\Http\Controllers;

use App\CalenderHolidayFile;
use App\CalenderHolidayModel;
use App\CalenderHolidaySplitMonth;
use App\FileType;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\LeaveBalance;
use App\OrganizationSetup;
use App\Repositories\CalenderHolidayRepository;
use App\StaffWorkScheduleMastModel;
use App\StafMainMastModel;
use App\SystemHolidayMastModel;
use App\SystemLeaveMastModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Config;

class CalenderHolidayController extends Controller
{
    private $calenderHolidayRepository;

    public function __construct(CalenderHolidayRepository $calenderHolidayRepository)
    {
        $this->calenderHolidayRepository = $calenderHolidayRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $leaves = SystemLeaveMastModel::select('leave_id', 'leave_name')->get();
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $calenderholidays = CalenderHolidayModel::with('staff', 'leave')->search($search_term)->latest()->paginate($records_per_page);

        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('calenderholiday.index', [
            'title' => 'Calender',
            'calenderholidays' => $calenderholidays,
            'records_per_page_options' => $records_per_page_options,
            'leaves' => $leaves,
            'records_per_page' => $records_per_page
        ]);
    }


    public function search(Request $request)
    {
        $staffs = StafMainMastModel::select('id', 'name_eng')->get();
        $leaves = SystemLeaveMastModel::select('leave_id', 'leave_name')->get();
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $model = new CalenderHolidayModel();
        //check if has leave from filter
        if ($request->has('leave_id') && !empty($request->leave_id)) {
            $model = $model->whereHas('leave', function ($query) use ($request) {
                $query->where('leave_id', $request->leave_id);
            });
        }
        //check if has Staff
        if ($request->has('staff_central_id') && !empty($request->staff_central_id)) {
            $model = $model->whereHas('staff', function ($query) use ($request) {
                $query->where('id', $request->staff_central_id);
            });
        }
        //check if has starting date
        if ($request->has('date_from') && !empty($request->date_from)) {
            $model = $model->whereDate('from_leave_day', '>=', date('Y-m-d', strtotime(BSDateHelper::BsToAd('-', $request->date_from))));
        }
        //check if has Ending date
        if ($request->has('date_to') && !empty($request->date_to)) {
            $model = $model->whereDate('to_leave_day', '<=', date('Y-m-d', strtotime(BSDateHelper::BsToAd('-', $request->date_to))));
        }
        $calenderholidays = $model->with('staff', 'leave')->search($search_term)->latest()->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('calenderholiday.index', [
            'title' => 'Calender Detail',
            'calenderholidays' => $calenderholidays,
            'leaves' => $leaves,
            'staffs' => $staffs,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $staffs = StafMainMastModel::with('branch')->select('id', 'name_eng', 'main_id', 'branch_id', 'staff_central_id')
            ->take(15)->get();
        $leavetypes = SystemLeaveMastModel::pluck('leave_name', 'leave_id');
        $organization = OrganizationSetup::first();
        $file_types = FileType::where('file_section', 'leave_request_documents')->get();
        return view('calenderholiday.create',
            [
                'staffs' => $staffs,
                'leavetypes' => $leavetypes,
                'organization' => $organization,
                'file_types' => $file_types,
                'title' => 'Add Approved Holiday'
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
            'staff_central_id' => 'required',
            'leave_id' => 'required',
            'from_leave_day_np' => 'required',
            'from_leave_day' => 'required',
            'to_leave_day_np' => 'required',
            'to_leave_day' => 'required',
        ],
            [
                'staff_central_id.required' => 'You must enter the Staff Name!',
                'leave_id.required' => 'You must enter the Staff Name!',
                'from_leave_day_np.required' => 'You must select leave date from!',
                'from_leave_day.required' => 'You must select leave date from!',
                'to_leave_day_np.required' => 'You must select leave date to!',
                'to_leave_day.required' => 'You must select leave date to!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('calender-holiday-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                $holidayDays = $this->daysDifference($request->from_leave_day, $request->to_leave_day);
                $organization = OrganizationSetup::first();
                if ($organization->organization_structure == 2) {
                    $response = $this->calenderHolidayRepository->check_conditions($request->leave_id, $request->staff_central_id, $request->from_leave_day_np, $request->to_leave_day_np, $request->is_half,null,$organization);
                    if (!$response['status']) {
                        return redirect()->route('calender-holiday-create')->withInput()->with('flash', array('status' => 'error', 'mesg' => $response['message']));
                    } else {
                        $holidayDays = $response['holiday_days'];
                    }
                }
                $leaveBalance = LeaveBalance::where('staff_central_id', $request->staff_central_id)->where('leave_id', $request->leave_id)->orderBy('id', 'desc')->latest()->first();

                $fiscal_years = FiscalYearModel::get();
                $fiscal_year = $fiscal_years->where('fiscal_start_date', '<=', $request->from_leave_day)->where('fiscal_end_date', '>=', $request->from_leave_day)->first();

                if ($request->is_half == 1) {
                    $holidayDays = $holidayDays - 0.5;
                }

                if ($leaveBalance->balance < $holidayDays) {
                    return redirect()->route('calender-holiday-create')->withInput()->with('flash', array('status' => 'error', 'mesg' => 'Leave Balance is not sufficient!'));
                }
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $calenderholiday = new CalenderHolidayModel();
                $calenderholiday->staff_central_id = $request->staff_central_id;
                $calenderholiday->fiscal_year_id = $fiscal_year->id;
                $calenderholiday->leave_id = $request->leave_id;
                $calenderholiday->from_leave_day_np = $request->from_leave_day_np;
                $calenderholiday->from_leave_day = $request->from_leave_day;
                $calenderholiday->to_leave_day_np = $request->to_leave_day_np;
                $calenderholiday->to_leave_day = $request->to_leave_day;
                $calenderholiday->authorized_by = \Auth::user()->id;
                $calenderholiday->created_by = \Auth::user()->id;
                $calenderholiday->leave_days = $holidayDays;

                if ($calenderholiday->save()) {
                    if (!empty($request->upload)) {
                        foreach ($request->upload as $staff_file_id) {
                            $training_detail_file = new CalenderHolidayFile();
                            $training_detail_file->calender_holiday_id = $calenderholiday->id;
                            $training_detail_file->staff_file_id = $staff_file_id;
                            $training_detail_file->save();
                        }
                    }
                    $month_days = BSDateHelper::getDaysInMonthOfDateRange($request->from_leave_day_np, $request->to_leave_day_np);
                    foreach ($month_days as $leaveMonth => $month_day) {
                        $fiscal_year = $fiscal_years->where('fiscal_start_date', '<=', $month_day['from'])->where('fiscal_end_date', '>=', $month_day['from'])->first();
                        $calenderholiday_split = new CalenderHolidaySplitMonth();
                        $calenderholiday_split->calender_holiday_id = $calenderholiday->id;
                        $calenderholiday_split->fiscal_year_id = $fiscal_year->id;
                        $calenderholiday_split->leave_month = $leaveMonth;
                        if ($request->is_half == 1) {
                            $month_day['days'] = $month_day['days'] - 0.5;
                        }
                        $calenderholiday_split->leave_days = $month_day['days'];
                        $calenderholiday_split->save();
                    }

                    $leavebalance = new LeaveBalance();
                    $leavebalance->staff_central_id = $request->staff_central_id;
                    $leavebalance->leave_id = $request->leave_id;
                    $leavebalance->date_np = BSDateHelper::AdToBs('-', date('Y-m-d'));
                    $leavebalance->date = date('Y-m-d');
                    $leavebalance->fy_id = FiscalYearModel::IsActiveFiscalYear()->value('id');
                    $leavebalance->description = Config::get('constants.balance_description')[2];
                    $leavebalance->consumption = $holidayDays;
                    $leavebalance->earned = 0;
                    $leavebalance->balance = (($leaveBalance->balance ?? 0) - $holidayDays);
                    $leavebalance->authorized_by = \Auth::user()->id;
                    if ($leavebalance->save()) {
                        $status_mesg = true;
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
        return redirect()->route('calender-holiday-create')->with('flash', array('status' => $status, 'mesg' => $mesg));

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
        $leavetypes = SystemLeaveMastModel::select('leave_id', 'leave_name', 'leave_code')->get();
        $calendarholiday = CalenderHolidayModel::with(['calenderHolidayFiles' => function ($query) {
            $query->with('staffFile');
        }])->where('id', $id)->first();
        $organization = OrganizationSetup::first();
        $file_types = FileType::where('file_section', 'leave_request_documents')->get();
        return view('calenderholiday.edit', [
            'title' => 'Edit Approved Holiday',
            'leavetypes' => $leavetypes,
            'calendarholiday' => $calendarholiday,
            'organization' => $organization,
            'file_types' => $file_types,
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
            'from_leave_day_np' => 'required',
            'from_leave_day' => 'required',
            'to_leave_day_np' => 'required',
            'to_leave_day' => 'required',
        ],
            [
                'from_leave_day_np.required' => 'You must select leave date from!',
                'from_leave_day.required' => 'You must select leave date from!',
                'to_leave_day_np.required' => 'You must select leave date to!',
                'to_leave_day.required' => 'You must select leave date to!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('calender-holiday-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                $calenderholiday = CalenderHolidayModel::find($id);
                $organization = OrganizationSetup::first();
                if ($organization->organization_structure == 2) {
                    $response = $this->calenderHolidayRepository->check_conditions($calenderholiday->leave_id, $calenderholiday->staff_central_id, $request->from_leave_day_np, $request->to_leave_day_np, $request->is_half, $id,$organization);
                    if (!$response['status']) {
                        return redirect()->back()->withInput()->with('flash', array('status' => 'error', 'mesg' => $response['message']));
                    }
                }

                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info
                $leaveBalance = LeaveBalance::where('staff_central_id', $calenderholiday->staff_central_id)->where('leave_id', $calenderholiday->leave_id)->orderBy('id', 'desc')->latest()->first();
                $previousLeaveDays = $calenderholiday->leave_days;
                $holidayDays = $this->daysDifference($request->from_leave_day, $request->to_leave_day);
                if ($request->is_half == 1) {
                    $holidayDays = $holidayDays - 0.5;
                }
                if (($leaveBalance->balance + $previousLeaveDays) < $holidayDays) {
                    return redirect()->back()->withInput()->with('flash', array('status' => 'error', 'mesg' => 'Leave Balance is not sufficient!'));
                }

                $fiscal_years = FiscalYearModel::get();
                $fiscal_year = $fiscal_years->where('fiscal_start_date', '<=', $request->from_leave_day)->where('fiscal_end_date', '>=', $request->from_leave_day)->first();

                //reverse of previous leave
                $leavebalance = new LeaveBalance();
                $leavebalance->staff_central_id = $calenderholiday->staff_central_id;
                $leavebalance->leave_id = $calenderholiday->leave_id;
                $leavebalance->date_np = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $leavebalance->date = date('Y-m-d');
                $leavebalance->fy_id = FiscalYearModel::IsActiveFiscalYear()->value('id');
                $leavebalance->description = 'Grant Leave Edit Operation';
                $leavebalance->consumption = 0;
                $leavebalance->earned = $previousLeaveDays;
                $leavebalance->balance = ($leaveBalance->balance + $previousLeaveDays);
                $leavebalance->authorized_by = \Auth::user()->id;
                $leavebalance->save();

                $newLeaveBalance = $leaveBalance->balance + $previousLeaveDays;

                $calenderholiday->fiscal_year_id = $fiscal_year->id;
                $calenderholiday->from_leave_day_np = $request->from_leave_day_np;
                $calenderholiday->from_leave_day = $request->from_leave_day;
                $calenderholiday->to_leave_day_np = $request->to_leave_day_np;
                $calenderholiday->to_leave_day = $request->to_leave_day;
                $calenderholiday->updated_by = Auth::id();
                $calenderholiday->leave_days = $holidayDays;
                if ($calenderholiday->save()) {
                    CalenderHolidayFile::where('calender_holiday_id', $calenderholiday->id)->delete();
                    if (!empty($request->upload)) {
                        foreach ($request->upload as $staff_file_id) {
                            $training_detail_file = new CalenderHolidayFile();
                            $training_detail_file->calender_holiday_id = $calenderholiday->id;
                            $training_detail_file->staff_file_id = $staff_file_id;
                            $training_detail_file->save();
                        }
                    }

                    CalenderHolidaySplitMonth::where('calender_holiday_id', $calenderholiday->id)->delete();
                    $month_days = BSDateHelper::getDaysInMonthOfDateRange($request->from_leave_day_np, $request->to_leave_day_np);
                    foreach ($month_days as $leaveMonth => $month_day) {
                        $fiscal_year = $fiscal_years->where('fiscal_start_date', '<=', $month_day['from'])->where('fiscal_end_date', '>=', $month_day['from'])->first();
                        $calenderholiday_split = new CalenderHolidaySplitMonth();
                        $calenderholiday_split->calender_holiday_id = $calenderholiday->id;
                        $calenderholiday_split->fiscal_year_id = $fiscal_year->id;
                        $calenderholiday_split->leave_month = $leaveMonth;
                        if ($request->is_half == 1) {
                            $month_day['days'] = $month_day['days'] - 0.5;
                        }
                        $calenderholiday_split->leave_days = $month_day['days'];
                        $calenderholiday_split->save();
                    }
                    //sleep because the model uses latest on most of the place and since there are two leave balance insertion at once it conflicts the latest data
                    sleep(2);
                    $leavebalance = new LeaveBalance();
                    $leavebalance->staff_central_id = $calenderholiday->staff_central_id;
                    $leavebalance->leave_id = $calenderholiday->leave_id;
                    $leavebalance->date_np = BSDateHelper::AdToBs('-', date('Y-m-d'));
                    $leavebalance->date = date('Y-m-d');
                    $leavebalance->fy_id = FiscalYearModel::IsActiveFiscalYear()->value('id');
                    $leavebalance->description = Config::get('constants.balance_description')[2];
                    $leavebalance->consumption = $holidayDays;
                    $leavebalance->earned = 0;
                    $leavebalance->balance = ($newLeaveBalance - $holidayDays);
                    $leavebalance->authorized_by = \Auth::user()->id;
                    $leaveBalance->created_at = Carbon::now()->addSecond(10);
                    $leaveBalance->updated_at = Carbon::now()->addSecond(10);
                    if ($leavebalance->save()) {
                        $status_mesg = true;
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
        return redirect()->route('calender-holiday-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

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
            $calenderholiday = CalenderHolidayModel::find($request->id);
            $leaveBalance = LeaveBalance::where('staff_central_id', $calenderholiday->staff_central_id)->where('leave_id', $calenderholiday->leave_id)->orderBy('id', 'desc')->latest()->first();
            //reverse of grant leave on leave balance
            $previousLeaveDays = $calenderholiday->leave_days;
            //reverse of previous leave
            $leavebalance = new LeaveBalance();
            $leavebalance->staff_central_id = $calenderholiday->staff_central_id;
            $leavebalance->leave_id = $calenderholiday->leave_id;
            $leavebalance->date_np = BSDateHelper::AdToBs('-', date('Y-m-d'));
            $leavebalance->date = date('Y-m-d');
            $leavebalance->fy_id = FiscalYearModel::IsActiveFiscalYear()->value('id');
            $leavebalance->description = 'Grant Leave Delete Operation';
            $leavebalance->consumption = 0;
            $leavebalance->earned = $previousLeaveDays;
            $leavebalance->balance = ($leaveBalance->balance + $previousLeaveDays);
            $leavebalance->authorized_by = \Auth::user()->id;
            $leavebalance->save();

            $calenderholiday->deleted_by = Auth::id();
            $calenderholiday->save();
            CalenderHolidaySplitMonth::where('calender_holiday_id', $calenderholiday->id)->delete();
            if ($calenderholiday->delete()) {
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
                    $user_id = Auth::id();
                    foreach ($ids as $id) {
                        $calenderholiday = CalenderHolidayModel::find($id);
                        $leaveBalance = LeaveBalance::where('staff_central_id', $calenderholiday->staff_central_id)->where('leave_id', $calenderholiday->leave_id)->orderBy('id', 'desc')->latest()->first();

                        //reverse of grant leave on leave balance
                        $previousLeaveDays = $calenderholiday->leave_days;

                        //reverse of previous leave
                        $leavebalance = new LeaveBalance();
                        $leavebalance->staff_central_id = $calenderholiday->staff_central_id;
                        $leavebalance->leave_id = $calenderholiday->leave_id;
                        $leavebalance->date_np = BSDateHelper::AdToBs('-', date('Y-m-d'));
                        $leavebalance->date = date('Y-m-d');
                        $leavebalance->fy_id = FiscalYearModel::IsActiveFiscalYear()->value('id');
                        $leavebalance->description = 'Grant Leave Edit Operation';
                        $leavebalance->consumption = 0;
                        $leavebalance->earned = $previousLeaveDays;
                        $leavebalance->balance = ($leaveBalance->balance + $previousLeaveDays);
                        $leavebalance->authorized_by = \Auth::user()->id;
                        $leavebalance->save();

                        $calenderholiday->deleted_by = $user_id;
                        $calenderholiday->save();
                        CalenderHolidaySplitMonth::where('calender_holiday_id', $calenderholiday->id)->delete();
                        $calenderholiday->delete();
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

    public function check_public_holiday(Request $request)
    {
        //checking if public holiday or weekend lies in the leave days
        $holiday_days = $this->daysDifference($request->date_from, $request->date_to);
        $system_holidays = SystemHolidayMastModel::with('branch')->get();
        $work_schedule = StaffWorkScheduleMastModel::where('staff_central_id', $request->staff_id)->latest()->first();
        $staff = StafMainMastModel::find($request->staff_id);
        $weekend = $work_schedule->weekend_day;
        if ($weekend == 7) {
            $weekend = 0;
        }
        $public_holidays = 0;
        $weekend_count = 0;
        $public_weekend = 0; // public holiday including weekend


        $start = strtotime($request->date_from);
        $end = strtotime($request->date_to);
        for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
            $date = date('Y-m-d', $i);
            if ($this->calenderHolidayRepository->checkIfPublicHoliday($date, $system_holidays, $staff)) {
                $public_holidays++;
            }
            $day = date("w", $i);
            if ($day == $weekend) {
                $weekend_count++;
                if ($this->calenderHolidayRepository->checkIfPublicHoliday($date, $system_holidays, $staff)) {
                    $public_weekend++;
                }
            }

        }
        return response()->json(['weekend' => $weekend_count, 'public_holidays' => $public_holidays, 'public_weekend' => $public_weekend, 'holiday_days' => $holiday_days]);

    }

    public function calenderHolidayCondition(Request $request)
    {
        $leave_id = $request->leave_id;
        $staff_id = $request->staff_id;
        $leave_from_np = $request->date_from_np;
        $leave_to_np = $request->date_to_np;
        $is_half_day = $request->is_half_day;
        $calenderholiday = $request->calenderholiday;
        $organization=OrganizationSetup::first();
        $response = $this->calenderHolidayRepository->check_conditions($leave_id, $staff_id, $leave_from_np, $leave_to_np, $is_half_day, $calenderholiday,$organization);
        return response()->json($response);

    }

    public function daysDifference($date_from, $date_to)
    {
        $dateFrom = date_create($date_from);
        $dateTo = date_create($date_to);
        $holidayDays = date_diff($dateFrom, $dateTo)->format('%a') + 1;
        return $holidayDays;
    }


}
