<?php

namespace App\Http\Controllers;

use App\CalenderHolidaySplitMonth;
use App\EarnableBalanceMonthLog;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\OrganizationSetup;
use App\Repositories\CalculateFetchAttendanceRepository;
use App\Repositories\CalculateLeaveRepository;
use App\LeaveBalance;
use App\StafMainMastModel;
use App\SystemHolidayMastModel;
use App\SystemLeaveMastModel;
use App\SystemOfficeMastModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalculateLeaveBalanceController extends Controller
{
    /**
     * @var CalculateFetchAttendanceRepository
     */
    private $calculateFetchAttendanceRepository;
    /**
     * @var CalculateLeaveRepository
     */
    private $calculateLeaveRepository;

    public function __construct(
        CalculateFetchAttendanceRepository $calculateFetchAttendanceRepository,
        CalculateLeaveRepository $calculateLeaveRepository
    )
    {
        $this->calculateFetchAttendanceRepository = $calculateFetchAttendanceRepository;
        $this->calculateLeaveRepository = $calculateLeaveRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $month_id = $request->month_id;

        $fiscal_year_id = $request->fiscal_year_id;
        $branch_id = $request->branch_id;
        $fiscalYear = FiscalYearModel::where('id', $request->fiscal_year_id)->first();
        $branchId = SystemOfficeMastModel::where('office_id', $request->branch_id)->first();

        if (empty($fiscalYear)) {
            return redirect()->back()->withInput()->withErrors([
                'Fiscal Year Not Found'
            ]);
        }

        if (empty($month_id)) {
            return redirect()->back()->withInput()->withErrors([
                'Month Not Found'
            ]);
        }

        $branch = SystemOfficeMastModel::select('office_id', 'office_name')->where('office_id', $request->branch_id)->first();

        if (empty($branch)) {
            return redirect()->back()->withInput()->withErrors([
                'Branch Not Found'
            ]);
        }

        $recentEarnableLog = EarnableBalanceMonthLog::select('id')
            ->where('fiscal_year_id', $fiscal_year_id)
            ->where('month_id', $month_id)
            ->where('branch_id', $branch_id)
            ->first();
        if (!empty($recentEarnableLog)) {
            $previousLeaveBalances = LeaveBalance::select('id', 'staff_central_id', 'leave_id', 'fy_id', 'balance', 'earned', 'consumption')->with('staff:id,name_eng', 'leave:leave_id,leave_name', 'fiscal:id,fiscal_code')
                ->where('log_id', $recentEarnableLog->id)
                ->orderBy('leave_id', 'ASC')
                ->get();

            $earnableSystemLeaves = SystemLeaveMastModel::select('leave_id', 'leave_name', 'leave_earnable_period')->leaveEarnabilityEnabled();
            if ($month_id != 1) {
                $earnableSystemLeaves = $earnableSystemLeaves->leaveEarnableMonthlyType();
            }
            $earnableSystemLeaves = $earnableSystemLeaves->whereIn('leave_id', $previousLeaveBalances->unique('leave_id')->pluck('leave_id'))
                ->orderBy('leave_id', 'ASC')
                ->get();
            $staffs = StafMainMastModel::select('id', 'name_eng','staff_main_mast.post_id')->join('system_post_mast', 'system_post_mast.post_id', '=', 'staff_main_mast.post_id')->orderBy('system_post_mast.order')->whereIn('id', $previousLeaveBalances->unique('staff_central_id')->pluck('staff_central_id'))->get();

            return view('calculateleavebalance.show', [
                'title' => 'Show Earnable',
                'previousLeaveBalances' => $previousLeaveBalances,
                'fiscalYear' => $fiscalYear,
                'branch' => $branch,
                'month_id' => $month_id,
                'earnableSystemLeaves' => $earnableSystemLeaves,
                'staffs' => $staffs
            ]);
        }

        $previousFiscalYearId = $fiscal_year_id;
        $previousMonth = $month_id - 1;

        if ($month_id == 1) {
            $previousMonth = 12;
        } elseif ($month_id == 4) {
            $previousTestingDateNp = BSDateHelper::getStartDateFromFiscalYearAndMonth($fiscal_year_id, $month_id);
            $previousTestingDate = Carbon::parse(BSDateHelper::BsToAd('-', $previousTestingDateNp))->subYear()->addDay(5);
            $previousFiscalYearId = FiscalYearModel::where('fiscal_start_date', '<=', $previousTestingDate)
                ->where('fiscal_end_date', '>', $previousTestingDate)->first()->id;
        }

        $logBasedOnBranch = EarnableBalanceMonthLog::where('branch_id', $request->branch_id)->first();

        if (!empty($logBasedOnBranch)) {
            $previousLog = EarnableBalanceMonthLog::where('fiscal_year_id', $previousFiscalYearId)->where('month_id', $previousMonth)->where('branch_id', $request->branch_id)->first();
            if (empty($previousLog)) {
                return redirect()->back()->withInput()->withErrors([
                    'Skipped a Month: Please create ' . config('constants.month_name')[$logBasedOnBranch->month_id + 1]
                ]);
            }

            /*if (!empty($previousLog)) {
                $latestLeaveBalance = LeaveBalance::where('log_id', $previousLog->id)
                    ->first();
//            $existing_fiscal_year = LeaveBalance::where('fiscal_year_id', $request->fiscal_year_id)->first();
//            if(!empty($existing_fiscal_year));
                if (!empty($latestLeaveBalance)) {

                    $latest_month = explode('-', $latestLeaveBalance->date_np)[1];
//                dd($latest_month);
                    if ($latest_month == 12) {
                        $latest_month = 0;
                    }
                    if ($latest_month > $request->month_id && $latest_month + 1 != $request->month_id) { //chaitra paxi ko lagi mildaina kina vane hamro ma 13 check garxa tara 1 lai check garnu parxa
                        return redirect()->back()->withInput()->withErrors([
                            'Skipped a Month'
                        ]);
                    }
                }
            }*/
        }

        $earnableSystemLeaves = SystemLeaveMastModel::leaveEarnabilityEnabled();
        if ($month_id != 1) {
            $earnableSystemLeaves = $earnableSystemLeaves->leaveEarnableMonthlyType();
        }
        $earnableSystemLeaves = $earnableSystemLeaves->get();

        $startDateNp = BSDateHelper::getStartDateFromFiscalYearAndMonth($fiscal_year_id, $month_id);
        $endDateNp = BSDateHelper::getEndDateFromFiscalYearAndMonth($fiscal_year_id, $month_id);

        $startDate = BSDateHelper::BsToAd('-', $startDateNp);
        $endDate = BSDateHelper::BsToAd('-', $endDateNp);

        $staffs = StafMainMastModel::with(['leaveBalance' => function ($query) use ($earnableSystemLeaves, $startDate) {
            $query->whereIn('leave_id', $earnableSystemLeaves->pluck('leave_id'))
                ->when(!empty($startDate), function ($innerQuery) use ($startDate) {
                    $innerQuery->where('date', '<', $startDate)
                        ->latest();
                });
        }, 'workschedule', 'grantLeave', 'staffStatus', 'branch', 'fetchAttendances'])->join('system_post_mast', 'system_post_mast.post_id', '=', 'staff_main_mast.post_id')->orderBy('system_post_mast.order')->get();

        $publicHolidays = SystemHolidayMastModel::with('branch')->get();

        $earnedStaffBalances = [];

        $organization = OrganizationSetup::first();

        foreach ($staffs as $staff) {
            foreach ($earnableSystemLeaves as $earnableSystemLeave) {
                $repositoryDataCollect = collect();
                $calculatedFetchAttendanceStaff = $this->calculateFetchAttendanceRepository->getCalculatedAttendanceInformation($staff, $publicHolidays, $startDate, $endDate, $organization);
                $repositoryDataCollect->present_days = $calculatedFetchAttendanceStaff['total_working_days_in_all_days'] ?? 0;
                $repositoryDataCollect->presentable_days = $calculatedFetchAttendanceStaff['total_working_days'] ?? 0;
                $repositoryDataCollect->staff_jobtype_id = $staff->jobtype_id;
                $earnedStaffBalances[$staff->id][$earnableSystemLeave->leave_id] = $this->calculateLeaveRepository->getLeaveEarnable($earnableSystemLeave, $repositoryDataCollect);
            }

        }

        return view('calculateleavebalance.index', [
            'title' => "Calculate Leave Balance",
            'fiscal_year_id' => $fiscal_year_id,
            'month_id' => $month_id,
            'fiscalYear' => $fiscalYear,
            'staffs' => $staffs,
            'earnableSystemLeaves' => $earnableSystemLeaves,
            'earnableStaffBalances' => $earnedStaffBalances,
            'branch_id' => $branch_id,
            'branchId' => $branchId
        ]);
    }

    public function filterView()
    {
        $fiscal_year = FiscalYearModel::pluck('fiscal_code', 'id');
        $months = config('constants.month_name');
        $activeFiscalYear = FiscalYearModel::isActiveFiscalYear()->first()->id ?? null;
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');

        return view('calculateleavebalance.filter-view', [
            'title' => "Filter",
            'fiscal_year' => $fiscal_year,
            'months' => $months,
            'activeFiscalYear' => $activeFiscalYear,
            'branches' => $branches
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fiscalYearId = $request->fiscal_year_id;
        $month_id = $request->month_id;
        $branch_id = $request->branch_id;
        $nowTime = Carbon::now();
        $leaveBalanceBulkInsertArray = [];

        $dateLeaveBalanceNp = BSDateHelper::getStartDateFromFiscalYearAndMonth($fiscalYearId, $month_id);
        $dateLeaveBalance = BSDateHelper::BsToAd('-', $dateLeaveBalanceNp);

        if (empty($request->calculate_balance) || count($request->calculate_balance) < 1) {
            return redirect()->back()->withInput()->withErrors(['No data']);
        }

        try {
            DB::beginTransaction();
            $earnableBalanceMonthLog = new EarnableBalanceMonthLog();
            $earnableBalanceMonthLog->fiscal_year_id = $fiscalYearId;
            $earnableBalanceMonthLog->month_id = $month_id;
            //TODO:: Need to work for branch:
            $earnableBalanceMonthLog->branch_id = $branch_id;
            $earnableBalanceMonthLog->leave_earnable_period_type = SystemLeaveMastModel::LEAVE_EARNABLE_PERIOD_FOR_MONTHLY;
            $earnableBalanceMonthLog->created_by = auth()->id();
            $earnableBalanceMonthLog->save();

            foreach ($request->calculate_balance as $staffId => $leaveBalanceArray) {
                foreach ($leaveBalanceArray as $leaveId => $leaveData) {
                    $tempLeaveBalanceBulkInsertArray['staff_central_id'] = $staffId;
                    $tempLeaveBalanceBulkInsertArray['leave_id'] = $leaveId;
                    $tempLeaveBalanceBulkInsertArray['fy_id'] = $fiscalYearId;
                    if ($month_id != 1) {
                        $tempLeaveBalanceBulkInsertArray['description'] = 'Leave Earnable Every Month';
                    } else {
                        $tempLeaveBalanceBulkInsertArray['description'] = 'Leave Earnable and Collapse Every Fiscal Year';
                    }
                    $tempLeaveBalanceBulkInsertArray['consumption'] = $leaveData['collapse'];
                    $tempLeaveBalanceBulkInsertArray['earned'] = $leaveData['earned'];
                    $tempLeaveBalanceBulkInsertArray['balance'] = $leaveData['balance'];
                    $tempLeaveBalanceBulkInsertArray['authorized_by'] = auth()->id();
                    $tempLeaveBalanceBulkInsertArray['date_np'] = $dateLeaveBalanceNp;
                    $tempLeaveBalanceBulkInsertArray['date'] = $dateLeaveBalance;
                    $tempLeaveBalanceBulkInsertArray['created_at'] = $nowTime;
                    $tempLeaveBalanceBulkInsertArray['created_at'] = $nowTime;
                    $tempLeaveBalanceBulkInsertArray['updated_at'] = $nowTime;
                    $tempLeaveBalanceBulkInsertArray['log_id'] = $earnableBalanceMonthLog->id;
                    $leaveBalanceBulkInsertArray[] = $tempLeaveBalanceBulkInsertArray;
                }
            }

            $leaveBalanceBulkInsertCollect = collect($leaveBalanceBulkInsertArray);
            $chunks = $leaveBalanceBulkInsertCollect->chunk(500);

            foreach ($chunks as $chunk) {
                LeaveBalance::insert($chunk->toArray());
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();


            if (app()->env == 'local') {
                dd($exception);
            } else {
                DB::beginTransaction();
                DB::commit();

                return redirect()->back()->withInput()->withErrors(['Error Occurred']);
            }
        }
        return redirect()->route('calculate-leave-balance-filter')->with('flash', array('status' => 'success', 'mesg' => 'Leave Balance Updated successfully!'));
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
