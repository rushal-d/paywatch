<?php

namespace App\Console\Commands;

use App\Mail\AlertNoSyncEmail;
use App\SyncRecord;
use App\SystemOfficeMastModel;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AlertNoSyncRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:nosync';

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
        $branches = [1, 2, 3];
        $deployed_branches = SystemOfficeMastModel::whereIn('office_id', $branches)->get();
        foreach ($branches as $branch) {
            $sync_record = SyncRecord::where('branch_id', $branch)->latest()->first();
            if (!empty($sync_record)) {
                $start_date = new DateTime(date('Y-m-d H:i:s'));
                $since_start = $start_date->diff(new DateTime($sync_record->sync_time));
                $no_sync_minutes = $since_start->i;
                if ($sync_record->alert_email_sent != 1 && $no_sync_minutes > 60) {
                    if (date('H') > 7 && date('H') < 20) {
                        Mail::to(['shreesthapit1@gmail.com', 'mebikramkc@gmail.com'])->send(new AlertNoSyncEmail($deployed_branches->where('office_id', $branch)->first()->office_name, $sync_record->sync_time));
                        $sync_record->alert_email_sent = 1;
                        $sync_record->save();

                    }
                }
            }
        }
    }
}
