<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAlert;
use App\Models\Alert;
use Carbon\Carbon;

class SendTaskAlerts extends Command
{
    protected $signature = 'tasks:send-alerts';
    protected $description = 'Send task alerts when the time reaches';

    public function handle()
    {
        $now = Carbon::now();
        $startTime = (clone $now)->subMinutes(1);
        $endTime = (clone $now)->addMinutes(1);

        $alerts = Alert::whereBetween('time_of_alert', [$startTime, $endTime])
                       ->where('alert_sent', false)
                       ->with('task')
                       ->get();

        if ($alerts->isEmpty()) {
            $this->warn("No task alerts found to send.");
            return;
        }

        $sentCount = 0;

        foreach ($alerts as $alert) {
            if ($alert->task) {

                $userEmail = $alert->task->user->email ?? null;

                if ($userEmail) {
                    Mail::to($userEmail)->send(new TaskAlert($alert->task));

                    $alert->update(['alert_sent' => true]);

                    $this->info("Task alert sent to {$userEmail} for task: {$alert->task->name}");
                    $sentCount++;
                }
            }
        }

        if ($sentCount === 0) {
            $this->error("No task alerts were sent.");
        }
    }
}


