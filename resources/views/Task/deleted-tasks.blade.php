<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Tasks</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #f9fafb;
            --accent: #818cf8;
            --text: #1f2937;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --bg: #ffffff;
            --bg-subtle: #f3f4f6;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --radius: 8px;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background-color: var(--secondary);
            line-height: 1.5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .header {
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .header p {
            color: var(--text-light);
        }
        
        .card {
            background: var(--bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #4338ca;
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        .btn-secondary {
            background-color: var(--bg-subtle);
            color: var(--text);
        }
        
        .btn-secondary:hover {
            background-color: var(--border);
        }
        
        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .btn i {
            margin-right: 0.5rem;
        }
        
        .task-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .task-table th {
            background-color: var(--bg-subtle);
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text);
            border-bottom: 1px solid var(--border);
        }
        
        .task-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }
        
        .task-table tr:hover {
            background-color: var(--bg-subtle);
            cursor: pointer;
        }
        
        .task-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-incomplete {
            background-color: #fef2f2;
            color: var(--danger);
        }
        
        .status-in-progress {
            background-color: #fffbeb;
            color: var(--warning);
        }
        
        .status-complete {
            background-color: #f0fdf4;
            color: var(--success);
        }
        
        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        
        .truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
        
        @media (max-width: 1024px) {
            .container {
                padding: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .responsive-table {
                overflow-x: auto;
            }
            
            .task-table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Archived Tasks</h1>
            <p>Review and manage your archived tasks</p>
        </div>

        <div class="card">
            <div class="responsive-table">
                <table class="task-table">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Category</th>
                            <th>Timeline</th>
                            <th>Budget</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedTasks as $task)
                            <tr class="task-row" data-id="{{ $task->id }}">
                                <td class="truncate">
                                    <a href="{{ route('tasks.showOneTask', $task->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $task->name }}
                                    </a>
                                    <div class="text-sm text-gray-500">{{ $task->description }}</div>
                                    @if($task->parentTask)
                                        <div class="text-xs text-indigo-500 mt-1">
                                            <span class="bg-indigo-100 px-2 py-1 rounded">Was a subtask of: {{ $task->parentTask->name }}</span>
                                        </div>
                                    @endif
                                    @if($task->childTasks->count() > 0)
                                        <div class="text-xs text-purple-500 mt-1">
                                            <span class="bg-purple-100 px-2 py-1 rounded">Has {{ $task->childTasks->count() }} subtasks</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $task->category ? $task->category->name : 'Uncategorized' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900">
                                        <div class="mb-1">
                                            <span class="font-semibold">Start:</span> {{ \Carbon\Carbon::parse($task->start_date)->format('M d, Y') }}
                                        </div>
                                        <div>
                                            <span class="font-semibold">Due:</span> {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                        </div>
                                        @if($task->recurrency)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-sync-alt mr-1"></i>
                                                    {{ $task->recurrency->name }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900">
                                        <div class="mb-1">
                                            <span class="font-semibold">Budget:</span> ${{ number_format($task->budget, 2) }}
                                        </div>
                                        <div>
                                            <span class="font-semibold">Cost:</span> ${{ number_format($task->cost, 2) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $task->completionStatus ? $task->completionStatus->name : 'Unknown' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('viewing-all-tasks') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Tasks
            </a>
        </div>
    </div>
</body>
</html>