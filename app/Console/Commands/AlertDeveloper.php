<?php

namespace App\Console\Commands;

use App\FetchAttendance;
use App\Mail\DeveloperAlertEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AlertDeveloper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:developer';

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
        $fetch_attendances = FetchAttendance::selectRaw('count(*) as counter')->groupBy('punchin_datetime', 'staff_central_id')->having('counter', '>', 1)->take(10)->get();
        if ($fetch_attendances->count() > 0) {
            //duplicate record found
            Mail::to(['shree@bmpinfology.com','bikram@bmpinfology.com','no-reply@bmpinfology.xyz','shreesthapit1@gmail.com','mebikramkc@gmail.com'])->send(new DeveloperAlertEmail());
        }
    }
}
