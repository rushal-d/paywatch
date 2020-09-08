<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlertNoSyncEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $branch_name,$last_sync_time;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($branch_name,$last_sync_time)
    {
        $this->branch_name=$branch_name;
        $this->last_sync_time=$last_sync_time;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('email.nosyncalert',['branch_name'=>$this->branch_name,'last_sync_time'=>$this->last_sync_time]);
    }
}
