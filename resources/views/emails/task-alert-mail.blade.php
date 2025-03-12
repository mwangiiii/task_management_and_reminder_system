<!-- resources/views/emails/task-alert.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        h1 {
            color: #2c5282;
        }
        .task-details {
            background: #fff;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            border: 1px solid #eee;
        }
        .label {
            font-weight: bold;
            margin-right: 10px;
        }
        .button {
            display: inline-block;
            background: #2c5282;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Task Alert</h1>
        <p>Hello {{ $task->user->name }},</p>
        <p>This is a reminder about your task:</p>
        
        <div class="task-details">
            <p><span class="label">Task:</span> {{ $task->name }}</p>
            <p><span class="label">Description:</span> {{ $task->description }}</p>
            <p><span class="label">Due Date:</span> {{ date('F j, Y, g:i a', strtotime($task->due_date)) }}</p>
            <p><span class="label">Category:</span> {{ $task->category->name ?? 'N/A' }}</p>
            <p><span class="label">Status:</span> {{ $task->completionStatus->name ?? 'N/A' }}</p>
        </div>
        
        <p>Please review this task and take appropriate action.</p>
        
        <a href="{{ url('/tasks/'.$task->id) }}" class="button">View Task</a>
        
        <p>Thank you,<br>
        Your Task Management System</p>
    </div>
</body>
</html>