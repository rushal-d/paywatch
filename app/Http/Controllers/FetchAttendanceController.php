<?php

namespace App\Http\Controllers;

use App\Department;
use App\EmployeeStatus;
use App\ErrorLog;
use App\FetchAttendance;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\Helpers\DateHelper;
use App\Http\Requests\FetchAttendanceRequest;
use App\OrganizationMastShift;
use App\OrganizationSetup;
use App\Repositories\DepartmentRepository;
use App\Repositories\ErrorLogRepository;
use App\Repositories\FetchAttendanceRepository;
use App\Repositories\ShiftRepository;
use App\Repositories\StafMainMastRepository;
use App\Repositories\SystemHolidayMastRepository;
use App\Repositories\SystemOfficeMastRepository;
use App\Shift;
use App\StaffType;
use App\StafMainMastModel;
use App\SystemHolidayMastModel;
use App\SystemLeaveMastModel;
use App\SystemOfficeMastModel;

use App\Traits\OvertimeCalculation;
use App\User;
use Carbon\Carbon;
use DateTime;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;

class FetchAttendanceController extends Controller
{
    use OvertimeCalculation;

    /**
     * @var FetchAttendanceRepository
     */
    private $fetchAttendanceRepository;
    private $systemOfficeMastRepository;
    protected $departmentRepository;
    protected $shiftRepository;
    private $staffMainMastRepository;
    private $systemHolidayMastRepository;
    private $errorLogRepository;

    public function __construct(
        FetchAttendanceRepository $fetchAttendanceRepository,
        SystemOfficeMastRepository $systemOfficeMastRepository,
        DepartmentRepository $departmentRepository,
        ShiftRepository $shiftRepository,
        StafMainMastRepository $staffMainMastRepository,
        SystemHolidayMastRepository $systemHolidayMastRepository,
        ErrorLogRepository $errorLogRepository
    )
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $this->fetchAttendanceRepository = $fetchAttendanceRepository;
        $this->systemOfficeMastRepository = $systemOfficeMastRepository;
        $this->departmentRepository = $departmentRepository;
        $this->shiftRepository = $shiftRepository;
        $this->staffMainMastRepository = $staffMainMastRepository;
        $this->systemHolidayMastRepository = $systemHolidayMastRepository;
        $this->errorLogRepository = $errorLogRepository;

        $currentNepaliDateMonth = BSDateHelper::getBSYearMonthDayArrayFromEnDate(date('Y-m-d'))['month'];

