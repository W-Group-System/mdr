<?php

namespace App\Console\Commands;

use App\Admin\Approve;
use App\Approver\MdrSummary;
use App\DeptHead\Mdr;
use App\Notifications\PendingNotification;
use App\User;
use Illuminate\Console\Command;

class SendApprovalList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:for_approval_list';

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
        $userList = User::where('account_role', 1)->get();

        foreach($userList as $userData) {
            $approvers = Approve::where('user_id', $userData->id)->get();

            foreach($approvers as $approver) {
                $mdrSummaries = MdrSummary::where('final_approved', 0)->where('status_level', $approver->status_level)->get();
                
                $table = "<table style='margin-bottom:10px;' width='100%' border='1' cellspacing=0><tr><th colspan='3'>For Your Approval</th></tr><tr><th>Date Submitted</th><th>Department</th><th>Rate</th></tr>";
    
                if ($mdrSummaries->isNotEmpty()) {
                    foreach($mdrSummaries as $mdrSummary) {
                        $table .= "<tr><td>".date('Y-m-d', strtotime($mdrSummary->submission_date))."</td><td>".$mdrSummary->departments->dept_name."</td><td>".$mdrSummary->rate."</td></tr>";
                    }
                }
                else {
                    $table .= "<tr><td colspan='3'>No pending approval.</td></tr>";
                }
                $table .= "</table>";
                
            }
            
            $userData->notify(new PendingNotification($table));
        }
    }
}
