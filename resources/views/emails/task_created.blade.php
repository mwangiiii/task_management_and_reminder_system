@component('mail::message')

<div style="text-align: center; margin-bottom: 20px;">
    <h1 style="color: #4f46e5; font-size: 24px; margin-bottom: 10px;">New Task Created</h1>
    <p style="color: #6b7280; font-size: 16px;">A new task has been assigned to you that requires your attention.</p>
</div>

<div style="background-color: #f9fafb; border-radius: 8px; padding: 20px; margin-bottom: 20px; border-left: 4px solid #4f46e5;">
    <p style="margin: 0 0 10px 0;"><strong style="color: #4b5563;">Task ID:</strong> <span style="color: #1f2937;">{{ $task->id }}</span></p>
    <p style="margin: 0 0 10px 0;"><strong style="color: #4b5563;">Title:</strong> <span style="color: #1f2937;">{{ $task->title }}</span></p>
    <p style="margin: 0 0 10px 0;"><strong style="color: #4b5563;">Description:</strong> <span style="color: #1f2937;">{{ $task->description }}</span></p>
    <p style="margin: 0;"><strong style="color: #4b5563;">Due Date:</strong> <span style="color: #1f2937; font-weight: 500;">{{ $task->due_date }}</span></p>
</div>

@component('mail::button', ['url' => route('tasks.showOneTask', ['id' => $task->id])])
View Task Details
@endcomponent

<p style="margin-top: 30px; font-size: 14px; color: #6b7280;">If you have any questions, please contact your project manager.</p>

<div style="margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 20px;">
    <p style="margin: 0; color: #4b5563;">Thanks,<br>{{ config('app.name') }}</p>
</div>

@endcomponent
