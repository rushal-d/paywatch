<?php

namespace App\Console\Commands;

use App\AlternativeDayShift;
use App\StafMainMastModel;
use Illuminate\Console\Command;

class AlternativeStaffDayShift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alternate:shift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today_day = date('N');
        $staffs = StafMainMastModel::whereHas('branch', function ($query) {
            $query->where('enable_alternative_shift', 1);
        })->whereHas('staffAlternativeShifts')->with(['staffAlternativeShifts' => function ($query) use ($today_day) {
            $query->where('day', $today_day);
            $query->with('shift');
        }])->get();
        foreach ($staffs as $staff) {
            if ($staff->shift_id != $staff->staffAlternativeShifts->shift_id) {
                $staff->shift_id = $staff->staffAlternativeShifts->shift_id;
                $staff->sync = 1;
                $staff->save();
            }
        }
    }
}
