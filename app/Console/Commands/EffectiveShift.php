<?php

namespace App\Console\Commands;

use App\StaffShiftHistory;
use Illuminate\Console\Command;

class EffectiveShift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'effective:shift';

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
        $shiftHistories = StaffShiftHistory::with('staff')->where('effective_from', date('Y-m-d'))->get();
        foreach ($shiftHistories as $shiftHistory) {
            if ($shiftHistory->shift_id != $shiftHistory->staff->shift_id) {
                $staff = $shiftHistory->staff;
                $staff->shift_id = $shiftHistory->shift_id;
                $staff->sync = 1;
                $staff->save();
            }
        }
    }
}
