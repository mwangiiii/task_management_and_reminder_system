<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $task->recurrency->frequency ?? 'N/A' }} Task Reminder</title>
    <style>
        :root {
            --primary: #3a86ff;
            --primary-dark: #2a75ee;
            --success: #38b000;
            --success-dark: #2e9000;
            --danger: #ff5a5f;
            --danger-dark: #e04045;
            --text-primary: #333333;
            --text-secondary: #555555;
            --text-light: #777777;
            --background: #f8f9fa;
            --card: #ffffff;
            --border: #e0e0e0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Roboto, -apple-system, BlinkMacSystemFont, Arial, sans-serif;
            background-color: var(--background);
            line-height: 1.6;
            color: var(--text-primary);
            padding: 40px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            max-width: 650px;
            width: 90%;
            background: var(--card);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 25px 35px;
            position: relative;
        }
        
        .header h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .header-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .content {
            padding: 35px;
        }
        
        .info-item {
            margin-bottom: 20px;
            position: relative;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
            display: block;
            font-size: 15px;
        }
        
        .info-value {
            background-color: rgba(240, 245, 255, 0.7);
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 15px;
            border-left: 3px solid var(--primary);
            color: var(--text-secondary);
        }
        
        h3 {
            color: var(--text-primary);
            font-size: 18px;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }
        
        .task-list {
            list-style-type: none;
            margin-left: 0;
            padding-left: 0;
        }
        
        .task-list li {
            padding: 10px 15px;
            margin-bottom: 8px;
            background-color: rgba(240, 245, 255, 0.5);
            border-radius: 8px;
            position: relative;
            padding-left: 35px;
        }
        
        .task-list li::before {
            content: "•";
            position: absolute;
            left: 15px;
            color: var(--primary);
            font-size: 22px;
            line-height: 20px;
        }
        
        .task-list ul {
            list-style-type: none;
            padding-left: 20px;
            margin-top: 8px;
        }
        
        .task-list ul li {
            margin-bottom: 5px;
            background-color: rgba(240, 245, 255, 0.2);
            padding: 8px 15px 8px 35px;
        }
        
        .task-list ul li::before {
            content: "◦";
            color: var(--primary-dark);
        }
        
        .action-section {
            margin-top: 30px;
            text-align: center;
            padding: 25px;
            background-color: rgba(240, 245, 255, 0.5);
            border-radius: 12px;
            border: 1px dashed var(--border);
        }
        
        .action-section p {
            font-size: 17px;
            font-weight: 500;
            margin-bottom: 20px;
            color: var(--text-primary);
        }
        
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 35px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .btn::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(255,255,255,0.1);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .btn:hover::after {
            opacity: 1;
        }
        
        .btn-yes {
            background-color: var(--success);
            color: white;
        }
        
        .btn-yes:hover {
            background-color: var(--success-dark);
            transform: translateY(-2px);
        }
        
        .btn-no {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-no:hover {
            background-color: var(--danger-dark);
            transform: translateY(-2px);
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            font-size: 13px;
            color: var(--text-light);
            text-align: center;
        }
        
        .empty-state {
            padding: 15px;
            background-color: rgba(240, 245, 255, 0.3);
            border-radius: 8px;
            font-style: italic;
            color: var(--text-light);
        }
        
        @media (max-width: 600px) {
            body {
                padding: 20px 0;
            }
            
            .container {
                width: 95%;
                border-radius: 12px;
            }
            
            .header {
                padding: 20px 25px;
            }
            
            .content {
                padding: 25px;
            }
            
            .btn-container {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
        <h1>{{ $task->recurrency->frequency ?? 'N/A' }} Task Reminder</h1>
            <h2>Task : {{ $task->name }}</h2>
            <div class="header-subtitle">Review and manage your task details, this is one of your {{ $task->recurrency->frequency ?? 'N/A' }} task that is to be recreated</div>
        </div>
        
        <div class="content">
            <div class="info-item">
                <span class="info-label">Description</span>
                <div class="info-value">{{ $task->description ?? 'No description provided' }}</div>
            </div>
            
            <div class="info-item">
                <span class="info-label">Budget/Cost</span>
                <div class="info-value">{{ $task->budget ?? 'N/A' }}</div>
            </div>
            
            <div class="info-item">
                <span class="info-label">Recurrence</span>
                <div class="info-value">{{ $task->recurrency->frequency ?? 'N/A' }}</div>
            </div>
            
            <h3>Subtasks to be Recreated</h3>
            <ul class="task-list">
                @if (!empty($task->childTasks) && $task->childTasks->count() > 0)
                    @foreach ($task->childTasks as $child)
                        <li>{{ $child->name }} ({{ ucfirst($child->recurrence) }})
                            @if ($child->childTasks->count() > 0)
                                <ul>
                                    @foreach ($child->childTasks as $subChild)
                                        <li>{{ $subChild->name }} ({{ ucfirst($subChild->recurrence) }})</li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                @else
                    <div class="empty-state">No subtasks available.</div>
                @endif
            </ul>
            
            <div class="action-section">
                <p>Would you like to recreate this task?</p>
                <div class="btn-container">
                    <a href="{{ route('tasks.recreate', ['id' => $task->id]) }}" class="btn btn-yes" onclick="this.style.pointerEvents='none';">
                        YES
                    </a>
                    <a href="{{ route('tasks.decline', ['id' => $task->id]) }}" class="btn btn-no">
                        NO
                    </a>
                </div>
            </div>
            
            <div class="footer">
                This is an automated email. If you have any questions, please contact support.
            </div>
        </div>
    </div>
</body>
</html>