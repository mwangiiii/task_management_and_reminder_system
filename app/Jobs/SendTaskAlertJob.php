<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\TaskAlert;
use Illuminate\Support\Facades\Mail;
use App\Models\Alert;

class SendTaskAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $alert;

    public function __construct($alert)
    {
        $this->alert = $alert;
    }

    public function handle()
    {
        $userEmail = $this->alert->task->user->email ?? null;

        if ($userEmail) {
            Mail::to($userEmail)->send(new TaskAlert($this->alert->task));
            $this->alert->update(['alert_sent' => true]);
        }
    }
}

