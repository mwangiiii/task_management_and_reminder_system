<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAlert;
use App\Mail\TaskRecreate;
use App\Models\Alert;
use App\Models\Task;
use Carbon\Carbon;

class SendTaskAlerts extends Command
{
    protected $signature = 'tasks:send-alerts';
    protected $description = 'Send task alerts when the time reaches';

    public function handle()
    {
        $now = Carbon::now();
        $startTime = $now->copy();
        $endTime = $now->copy()->addMinute();

        $alerts = Alert::whereBetween('time_of_alert', [$startTime, $endTime])
                       ->where('alert_sent', false)
                       ->with('task')
                       ->get();

        if ($alerts->isEmpty()) {
            $this->warn("Log: {$now} : No task alerts found to send.");
        }

        foreach ($alerts as $alert) {
            if ($alert->task) {

                $userEmail = $alert->task->user->email ?? null;

                if ($userEmail) {
                    Mail::to($userEmail)->send(new TaskAlert($alert->task));

                    $alert->update(['alert_sent' => true]);

                    $this->info("Log: {$now} : ✅ Task alert sent to {$userEmail} for task: {$alert->task->name}");
                }
            }
        }
                // Handle repeating tasks
                $repeatingTasks = Task::where('repeat', true)->get();

                if ($repeatingTasks->isEmpty()) {
                    $this->warn("Log: {$now} : 🔄 No repeating tasks found.");
                }
        
                foreach ($repeatingTasks as $task) {
                    // $this->warn("start");
                    // $this->warn("Hourly {$task->name} {$this->getNextHourlyTime($task)}");
                    // $this->warn("daily {$task->name} {$this->getNextDailyTime($task)}");
                    // $this->warn("weekly {$task->name} {$this->getNextWeeklyTime($task)}");
                    // $this->warn("monthly {$task->name} {$this->getNextMonthlyTime($task)}");
                    // $this->warn("yearly {$task->name} {$this->getNextYearlyTime($task)}");
                    // $this->warn("end");
                    $this->handleRepeatingTask($task);
                }
    }
    private function handleRepeatingTask($task)
    {    
        $now = Carbon::now();
        $startTime = $now->copy();
        $endTime = $now->copy()->addMinute();
        if ($task->recurrency_id == 1) {
            $this->warn("Log: {$now} : 🔕 Task '{$task->name}' is a one-time task and will not repeat.");
            return;
        }


        $recurrencyMapping = [
            2 => 'hourly',
            3 => 'daily',
            5 => 'weekly',
            4 => 'monthly',
            6 => 'annually'
        ];
        
        $frequency = $recurrencyMapping[$task->recurrency_id] ?? null;
        
        if (!$frequency) {
            $this->warn("Log: {$now} : ⚠️ Unknown recurrency ID '{$task->recurrency_id}' for task: {$task->name}");
            return;
        }

        // Determine next scheduled alert time
        $nextTrigger = match ($frequency) {
            'hourly' => $this->getNextHourlyTime($task)->addMinute(),
            'daily' => $this->getNextDailyTime($task)->addMinute(),
            'weekly' => $this->getNextWeeklyTime($task)->addMinute(),
            'monthly' => $this->getNextMonthlyTime($task)->addMinute(),
            'annually' => $this->getNextYearlyTime($task)->addMinute(),
            default => null
        };
        $this->info("Log: {$now} : Recreation of {$task->name} will be sent at {$nextTrigger} dont worry");
        if($nextTrigger->between($startTime, $endTime, true)){
            $this->info("Log: {$now} : ✅ {$task->name} time to recreate {$nextTrigger->subMinute()}");
            $userEmail = $task->user->email ?? null;

                if ($userEmail) {
                    Mail::to($userEmail)->send(new TaskRecreate($task));

                    $task->update(['repeat' => false]);

                    $this->info("Log: {$now} : Task recreate sent to {$userEmail} for task: {$task->name}");
                }else{
                    $this->error("Log: {$now} : user email not found for task {$task->name}");
                }
        }else{
            $this->warn("Log: {$now} : No task found to send for recreation.");
        }


    }
    private function getNextHourlyTime($task)
    {
        $createdAt = Carbon::parse($task->created_at);
        $nextHourly = $createdAt->copy()->addHour();

        return $nextHourly;
    }
    private function getNextDailyTime($task)
    {
        $createdAt = Carbon::parse($task->created_at);
        $nextDaily = $createdAt->copy()->addDay();

        return $nextDaily;
    }
    private function getNextWeeklyTime($task)
    {
        $createdAt = Carbon::parse($task->created_at);
        $nextWeekly = $createdAt->copy()->addWeek();

        return $nextWeekly;
    }

    private function getNextMonthlyTime($task)
    {   $createdAt = Carbon::parse($task->created_at);
        $nextMonth = $createdAt->copy()->addMonth();

        return $nextMonth;
    }
    private function getNextYearlyTime($task)
    {
        $createdAt = Carbon::parse($task->created_at);
        $nextYearly = $createdAt->copy()->addYear();

        return $nextYearly;
    }
    

}


