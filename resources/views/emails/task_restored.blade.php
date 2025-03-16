<!DOCTYPE html>
<html>
<head>
    <title>Task Restored</title>
</head>
<body>
    <h2>Hello {{ $task->user->name }},</h2>
    
    <p>Your task <strong>"{{ $task->name }}"</strong> has been successfully restored.</p>

    <p>You can now access and continue working on your task.</p>

    <p><a href="{{ route('tasks.show', $task->id) }}">View Task</a></p>

    <p>Best Regards,<br> Your App Team</p>
</body>
</html>
