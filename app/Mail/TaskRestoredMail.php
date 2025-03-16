<?php
namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskRestoredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task; // Task instance

    /**
     * Create a new message instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Task Has Been Restored')
                    ->view('emails.task_restored') // The Blade view for the email
                    ->with(['task' => $this->task]);
    }
}

