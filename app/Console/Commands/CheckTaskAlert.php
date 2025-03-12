<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Mail\TaskReminderMail;
use App\Models\Task;
use App\Models\Alert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckTaskAlerts extends Command
{
    protected $signature = 'tasks:check-alerts';
    protected $description = 'Check for task alerts that need to be sent';

    // public function handle()
    // {
    //     $now = Carbon::now();
    //     $oneMinuteFromNow = Carbon::now()->addMinute();

    //     // Find alerts scheduled within the next minute

    //         $alerts = Alert::whereBetween('time_of_alert', [$now->toDateTimeString(), $oneMinuteFromNow->toDateTimeString()])
    // ->whereHas('task', function ($query) {
    //     $query->where('alert_sent', false);
    // })
    // ->get();


    //     $this->info("Found {$alerts->count()} alerts with pending notifications");

    //     foreach ($alerts as $alert) {
    //         if ($alert->task && !$alert->task->alert_sent) {
    //             try {
    //                 // Send notification
    //                 Mail::to($alert->task->user->email)->send(new TaskReminderMail($alert->task));

    //                 // Mark the alert as sent
    //                 $alert->task->update(['alert_sent' => true]);

    //                 $this->info("Alert sent for task: {$alert->task->name}");
    //             } catch (\Exception $e) {
    //                 $this->error("Failed to send alert for task: {$alert->task->id}");
    //                 Log::error('Task alert failed', [
    //                     'task_id' => $alert->task->id,
    //                     'alert_id' => $alert->id,
    //                     'error' => $e->getMessage()
    //                 ]);
    //             }
    //         }
    //     }

    //     return Command::SUCCESS;
    // }

    public function handle()
    {
        $now = Carbon::now();
        $alerts = Alert::where('time_of_alert', '<=', $now)
            ->where('alert_sent', false)
            ->get();

        foreach ($alerts as $alert) {
            $task = Task::find($alert->task_id);

            if ($task) {
                $userEmail = $task->user->email ?? null;

                if ($userEmail) {
                    Mail::raw("Reminder: Your task '{$task->name}' is due soon!", function ($message) use ($userEmail) {
                        $message->to($userEmail)
                            ->subject('Task Reminder');
                    });

                    $alert->update(['alert_sent' => true]);
                    $this->info("Alert sent for task ID: {$task->id}");
                }
            }
        }

        $this->info('Alert check complete.');
    }
}