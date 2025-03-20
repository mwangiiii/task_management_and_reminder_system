<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Reminder</title>
    <style>
        :root {
            --primary: #4361ee;
            --success: #2ec4b6;
            --warning: #ff9f1c;
            --danger: #e71d36;
            --light: #f8f9fa;
            --dark: #212529;
            --shadow: rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px var(--shadow);
            overflow: hidden;
            width: 100%;
            max-width: 600px;
            margin: 20px;
        }
        
        .card-header {
            background-color: var(--primary);
            color: white;
            padding: 20px 30px;
            position: relative;
        }
        
        .card-header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .task-icon {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 24px;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .info-group {
            margin-bottom: 20px;
        }
        
        .info-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            display: block;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 1.1rem;
            margin: 0;
            padding: 8px 12px;
            background-color: var(--light);
            border-radius: 6px;
            border-left: 4px solid var(--primary);
        }
        
        .status-box {
            padding: 15px;
            border-radius: 8px;
            margin-top: 30px;
            font-weight: 500;
        }
        
        .status-upcoming {
            background-color: rgba(46, 196, 182, 0.1);
            border-left: 4px solid var(--success);
            color: var(--success);
        }
        
        .status-progress {
            background-color: rgba(67, 97, 238, 0.1);
            border-left: 4px solid var(--primary);
            color: var(--primary);
        }
        
        .status-overdue {
            background-color: rgba(231, 29, 54, 0.1);
            border-left: 4px solid var(--danger);
            color: var(--danger);
        }
        
        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            padding: 12px 24px;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-success {
            background-color: var(--success);
        }
        
        .btn-success:hover {
            background-color: #25a99d;
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: #3050d8;
            transform: translateY(-2px);
        }
        
        .card-footer {
            padding: 15px 30px;
            background-color: #f8f9fa;
            border-top: 1px solid #eee;
            font-size: 0.9rem;
            color: #666;
            text-align: center;
        }
        
        @media (max-width: 650px) {
            .card {
                margin: 15px;
                width: calc(100% - 30px);
            }
            
            .card-header h2 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h2>Task Alert: {{ $task->name }}</h2>
            <div class="task-icon">📋</div>
        </div>
        <div class="card-body">
            <div class="info-group">
                <span class="info-label">Description</span>
                <p class="info-value">{{ $task->description }}</p>
            </div>
            
            <div class="info-group">
                <span class="info-label">Start Date</span>
                <p class="info-value">{{ $task->start_date->format('d M Y H:i') }}</p>
            </div>
            
            <div class="info-group">
                <span class="info-label">Due Date</span>
                <p class="info-value">{{ $task->due_date->format('d M Y H:i') }}</p>
            </div>
            
            @php
                $now = now();
                $start_time = $task->start_date;
                $due_time = $task->due_date;
            @endphp
            
            @if($now->lt($start_time))
                <div class="status-box status-upcoming">
                    <strong>Status:</strong> Task has not started yet. It will start at {{ $start_time->format('d M Y H:i') }}.
                </div>
                <a href="{{ route('tasks.start', $task->id) }}" class="btn btn-success">Start Task Now</a>
            @elseif($now->between($start_time, $due_time))
                @php
                    $timeLeft = $due_time->diff($now);
                @endphp
                <div class="status-box status-progress">
                @php
                    $timeLeft = $due_time->diff(now());
                @endphp

                <strong>Status:</strong> Task is in progress. Time left: 
                {{ $timeLeft->format('%d days %h hours %i minutes') }}

                </div>
                <a href="{{ route('tasks.extend', $task->id) }}" class="btn btn-primary">Extend/Reduce Due Time</a>
            @else
                <div class="status-box status-overdue">
                    <strong>Status:</strong> Task is past due!
                </div>
            @endif
        </div>
        <div class="card-footer">
            This is an automated task reminder. Please do not reply to this email.
        </div>
    </div>
</body>
</html>