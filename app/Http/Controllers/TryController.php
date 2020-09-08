<?php

namespace App\Http\Controllers;

use App\BankMastModel;
use App\CalenderHolidayModel;
use App\EmployeeStatus;
use App\FetchAttendance;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\LeaveRequest;
use App\PayrollDetailModel;
use App\Repositories\CalculateFetchAttendanceRepository;
use App\Repositories\CalculateLeaveRepository;
use App\Shift;
use App\StaffJobPosition;
use App\StaffPaymentMast;
use App\StaffSalaryModel;
use App\StaffShiftHistory;
use App\StaffTransferModel;
use App\StaffWorkScheduleMastModel;
use App\StafMainMastModel;
use App\SystemHolidayMastModel;
use App\SystemLeaveMastModel;
use App\SystemTdsMastModel;
use App\Traits\AppUtils;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TryController extends Controller
{
    use AppUtils;

    /**
     * @var CalculateLeaveRepository
     */
    private $calculateLeaveRepository;
    /**
     * @var CalculateFetchAttendanceRepository
     */
    private $calculateFetchAttendanceRepository;

    public function __construct(
        CalculateLeaveRepository $calculateLeaveRepository,
        CalculateFetchAttendanceRepository $calculateFetchAttendanceRepository)
    {
        $this->calculateLeaveRepository = $calculateLeaveRepository;
        $this->calculateFetchAttendanceRepository = $calculateFetchAttendanceRepository;
    }

    public function removeTransferMistakeRecords()
    {
        $staffs = StafMainMastModel::withCount('staffTransfer')->whereIn('id', [9, 10, 11, 18, 105, 106, 107, 109, 124, 190, 198, 199, 200, 201, 202, 213, 224, 239, 244, 253, 258, 330, 334, 344, 345, 346, 347, 348, 349, 350, 351, 352, 353, 354, 358, 383, 419, 420, 425, 426, 427, 430, 434, 444, 820, 828, 900, 994, 995, 996, 997, 998, 999, 1001, 1002, 1005, 1006, 1007, 1009, 1010, 1130, 1197, 1229, 1239, 1378, 1383, 1385, 1388, 1402, 1408, 1417, 1419, 1422, 1423, 1424, 1427, 1428, 1454, 1473, 1474, 1475, 1476, 1477, 1478, 1480, 1486, 780, 779, 1491, 1495, 1499, 1501, 1503, 1504, 1505, 1506, 1511, 1512, 1513, 924, 1516, 1518, 1519, 1523, 1524, 1525, 1526, 1527, 1531, 1541, 1544, 1545, 1546, 1547, 1548, 1549, 967, 1550, 1551, 1552, 1556, 1557, 1585, 1589, 1709, 1710, 1711, 1712, 1713, 1714, 1715, 1717, 1718, 1910, 1911, 1969, 1970, 1972, 1973, 1974, 1975, 1976, 1977, 1978, 1979, 1980, 1981, 1984, 1986, 1987, 1988, 1989, 1990, 1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999, 2000, 2001, 2002, 2003, 2004, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014, 2016, 2017, 2019, 2020, 2021, 2023, 2024, 2025, 2026, 2027, 2029, 2030, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040, 2041, 2042, 2043, 2044, 2045, 2046, 2047, 2048, 2049, 2051, 2053, 2054, 2055, 2056, 2057, 2058, 2060, 2061, 2063, 2064, 2065, 2066, 2417, 2419, 2420, 2421, 2422, 2423, 2424, 2426, 2427, 2428, 2429, 2430, 2431, 2432, 2433, 2434, 2435, 2436, 2437, 2438, 2439, 2440, 2441, 2442, 2443, 2444, 2445, 2446, 2447, 2448, 2449, 2450, 2451, 2452, 2453, 2454, 2455, 2456, 2457, 2458, 2459, 2460, 2461, 2462, 2463, 2464, 2465, 2466, 2467, 2468, 2469, 2471, 2472, 2473, 2474, 2475, 2476, 2477, 2478, 2479, 2480, 2481, 2482, 2483, 2484, 2485, 2486, 2487, 2489, 2490, 2491, 2492, 2493, 2494, 2495, 2496, 2497, 2498, 2499, 2500, 2501, 2502, 2503, 2504, 2506, 2508, 2509, 2510, 2512, 2513, 2514, 2515, 2516, 2518, 2519, 2521, 2522, 2523, 2539, 2554, 2605, 2707, 2709, 2712, 2725, 2862, 2931, 2932, 2935, 2936, 2937, 2938, 2939, 2941, 2942, 2943, 2944, 2945, 2946, 2947, 2949, 2950, 2951, 2952, 2954, 2955, 2956, 2957, 2958, 2959, 2961, 2962, 2963, 2964, 2965, 2967, 2969, 2972, 2973, 2975, 2976, 2977, 2978, 2979, 2980, 2981, 2982, 2983, 2984, 2985, 2986, 2987, 2988, 2989, 2990, 2991, 2992, 2994, 2995, 2996, 2998, 2999, 3001, 3002, 3003, 3005, 3006, 3007, 3009, 3010, 3012, 3013, 3014, 3015, 3016, 3017, 3018, 3019, 3020, 3021, 3022, 3023, 3024, 3025, 3026, 3027, 3028, 3029, 3030, 3031, 3033, 3034, 3035, 3037, 3038, 3039, 3041, 3042, 3044, 3045, 3046, 3047, 3048, 3049, 3050, 3052, 3053, 3054, 3056, 3057, 3058, 3059, 3060, 3061, 3062, 3063, 3064, 3065, 3066, 3067, 3068, 3069, 3070, 3287, 3326, 3327, 3328, 3329, 3383, 3435, 3442, 3443, 3448, 3450, 3452, 3457, 3686, 3863, 3864, 3866, 3869, 3871, 3872, 3873, 3874, 3875, 3876, 3889, 3890])->get();
        foreach ($staffs->where('staff_transfer_count', 1) as $staff) {
            echo $staff->id . '<br>';
        }
    }

    public function staffWithoutInitialRecords()
    {
        $staffs_without_initital_transfer_record = StafMainMastModel::whereNotNull('appo_date')->whereNotNull('appo_office')->whereDoesntHave('staffTransfer')->get();
        foreach ($staffs_without_initital_transfer_record as $staff) {
            $staff_transfer = new StaffTransferModel();
            $staff_transfer->staff_central_id = $staff->id;
            $staff_transfer->from_date = $staff->appo_date;
            $staff_transfer->from_date_np = BSDateHelper::AdToBs('-', $staff->appo_date);
            $staff_transfer->autho_id = Auth::id();
            $staff_transfer->office_from = $staff->appo_office;
            $staff_transfer->sync = 0;
            $staff_transfer->save();
        }
    }

    public function staffWithMultipleInitialRecords()
    {
        $staffs_with_multiple_initital_transfer_record = StafMainMastModel::with(['staffTransfer' => function ($query) {
            $query->whereNull('office_id');
        }])->withCount('staffTransfer')->get();
        foreach ($staffs_with_multiple_initital_transfer_record as $staff) {
            if ($staff->staffTransfer->count() > 1) {
                echo $staff->id . '<br>';
            }
        }
    }

    public function shiftView()
    {
        $start_time = '7:00 am';
        $end_time = '10:00 pm';
        $total_duration = strtotime($end_time) - strtotime($start_time);
        $total_duration_in_seconds = $total_duration / 60;
        $data = [];
        $shifts = Shift::withCount('staff')->where('branch_id', 1)->where('active', 1)->orderBy('punch_in')->get();
        $i = 1;
        foreach ($shifts as $shift) {
            $data[$i]['shift_name'] = $shift->shift_name;
            $punch_in = $shift->punch_in;
            $punch_out = $shift->punch_out;
            $total_shift_duration = ((strtotime($punch_out) - strtotime($punch_in)) / 60);
            $punchin_margin_duration = ((strtotime($punch_in) - strtotime($start_time)) / 60);
            $data[$i]['duration_percentage'] = ($total_shift_duration / $total_duration_in_seconds) * 100;
            $data[$i]['margin_percentage'] = ($punchin_margin_duration / $total_duration_in_seconds) * 100;
            $data[$i]['staff_count'] = $shift->staff_count;
            $i++;
        }

        dd($data);
    }

    public function alternativeTest()
    {
        $today_day = date('N');
        $staffs = StafMainMastModel::whereHas('staffAlternativeShifts')->with(['staffAlternativeShifts' => function ($query) use ($today_day) {
            $query->where('day', $today_day);
            $query->with('shift');
        }])->get();
        foreach ($staffs as $staff) {
            if ($staff != $staff->staffAlternativeShifts->shift_id) {
                $staff = $staff->staffAlternativeShifts->shift_id;
                $staff->sync = 1;
                $staff->save();
            }
        }
    }

    public function gradeSplitTest()
    {
        $staff = StafMainMastModel::with(['staffPosts' => function ($query) {
            $query->with('post');
        }])->find(42);
        $from = BSDateHelper::BsToAd('-', '2076-04-01');
        $to = BSDateHelper::BsToAd('-', '2077-03-30');
        dd($this->basicSalaryChangeDivisionByCollection($staff->staffPosts, $from, $to));
    }

    public function try()
    {
        /*$payroll = PayrollDetailModel::find(20);
        dd($payroll->payrollConfirmAllowances->first());*/
        $staffJobPositions = StaffJobPosition::with('staff')->where('effective_to_date', null)->get();
        foreach ($staffJobPositions as $position) {
            $staff = $position->staff;
            if ($staff->post_id != $position->post_id) {
                $staff->post_id = $position->post_id;
                $staff->save();
            }
        }
    }

    public function checkHere()
    {
        $homeLeave = SystemLeaveMastModel::where('leave_earnable_type', 1)->latest()->first();
//        dd($homeLeave);
        $collect = collect();
        $collect->threshold = $homeLeave->threshold_for_earnability;
        $collect->leave_type = $homeLeave->leave_type;
        return $this->calculateLeaveRepository->checkTypeHere($homeLeave, $collect);
    }

//    public function calculateLeave()
//    {
//        $homeLeave = SystemLeaveMastModel::where('leave_earnable_type', 4)->latest()->first();
//        $collect = collect();
////        $collect->present_days = 10;
////        $collect->abs_days = 15;
//        return $this->calculateLeaveRepository->flatReplaceNameHere($homeLeave, $collect);
////        return $this->calculateLeaveRepository->presentDays($homeLeave, $collect);
//    }
//
    public function forPDR()
    {
        $homeLeave = SystemLeaveMastModel::where('leave_earnable_type', 6)->latest()->first();
//        dd($homeLeave);
        $collect = collect();
        $collect->threshold = $homeLeave->threshold_for_earnability;
        $collect->leave_type = $homeLeave->leave_type;
        return $this->calculateLeaveRepository->pdrReplaceNameHere($homeLeave, $collect);
    }

    public function calculateTotalAttendance()
    {
        $staffIds = StafMainMastModel::pluck('id');

        $public_holidays = SystemHolidayMastModel::with('branch')->get();

        $date_from = Carbon::now()->subDay(21);
        $date_to = Carbon::now()->subDay(14);


        $staffs = StafMainMastModel::with(['workschedule', 'grantLeave', 'staffStatus', 'branch', 'fetchAttendances' => function ($query) use ($date_from, $date_to) {
            $query->where('punchin_datetime', '>', $date_from . ' 00:00:00')
                ->where('punchin_datetime', '<', $date_to . ' 23:59:00');
        }])->limit(2)->get();

        $values = [];
        foreach ($staffs as $staff) {
            $values[] = $this->calculateFetchAttendanceRepository->getCalculatedAttendanceInformation($staff, $public_holidays, $date_from->toDateString(), $date_to->toDateString());
        }

        return response()->json([$values, BSDateHelper::AdToBs('-', $date_from->toDateString()), BSDateHelper::AdToBs('-', $date_to->toDateString())]);
    }
}