        view()->share('currentNepaliDateMonth', $currentNepaliDateMonth);
    }

    public function index(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $departments = $this->departmentRepository->getAllDepartments();
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');

        $months = Config::get('constants.month_name');

        return view('localattendance.index', [
            'branches' => $branches,
            'departments' => $departments,
            'fiscal_years' => $fiscal_years,
            'title' => 'Staff Attendance',
            'i' => 1,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'months' => $months,
            'current_fiscal_year_id' => $current_fiscal_year_id,
        ]);
    }

    public function create(Request $request)
    {
        $title = 'Add Force Entry Attendance';
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();

        $staff_central_id = $branch_id = $attendance_date_np = null;

        if (request()->has('staff_central_id')) {
            $staff_central_id = request('staff_central_id');
        }

        if (request()->has('branch_id')) {
            $branch_id = request('branch_id');
        }

        if (request()->has('attendance_date_np')) {
            $attendance_date_np = request('attendance_date_np');
        }

        return view('localattendance.create', compact('title', 'branches', 'staff_central_id', 'branch_id', 'attendance_date_np'));
    }


    public function edit(Request $request)
    {
        $title = 'Edit Local Attendance';
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();

        $localAttendance = FetchAttendance::where('id', request()->id)->first();
        $staff_central_id = $localAttendance->staff_central_id;

        $branch_id = $localAttendance->branch_id;

        return view('localattendance.edit', compact('title', 'branches', 'localAttendance'));
    }

    public function store(FetchAttendanceRequest $request)
    {
        $staff = StafMainMastModel::where('id', $request->staff_central_id)->first();

        if (empty($staff)) {
            return redirect()->back()->withInput()->withErrors([
                'Staff Not Found'
            ]);
        }

        $request['attendance_date_en'] = $request->attendance_date;
        $inputs = $this->fetchAttendanceRepository->getInputsForDateTimeFields($request->all());

        $inputs['status'] = FetchAttendance::forceLeave;
        $inputs['branch_id'] = $request->branch_id;
        $inputs['sync'] = FetchAttendance::sync;
        $inputs['is_force'] = FetchAttendance::forced;
        $inputs['shift_id'] = $staff->shift_id;
        $inputs['staff_central_id'] = $staff->id;
        $inputs['total_work_hour'] = 0;
        //init array here to avoid last value of personalIn, personalOut when prev value is there
        $datesArray = [];

        if (!empty(request('punchout_datetime_np'))) {
            $datesArray['punchOut'] = Carbon::parse($inputs['punchout_datetime']);
            $datesArray['punchIn'] = Carbon::parse($inputs['punchin_datetime']);
            //also subtract personal in out duration
            $datesArray['personalIn'] = $inputs['personalin_datetime'];
            $datesArray['personalOut'] = $inputs['personalout_datetime'];
            $inputs['total_work_hour'] = $this->fetchAttendanceRepository->calculateTotalHoursWork($datesArray);
        }

        if ((empty($request->previous_remarks) || $request->previous_remarks == '' || trim($request->previous_remarks) == '') && (empty($request->remarks) || $request->remarks == '' || trim($request->remarks) == '')) {
            $inputs['remarks'] = '';
        } else if (empty($request->remarks) || $request->remarks == '' || trim($request->remarks) == '') {
            $inputs['remarks'] = $request->previous_remarks;
        } else if (empty($request->previous_remarks) || $request->previous_remarks == '' || trim($request->previous_remarks) == '') {
            $inputs['remarks'] = $request->remarks;
        } else {
            $inputs['remarks'] = $request->previous_remarks . '<br>' . $request->remarks;
        }

        $fetchAttendance = FetchAttendance::where('staff_central_id', $request->staff_central_id)
            ->whereDate('punchin_datetime', $request->attendance_date)
            ->first();

        try {
            if (empty($fetchAttendance)) {
                $fetchAttendance = new FetchAttendance();
                $fetchAttendance->created_at = Carbon::now();
                $fetchAttendance->created_by = auth()->id();
            } else {
                $fetchAttendance->updated_by = auth()->id();
                $fetchAttendance->updated_at = Carbon::now();
            }

            $fetchAttendance->punchin_datetime_np = $inputs['punchin_datetime_np'];
            $fetchAttendance->branch_id = $inputs['branch_id'];
            $fetchAttendance->punchout_datetime_np = $inputs['punchout_datetime_np'];
            $fetchAttendance->tiffinin_datetime_np = $inputs['tiffinin_datetime_np'];
            $fetchAttendance->tiffinout_datetime_np = $inputs['tiffinout_datetime_np'];
            $fetchAttendance->personalin_datetime_np = $inputs['personalin_datetime_np'];
            $fetchAttendance->personalout_datetime_np = $inputs['personalout_datetime_np'];
            $fetchAttendance->lunchin_datetime_np = $inputs['lunchin_datetime_np'];
            $fetchAttendance->lunchout_datetime_np = $inputs['lunchout_datetime_np'];
            $fetchAttendance->punchin_datetime = $inputs['punchin_datetime'];
            $fetchAttendance->punchout_datetime = $inputs['punchout_datetime'];
            $fetchAttendance->total_work_hour = $inputs['total_work_hour'];
            $fetchAttendance->tiffinin_datetime = $inputs['tiffinin_datetime'];
            $fetchAttendance->tiffinout_datetime = $inputs['tiffinout_datetime'];
            $fetchAttendance->personalin_datetime = $inputs['personalin_datetime'];
            $fetchAttendance->personalout_datetime = $inputs['personalout_datetime'];
            $fetchAttendance->lunchin_datetime = $inputs['lunchin_datetime'];
            $fetchAttendance->lunchout_datetime = $inputs['lunchout_datetime'];
            $fetchAttendance->remarks = $inputs['remarks'];
            $fetchAttendance->sync = $inputs['sync'];
            $fetchAttendance->status = $inputs['status'];
            $fetchAttendance->shift_id = $inputs['shift_id'];
            $fetchAttendance->staff_central_id = $inputs['staff_central_id'];
            $fetchAttendance->remarks = $inputs['remarks'];
            $fetchAttendance->is_force = $inputs['is_force'];

            $fetchAttendance->save();

            $status = true;
            $mesg = 'Added Successfully';
        } catch (\Exception $exception) {
            DB::rollback();
            $this->errorLogRepository->store($exception);
            $status = false;
            $mesg = 'Error Occured! Try Again!';
        }

        if ($status) {
            DB::commit();
        }

        return redirect()->route('localattendance-create')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /*  public function update(FetchAttendanceRequest $request, $id)
      {
          $request['attendance_date_en'] = BSDateHelper::BsToAd('-', $request->attendance_date_np);

          $inputs = $this->fetchAttendanceRepository->getStoreInputs($request);

          $status = $this->fetchAttendanceRepository->update($inputs, $id);

          $mesg = $this->fetchAttendanceRepository->retrieveSaveMessageForUpdate($status);

          return redirect()->route('localattendance-edit', $id)->with('flash', array('status' => $status, 'mesg' => $mesg));
      }*/

    public function print(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'staff_central_id' => 'required',
            'branch_id' => 'required',
        ],
            [
                'staff_central_id.required' => 'You must Select Staff Name!',
                'from_date_np.required' => 'You must enter Date From!',
                'to_date_np.required' => 'You must enter Date To!',
                'branch_id.required' => 'You must Select Branch!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            $organization = OrganizationSetup::first();
            $local_attendances = FetchAttendance::query();


            $requestStaffCentralId = $this->staffMainMastRepository->getViewableStaffCentralIdFromRequestedStaffCentralId($request->staff_central_id);
            $staff = StafMainMastModel::with('workschedule', 'grantLeave', 'branch', 'payrollBranch')->find($requestStaffCentralId);


            $next_staff = StafMainMastModel::query();
            $previous_staff = StafMainMastModel::query();

            if (!empty($request->branch_id)) {
                $next_staff->where('branch_id', $request->branch_id);
                $previous_staff->where('branch_id', $request->branch_id);
            }

            if (!empty($request->department_id)) {
                $next_staff->where('department', $request->department_id);
                $previous_staff->where('department', $request->department_id);
            }
            if (!empty($request->staff_central_id)) {
                $next_staff->orderBy('main_id', 'ASC')->where('main_id', '>', $staff->main_id);
                $previous_staff->orderBy('main_id', 'DESC')->where('main_id', '<', $staff->main_id);
                $local_attendances->where('staff_central_id', $request->staff_central_id);
            }

            if (!empty($request->from_date) && !empty($request->to_date)) {
                $local_attendances->whereDate('punchin_datetime', '>=', $request->from_date)->whereDate('punchin_datetime', '<=', $request->to_date);
            }

            $next_staff = $next_staff->first();
            $previous_staff = $previous_staff->latest()->first();


            $previous_staff_id = !empty($previous_staff) ? $previous_staff->id : null;
            $next_staff_id = !empty($next_staff) ? $next_staff->id : null;

            $local_attendances = $local_attendances->with('staff')->get();
            $suspenses = EmployeeStatus::where('staff_central_id', $staff->id)
                ->get();

            $public_holidays = SystemHolidayMastModel::with('branch')->get();
            $system_leave_name = SystemLeaveMastModel::pluck('leave_name', 'leave_id');
            $weekend_name = Config::get('constants.weekend_days');
            $date = $request->from_date ?? BSDateHelper::BsToAd('-', $request->from_date_np);
            $end_date = $request->to_date;
            $attendance_data = array();
            $i = 1;
            $public_holiday_count = 0;
            $present_on_public_holiday = 0;
            $weekend_count = 0;
            $public_holiday_on_weekend = 0;//check if it is public holiday on weekend
            $present_weekend_count = 0;
            $present_days = 0;
            $absent_days = 0;
            $paid_leave = 0;
            $public_holiday_work_hour = 0;
            $weekend_work_hour = 0;
            $suspension_days = 0;
            $publicHolidayPresentFetchAttendanceIds = [];
            $overall_total_working_hour = 0;
            $original_date_from = $date;
            $date_to = $end_date;
            while (strtotime($date) <= strtotime($end_date)) {
                $work_hour_day = 0;
                $attendance_data[$i]['id'] = null;
                $checkResignDays = $suspenses->whereNotIn('status', [EmployeeStatus::STATUS_WORKING, EmployeeStatus::STATUS_SUSPENSE])
                    ->where('date_from', '<=', $date);

                if ($checkResignDays->count() >= 1) {
                    $suspension_days++;
                    $absent_days++;
                    $attendance_data[$i]['status'] = 'Resignation'; //public holiday, weekend, grant leave

                    $attendance_data[$i]['add_classes'] = array();
                    $attendance_data[$i]['date'] = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($date)));
                    $i++;
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                    continue;
                }

                $checkSuspenseDays = $suspenses->whereIn('status', [EmployeeStatus::STATUS_SUSPENSE])
                    ->where('date_from', '<=', $date)
                    ->where('date_to', '>=', $date);

                if ($checkSuspenseDays->count() >= 1) {

                    $suspension_days++;
                    $absent_days++;
                    $attendance_data[$i]['status'] = 'Suspense'; //public holiday, weekend, grant leave

                    $attendance_data[$i]['add_classes'] = array();
                    $attendance_data[$i]['date'] = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($date)));
                    $i++;
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                    continue;
                }


                $detail = $local_attendances->where('punchin_datetime', '>=', $date . ' 00:00:00')
                    ->where('punchin_datetime', '<=', $date . ' 23:59:59')->first();
                if (!empty($detail)) {
                    $attendance_data[$i]['id'] = $detail->id;
                }
                $attendance_data[$i]['date'] = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($date)));
                $attendance_data[$i]['staff_central_id'] = $staff->id;
                $attendance_data[$i]['staff_name'] = $staff->name_eng;
                $attendance_data[$i]['branch_name'] = 1;
                $attendance_data[$i]['add_classes'] = array();

                $check_if_public_holiday = $this->getPublicHolidayCollectionBasedOnDateStaff($public_holidays, $date, $staff);

                $weekend_on_this_date = $staff->workschedule->where('effect_day', '<=', $date)->last();

                if (!empty($weekend_on_this_date) && (date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) && ($check_if_public_holiday->count() > 0)) {
                    $public_holiday_on_weekend++;
                }
                $check_if_grant_leave = $staff->grantLeave->where('from_leave_day', '<=', $date)->where('to_leave_day', '>=', $date)->first();
                if (!empty($detail)) {
                    $present_days++;
                    $attendance_data[$i]['status'] = 'Present';
                    $attendance_data[$i]['punch_in'] = $this->showTime($detail->punchin_datetime);
                    $attendance_data[$i]['punch_out'] = $this->showTime($detail->punchout_datetime);
                    $attendance_data[$i]['lunch_out'] = $this->showTime($detail->lunchout_datetime);
                    $attendance_data[$i]['lunch_in'] = $this->showTime($detail->lunchin_datetime);
                    $attendance_data[$i]['tiffin_out'] = $this->showTime($detail->tiffinout_datetime);
                    $attendance_data[$i]['tiffin_in'] = $this->showTime($detail->tiffinin_datetime);
                    $attendance_data[$i]['personal_out'] = $this->showTime($detail->personalout_datetime);
                    $attendance_data[$i]['personal_in'] = $this->showTime($detail->personalin_datetime);
                    $attendance_data[$i]['total_work_hour'] = $detail->total_work_hour;
                    $work_hour_day = $detail->total_work_hour;
                    $attendance_data[$i]['total_work_time_format'] = DateHelper::convertHourToHourAndMinutesFormat($work_hour_day);
                    $attendance_data[$i]['created_by'] = $detail->created_by ? ucwords(User::find($detail->created_by)->name) : null;
                    $attendance_data[$i]['updated_by'] = $detail->updated_by ? ucwords(User::find($detail->updated_by)->name) : null;
                    $attendance_data[$i]['remarks'] = $detail->remarks ?? null;
                    if ($detail->status == 99) {
                        array_push($attendance_data[$i]['add_classes'], 'is-force');
                    }
                    if ($detail->is_force == 1 || $detail->status == 99 || $detail->status == 77) {
                        $attendance_data[$i]['is_force'] = 1;
                    } else {
                        $attendance_data[$i]['is_force'] = 0;
                    }

                    /*count public holiday*/
                    if ($check_if_public_holiday->count() > 0) {
                        $public_holiday_count++;
                        $present_on_public_holiday++;
//                        $roundedPublicTotalWorkHour = DateHelper::getMaximumWorkHourForNonNormalDays($detail->total_work_hour);
                        $roundedPublicTotalWorkHour = $detail->total_work_hour;
                        $work_hour_day = $roundedPublicTotalWorkHour;
                        $public_holiday_work_hour += $roundedPublicTotalWorkHour;
//                        $attendance_data[$i]['total_work_time_format'] = DateHelper::convertHourToHourAndMinutesFormat($roundedPublicTotalWorkHour);
                        $publicHolidayPresentFetchAttendanceIds[] = $detail->id;
                        if (!(!empty($weekend_on_this_date) && date('N', strtotime($date)) == $weekend_on_this_date->weekend_day)) {
                            array_push($attendance_data[$i]['add_classes'], 'present-on-public-holiday');
                        }
                    }

                    // display total work hour for weekend
                    if (!empty($weekend_on_this_date) && date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                        $weekend_count++;
                        $present_weekend_count++;
//                        $attendance_data[$i]['total_work_time_format'] = DateHelper::convertHourToHourAndMinutesFormat($detail->total_work_hour);
//                        $roundedWeekendTotalWorkHour = DateHelper::getMaximumWorkHourForNonNormalDays($detail->total_work_hour);
                        $roundedWeekendTotalWorkHour = $detail->total_work_hour;
                        $work_hour_day = $detail->total_work_hour;
                        $weekend_work_hour += $roundedWeekendTotalWorkHour;

                        array_push($attendance_data[$i]['add_classes'], 'present-on-weekend');
                    }


                    if (!empty($check_if_grant_leave)) {
                        array_push($attendance_data[$i]['add_classes'], 'present-on-leave');
                        if ($check_if_grant_leave->leave_days == 0.5) {
                            $attendance_data[$i]['remarks'] .= ' Half Day Leave';
                        }
                    }

                } else {
                    $absent_days++;
                    $attendance_data[$i]['status'] = 'Absent'; //public holiday, weekend, grant leave
                    //if grant leave on weekend then do not regard weekend and public holiday
                    if (empty($check_if_grant_leave)) {
                        if ($check_if_public_holiday->count() > 0) {
                            $attendance_data[$i]['status'] = "Public Holiday " . $check_if_public_holiday->first()->holiday_descri ?? '';
                            $public_holiday_count++;
                        }

                        if (!empty($weekend_on_this_date) && date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                            $weekend_count++;
                            $attendance_data[$i]['status'] = "Weekend Day";

                            if ($organization->absent_weekend_on_cons_absent == 1) {
                                $prevDay = date("Y-m-d", strtotime("-1 day", strtotime($date)));
                                $nextDay = date("Y-m-d", strtotime("+1 day", strtotime($date)));

                                $check_if_public_holiday_prev_day = $public_holidays->filter(function ($public_holidays) use ($prevDay) {
                                    return $public_holidays->from_date <= $prevDay && $public_holidays->to_date >= $prevDay;
                                });
                                $check_if_public_holiday_next_day = $public_holidays->filter(function ($public_holidays) use ($nextDay) {
                                    return $public_holidays->from_date <= $nextDay && $public_holidays->to_date >= $nextDay;
                                });

                                if ($check_if_public_holiday_prev_day->count() == 0 && $check_if_public_holiday_next_day->count() == 0
                                    && ($original_date_from != $date) && ($date != $date_to)) {
                                    $prevDayWorkHour = $local_attendances->where('punchin_datetime', '>', $prevDay . ' 00:00:00')
                                            ->where('punchin_datetime', '<', $prevDay . ' 23:59:00')->first()->total_work_hour ?? 0;

                                    $nextDayWorkHour = $local_attendances->where('punchin_datetime', '>', $nextDay . ' 00:00:00')
                                            ->where('punchin_datetime', '<', $nextDay . ' 23:59:00')->first()->total_work_hour ?? 0;

                                    if ($prevDayWorkHour == 0 && $nextDayWorkHour == 0) {
                                        $attendance_data[$i]['status'] = "Weekend Day But Absent Case";
                                        $weekend_count--;
                                    }
                                }
                            }
                        }


                        if (($check_if_public_holiday->count() > 0) && !empty($weekend_on_this_date) && date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                            $attendance_data[$i]['status'] = 'Weekend Day' . " And Public Holiday " . $check_if_public_holiday->first()->holiday_descri ?? '';
                        }
                    }
                    if (!empty($check_if_grant_leave)) {
                        $attendance_data[$i]['status'] = "Approved Leave (" . $system_leave_name[$check_if_grant_leave->leave_id] . ')';
                        $paid_leave++;
                    }
                }

                $i++;
                $overall_total_working_hour += $work_hour_day;
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
            }

            $dStart = new DateTime($_GET['from_date']);
            $dEnd = new DateTime($_GET['to_date']);
            $dDiff = $dStart->diff($dEnd);
            $total_days = $dDiff->days + 1;
            $total_working_day = $total_days + $public_holiday_on_weekend - $weekend_count - $public_holiday_count;


            $absent_on_public_holiday = $public_holiday_count - $present_on_public_holiday;
            $absent_on_weekend = $weekend_count - $present_weekend_count;


            $actual_absent_days = $absent_days + $public_holiday_on_weekend - $absent_on_weekend - $absent_on_public_holiday;
            $unpaid_leave = $actual_absent_days - $paid_leave;
            $absent_days_without_public_holiday = $absent_days + $public_holiday_on_weekend - $absent_on_public_holiday;

            if ($staff->branch->manual_weekend_enable == SystemOfficeMastModel::MANUAL_WEEKEND_ENABLE) {

                if ($absent_days_without_public_holiday >= $weekend_count) {
                    $absent_on_weekend = $weekend_count;
                    $present_weekend_count = 0;
                } else {
                    $absent_on_weekend = $absent_days_without_public_holiday;
                    $present_weekend_count = $weekend_count - $absent_days_without_public_holiday;
                }

                $weekendsForManualWeekendEnabledBranch = $local_attendances->whereNotIn('id', $publicHolidayPresentFetchAttendanceIds)->sortByDesc('total_work_hour')->take($present_weekend_count);
                $weekend_work_hour = 0;

                foreach ($weekendsForManualWeekendEnabledBranch as $weekendForManualWeekendEnabledBranch) {
//                    $weekend_work_hour += DateHelper::getMaximumWorkHourForNonNormalDays($weekendForManualWeekendEnabledBranch->total_work_hour);
                    $weekend_work_hour += $weekendForManualWeekendEnabledBranch->total_work_hour;
                }
            }

            if ($staff->manual_attendance_enable == StafMainMastModel::MANUAL_ATTENDANCE_ENABLED) {
                $weekend_count = 4;
                if ($absent_days_without_public_holiday >= 4) {
                    $absent_on_weekend = 4;
                    $present_weekend_count = 0;
                } else {
                    $absent_on_weekend = $absent_days_without_public_holiday;
                    $present_weekend_count = 4 - $absent_days_without_public_holiday;
                }
                $weekend_work_hour = $present_weekend_count * DateHelper::getMaximumWorkHourForNonNormalDays();
            }

            $data['localattendances'] = $attendance_data;
            $data['i'] = 1;


            $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
            $departments = $this->departmentRepository->getAllDepartments();
            $associateStaffInBranch = StafMainMastModel::where('branch_id', request('branch_id'))->pluck('name_eng', 'id');
            $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
            $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
            $months = Config::get('constants.month_name');
            $forExcel = false;
            return view('localattendance.localattendance', [
                'weekend_name' => $weekend_name,
                'absent_on_weekend' => $absent_on_weekend,
                'absent_on_public_holiday' => $absent_on_public_holiday,
                'suspension_days' => $suspension_days,
                'present_on_public_holiday' => $present_on_public_holiday,
                'present_on_weekend' => $present_weekend_count,
                'branches' => $branches,
                'departments' => $departments,
                'associateStaffInBranchArray' => $associateStaffInBranch,
                'fiscal_years' => $fiscal_years,
                'current_fiscal_year_id' => $current_fiscal_year_id,
                'months' => $months,
                'staff' => $staff,
                'localattendances' => $attendance_data,
                'title' => 'Local Attendance',
                'i' => 1,
                'total_days' => $total_days,
                'total_working_days' => $total_working_day,
                'present_days' => $present_days,
                'absent_days' => $actual_absent_days,
                'public_holidays' => $public_holiday_count,
                'weekend_holidays' => $weekend_count,
                'public_holiday_on_weekend' => $public_holiday_on_weekend,
                'paid_leave' => $paid_leave,
                'unpaid_leave' => $unpaid_leave,
                'organization' => $organization,
                'previous_staff_id' => $previous_staff_id,
                'next_staff_id' => $next_staff_id,
                'public_holiday_work_hour' => $public_holiday_work_hour,
                'weekend_work_hour' => $weekend_work_hour,
                'overall_total_working_hour' => $overall_total_working_hour,
                'present_on_weekend_background_color' => config('constants.attendance_background_color_code.present_on_weekend'),
                'present_on_public_holiday_background_color' => config('constants.attendance_background_color_code.present_on_public_holiday'),
                'is_force_background_color' => config('constants.attendance_background_color_code.is_force'),
                'Weekend_background_color' => config('constants.attendance_background_color_code.Weekend'),
                'Absent_background_color' => config('constants.attendance_background_color_code.Absent'),
                'Approved_background_color' => config('constants.attendance_background_color_code.Approved'),
                'forExcel' => $forExcel
            ]);
        }

    }

    public function showTime($dateTime)
    {
        return !(empty($dateTime)) ? date('h:i a', strtotime($dateTime)) : '';
    }

    public function excelExport(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'staff_central_id' => 'required',
            'branch_id' => 'required',
        ],
            [
                'staff_central_id.required' => 'You must Select Staff Name!',
                'from_date_np.required' => 'You must enter Date From!',
                'to_date_np.required' => 'You must enter Date To!',
                'branch_id.required' => 'You must Select Branch!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {

            $local_attendances = FetchAttendance::query();
            $organization = OrganizationSetup::first();

            $requestStaffCentralId = $this->staffMainMastRepository->getViewableStaffCentralIdFromRequestedStaffCentralId($request->staff_central_id);
            $staff = StafMainMastModel::with('workschedule', 'grantLeave', 'branch', 'payrollBranch')->find($requestStaffCentralId);


            $next_staff = StafMainMastModel::query();
            $previous_staff = StafMainMastModel::query();

            if (!empty($request->branch_id)) {
                $next_staff->where('branch_id', $request->branch_id);
                $previous_staff->where('branch_id', $request->branch_id);
//                $local_attendances->where('branch_id', $request->branch_id);
            }

            if (!empty($request->department_id)) {
                $next_staff->where('department', $request->department_id);
                $previous_staff->where('department', $request->department_id);
            }
            if (!empty($request->staff_central_id)) {
                $next_staff->orderBy('main_id', 'ASC')->where('main_id', '>', $staff->main_id);
                $previous_staff->orderBy('main_id', 'DESC')->where('main_id', '<', $staff->main_id);
                $local_attendances->where('staff_central_id', $request->staff_central_id);
            }

            if (!empty($request->from_date) && !empty($request->to_date)) {
                $local_attendances->whereDate('punchin_datetime', '>=', $request->from_date)->whereDate('punchin_datetime', '<=', $request->to_date);
            }

            $next_staff = $next_staff->first();
            $previous_staff = $previous_staff->latest()->first();


            $previous_staff_id = !empty($previous_staff) ? $previous_staff->id : null;
            $next_staff_id = !empty($next_staff) ? $next_staff->id : null;

            $local_attendances = $local_attendances->with('staff')->get();
            $suspenses = EmployeeStatus::where('staff_central_id', $staff->id)
                ->get();

            $public_holidays = SystemHolidayMastModel::with('branch')->get();
            $system_leave_name = SystemLeaveMastModel::pluck('leave_name', 'leave_id');
            $weekend_name = Config::get('constants.weekend_days');
            $date = $request->from_date ?? BSDateHelper::BsToAd('-', $request->from_date_np);
            $end_date = $request->to_date;
            $attendance_data = array();
            $i = 1;
            $public_holiday_count = 0;
            $present_on_public_holiday = 0;
            $weekend_count = 0;
            $public_holiday_on_weekend = 0;//check if it is public holiday on weekend
            $present_weekend_count = 0;
            $present_days = 0;
            $absent_days = 0;
            $paid_leave = 0;
            $public_holiday_work_hour = 0;
            $weekend_work_hour = 0;
            $suspension_days = 0;
            $publicHolidayPresentFetchAttendanceIds = [];
            $overall_total_working_hour = 0;
            $original_date_from = $date;
            $date_to = $end_date;
            while (strtotime($date) <= strtotime($end_date)) {
                $work_hour_day = 0;
                $attendance_data[$i]['id'] = null;
                $checkResignDays = $suspenses->whereNotIn('status', [EmployeeStatus::STATUS_WORKING, EmployeeStatus::STATUS_SUSPENSE])
                    ->where('date_from', '<=', $date);

                if ($checkResignDays->count() >= 1) {
                    $suspension_days++;
                    $absent_days++;
                    $attendance_data[$i]['status'] = 'Resignation'; //public holiday, weekend, grant leave

                    $attendance_data[$i]['add_classes'] = array();
                    $attendance_data[$i]['date'] = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($date)));
                    $i++;
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                    continue;
                }

                $checkSuspenseDays = $suspenses->whereIn('status', [EmployeeStatus::STATUS_SUSPENSE])
                    ->where('date_from', '<=', $date)
                    ->where('date_to', '>=', $date);

                if ($checkSuspenseDays->count() >= 1) {

                    $suspension_days++;
                    $absent_days++;
                    $attendance_data[$i]['status'] = 'Suspense'; //public holiday, weekend, grant leave

                    $attendance_data[$i]['add_classes'] = array();
                    $attendance_data[$i]['date'] = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($date)));
                    $i++;
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                    continue;
                }


                $detail = $local_attendances->where('punchin_datetime', '>=', $date . ' 00:00:00')
                    ->where('punchin_datetime', '<=', $date . ' 23:59:59')->first();
                if (!empty($detail)) {
                    $attendance_data[$i]['id'] = $detail->id;
                }
                $attendance_data[$i]['date'] = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($date)));
                $attendance_data[$i]['staff_central_id'] = $staff->id;
                $attendance_data[$i]['staff_name'] = $staff->name_eng;
                $attendance_data[$i]['branch_name'] = 1;
                $attendance_data[$i]['add_classes'] = array();

                $check_if_public_holiday = $this->getPublicHolidayCollectionBasedOnDateStaff($public_holidays, $date, $staff);

                $weekend_on_this_date = $staff->workschedule->where('effect_day', '<=', $date)->last();

                if (!empty($weekend_on_this_date) && (date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) && ($check_if_public_holiday->count() > 0)) {
                    $public_holiday_on_weekend++;
                }
                $check_if_grant_leave = $staff->grantLeave->where('from_leave_day', '<=', $date)->where('to_leave_day', '>=', $date)->first();
                if (!empty($detail)) {
                    $present_days++;
                    $attendance_data[$i]['status'] = 'Present';
                    $attendance_data[$i]['punch_in'] = $this->showTime($detail->punchin_datetime);
                    $attendance_data[$i]['punch_out'] = $this->showTime($detail->punchout_datetime);
                    $attendance_data[$i]['lunch_out'] = $this->showTime($detail->lunchout_datetime);
                    $attendance_data[$i]['lunch_in'] = $this->showTime($detail->lunchin_datetime);
                    $attendance_data[$i]['tiffin_out'] = $this->showTime($detail->tiffinout_datetime);
                    $attendance_data[$i]['tiffin_in'] = $this->showTime($detail->tiffinin_datetime);
                    $attendance_data[$i]['personal_out'] = $this->showTime($detail->personalout_datetime);
                    $attendance_data[$i]['personal_in'] = $this->showTime($detail->personalin_datetime);
                    $attendance_data[$i]['total_work_hour'] = $detail->total_work_hour;
                    $work_hour_day = $detail->total_work_hour;
                    $attendance_data[$i]['total_work_time_format'] = DateHelper::convertHourToHourAndMinutesFormat($work_hour_day);
                    $attendance_data[$i]['created_by'] = $detail->created_by ? ucwords(User::find($detail->created_by)->name) : null;
                    $attendance_data[$i]['updated_by'] = $detail->updated_by ? ucwords(User::find($detail->updated_by)->name) : null;
                    $attendance_data[$i]['remarks'] = $detail->remarks ?? null;
                    if ($detail->status == 99) {
                        array_push($attendance_data[$i]['add_classes'], 'is-force');
                    }
                    if ($detail->is_force == 1 || $detail->status == 99 || $detail->status == 77) {
                        $attendance_data[$i]['is_force'] = 1;
                    } else {
                        $attendance_data[$i]['is_force'] = 0;
                    }

                    /*count public holiday*/
                    if ($check_if_public_holiday->count() > 0) {
                        $public_holiday_count++;
                        $present_on_public_holiday++;
//                        $roundedPublicTotalWorkHour = DateHelper::getMaximumWorkHourForNonNormalDays($detail->total_work_hour);
                        $roundedPublicTotalWorkHour = $detail->total_work_hour;
                        $work_hour_day = $roundedPublicTotalWorkHour;
                        $public_holiday_work_hour += $roundedPublicTotalWorkHour;
//                        $attendance_data[$i]['total_work_time_format'] = DateHelper::convertHourToHourAndMinutesFormat($roundedPublicTotalWorkHour);
                        $publicHolidayPresentFetchAttendanceIds[] = $detail->id;
                        if (!(!empty($weekend_on_this_date) && date('N', strtotime($date)) == $weekend_on_this_date->weekend_day)) {
                            array_push($attendance_data[$i]['add_classes'], 'present-on-public-holiday');
                        }
                    }

                    // display total work hour for weekend
                    if (!empty($weekend_on_this_date) && date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                        $weekend_count++;
                        $present_weekend_count++;
//                        $attendance_data[$i]['total_work_time_format'] = DateHelper::convertHourToHourAndMinutesFormat($detail->total_work_hour);
//                        $roundedWeekendTotalWorkHour = DateHelper::getMaximumWorkHourForNonNormalDays($detail->total_work_hour);
                        $roundedWeekendTotalWorkHour = $detail->total_work_hour;
                        $work_hour_day = $detail->total_work_hour;
                        $weekend_work_hour += $roundedWeekendTotalWorkHour;

                        array_push($attendance_data[$i]['add_classes'], 'present-on-weekend');
                    }


                    if (!empty($check_if_grant_leave)) {
                        array_push($attendance_data[$i]['add_classes'], 'present-on-leave');
                    }

                } else {
                    $absent_days++;
                    $attendance_data[$i]['status'] = 'Absent'; //public holiday, weekend, grant leave
                    //if grant leave on weekend then do not regard weekend and public holiday
                    if (empty($check_if_grant_leave)) {
                        if ($check_if_public_holiday->count() > 0) {
                            $attendance_data[$i]['status'] = "Public Holiday " . $check_if_public_holiday->first()->holiday_descri ?? '';
                            $public_holiday_count++;
                        }

                        if (!empty($weekend_on_this_date) && date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                            $weekend_count++;
                            $attendance_data[$i]['status'] = "Weekend Day";
                            if ($organization->absent_weekend_on_cons_absent == 1) {
                                $prevDay = date("Y-m-d", strtotime("-1 day", strtotime($date)));
                                $nextDay = date("Y-m-d", strtotime("+1 day", strtotime($date)));

                                $check_if_public_holiday_prev_day = $public_holidays->filter(function ($public_holidays) use ($prevDay) {
                                    return $public_holidays->from_date <= $prevDay && $public_holidays->to_date >= $prevDay;
                                });
                                $check_if_public_holiday_next_day = $public_holidays->filter(function ($public_holidays) use ($nextDay) {
                                    return $public_holidays->from_date <= $nextDay && $public_holidays->to_date >= $nextDay;
                                });

                                if ($check_if_public_holiday_prev_day->count() == 0 && $check_if_public_holiday_next_day->count() == 0
                                    && ($original_date_from != $date) && ($date != $date_to)) {
                                    $prevDayWorkHour = $local_attendances->where('punchin_datetime', '>', $prevDay . ' 00:00:00')
                                            ->where('punchin_datetime', '<', $prevDay . ' 23:59:00')->first()->total_work_hour ?? 0;

                                    $nextDayWorkHour = $local_attendances->where('punchin_datetime', '>', $nextDay . ' 00:00:00')
                                            ->where('punchin_datetime', '<', $nextDay . ' 23:59:00')->first()->total_work_hour ?? 0;

                                    if ($prevDayWorkHour == 0 && $nextDayWorkHour == 0) {
                                        $attendance_data[$i]['status'] = "Weekend Day But Absent Case";
                                        $weekend_count--;
                                    }
                                }
                            }
                        }


                        if (($check_if_public_holiday->count() > 0) && !empty($weekend_on_this_date) && date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                            $attendance_data[$i]['status'] = 'Weekend Day' . " And Public Holiday " . $check_if_public_holiday->first()->holiday_descri ?? '';
                        }
                    }

                    if (!empty($check_if_grant_leave)) {
                        $attendance_data[$i]['status'] = "Approved Leave (" . $system_leave_name[$check_if_grant_leave->leave_id] . ')';
                        $paid_leave++;
                    }
                }

                $i++;
                $overall_total_working_hour += $work_hour_day;
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
            }

            $dStart = new DateTime($_GET['from_date']);
            $dEnd = new DateTime($_GET['to_date']);
            $dDiff = $dStart->diff($dEnd);
            $total_days = $dDiff->days + 1;
            $total_working_day = $total_days + $public_holiday_on_weekend - $weekend_count - $public_holiday_count;


            $absent_on_public_holiday = $public_holiday_count - $present_on_public_holiday;
            $absent_on_weekend = $weekend_count - $present_weekend_count;


            $actual_absent_days = $absent_days + $public_holiday_on_weekend - $absent_on_weekend - $absent_on_public_holiday;
            $unpaid_leave = $actual_absent_days - $paid_leave;
            $absent_days_without_public_holiday = $absent_days + $public_holiday_on_weekend - $absent_on_public_holiday;

            if ($staff->branch->manual_weekend_enable == SystemOfficeMastModel::MANUAL_WEEKEND_ENABLE) {

                if ($absent_days_without_public_holiday >= $weekend_count) {
                    $absent_on_weekend = $weekend_count;
                    $present_weekend_count = 0;
                } else {
                    $absent_on_weekend = $absent_days_without_public_holiday;
                    $present_weekend_count = $weekend_count - $absent_days_without_public_holiday;
                }

                $weekendsForManualWeekendEnabledBranch = $local_attendances->whereNotIn('id', $publicHolidayPresentFetchAttendanceIds)->sortByDesc('total_work_hour')->take($present_weekend_count);
                $weekend_work_hour = 0;

                foreach ($weekendsForManualWeekendEnabledBranch as $weekendForManualWeekendEnabledBranch) {
//                    $weekend_work_hour += DateHelper::getMaximumWorkHourForNonNormalDays($weekendForManualWeekendEnabledBranch->total_work_hour);
                    $weekend_work_hour += $weekendForManualWeekendEnabledBranch->total_work_hour;
                }
            }

            if ($staff->manual_attendance_enable == StafMainMastModel::MANUAL_ATTENDANCE_ENABLED) {
                $weekend_count = 4;
                if ($absent_days_without_public_holiday >= 4) {
                    $absent_on_weekend = 4;
                    $present_weekend_count = 0;
                } else {
                    $absent_on_weekend = $absent_days_without_public_holiday;
                    $present_weekend_count = 4 - $absent_days_without_public_holiday;
                }
                $weekend_work_hour = $present_weekend_count * DateHelper::getMaximumWorkHourForNonNormalDays();
            }

            $data['localattendances'] = $attendance_data;
            $data['i'] = 1;


            $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
            $departments = $this->departmentRepository->getAllDepartments();
            $associateStaffInBranch = StafMainMastModel::where('branch_id', request('branch_id'))->pluck('name_eng', 'id');
            $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
            $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
            $months = Config::get('constants.month_name');

            $forExcel = true;

            \Excel::create('Local Attendance Export', function ($excel) use ($forExcel, $weekend_name, $branches, $departments, $associateStaffInBranch, $fiscal_years, $current_fiscal_year_id, $months, $staff, $attendance_data, $total_days, $total_working_day, $present_days, $actual_absent_days, $public_holiday_count, $weekend_count, $public_holiday_on_weekend, $paid_leave, $unpaid_leave, $organization, $previous_staff_id, $next_staff_id, $public_holiday_work_hour, $weekend_work_hour, $overall_total_working_hour, $absent_on_public_holiday, $present_on_public_holiday, $absent_on_weekend, $present_weekend_count, $suspension_days) {

                $excel->sheet('New sheet', function ($sheet) use ($forExcel, $weekend_name, $branches, $departments, $associateStaffInBranch, $fiscal_years, $current_fiscal_year_id, $months, $staff, $attendance_data, $total_days, $total_working_day, $present_days, $actual_absent_days, $public_holiday_count, $weekend_count, $public_holiday_on_weekend, $paid_leave, $unpaid_leave, $organization, $previous_staff_id, $next_staff_id, $public_holiday_work_hour, $weekend_work_hour, $overall_total_working_hour, $absent_on_public_holiday, $present_on_public_holiday, $absent_on_weekend, $present_weekend_count, $suspension_days) {

                    $sheet->loadView('localattendance.tablelocalattendance', [
                        'weekend_name' => $weekend_name,
                        'absent_on_weekend' => $absent_on_weekend,
                        'absent_on_public_holiday' => $absent_on_public_holiday,
                        'suspension_days' => $suspension_days,
                        'present_on_public_holiday' => $present_on_public_holiday,
                        'present_on_weekend' => $present_weekend_count,
                        'branches' => $branches,
                        'departments' => $departments,
                        'associateStaffInBranchArray' => $associateStaffInBranch,
                        'fiscal_years' => $fiscal_years,
                        'current_fiscal_year_id' => $current_fiscal_year_id,
                        'months' => $months,
                        'staff' => $staff,
                        'localattendances' => $attendance_data,
                        'title' => 'Local Attendance',
                        'i' => 1,
                        'total_days' => $total_days,
                        'total_working_days' => $total_working_day,
                        'present_days' => $present_days,
                        'absent_days' => $actual_absent_days,
                        'public_holidays' => $public_holiday_count,
                        'weekend_holidays' => $weekend_count,
                        'public_holiday_on_weekend' => $public_holiday_on_weekend,
                        'paid_leave' => $paid_leave,
                        'unpaid_leave' => $unpaid_leave,
                        'organization' => $organization,
                        'previous_staff_id' => $previous_staff_id,
                        'next_staff_id' => $next_staff_id,
                        'public_holiday_work_hour' => $public_holiday_work_hour,
                        'weekend_work_hour' => $weekend_work_hour,
                        'overall_total_working_hour' => $overall_total_working_hour,
                        'present_on_weekend_background_color' => config('constants.attendance_background_color_code.present_on_weekend'),
                        'present_on_public_holiday_background_color' => config('constants.attendance_background_color_code.present_on_public_holiday'),
                        'is_force_background_color' => config('constants.attendance_background_color_code.is_force'),
                        'Weekend_background_color' => config('constants.attendance_background_color_code.Weekend'),
                        'Absent_background_color' => config('constants.attendance_background_color_code.Absent'),
                        'Approved_background_color' => config('constants.attendance_background_color_code.Approved'),
                        'forExcel' => $forExcel
                    ]);

                });

            })->download('xlsx');
        }
    }

    public function excelExport2(Request $request)
    {
        $local_attendances = FetchAttendance::query();

        $requestStaffCentralId = $this->staffMainMastRepository->getViewableStaffCentralIdFromRequestedStaffCentralId($request->staff_central_id);

        $staff = StafMainMastModel::with('workschedule', 'grantLeave', 'branch')->find($requestStaffCentralId);

        $next_staff = StafMainMastModel::where('branch_id', $request->branch_id)->where('id', '>', $request->staff_central_id)->first();
        $previous_staff = StafMainMastModel::where('branch_id', $request->branch_id)->where('id', '<', $request->staff_central_id)->first();
        $previous_staff_id = $previous_staff ? $previous_staff->id : null;
        $next_staff_id = $next_staff ? $next_staff->id : null;

        $local_attendances = $local_attendances->where('staff_central_id', $requestStaffCentralId);
        $local_attendances = $local_attendances->with('staff')->get();
        $public_holidays = SystemHolidayMastModel::with('branch')->get();
        $system_leave_name = SystemLeaveMastModel::pluck('leave_name', 'leave_id');
        $weekend_name = Config::get('constants.weekend_days');
        $date = $request->from_date;
        $end_date = $request->to_date;
        $attendance_data = array();
        $i = 1;
        $public_holiday_count = 0;
        $present_on_public_holiday = 0;
        $weekend_count = 0;
        $public_holiday_on_weekend = 0;//check if it is public holiday on weekend
        $present_weekend_count = 0;
        $present_days = 0;
        $absent_days = 0;
        $paid_leave = 0;
        while (strtotime($date) <= strtotime($end_date)) {
            $detail = $local_attendances->where('punchin_datetime', '>', $date . ' 00:00:00')->where('punchin_datetime', '<', $date . ' 23:59:00')->first();
            $attendance_data[$i]['date'] = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($date)));
            $attendance_data[$i]['staff_central_id'] = $staff->id;
            $attendance_data[$i]['staff_name'] = $staff->name_eng;
            $attendance_data[$i]['branch_name'] = 1;
            $attendance_data[$i]['add_classes'] = array();
            $check_if_public_holiday = $this->getPublicHolidayCollectionBasedOnDateStaff($public_holidays, $date, $staff);
            $weekend_on_this_date = $staff->workschedule->where('effect_day', '<=', $date)->last();
            if ((date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) && ($check_if_public_holiday->count() > 0)) {
                $public_holiday_on_weekend++;
            }
            if (!empty($detail)) {
                $present_days++;
                $attendance_data[$i]['status'] = 'Present';
                $attendance_data[$i]['punch_in'] = date('H:i', strtotime($detail->punchin_datetime));
                $attendance_data[$i]['punch_out'] = date('H:i', strtotime($detail->punchout_datetime));
                $attendance_data[$i]['lunch_out'] = date('H:i', strtotime($detail->lunchout_datetime));
                $attendance_data[$i]['lunch_in'] = date('H:i', strtotime($detail->lunchin_datetime));
                $attendance_data[$i]['tiffin_out'] = date('H:i', strtotime($detail->tiffinout_datetime));
                $attendance_data[$i]['tiffin_in'] = date('H:i', strtotime($detail->tiffinin_datetime));
                $attendance_data[$i]['personal_out'] = date('H:i', strtotime($detail->personalout_datetime));
                $attendance_data[$i]['personal_in'] = date('H:i', strtotime($detail->personalin_datetime));
                $attendance_data[$i]['total_work_hour'] = $detail->total_work_hour;
                if ($detail->status != 0) {
                    array_push($attendance_data[$i]['add_classes'], 'is-force');
                }

                /*count public holiday*/
                if ($check_if_public_holiday->count() > 0) {
                    $public_holiday_count++;
                    $present_on_public_holiday++;
                    array_push($attendance_data[$i]['add_classes'], 'present-on-public-holiday');
                }

                if (date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                    $weekend_count++;
                    $present_weekend_count++;
                    array_push($attendance_data[$i]['add_classes'], 'present-on-weekend');
                }
            } else {
                $absent_days++;
                $attendance_data[$i]['status'] = 'Absent'; //public holiday, weekend, grant leave
                if ($check_if_public_holiday->count() > 0) {
                    $attendance_data[$i]['status'] = "Public Holiday";
                    $public_holiday_count++;
                }

                if (date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                    $weekend_count++;
                    $attendance_data[$i]['status'] = "Weekend Day";
                }

                if ($date > date('Y-m-d')) {
                    $absent_days--;
                    $attendance_data[$i]['status'] = "";
                }

                $check_if_grant_leave = $staff->grantLeave->where('from_leave_day', '>=', $date)->where('to_leave_day', '<=', $date)->first();
                if (!empty($check_if_grant_leave)) {
                    $attendance_data[$i]['status'] = "Approved Leave (" . $system_leave_name[$check_if_grant_leave->leave_id] . ')';
                    $paid_leave++;
                }
            }

            $i++;
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }
        $dStart = new DateTime($_GET['from_date']);
        $dEnd = new DateTime($_GET['to_date']);
        $dDiff = $dStart->diff($dEnd);
        $total_days = $dDiff->days + 1;
        $total_working_day = $total_days + $public_holiday_on_weekend - $weekend_count - $public_holiday_count;
        $actual_absent_days = $absent_days + $public_holiday_on_weekend - $weekend_count - $public_holiday_count;
        $unpaid_leave = $actual_absent_days - $paid_leave;
        $data['localattendances'] = $attendance_data;
        $data['i'] = 1;
        $organization = OrganizationSetup::first();

        $data = [
            'weekend_name' => $weekend_name,
            'staff' => $staff,
            'localattendances' => $attendance_data,
            'title' => 'Local Attendance',
            'i' => 1,
            'total_days' => $total_days,
            'total_working_days' => $total_working_day,
            'present_days' => $present_days,
            'absent_days' => $actual_absent_days,
            'public_holidays' => $public_holiday_count,
            'weekend_holidays' => $weekend_count,
            'public_holiday_on_weekend' => $public_holiday_on_weekend,
            'paid_leave' => $paid_leave,
            'unpaid_leave' => $unpaid_leave,
            'organization' => $organization,
            'previous_staff_id' => $previous_staff_id,
            'next_staff_id' => $next_staff_id,
            'present_on_weekend_background_color' => config('constants.attendance_background_color_code.present_on_weekend'),
            'present_on_public_holiday_background_color' => config('constants.attendance_background_color_code.present_on_public_holiday'),
            'is_force_background_color' => config('constants.attendance_background_color_code.is_force'),
            'Weekend_background_color' => config('constants.attendance_background_color_code.Weekend'),
            'Absent_background_color' => config('constants.attendance_background_color_code.Absent'),
            'Approved_background_color' => config('constants.attendance_background_color_code.Approved')
        ];

        \Excel::create('Attendance Report', function ($excel) use ($data, $weekend_name) {
            $excel->sheet('Attendance Report', function ($sheet) use ($data, $weekend_name) {
                $sheet->mergeCells('A1:J2');
                $sheet->cell('A1', function ($cell) use ($data) {
                    $cell->setValue($data['organization']->organization_name ?? 'Organization Name')
                        ->setFontSize(20)
                        ->setAlignment('center');
                });

                $sheet->mergeCells('A4:J4');
                $sheet->cell('A4', function ($cell) {
                    $cell->setValue('Staff Attendance Detail')
                        ->setFontSize(15)
                        ->setAlignment('center');
                });

                $sheet->cell('A6', function ($cell) {
                    $cell->setValue('Staff Name:')
                        ->setFontWeight('bold');
                });

                $sheet->cell('B6', function ($cell) use ($data) {
                    $cell->setValue($data['staff']->name_eng);
                });

                $sheet->cell('A7', function ($cell) {
                    $cell->setValue('Work Hour:')
                        ->setFontWeight('bold');
                });

                $sheet->cell('B7', function ($cell) use ($data) {
                    $cell->setValue($data['staff']->workschedule->last()->work_hour ?? '')
                        ->setAlignment('left');
                });

                $sheet->cell('A8', function ($cell) {
                    $cell->setValue('Attendance From:')
                        ->setFontWeight('bold');
                });

                $sheet->cell('B8', function ($cell) {
                    $cell->setValue($_GET['from_date_np'] ?? '');
                });

                $sheet->cell('A10', function ($cell) {
                    $cell->setValue('Date')
                        ->setFontWeight('bold');
                });

                $sheet->cell('B10', function ($cell) {
                    $cell->setValue('Punch In')
                        ->setFontWeight('bold');
                });

                $sheet->cell('C10', function ($cell) {
                    $cell->setValue('Punch Out')
                        ->setFontWeight('bold');
                });

                $sheet->cell('D10', function ($cell) {
                    $cell->setValue('Lunch Out')
                        ->setFontWeight('bold');
                });

                $sheet->cell('E10', function ($cell) {
                    $cell->setValue('Lunch In')
                        ->setFontWeight('bold');
                });

                $sheet->cell('F10', function ($cell) {
                    $cell->setValue('Tiffin Out')
                        ->setFontWeight('bold');
                });

                $sheet->cell('G10', function ($cell) {
                    $cell->setValue('Tiffin In')
                        ->setFontWeight('bold');
                });

                $sheet->cell('H10', function ($cell) {
                    $cell->setValue('Personal Out')
                        ->setFontWeight('bold');
                });

                $sheet->cell('I10', function ($cell) {
                    $cell->setValue('Personal In')
                        ->setFontWeight('bold');
                });

                $sheet->cell('J10', function ($cell) {
                    $cell->setValue('Total Work Hour')
                        ->setFontWeight('bold');
                });

                $sheet->cell('I6', function ($cell) {
                    $cell->setValue('Branch:')
                        ->setFontWeight('bold');
                });

                $sheet->cell('J6', function ($cell) use ($data) {
                    $cell->setValue($data['staff']->branch->office_name);
                });

                $sheet->cell('I7', function ($cell) {
                    $cell->setValue('Weekend Day:')
                        ->setFontWeight('bold');
                });

                $sheet->cell('J7', function ($cell) use ($weekend_name, $data) {
                    $cell->setValue($weekend_name[$data['staff']->workschedule->last()->weekend_day ?? '']);
                });

                $sheet->cell('I8', function ($cell) {
                    $cell->setValue('Attendance To:')
                        ->setFontWeight('bold');
                });

                $sheet->cell('J8', function ($cell) use ($data) {
                    $cell->setValue($_GET['to_date_np'] ?? '');
                });

                $positionOfTheRow = 11;

                foreach ($data['localattendances'] as $localattendance) {
                    $sheet->cell("A{$positionOfTheRow}", function ($cell) use ($positionOfTheRow, $localattendance) {
                        $cell->setValue($localattendance['date'] . ' (' . date('D', strtotime(BSDateHelper::BsToAd('-', $localattendance['date']))) . ')');
                    });

                    if ($localattendance['status'] == 'Present') {
                        $sheet->cell("B{$positionOfTheRow}", function ($cell) use ($positionOfTheRow, $localattendance) {
                            $cell->setValue($localattendance['punch_in']);
                        });
                        $sheet->cell("C{$positionOfTheRow}", function ($cell) use ($positionOfTheRow, $localattendance) {
                            $cell->setValue($localattendance['punch_out']);
                        });
                        $sheet->cell("D{$positionOfTheRow}", function ($cell) use ($positionOfTheRow, $localattendance) {
                            $cell->setValue($localattendance['lunch_out']);
                        });
                        $sheet->cell("E{$positionOfTheRow}", function ($cell) use ($positionOfTheRow, $localattendance) {
                            $cell->setValue($localattendance['lunch_in']);
                        });
                        $sheet->cell("F{$positionOfTheRow}", function ($cell) use ($positionOfTheRow, $localattendance) {
                            $cell->setValue($localattendance['tiffin_out']);
                        });
                        $sheet->cell("G{$positionOfTheRow}", function ($cell) use ($positionOfTheRow, $localattendance) {
                            $cell->setValue($localattendance['tiffin_in']);
                        });
                        $sheet->cell("H{$positionOfTheRow}", function ($cell) use ($positionOfTheRow, $localattendance) {
                            $cell->setValue($localattendance['personal_out']);
                        });
                        $sheet->cell("I{$positionOfTheRow}", function ($cell) use ($positionOfTheRow, $localattendance) {
                            $cell->setValue($localattendance['personal_in']);
                        });
                        $sheet->cell("J{$positionOfTheRow}", function ($cell) use ($positionOfTheRow, $localattendance) {
                            $cell->setValue($localattendance['total_work_hour'] . ' ' . str_plural('hours', $localattendance['total_work_hour']));
                        });
                    } else {
                        $sheet->mergeCells("B{$positionOfTheRow}:J{$positionOfTheRow}");
                        $sheet->cell("B{$positionOfTheRow}", function ($cell) use ($localattendance) {
                            $cell->setValue($localattendance['status']);
                        });
                    }

                    $positionOfTheRow++;
                }

                //Added an empty row to separate two tables
                $positionOfTheRow++;

                $sheet->cell("A{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Total Days: {$data['total_days']} " . str_plural('days', $data['total_days']));
                });

                $sheet->cell("B{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Total Working Days: {$data['total_working_days']} " . str_plural('days', $data['total_working_days']));
                });

                $sheet->cell("D{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Absent:");
                });

                $sheet->cell("E{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Color");
                });

                $positionOfTheRow++;

                $sheet->cell("A{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Present Days: {$data['present_days']} " . str_plural('days', $data['present_days']));
                });

                $sheet->cell("B{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Absent Days: {$data['absent_days']} " . str_plural('days', $data['absent_days']));
                });

                $sheet->cell("D{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Weekend:");
                });

                $sheet->cell("E{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Color");
                });

                $positionOfTheRow++;

                $sheet->cell("A{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Weekend Days: {$data['weekend_holidays']} " . str_plural('days', $data['weekend_holidays']));
                });

                $sheet->cell("B{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Public Holiday: {$data['public_holidays']} " . str_plural('days', $data['public_holidays']));
                });

                $sheet->cell("D{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Approved:");
                });

                $sheet->cell("E{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Color");
                });

                $positionOfTheRow++;

                $sheet->cell("A{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Public Holidays on Weekend:" . "{$data['public_holiday_on_weekend']} " . str_plural('days', $data['public_holiday_on_weekend']));
                });

                $sheet->cell("D{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Present On Weekend:");
                });

                $sheet->cell("E{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Color");
                });

                $positionOfTheRow++;

                $sheet->cell("A{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Paid Leave: {$data['paid_leave']} " . str_plural('days', $data['paid_leave']));
                });

                $sheet->cell("B{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Unpaid Leave: {$data['unpaid_leave']} " . str_plural('days', $data['unpaid_leave']));
                });

                $sheet->cell("D{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Present On Public Holiday:");
                });

                $sheet->cell("E{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Color");
                });

                $positionOfTheRow++;

                $sheet->cell("D{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Is Force:");
                });

                $sheet->cell("E{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Color");
                });

                $positionOfTheRow++;

                $sheet->cell("D{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Working:");
                });

                $sheet->cell("E{$positionOfTheRow}", function ($cell) use ($data) {
                    $cell->setValue("Color");
                });

            });
        })->download('xlsx');
    }

    public function show($id)
    {
        $local_attendance = FetchAttendance::find($id);
        return view('localattendance.show', [
            'title' => 'Local Attendance Show',
            'localattendance' => $local_attendance
        ]);
    }

    public function summary()
    {
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
        $departments = $this->departmentRepository->getAllDepartments();
        $months = Config::get('constants.month_name');
        $staff_types = StaffType::pluck('staff_type_title', 'staff_type_code');

        return view('localattendance.summary-index', [
            'branches' => $branches,
            'title' => 'Local Attendance Summary',
            'departments' => $departments,
            'i' => 1,
            'months' => $months,
            'current_fiscal_year_id' => $current_fiscal_year_id,
            'fiscal_years' => $fiscal_years,
            'staff_types' => $staff_types,
        ]);
    }

    public function summary_show(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'from_date_np' => 'required',
            'to_date_np' => 'required',
            'branch_id' => 'required',
        ],
            [
                'from_date_np.required' => 'You must enter Date From!',
                'to_date_np.required' => 'You must enter Date To!',
                'branch_id.required' => 'Please Select Brach!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
        $organization = OrganizationSetup::first();
        $date_from = BSDateHelper::BsToAd('-', $request->from_date_np);
        $date_to = BSDateHelper::BsToAd('-', $request->to_date_np);
        $dStart = new DateTime($date_from);
        $dEnd = new DateTime($date_to);
        $staff_types = StaffType::pluck('staff_type_title', 'staff_type_code');

        $branch = SystemOfficeMastModel::find($request->branch_id);
        $staffs = StafMainMastModel::with(['workschedule', 'grantLeave', 'staffStatus', 'branch', 'fetchAttendances' => function ($query) use ($date_from, $date_to) {
            $query->where('punchin_datetime', '>', $date_from . ' 00:00:00')
                ->where('punchin_datetime', '<', $date_to . ' 23:59:00');
        }])->where('branch_id', $request->branch_id);

        if (!empty($request->department_id)) {
            $staffs = $staffs->where('department', $request->department_id);
        }

        if ($request->staff_type != null) {
            $staffs = $staffs->whereIn('staff_type', $request->staff_type);
        }
        if (!empty($request->staff_central_id)) {
            $staffs = $staffs->where('id', $request->staff_central_id);
        }
        $staffs = $staffs->where('staff_status', 1);

        $dismissed_retired_staffs_this_month = EmployeeStatus::whereHas('staff', function ($query) use ($request) {
            $query->where('payroll_branch_id', $request->branch_id);
            if (!empty($request->department_id)) {
                $query->where('department', $request->department_id);
            }
            if ($request->staff_type != null) {
                $query->whereIn('staff_type', $request->staff_type);
            }
            if (!empty($request->staff_central_id)) {
                $query->where('id', $request->staff_central_id);
            }
        })
            ->whereIn(DB::raw('MONTH(date_from)'), [date('m', strtotime($date_from)), date('m', strtotime($date_to))])
            ->pluck('staff_central_id')
            ->toArray();;

        if (count($dismissed_retired_staffs_this_month) > 0) {
            $staffs = $staffs->orWhereIn('id', $dismissed_retired_staffs_this_month);
        }
        if ($organization->organization_code == "NEPALRE") {
            $staffs = $staffs->join('system_post_mast', 'system_post_mast.post_id', '=', 'staff_main_mast.post_id')->orderBy('system_post_mast.order');
        } else {
            $staffs = $staffs->orderBy('main_id', 'asc');
        }

        $staffs = $staffs->get();

        //ordering the staff according to the staff excel file
        if (!empty($branch->order_staff_ids)) {
            //mapping the staff branch ids to array
            $staff_order_ids = array_map('intval', explode(',', $branch->order_staff_ids));
            //adding the branch ids not available on the
            $staff_order_ids = array_merge($staff_order_ids, array_diff($staffs->pluck('main_id')->toArray(), $staff_order_ids));
            //sorting function
            $staffs = $staffs->sortBy(function ($model) use ($staff_order_ids) {
                return array_search($model->main_id, $staff_order_ids);
            });
        }
        $public_holidays = SystemHolidayMastModel::with('branch')->get();
        $weekend_name = Config::get('constants.weekend_days');
        $attendance_data = array();

        $dDiff = $dStart->diff($dEnd);
        $total_days = $dDiff->days + 1;
        $i = 1;
        if ($staffs->count() > 0) {

            foreach ($staffs as $staff) {
                $public_holiday_count = 0;
                $suspense_days = 0;
                $present_on_public_holiday = 0;
                $weekend_count = 0;
                $public_holiday_on_weekend = 0;//check if it is public holiday on weekend
                $present_weekend_count = 0;
                $present_on_leave = 0;
                $present_days = 0;
                $absent_days = 0;
                $paid_leave = 0;
                $grant_leave = 0;
                $total_work_hour = 0;
                $date = BSDateHelper::BsToAd('-', $request->from_date_np);
                $end_date = BSDateHelper::BsToAd('-', $request->to_date_np);
                $today = Carbon::now()->toDateString();
                $public_holiday_work_hour = 0;
                $weekend_holiday_work_hour = 0;
                $absent_on_pubic_holiday_on_weekend = 0;
                $original_date_from = $date;
                $date_to = $end_date;
                while (strtotime($date) <= strtotime($end_date)) {
                    $check_if_grant_leave = $staff->grantLeave->where('from_leave_day', '<=', $date)->where('to_leave_day', '>=', $date)->first();
                    $check_if_public_holiday = $this->getPublicHolidayCollectionBasedOnDateStaff($public_holidays, $date, $staff);

                    $suspenses = $staff->staffStatus;

                    $checkResignDays = $suspenses->whereNotIn('status', [EmployeeStatus::STATUS_WORKING, EmployeeStatus::STATUS_SUSPENSE])->where('date_from', '<=', $date);

                    if ($checkResignDays->count() > 0) {
                        $absent_days++;
                        $suspense_days++;
                        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                        continue;
                    }

                    $checkSuspenseDays = $suspenses->whereIn('status', [EmployeeStatus::STATUS_SUSPENSE])->where('date_from', '<=', $date)->where('date_to', '>=', $date);

                    if ($checkSuspenseDays->count() >= 1) {
                        $suspense_days++;
                        $absent_days++;
                        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                        continue;
                    }

                    $weekend_on_this_date = $staff->workschedule->where('effect_day', '<=', $date)->last();
                    if (empty($check_if_grant_leave)) {
                        if ($check_if_public_holiday->count() > 0) {
                            $public_holiday_count++;
                        }

                        if ($weekend_on_this_date) {
                            if ((date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) && ($check_if_public_holiday->count() > 0)) {
                                $public_holiday_on_weekend++;
                            }
                        } else {
                            $public_holiday_on_weekend += 0;
                        }
                    }

                    $detail = $staff->fetchAttendances->where('punchin_datetime', '>', $date . ' 00:00:00')
                        ->where('punchin_datetime', '<', $date . ' 23:59:00')->first();

                    if (!empty($detail)) {
                        $present_days++;
                        $work_hour_day = $detail->total_work_hour;
                        if ($staff->is_holding == 1) {
                            if ($detail->total_work_hour > 8) {
                                $work_hour_day = 8;
                            }
                        }
                        if ($check_if_public_holiday->count() > 0) {
                            $present_on_public_holiday++;
//                            $roundedPublicTotalWorkHour = DateHelper::getMaximumWorkHourForNonNormalDays($detail->total_work_hour);
                            $roundedPublicTotalWorkHour = $detail->total_work_hour;
                            $work_hour_day = $roundedPublicTotalWorkHour;
                            $public_holiday_work_hour += $roundedPublicTotalWorkHour;
                        }
                        if (!empty($weekend_on_this_date) && date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                            $weekend_count++;
                            $present_weekend_count++;
//                            $roundedWeekendTotalWorkHour = DateHelper::getMaximumWorkHourForNonNormalDays($detail->total_work_hour);
                            $roundedWeekendTotalWorkHour = $detail->total_work_hour;
                            $weekend_holiday_work_hour += $roundedWeekendTotalWorkHour;
                        }

                        if (!empty($check_if_grant_leave)) {
                            $present_on_leave++;
                            $grant_leave++;
                            $absent_days++;
                        }
                        $total_work_hour += $work_hour_day;

                    } else {
                        $absent_days++;

                        /*absent on weekend with public holiday*/
                        if (empty($check_if_grant_leave)) {
                            $weekend_on_this_date = $staff->workschedule->where('effect_day', '<=', $date)->last();
                            if ($weekend_on_this_date) {
                                if ((date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) && ($check_if_public_holiday->count() > 0)) {
                                    $absent_on_pubic_holiday_on_weekend++;
                                }
                            }


                            if ($weekend_on_this_date) {
                                if (date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                                    $weekend_count++;
                                    if ($organization->absent_weekend_on_cons_absent == 1) {
                                        $prevDay = date("Y-m-d", strtotime("-1 day", strtotime($date)));
                                        $nextDay = date("Y-m-d", strtotime("+1 day", strtotime($date)));

                                        $check_if_public_holiday_prev_day = $public_holidays->filter(function ($public_holidays) use ($prevDay) {
                                            return $public_holidays->from_date <= $prevDay && $public_holidays->to_date >= $prevDay;
                                        });
                                        $check_if_public_holiday_next_day = $public_holidays->filter(function ($public_holidays) use ($nextDay) {
                                            return $public_holidays->from_date <= $nextDay && $public_holidays->to_date >= $nextDay;
                                        });

                                        if ($check_if_public_holiday_prev_day->count() == 0 && $check_if_public_holiday_next_day->count() == 0
                                            && ($original_date_from != $date) && ($date != $date_to)) {
                                            $prevDayWorkHour = $staff->fetchAttendances->where('punchin_datetime', '>', $prevDay . ' 00:00:00')
                                                    ->where('punchin_datetime', '<', $prevDay . ' 23:59:00')->first()->total_work_hour ?? 0;

                                            $nextDayWorkHour = $staff->fetchAttendances->where('punchin_datetime', '>', $nextDay . ' 00:00:00')
                                                    ->where('punchin_datetime', '<', $nextDay . ' 23:59:00')->first()->total_work_hour ?? 0;

                                            if ($prevDayWorkHour == 0 && $nextDayWorkHour == 0) {
                                                $weekend_count--;
                                            }
                                        }
                                    }
                                }
                            }
                        }


                        if (!empty($check_if_grant_leave)) {
                            $grant_leave++;
                        }
                    }
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                }
                $absent_on_weekend = $weekend_count - $present_weekend_count;
                $absent_on_public_holiday = $public_holiday_count - $present_on_public_holiday;
                $total_working_day = $total_days + $public_holiday_on_weekend - $weekend_count - $public_holiday_count;
//            $actual_absent_days = $absent_days + $public_holiday_on_weekend - $weekend_count - $public_holiday_count + $present_weekend_count + $present_on_public_holiday;
                $paid_leave = ($absent_on_weekend + $absent_on_public_holiday + $grant_leave - $absent_on_pubic_holiday_on_weekend);
                $unpaid_leave = $total_days - $present_days - $paid_leave;
                $actual_absent_days = $paid_leave + $unpaid_leave;
                $absent_days_without_public_holiday = $absent_days + $public_holiday_on_weekend - $absent_on_public_holiday;

                if ($staff->branch->manual_weekend_enable == SystemOfficeMastModel::MANUAL_WEEKEND_ENABLE) {
                    if ($absent_days_without_public_holiday >= $weekend_count) {
                        $absent_on_weekend = $weekend_count;
                        $present_weekend_count = 0;
                    } else {
                        $absent_on_weekend = $absent_days_without_public_holiday;
                        $present_weekend_count = $weekend_count - $absent_days_without_public_holiday;
                    }
                    $weekendsForManualWeekendEnabledBranch = $staff->fetchAttendances->sortByDesc('total_work_hour')->take($present_weekend_count);
                    $weekend_holiday_work_hour = 0;

                    foreach ($weekendsForManualWeekendEnabledBranch as $weekendForManualWeekendEnabledBranch) {
                        $weekend_holiday_work_hour += DateHelper::getMaximumWorkHourForNonNormalDays($weekendForManualWeekendEnabledBranch->total_work_hour);
                    }
                }

                if ($staff->manual_attendance_enable == StafMainMastModel::MANUAL_ATTENDANCE_ENABLED) {
                    $weekend_count = 4;
                    if ($absent_days_without_public_holiday >= 4) {
                        $absent_on_weekend = 4;
                        $present_weekend_count = 0;
                    } else {
                        $absent_on_weekend = $absent_days_without_public_holiday;
                        $present_weekend_count = 4 - $absent_days_without_public_holiday;
                    }
                    $weekend_holiday_work_hour = $present_weekend_count * DateHelper::getMaximumWorkHourForNonNormalDays();
                }

                $attendance_data[$staff->id]['CID'] = $staff->id;
                $attendance_data[$staff->id]['name'] = $staff->name_eng;
                $attendance_data[$staff->id]['total_days'] = $total_days;
                $attendance_data[$staff->id]['total_working_days'] = $total_working_day;
                $attendance_data[$staff->id]['present_days'] = $present_days;
                $attendance_data[$staff->id]['absent_days'] = $actual_absent_days;
                $attendance_data[$staff->id]['weekend_days'] = $weekend_count;
                $attendance_data[$staff->id]['public_holidays'] = $public_holiday_count;
                $attendance_data[$staff->id]['public_holidays_on_weekend'] = $public_holiday_on_weekend;
                $attendance_data[$staff->id]['present_on_public_holidays'] = $present_on_public_holiday;
                $attendance_data[$staff->id]['present_on_weekend'] = $present_weekend_count;
                $attendance_data[$staff->id]['paid_leave'] = $paid_leave;
                $attendance_data[$staff->id]['unpaid_leave'] = $unpaid_leave;
                $attendance_data[$staff->id]['absent_on_weekend'] = $absent_on_weekend;
                $attendance_data[$staff->id]['grant_leave'] = $grant_leave;
                $attendance_data[$staff->id]['total_work_hour'] = $total_work_hour;
                $attendance_data[$staff->id]['public_holiday_work_hour'] = $public_holiday_work_hour;
                $attendance_data[$staff->id]['weekend_holiday_work_hour'] = $weekend_holiday_work_hour;
                $attendance_data[$staff->id]['main_id'] = $staff->main_id;
                $attendance_data[$staff->id]['suspense_days'] = $suspense_days;
                $i++;
            }

        } else {
            $staff = [];
            $public_holiday_count = 0;
        }

        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');

        $departments = $this->departmentRepository->getAllDepartments();

        $organization = OrganizationSetup::first();

        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
        $months = Config::get('constants.month_name');

        $associateStaffInBranch = StafMainMastModel::where('branch_id', request('branch_id'))->pluck('name_eng', 'id');


        return view('localattendance.summary', [
            'weekend_name' => $weekend_name,
            'staff' => $staff,
            'departments' => $departments,
            'localattendances' => $attendance_data,
            'title' => 'Local Attendance Summary',
            'branches' => $branches,
            'public_holiday_count' => $public_holiday_count,
            'organization' => $organization,
            'fiscal_years' => $fiscal_years,
            'current_fiscal_year_id' => $current_fiscal_year_id,
            'months' => $months,
            'staff_types' => $staff_types,
            'associateStaffInBranchArray' => $associateStaffInBranch
        ]);

    }

    public function summary_export(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'from_date_np' => 'required',
            'to_date_np' => 'required',
            'branch_id' => 'required',
        ],
            [
                'from_date_np.required' => 'You must enter Date From!',
                'to_date_np.required' => 'You must enter Date To!',
                'branch_id.required' => 'Please Select Brach!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
        $organization = OrganizationSetup::first();
        $date_from = BSDateHelper::BsToAd('-', $request->from_date_np);
        $date_to = BSDateHelper::BsToAd('-', $request->to_date_np);
        $dStart = new DateTime($date_from);
        $dEnd = new DateTime($date_to);
        $branch = SystemOfficeMastModel::find($request->branch_id);
        $staffs = StafMainMastModel::with(['workschedule', 'grantLeave', 'staffStatus', 'branch', 'fetchAttendances' => function ($query) use ($date_from, $date_to) {
            $query->where('punchin_datetime', '>', $date_from . ' 00:00:00')
                ->where('punchin_datetime', '<', $date_to . ' 23:59:00');
        }])->where('branch_id', $request->branch_id);

        if (!empty($request->department_id)) {
            $staffs = $staffs->where('department', $request->department_id);
        }
        if (empty($request->regular)) {
            $staffs = $staffs->whereIn('staff_type', [0, 1]);
        }
        if ($request->staff_type != null && !empty($request->regular)) {
            $staffs = $staffs->whereIn('staff_type', $request->staff_type);
        }
        if (!empty($request->staff_central_id)) {
            $staffs = $staffs->where('id', $request->staff_central_id);
        }
        $staffs = $staffs->where('staff_status', 1);

        $dismissed_retired_staffs_this_month = EmployeeStatus::whereHas('staff', function ($query) use ($request) {
            $query->where('payroll_branch_id', $request->branch_id);
            if (!empty($request->department_id)) {
                $query->where('department', $request->department_id);
            }
            if ($request->staff_type != null) {
                $query->whereIn('staff_type', $request->staff_type);
            }
            if (!empty($request->staff_central_id)) {
                $query->where('id', $request->staff_central_id);
            }
        })->whereIn(DB::raw('MONTH(date_from)'), [date('m', strtotime($date_from)), date('m', strtotime($date_to))])->pluck('staff_central_id')->toArray();;

        if (count($dismissed_retired_staffs_this_month) > 0) {
            $staffs = $staffs->orWhereIn('id', $dismissed_retired_staffs_this_month);
        }

        if ($organization->organization_code == "NEPALRE") {
            $staffs = $staffs->join('system_post_mast', 'system_post_mast.post_id', '=', 'staff_main_mast.post_id')->orderBy('system_post_mast.order');
        } else {
            $staffs = $staffs->orderBy('main_id', 'asc');
        }
        $staffs = $staffs->get();
        //ordering the staff according to the staff excel file
        if (!empty($branch->order_staff_ids)) {
            //mapping the staff branch ids to array
            $staff_order_ids = array_map('intval', explode(',', $branch->order_staff_ids));
            //adding the branch ids not available on the
            $staff_order_ids = array_merge($staff_order_ids, array_diff($staffs->pluck('main_id')->toArray(), $staff_order_ids));
            //sorting function
            $staffs = $staffs->sortBy(function ($model) use ($staff_order_ids) {
                return array_search($model->main_id, $staff_order_ids);
            });
        }


        $public_holidays = SystemHolidayMastModel::with('branch')->get();
        $system_leave_name = SystemLeaveMastModel::pluck('leave_name', 'leave_id');
        $weekend_name = Config::get('constants.weekend_days');
        $attendance_data = array();

        $dDiff = $dStart->diff($dEnd);
        $total_days = $dDiff->days + 1;
        $i = 1;
        if ($staffs->count() > 0) {

            foreach ($staffs as $staff) {
                $public_holiday_count = 0;
                $suspense_days = 0;
                $present_on_public_holiday = 0;
                $weekend_count = 0;
                $public_holiday_on_weekend = 0;//check if it is public holiday on weekend
                $public_holiday_on_weekend_absent = 0;//check if it is public holiday on weekend
                $present_weekend_count = 0;
                $present_on_leave = 0;
                $present_days = 0;
                $absent_days = 0;
                $paid_leave = 0;
                $grant_leave = 0;
                $total_work_hour = 0;
                $date = BSDateHelper::BsToAd('-', $request->from_date_np);
                $end_date = BSDateHelper::BsToAd('-', $request->to_date_np);
                $today = Carbon::now()->toDateString();
                $public_holiday_work_hour = 0;
                $weekend_holiday_work_hour = 0;

                $public_holiday_work_hour_for_payroll = 0;
                $weekend_holiday_work_hour_for_payroll = 0;
                $absent_on_pubic_holiday_on_weekend = 0;
                $original_date_from = $date;
                $date_to = $end_date;
                while (strtotime($date) <= strtotime($end_date)) {
                    $check_if_grant_leave = $staff->grantLeave->where('from_leave_day', '<=', $date)->where('to_leave_day', '>=', $date)->first();
                    $check_if_public_holiday = $this->getPublicHolidayCollectionBasedOnDateStaff($public_holidays, $date, $staff);

                    $suspenses = $staff->staffStatus;

                    $checkResignDays = $suspenses->whereNotIn('status', [EmployeeStatus::STATUS_WORKING, EmployeeStatus::STATUS_SUSPENSE])->where('date_from', '<=', $date);

                    if ($checkResignDays->count() > 0) {
                        $absent_days++;
                        $suspense_days++;
                        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                        continue;
                    }

                    $checkSuspenseDays = $suspenses->whereIn('status', [EmployeeStatus::STATUS_SUSPENSE])->where('date_from', '<=', $date)->where('date_to', '>=', $date);

                    if ($checkSuspenseDays->count() >= 1) {
                        $suspense_days++;
                        $absent_days++;
                        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                        continue;
                    }

                    if (empty($check_if_grant_leave)) {
                        if ($check_if_public_holiday->count() > 0) {
                            $public_holiday_count++;
                        }

                        $weekend_on_this_date = $staff->workschedule->where('effect_day', '<=', $date)->last();
                        if ($weekend_on_this_date) {
                            if ((date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) && ($check_if_public_holiday->count() > 0)) {
                                $public_holiday_on_weekend++;
                            }
                        } else {
                            $public_holiday_on_weekend += 0;
                        }
                    }
                    $detail = $staff->fetchAttendances->where('punchin_datetime', '>', $date . ' 00:00:00')
                        ->where('punchin_datetime', '<', $date . ' 23:59:00')->first();


                    if (!empty($detail)) {
                        $present_days++;
                        $work_hour_day = $detail->total_work_hour;
                        if ($staff->is_holding == 1) {
                            if ($detail->total_work_hour > 8) {
                                $work_hour_day = 8;
                            }
                        }
                        if ($check_if_public_holiday->count() > 0) {
                            $present_on_public_holiday++;

                            $roundedPublicTotalWorkHour = $detail->total_work_hour;
                            $work_hour_day = $roundedPublicTotalWorkHour;
                            $public_holiday_work_hour += $roundedPublicTotalWorkHour;

                            $roundedPublicTotalWorkHour_for_payroll = DateHelper::getMaximumWorkHourForNonNormalDays($detail->total_work_hour);
                            $public_holiday_work_hour_for_payroll += $roundedPublicTotalWorkHour_for_payroll;
                        }
                        if (!empty($weekend_on_this_date) && date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                            $weekend_count++;
                            $present_weekend_count++;

                            $roundedWeekendTotalWorkHour = $detail->total_work_hour;
                            $weekend_holiday_work_hour += $roundedWeekendTotalWorkHour;

                            $roundedWeekendTotalWorkHour_for_payroll = DateHelper::getMaximumWorkHourForNonNormalDays($detail->total_work_hour);
                            $weekend_holiday_work_hour_for_payroll += $roundedWeekendTotalWorkHour_for_payroll;
                        }
                        if (!empty($check_if_grant_leave)) {
                            $present_on_leave++;
                            $grant_leave++;
                            $absent_days++;
                        }
                        $total_work_hour += $work_hour_day;

                    } else {
                        $absent_days++;

                        /*absent on weekend with public holiday*/

                        $weekend_on_this_date = $staff->workschedule->where('effect_day', '<=', $date)->last();
                        if ($weekend_on_this_date) {
                            if ((date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) && ($check_if_public_holiday->count() > 0)) {
                                $absent_on_pubic_holiday_on_weekend++;
                            }
                        }

                        if (empty($check_if_grant_leave)) {
                            if ($weekend_on_this_date) {

                                if (date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                                    $weekend_count++;
                                    if ($organization->absent_weekend_on_cons_absent == 1) {
                                        $prevDay = date("Y-m-d", strtotime("-1 day", strtotime($date)));
                                        $nextDay = date("Y-m-d", strtotime("+1 day", strtotime($date)));

                                        $check_if_public_holiday_prev_day = $public_holidays->filter(function ($public_holidays) use ($prevDay) {
                                            return $public_holidays->from_date <= $prevDay && $public_holidays->to_date >= $prevDay;
                                        });
                                        $check_if_public_holiday_next_day = $public_holidays->filter(function ($public_holidays) use ($nextDay) {
                                            return $public_holidays->from_date <= $nextDay && $public_holidays->to_date >= $nextDay;
                                        });

                                        if ($check_if_public_holiday_prev_day->count() == 0 && $check_if_public_holiday_next_day->count() == 0
                                            && ($original_date_from != $date) && ($date != $date_to)) {
                                            $prevDayWorkHour = $staff->fetchAttendances->where('punchin_datetime', '>', $prevDay . ' 00:00:00')
                                                    ->where('punchin_datetime', '<', $prevDay . ' 23:59:00')->first()->total_work_hour ?? 0;

                                            $nextDayWorkHour = $staff->fetchAttendances->where('punchin_datetime', '>', $nextDay . ' 00:00:00')
                                                    ->where('punchin_datetime', '<', $nextDay . ' 23:59:00')->first()->total_work_hour ?? 0;

                                            if ($prevDayWorkHour == 0 && $nextDayWorkHour == 0) {
                                                $weekend_count--;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (!empty($check_if_grant_leave)) {
                            $grant_leave++;
                        }
                    }
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                }
                $absent_on_weekend = $weekend_count - $present_weekend_count;
                $absent_on_public_holiday = $public_holiday_count - $present_on_public_holiday;
                $total_working_day = $total_days + $public_holiday_on_weekend - $weekend_count - $public_holiday_count;
//            $actual_absent_days = $absent_days + $public_holiday_on_weekend - $weekend_count - $public_holiday_count + $present_weekend_count + $present_on_public_holiday;
                $paid_leave = ($absent_on_weekend + $absent_on_public_holiday + $grant_leave - $absent_on_pubic_holiday_on_weekend);
                $unpaid_leave = $total_days - $present_days - $paid_leave;
                $actual_absent_days = $paid_leave + $unpaid_leave;
                $absent_days_without_public_holiday = $absent_days + $public_holiday_on_weekend - $absent_on_public_holiday;

                if ($staff->branch->manual_weekend_enable == SystemOfficeMastModel::MANUAL_WEEKEND_ENABLE) {
                    if ($absent_days_without_public_holiday >= $weekend_count) {
                        $absent_on_weekend = $weekend_count;
                        $present_weekend_count = 0;
                    } else {
                        $absent_on_weekend = $absent_days_without_public_holiday;
                        $present_weekend_count = $weekend_count - $absent_days_without_public_holiday;
                    }
                    $weekendsForManualWeekendEnabledBranch = $staff->fetchAttendances->sortByDesc('total_work_hour')->take($present_weekend_count);
                    $weekend_holiday_work_hour = 0;

                    foreach ($weekendsForManualWeekendEnabledBranch as $weekendForManualWeekendEnabledBranch) {
                        $weekend_holiday_work_hour += DateHelper::getMaximumWorkHourForNonNormalDays($weekendForManualWeekendEnabledBranch->total_work_hour);
                    }
                }

                if ($staff->manual_attendance_enable == StafMainMastModel::MANUAL_ATTENDANCE_ENABLED) {
                    $weekend_count = 4;
                    if ($absent_days_without_public_holiday >= 4) {
                        $absent_on_weekend = 4;
                        $present_weekend_count = 0;
                    } else {
                        $absent_on_weekend = $absent_days_without_public_holiday;
                        $present_weekend_count = 4 - $absent_days_without_public_holiday;
                    }
                    $weekend_holiday_work_hour = $present_weekend_count * DateHelper::getMaximumWorkHourForNonNormalDays();
                }

                $attendance_data[$staff->id]['CID'] = $staff->id;
                $attendance_data[$staff->id]['name'] = $staff->name_eng;
                $attendance_data[$staff->id]['total_days'] = $total_days;
                $attendance_data[$staff->id]['total_working_days'] = $total_working_day;
                $attendance_data[$staff->id]['present_days'] = $present_days;
                $attendance_data[$staff->id]['absent_days'] = $actual_absent_days;
                $attendance_data[$staff->id]['weekend_days'] = $weekend_count;
                $attendance_data[$staff->id]['public_holidays'] = $public_holiday_count;
                $attendance_data[$staff->id]['public_holidays_on_weekend'] = $public_holiday_on_weekend;
                $attendance_data[$staff->id]['present_on_public_holidays'] = $present_on_public_holiday;
                $attendance_data[$staff->id]['present_on_weekend'] = $present_weekend_count;
                $attendance_data[$staff->id]['paid_leave'] = $paid_leave;
                $attendance_data[$staff->id]['unpaid_leave'] = $unpaid_leave;
                $attendance_data[$staff->id]['absent_on_weekend'] = $absent_on_weekend;
                $attendance_data[$staff->id]['absent_on_public_holiday'] = $absent_on_public_holiday;
                $attendance_data[$staff->id]['grant_leave'] = $grant_leave;
                $attendance_data[$staff->id]['total_work_hour'] = $total_work_hour;
                $attendance_data[$staff->id]['public_holiday_work_hour'] = $public_holiday_work_hour;
                $attendance_data[$staff->id]['weekend_holiday_work_hour'] = $weekend_holiday_work_hour;
                $attendance_data[$staff->id]['public_holiday_work_hour_for_payroll'] = $public_holiday_work_hour_for_payroll;
                $attendance_data[$staff->id]['weekend_holiday_work_hour_for_payroll'] = $weekend_holiday_work_hour_for_payroll;
                $attendance_data[$staff->id]['main_id'] = $staff->main_id;
                $attendance_data[$staff->id]['staff_central_id'] = $staff->staff_central_id;
                $attendance_data[$staff->id]['suspense_days'] = $suspense_days;
                $attendance_data[$staff->id]['absent_on_pubic_holiday_on_weekend'] = $absent_on_pubic_holiday_on_weekend;
                $i++;
            }

        } else {
            $staff = [];
            $public_holiday_count = 0;
        }
        if ($request->regular == 1) {
            \Excel::create('Local Attendance Summary Export', function ($excel) use ($attendance_data) {
                $excel->sheet('Attendance Sheet', function ($sheet) use ($attendance_data) {
                    $sheet->loadView('localattendance.summary-regular-export', [
                        'localattendances' => $attendance_data,
                    ]);
                });
            })->download('xlsx');
        }

        \Excel::create('Local Attendance Summary Export', function ($excel) use ($attendance_data) {
            $excel->sheet('Attendance Sheet', function ($sheet) use ($attendance_data) {
                $sheet->loadView('localattendance.summary-export', [
                    'localattendances' => $attendance_data,
                ]);
            });
        })->download('xlsx');

    }

    public function daywiseindex()
    {
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();

        $departments = $this->departmentRepository->getAllDepartments();

        $todayDate = Carbon::now()->toDateString();

        return view('localattendance.daywise-index', [
            'branches' => $branches,
            'title' => 'Daywise Attendance',
            'i' => 1,
            'todayDate' => $todayDate,
            'departments' => $departments
        ]);
    }

    public function daywise_show(Request $request)
    {

        $organization = OrganizationSetup::first();
        if (!isset($request->date)) {
            return redirect()->route('localattendance-daywise')->with('flash', array('status' => false, 'mesg' => 'Please select a date'));
        }

        $date_np = $request->date;

        $branchName = $departmentName = $shiftName = '';

        $date = BSDateHelper::BsToAd('-', $date_np);
        $public_holidays = SystemHolidayMastModel::with('branch')->get();
        $checkIfPublicHoliday = $this->getPublicHolidayCollectionBasedOnDateStaff($public_holidays, $date, null, $request->branch_id);
        $local_attendances = FetchAttendance::with(['staff' => function ($query) {
            $query->with('shift', 'workschedule', 'grantLeave', 'jobposition');
        }, 'branch', 'shift'])
            ->whereDate('punchin_datetime', $date);

        $absentStaffs = StafMainMastModel::whereDoesntHave('fetchAttendances', function ($query) use ($date) {
            $query->whereDate('punchin_datetime', $date);
        });


        if (isset($request->department_id)) {
            $departmentName = Department::find($request->department_id)->department_name;

            $absentStaffs->where('department', $request->department_id);

            $local_attendances->whereHas('staff', function ($query) use ($request) {
                $query->where('department', $request->department_id);
            });
        }

        if (isset($request->branch_id)) {
            $branchName = SystemOfficeMastModel::find($request->branch_id)->office_name;
            $local_attendances = $local_attendances->where('branch_id', $request->branch_id);

            $absentStaffs->where('branch_id', $request->branch_id);
        }
        if (isset($request->shift_id)) {
            $shiftName = Shift::find($request->shift_id)->shift_name;
            $local_attendances = $local_attendances->where('shift_id', $request->shift_id);

            $absentStaffs->where('shift_id', $request->shift_id);
        }

        if ($request->status == 0 || $request->status == null) {
            $absentStaffs = $absentStaffs->with(['workschedule', 'grantLeave', 'branch', 'shift', 'jobposition'])->get();
            if ($organization->organization_code == "NEPALRE") {
                $absentStaffs = $absentStaffs->sortBy('jobposition.order');
            }
        } else {
            $absentStaffs = collect([]);
        }


        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $departments = $this->departmentRepository->getAllDepartments();

        $shifts = $this->shiftRepository->getAllShifts()->pluck('shift_name', 'id');

        $absentStaffsCount = $absentStaffs->count();

        if ($request->status == 1 || $request->status == null) {
            $presentStaffsCount = $local_attendances->distinct('staff_central_id')->count();
            $local_attendances = $local_attendances->get();
            if ($organization->organization_code == "NEPALRE") {
                $local_attendances = $local_attendances->sortBy('staff.jobposition.order');
            }
        } else {
            $presentStaffsCount = 0;
            $local_attendances = collect([]);
        }

        foreach ($local_attendances as $local_attendance) {
            if ($checkIfPublicHoliday->count() > 0) {
                $local_attendance['add_classes'] = 'present-on-public-holiday';
            }
            if ($local_attendance->status == FetchAttendance::forceLeave) {
                $local_attendance['add_classes'] = 'is-force';
            }

            if (!empty($local_attendance->staff)) {
                $weekend_on_this_date = $local_attendance->staff->workschedule->where('effect_day', '<=', $date)->last();

                if (!empty($weekend_on_this_date) && date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                    $local_attendance['add_classes'] = 'present-on-weekend';
                }

                $check_if_grant_leave = $local_attendance->staff->grantLeave->where('from_leave_day', '<=', $date)->where('to_leave_day', '>=', $date)->first();
                if (!empty($check_if_grant_leave)) {
                    $local_attendance['add_classes'] = "present-on-leave";
                }
            }

        }

        $system_leave_name = SystemLeaveMastModel::pluck('leave_name', 'leave_id');

        foreach ($absentStaffs as $absentStaff) {
            $absentStaff['status'] = 'Absent';

            if ($checkIfPublicHoliday->count() > 0) {
                $absentStaff['status'] = 'Public Holiday';
            }
            $weekend_on_this_date = $absentStaff->workschedule->where('effect_day', '<=', $date)->last();

            if (!empty($weekend_on_this_date) && date('N', strtotime($date)) == $weekend_on_this_date->weekend_day) {
                $absentStaff['status'] = 'Weekend Day';
            }

            $check_if_grant_leave = $absentStaff->grantLeave->where('from_leave_day', '<=', $date)->where('to_leave_day', '>=', $date)->first();
            if (!empty($check_if_grant_leave)) {
                $absentStaff['status'] = "Approved Leave (" . $system_leave_name[$check_if_grant_leave->leave_id] . ')';
            }
        }

        $totalStaffsCount = $absentStaffsCount + $presentStaffsCount;

        $data = [
            'branches' => $branches,
            'departments' => $departments,
            'localattendances' => $local_attendances,
            'title' => 'Local Attendance Summary',
            'organization' => $organization,
            'shifts' => $shifts,
            'totalStaffsCount' => $totalStaffsCount,
            'presentStaffsCount' => $presentStaffsCount,
            'absentStaffsCount' => $absentStaffsCount,
            'shiftName' => $shiftName,
            'branchName' => $branchName,
            'departmentName' => $departmentName,
            'absentStaffs' => $absentStaffs
        ];
        if ($request->export == 1) {
            \Excel::create($date_np . ' Day Wise Attendance', function ($excel) use ($data) {
                $excel->sheet('Day Wise Attendance', function ($sheet) use ($data) {
                    $sheet->loadView('localattendance.daywise-table', $data);
                });
            })->download('xlsx');
        }

        return view('localattendance.daywise-show', $data);

    }

    /** Get Month Date From to By Month id and date id
     * @param Request $request
     */
    public function getMonthDateFromTo(Request $request)
    {
        $selected_month = $request->selected_month;
        $selected_year_id = $request->selected_year;
        $date_en = '';
        $selected_year = '';
        $date_np = '';
        $fiscal_year = FiscalYearModel::find($selected_year_id);
        $s_fy = explode('-', $fiscal_year->fiscal_start_date_np);
        if ($selected_month >= 4 && $selected_month <= 12) {
            $selected_year = $s_fy[0];
        } else { //baisakh to asar
            $selected_year = (int)$s_fy[0] + 1;
        }
        if (!empty($selected_year)) {
            $start_date_np = $selected_year . '-' . $selected_month . '-1';
            $start_date_en = BSDateHelper::BsToAd('-', $selected_year . '-' . $selected_month . '-1');


            $end_date_np = $selected_year . '-' . $selected_month . '-' . BSDateHelper::getLastDayByYearMonth($selected_year, $selected_month);
            $end_date_en = BSDateHelper::BsToAd('-', $end_date_np);

        } else {
            return response()->json(['status' => false]);
        }
        return response()->json(['status' => true, 'start_date_np' => $start_date_np, 'start_date_en' => $start_date_en,
            'end_date_np' => $end_date_np, 'end_date_en' => $end_date_en]);
    }

    public function punchOutWarning(Request $request)
    {
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
        $months = Config::get('constants.month_name');
        $staff_types = StaffType::pluck('staff_type_title', 'staff_type_code');

        $staff_data = null;
        $date_from_np = $request->from_date_np;
        $date_from = BSDateHelper::BsToAd('-', $date_from_np);
        $date_to_np = $request->to_date_np;
        $date_to = BSDateHelper::BsToAd('-', $date_to_np);
        $branch_id = $request->branch_id;
        $staff_types_selected = $request->staff_types;
        $should_not_display_today_date = $request->should_not_display_today_date;
        $staff_id = $this->staffMainMastRepository->getViewableStaffCentralIdFromRequestedStaffCentralId($request->staff_central_id);
        $input = $request->all();

        $punchout_warning_staffs = null;
        if (count($input) > 0) {

            $punchout_warning_staffs = StafMainMastModel::withAndWhereHas('fetchAttendances', function ($query) use ($date_from, $date_to, $should_not_display_today_date, $branch_id, $staff_id) {
                if (!empty($date_from)) {
                    $query->whereDate('punchin_datetime', '>=', $date_from);
                }
                if (!empty($date_to)) {
                    $query->whereDate('punchin_datetime', '<=', $date_to);
                }
                if (!empty($should_not_display_today_date) && $should_not_display_today_date == 1) {
                    $todayDate = Carbon::now();
                    $query->whereDate('punchin_datetime', '<>', $todayDate->toDateString());
                }
                if (!empty($branch_id)) {
                    $query->where('branch_id', $branch_id);
                }
                if (!empty($staff_id)) {
                    $query->where('staff_central_id', $staff_id);
                }
                $query->where('punchout_datetime', '=', null);

            })->with('getDepartment', 'shift');

            if (!empty($staff_types_selected) && count($staff_types_selected) > 0) {
                $punchout_warning_staffs = $punchout_warning_staffs->whereIn('staff_type', $staff_types_selected);
            }
            $punchout_warning_staffs = $punchout_warning_staffs->get()->sortByDesc(function ($query) {
                return $query->fetchAttendances->count();
            });


            if (!empty($branch_id)) {
                $staff_data = $this->staffMainMastRepository->getListByBranch($branch_id);
            }

        }

        return view('localattendance.punchout-warning', [
            'branches' => $branches,
            'title' => 'Punch Out Warning',
            'i' => 1,
            'months' => $months,
            'current_fiscal_year_id' => $current_fiscal_year_id,
            'fiscal_years' => $fiscal_years,
            'staff_data' => $staff_data,
            'staff_types' => $staff_types,
            'punchout_warning_staffs' => $punchout_warning_staffs
        ]);
    }

    public function overtimeworkIndex(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $months = Config::get('constants.month_name');

        return view('localattendance.overtime.index', [
            'branches' => $branches,
            'fiscal_years' => $fiscal_years,
            'title' => 'Overtime Work Hour',
            'i' => 1,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'months' => $months,
            'current_fiscal_year_id' => $current_fiscal_year_id,
        ]);
    }

    public function overtimeworkShow(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'staff_central_id' => 'required',
            'branch_id' => 'required',
        ],
            [
                'staff_central_id.required' => 'You must Select Staff Name!',
                'from_date_np.required' => 'You must enter Date From!',
                'to_date_np.required' => 'You must enter Date To!',
                'branch_id.required' => 'You must Select Branch!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            $organization = OrganizationSetup::first();
            $local_attendances = FetchAttendance::query();
            $staff = StafMainMastModel::with('workschedule', 'grantLeave', 'branch', 'payrollBranch')->where('id', $request->staff_central_id)->first();

            $next_staff = StafMainMastModel::query();
            $previous_staff = StafMainMastModel::query();
            if (!empty($request->branch_id)) {
                $next_staff->where('branch_id', $request->branch_id);
                $previous_staff->where('branch_id', $request->branch_id);
            }
            $next_staff->orderBy('main_id', 'ASC')->where('main_id', '>', $staff->main_id);
            $previous_staff->orderBy('main_id', 'DESC')->where('main_id', '<', $staff->main_id);
            $local_attendances->where('staff_central_id', $request->staff_central_id);

            if (!empty($request->from_date) && !empty($request->to_date)) {
                $local_attendances->whereDate('punchin_datetime', '>=', $request->from_date)->whereDate('punchin_datetime', '<=', $request->to_date);
            }
            $next_staff = $next_staff->first();
            $previous_staff = $previous_staff->latest()->first();
            $previous_staff_id = !empty($previous_staff) ? $previous_staff->id : null;
            $next_staff_id = !empty($next_staff) ? $next_staff->id : null;
            $local_attendances = $local_attendances->with('staff')->get();
            $suspenses = EmployeeStatus::where('staff_central_id', $staff->id)
                ->get();

            $date = $request->from_date ?? BSDateHelper::BsToAd('-', $request->from_date_np);
            $end_date = $request->to_date;

            $total_overtime_hours = 0;
            $overtime_data = $this->calcuateOvertimeData($date, $end_date, $staff, $suspenses, $local_attendances, $total_overtime_hours, $organization);

            $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
            $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');
            $months = Config::get('constants.month_name');
            $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
            $associateStaffInBranch = StafMainMastModel::where('branch_id', request('branch_id'))->pluck('name_eng', 'id');

            return view('localattendance.overtime.show', [
                'title' => 'Overtime Hour Show',
                'i' => 1,
                'organization' => $organization,
                'previous_staff_id' => $previous_staff_id,
                'next_staff_id' => $next_staff_id,
                'fiscal_years' => $fiscal_years,
                'current_fiscal_year_id' => $current_fiscal_year_id,
                'months' => $months,
                'staff' => $staff,
                'branches' => $branches,
                'associateStaffInBranchArray' => $associateStaffInBranch,
                'overtime_datas' => $overtime_data,
                'total_overtime_work_hour' => $total_overtime_hours,
            ]);
        }
    }

    public function overtimeworkExcelExport(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'staff_central_id' => 'required',
            'branch_id' => 'required',
        ],
            [
                'staff_central_id.required' => 'You must Select Staff Name!',
                'from_date_np.required' => 'You must enter Date From!',
                'to_date_np.required' => 'You must enter Date To!',
                'branch_id.required' => 'You must Select Branch!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            $local_attendances = FetchAttendance::query();
            $staff = StafMainMastModel::with('workschedule', 'grantLeave', 'branch', 'payrollBranch')->where('id', $request->staff_central_id)->first();

            $local_attendances->where('staff_central_id', $request->staff_central_id);

            if (!empty($request->from_date) && !empty($request->to_date)) {
                $local_attendances->whereDate('punchin_datetime', '>=', $request->from_date)->whereDate('punchin_datetime', '<=', $request->to_date);
            }
            $local_attendances = $local_attendances->with('staff')->get();
            $suspenses = EmployeeStatus::where('staff_central_id', $staff->id)
                ->get();

            $date = $request->from_date ?? BSDateHelper::BsToAd('-', $request->from_date_np);
            $end_date = $request->to_date;

            $total_overtime_hours = 0;
            $organization = OrganizationSetup::first();
            $overtime_data = $this->calcuateOvertimeData($date, $end_date, $staff, $suspenses, $local_attendances, $total_overtime_hours, $organization);
            $weekend_name = Config::get('constants.weekend_days');
            $data = [
                'organization' => $organization,
                'staff' => $staff,
                'weekend_name' => $weekend_name,
                'overtime_datas' => $overtime_data,
                'total_overtime_work_hour' => $total_overtime_hours
            ];
            \Excel::create('Overtime Work Hour Export', function ($excel) use ($data) {

                $excel->sheet('New sheet', function ($sheet) use ($data) {

                    $sheet->loadView('localattendance.overtime.overtime-table', $data);
                });

            })->download('xlsx');

            return view('localattendance.overtime.overtime-table', [
                'organization' => $organization,
                'staff' => $staff,
                'weekend_name' => $weekend_name,
                'overtime_datas' => $overtime_data,
                'total_overtime_work_hour' => $total_overtime_hours
            ]);
        }
    }


    public function destroy(Request $request)
    {
        if (!$request->has($request->id) && empty($request->id)) {
            return response()->json([
                'status' => 'false',
                'data' => 'No attendance selected'
            ]);
        }

        $fetchAttendanceId = $request->id;

        $fetchAttendance = FetchAttendance::where('id', $fetchAttendanceId)->first();

        if (empty($fetchAttendance)) {
            return response()->json([
                'status' => 'false',
                'data' => 'No attendance found'
            ]);
        }

        DB::beginTransaction();

        try {
            $fetchAttendance->sync = FetchAttendance::sync;
            $fetchAttendance->status = FetchAttendance::forceDelete;
            $fetchAttendance->deleted_by = auth()->user()->id;
            $fetchAttendance->save();
            $deleteStatus = $fetchAttendance->delete();
            DB::commit();
        } catch (\Exception $e) {
            $deleteStatus = false;
            DB::rollBack();
        }
        if (!$deleteStatus) {
            return response()->json([
                'status' => 'false',
                'data' => 'Unable to delete the attendance'
            ]);
        }

        return response()->json([
            'status' => 'true',
            'data' => 'Deleted Successfully'
        ]);
    }

    /**
     * @param $public_holidays
     * @param $date
     * @param $staff
     * @return mixed
     */
    protected function getPublicHolidayCollectionBasedOnDateStaff($public_holidays, $date, $staff = null, $branch_id = null)
    {
        $check_if_public_holiday = $public_holidays->filter(function ($public_holidays) use ($date, $staff, $branch_id) {
            $checkDateStatus = true;
            if (!($public_holidays->from_date <= $date && $public_holidays->to_date >= $date)) {
                $checkDateStatus = false;
            }
            $checkGenderStatus = true;
            if (!empty($staff)) {
                if (empty($public_holidays->gender_id)) {
                    $checkGenderStatus = true;
                } elseif ($public_holidays->gender_id == $staff->Gender) {
                    $checkGenderStatus = true;
                } else {
                    $checkGenderStatus = false;
                }
            }
            $checkBranchStatus = true;
            if (!empty($staff) || !empty($branch_id)) {
                $branch_id_to_check = !empty($staff) ? $staff->branch_id : null;
                if (empty($branch_id_to_check)) {
                    $branch_id_to_check = $branch_id;
                }
                $checkBranchStatus = false;
                if ($public_holidays->branch->where('office_id', $branch_id_to_check)->count() > 0) {
                    $checkBranchStatus = true;
                }
            }


            return ($checkDateStatus && $checkGenderStatus && $checkBranchStatus);
        });

        return $check_if_public_holiday;
    }

    public function allStaffMontlyAttendanceIndex()
    {
        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');

        $months = Config::get('constants.month_name');

        return view('localattendance.allstaff.index', [
            'fiscal_years' => $fiscal_years,
            'title' => 'Staff Attendance',
            'i' => 1,
            'months' => $months,
            'current_fiscal_year_id' => $current_fiscal_year_id,
        ]);
    }

    public function allStaffMontlyAttendanceShow(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'from_date_np' => 'required',
            'to_date_np' => 'required',
        ],
            [
                'from_date_np.required' => 'You must enter Date From!',
                'to_date_np.required' => 'You must enter Date To!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {

            $organization = OrganizationSetup::first();
            $local_attendances = FetchAttendance::query();
            $date = $request->from_date ?? BSDateHelper::BsToAd('-', $request->from_date_np);
            $end_date = $request->to_date;
            $staffs = StafMainMastModel::with(['workschedule', 'branch', 'payrollBranch', 'jobposition', 'grantLeave' => function ($query) use ($date, $end_date) {
                $query->where([['from_leave_day', '>=', $date], ['to_leave_day', '<=', $end_date]])
                    ->orWhere([['from_leave_day', '<=', $date], ['to_leave_day', '>=', $date]])
                    ->orWhere([['from_leave_day', '<=', $end_date], ['to_leave_day', '>=', $end_date]])
                    ->orWhere([['from_leave_day', '<=', $date], ['to_leave_day', '>=', $end_date]]);
                $query->with('leave');
            }])->get();

            if ($organization->organization_code == "NEPALRE") {
                $staffs = $staffs->sortBy('jobposition.order');
            }
            $local_attendances->whereDate('punchin_datetime', '>=', $request->from_date)->whereDate('punchin_datetime', '<=', $request->to_date);
            $all_local_attendances = $local_attendances->with('staff')->get();


            $allsuspenses = EmployeeStatus::where([['date_from', '>=', $date], ['date_to', '<=', $end_date]])
                ->orWhere([['date_from', '<=', $date], ['date_to', '>=', $date]])
                ->orWhere([['date_from', '<=', $end_date], ['date_to', '>=', $end_date]])
                ->orWhere([['date_from', '<=', $date], ['date_to', '>=', $end_date]])
                ->orWhere([['date_from', '<=', $end_date], ['date_to', '=', null]])->get();
            $all_attendance_data = [];
            foreach ($staffs as $staff) {
                $local_attendances = $all_local_attendances->where('staff_central_id', $staff->id);
                $suspenses = $allsuspenses->where('staff_central_id', $staff->id);
                $total_overtime_hours = 0;
                $attendance_data['attendance'] = $this->calcuateOvertimeData($date, $end_date, $staff, $suspenses, $local_attendances, $total_overtime_hours, $organization);
                $attendance_data['name'] = $staff->name_eng;
                $all_attendance_data[$staff->id] = $attendance_data;
            }
            if ($request->excel == 1) {
                $data = [
                    'organization' => $organization,
                    'all_attendance_data' => $all_attendance_data,
                ];
                \Excel::create('All Staff Attendance', function ($excel) use ($data) {

                    $excel->sheet('Date Range Attendance', function ($sheet) use ($data) {

                        $sheet->loadView('localattendance.allstaff.table', $data);
                    });

                })->download('xlsx');
            }

            $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
            $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');

            $months = Config::get('constants.month_name');

            return view('localattendance.allstaff.show', [
                'fiscal_years' => $fiscal_years,
                'title' => 'Staff Attendance',
                'months' => $months,
                'current_fiscal_year_id' => $current_fiscal_year_id,
                'all_attendance_data' => $all_attendance_data,
                'organization' => $organization
            ]);
        }
    }
}
