<?php

namespace App\Jobs;

use App\Notifications\ApprovedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ApprovedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $user;
    public $approver;
    public $monthOf;

    public function __construct($user, $approver, $monthOf)
    {
        $this->user = $user;
        $this->approver = $approver;
        $this->monthOf = $monthOf;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->notify(new ApprovedNotification($this->user->name, $this->approver, $this->monthOf));
    }
}
