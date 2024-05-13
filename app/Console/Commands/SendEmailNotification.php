<?php

namespace App\Console\Commands;

use App\Admin\Department;
use App\Notifications\EmailNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendEmailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_email_notification';

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
        $department = Department::with(['departmentalGoals', 'innovation', 'process_development'])->get();

        foreach($department as $dept) {
            $deptGoals = $dept->departmentalGoals()->where('status_level', 0)->get();
            $innovation = $dept->innovation()->where('status_level', 0)->get();
            $processDevelopment = $dept->process_development()->where('status_level', 0)->get();

            $targetDate = date("Y-m").'-'.$dept->target_date;
            $threeDaysBeforeTarget = Carbon::now()->subDays(3);

            if ($threeDaysBeforeTarget <= $targetDate) {
                if ($deptGoals->isNotEmpty() && $innovation->isNotEmpty() && $processDevelopment->isNotEmpty()) {
                    
                    $user = $dept->user;
                    $user->notify(new EmailNotification($user->name));
                };
                
            }
        }
    }
}
