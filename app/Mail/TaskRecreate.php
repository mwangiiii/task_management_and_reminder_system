<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskRecreate extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), 'Task Recreate')
            ->subject(($this->task->recurrency?->frequency ?? 'N/A') . ' Task Recreation Reminder ')
            ->view('emails.task_recreate')
            ->with(['task' => $this->task]);
    
    }
}

