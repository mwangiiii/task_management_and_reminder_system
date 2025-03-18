<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
    <title>Task Details</title>
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

        .task-card {
            background: var(--bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(to right, var(--primary), #9333ea);
            padding: 1.5rem;
            color: white;
        }

        .breadcrumbs {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .breadcrumbs a:hover {
            color: white;
            transition: color 0.2s;
        }

        .header-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .header-content {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .title-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .back-button {
            border-radius: 9999px;
            padding: 0.5rem;
            transition: background-color 0.2s;
        }

        .back-button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .task-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.025em;
        }

        @media (min-width: 640px) {
            .task-title {
                font-size: 1.5rem;
            }
        }

        .status-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-completed {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .status-in-progress {
            background-color: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        .status-pending {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            margin-top: 0.5rem;
            width: 12rem;
            background-color: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 0.25rem 0;
            z-index: 10;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            color: var(--text);
            transition: background-color 0.2s;
        }

        .dropdown-item:hover {
            background-color: var(--bg-subtle);
        }

        .dropdown-item.danger {
            color: var(--danger);
        }

        .card-body {
            padding: 1.5rem 2rem;
        }

        .task-details-grid {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 640px) {
            .task-details-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .task-field {
            position: relative;
            padding: 1.25rem;
            border-radius: var(--radius);
            background: linear-gradient(135deg, var(--bg-subtle) 0%, var(--bg) 100%);
            transition: transform 0.3s ease;
        }

        .task-field:hover {
            transform: translateY(-2px);
        }

        .task-field-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-light);
            margin-bottom: 0.25rem;
        }

        .task-field-value {
            display: flex;
            align-items: center;
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text);
        }

        .task-field-value svg {
            margin-right: 0.5rem;
            flex-shrink: 0;
        }

        .task-field-value svg path {
            stroke: var(--primary);
        }

        .task-field-hint {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-top: 0.25rem;
        }

        .task-field-hint.overdue {
            color: var(--danger);
        }

        .task-description {
            grid-column: span 2;
        }

        .priority-indicator-inline {
            display: inline-block;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 9999px;
            margin-right: 0.5rem;
        }

        .priority-high-inline {
            background-color: var(--danger);
        }

        .priority-medium-inline {
            background-color: var(--warning);
        }

        .priority-low-inline {
            background-color: var(--success);
        }

        .avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 9999px;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 500;
            margin-right: 0.75rem;
        }

        .task-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
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

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: var(--bg);
            border-radius: var(--radius);
            padding: 1.5rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s ease, opacity 0.3s ease;
            opacity: 0;
            transform: translateY(10px);
        }

        .modal.active .modal-content {
            opacity: 1;
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text);
        }

        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            color: var(--text-light);
        }

        .modal-body {
            margin-bottom: 1.5rem;
        }

        .detail-row {
            display: flex;
            margin-bottom: 0.75rem;
        }

        .detail-label {
            flex: 0 0 120px;
            font-weight: 500;
            color: var(--text-light);
        }

        .detail-value {
            flex: 1;
            color: var(--text);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border);
        }

        .toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: var(--bg);
            border-left: 4px solid var(--success);
            padding: 1rem;
            box-shadow: var(--shadow);
            border-radius: 0.25rem;
            z-index: 1001;
            display: flex;
            align-items: center;
            transform: translateX(120%);
            transition: transform 0.3s ease;
        }

        .toast.active {
            transform: translateX(0);
        }

        .toast i {
            margin-right: 0.75rem;
            font-size: 1rem;
            color: var(--success);
        }

        .toast-message {
            font-size: 0.875rem;
            font-weight: 500;
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
            .task-details-grid {
                grid-template-columns: 1fr;
            }

            .task-description {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content-wrapper">
            <!-- Success Toast -->
            <div id="success-toast" style="display: none;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Task updated successfully!</span>
                <button onclick="document.getElementById('success-toast').style.display = 'none'" class="ml-4 text-white hover:text-gray-200">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Card Container -->
            <div class="task-card">
                <!-- Header with breadcrumbs -->
                <div class="card-header">
                    <div class="breadcrumbs">
                        <a href="{{ route('viewing-all-tasks') }}"></a>
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <a href="{{ route('viewing-all-tasks') }}">Tasks</a>
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span>Task Details</span>
                    </div>
                    <div class="header-content">
                        <div class="title-section">
                            <a href="{{ route('viewing-all-tasks') }}" class="back-button">
                                <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </a>
                            <h1 class="task-title">{{ $task->name }}</h1>
                        </div>
                        <div>
                        <td>
                            @if($task->completion_status_id == 1) 
                                <!-- Start Task Button (if status is Pending) -->
                                <form action="{{ route('task.start', ['id' => $task->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-play"></i> Start Task
                                    </button>
                                </form>
                            @else 
                                <!-- Status Dropdown -->
                                <form action="{{ route('task.updateStatus', ['id' => $task->id]) }}" method="POST">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="form-control">
                                        <option value="1" {{ $task->completion_status_id == 1 ? 'selected' : '' }}>Pending</option>
                                        <option value="2" {{ $task->completion_status_id == 2 ? 'selected' : '' }}>In Progress</option>
                                        <option value="3" {{ $task->completion_status_id == 3 ? 'selected' : '' }}>Completed</option>
                                        <option value="4" {{ $task->completion_status_id == 4 ? 'selected' : '' }}>Incomplete</option>
                                    </select>
                                </form>
                            @endif
                        </td>

                        </div>
                        <div class="status-actions">
                            <!-- Status Badge -->
                            <span class="status-badge status-in-progress">{{ $task->completionStatus->name }}</span>
                            
                            <!-- Task Actions Dropdown -->
                            <div class="dropdown">
                                <button onclick="toggleDropdown()" class="back-button">
                                    <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                <div id="dropdown-menu" class="dropdown-menu">
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="dropdown-item">Edit Task</a>
                                    <button onclick="document.getElementById('delete-modal').classList.add('active')" class="dropdown-item danger">Delete Task</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="card-body">
                    <!-- Task Details -->
                    <div class="task-details-grid">
                        <!-- Due Date -->
                        <div class="task-field">
                            <div class="floating-label">Task : {{ $task->name }}</div>
                            <div class="task-field-label">Due Date</div>
                            <div class="task-field-value">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $task->due_date }}
                            </div>
                            <div class="task-field-hint">Due in {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}</div>
                        </div>

                        <!-- Assigned To -->
                        <div class="task-field">
                            <div class="task-field-label">Assigned To</div>
                            <button onclick="document.getElementById('user-modal').classList.add('active')" class="task-field-value" style="background: none; border: none; cursor: pointer; padding: 0; font: inherit; color: inherit; text-align: left;">
                                <div class="avatar">{{ substr($task->user->name, 0, 1) }}</div>
                                <span>{{ $task->user->name }}</span>
                            </button>
                        </div>

                        <!-- Description -->
                        <div class="task-field task-description">
                            <div class="task-field-label">Description</div>
                            <div class="task-field-value" style="display: block; margin-top: 0.5rem;">
                                {{ $task->description }}
                            </div>
                        </div>

                        <!-- Priority -->
                        <div class="task-field">
                            <div class="task-field-label">Priority</div>
                            <div class="task-field-value">
                                <span class="priority-indicator-inline priority-high-inline"></span>
                                <span>High</span>
                            </div>
                        </div>

                        <!-- Created/Updated Info -->
                        <div class="task-field">
                            <div class="task-field-label">Created</div>
                            <div class="task-field-value" style="display: block;">
                                {{ $task->created_at }}
                            </div>
                            <div class="task-field-hint">
                                Updated {{ $task->updated_at->diffForHumans() }}
                            </div>
                        </div>

                        <!-- Uploads Section -->
                        <div class="task-field task-description">
                            <div class="task-field-label">Uploads</div>
                            <div class="task-field-value" style="display: block; margin-top: 0.5rem;">
                                @if($task->uploads->count() > 0)
                                    <ul>
                                        @foreach($task->uploads as $upload)
                                            <li>
                                                <a href="{{ asset('storage/' . $upload->paths) }}" target="_blank">
                                                    {{ basename($upload->paths) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No uploads available for this task.</p>
                                @endif
                            </div>
                        </div>
                    </div>
 <!-- Child Tasks Section -->
 <div class="task-field task-description">
                            <div class="task-field-label">Child Tasks</div>
                            <div class="task-field-value" style="display: block; margin-top: 0.5rem;">
                            @if($task->childTasks->count() > 0)
    <div class="card">
        <div class="responsive-table">
            <table class="task-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Budget / Cost</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($task->childTasks as $childTask)
                        <tr class="task-row" data-id="{{ $childTask->id }}">
                            <td class="truncate">
                                <span style="margin-left: 20px;">↳ {{ $childTask->name }}</span>
                            </td>
                            <td>
                                @php
                                    $status = collect($completionStatus)->firstWhere('id', $childTask->completion_status_id)['status'] ?? 'Unknown';
                                    $statusClass = strtolower($status) === 'complete' ? 'status-complete' : 
                                                (strtolower($status) === 'in progress' ? 'status-in-progress' : 'status-incomplete');
                                @endphp
                                <span class="task-status {{ $statusClass }}">{{ $status }}</span>
                            </td>
                            <td>${{ number_format($childTask->budget, 2) }} / ${{ number_format($childTask->cost, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($childTask->due_date)->format('M d, Y') }}</td>
                            <td class="actions">
                                <a href="{{ route('tasks.showOneTask', ['id' => $childTask->id])}}">
                                    <button class="btn btn-secondary btn-sm view-task-btn" data-id="{{$childTask->id}}>
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </a>
                                <a href="{{ route('tasks.edit', $childTask->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $childTask->id }}" data-name="{{ $childTask->name }}">
                                    <i class="fas fa-trash"></i>Delete
                                </button>
                            </td>
                        </tr>

                        @if($childTask->childTasks->count() > 0)
                            @foreach($childTask->childTasks as $subTask)
                                <tr class="task-row" data-id="{{ $subTask->id }}">
                                    <td class="truncate" style="padding-left: 40px;">↳ {{ $subTask->name }}</td>
                                    <td>
                                        @php
                                            $status = collect($completionStatus)->firstWhere('id', $subTask->completion_status_id)['status'] ?? 'Unknown';
                                            $statusClass = strtolower($status) === 'complete' ? 'status-complete' : 
                                                        (strtolower($status) === 'in progress' ? 'status-in-progress' : 'status-incomplete');
                                        @endphp
                                        <span class="task-status {{ $statusClass }}">{{ $status }}</span>
                                    </td>
                                    <td>${{ number_format($subTask->budget, 2) }} / ${{ number_format($subTask->cost, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($subTask->due_date)->format('M d, Y') }}</td>
                                    <td class="actions">
                                        <a href="{{ route('tasks.show', $subTask->id) }}">
                                            <button class="btn btn-secondary btn-sm view-task-btn">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </a>
                                        <a href="{{ route('tasks.edit', $subTask->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        </a>
                                            <a href="#">
                                            <button class="btn btn-danger btn-sm delete-btn"
                                            data-id="{{ $task->id }}"
                                            data-name="{{ $task->name }}">
                                            <i class="fas fa-trash"></i>
                                            </button>
                                            </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <p>No child tasks available for this task.</p>
@endif

                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="task-actions">
                        <div class="action-buttons">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-outline-primary">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Task
                            </a>
                            
                            <form action="{{ route('task.remove', ['id' => $task->id]) }}" method="POST" style="display: inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-success">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        Mark as Complete
    </button>
</form>
                        </div>
                        <div>
                            <a href="{{ route('viewing-all-tasks') }}" class="btn btn-primary">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to All Tasks
                            </a>
                        </div>
                        <div>
                        <a href="{{ route('tasks.createChild', ['parentTaskId' => $task->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Child Task
                    </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Modal -->
        <div id="user-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header modal-header-user">
                    <div class="modal-header-title">
                        <h3>Assigned User</h3>
                        <button onclick="document.getElementById('user-modal').classList.remove('active')" class="modal-close-btn">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="user-info">
                        <div class="user-avatar-large">{{ substr($task->user->name, 0, 1) }}</div>
                        <div class="user-details">
                            <h4>{{ $task->user->name }}</h4>
                            <p>{{ $task->user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="user-meta">
                        <div class="user-meta-item">
                            <div class="task-field-label">Department</div>
                            <div>Design</div>
                        </div>
                        
                        <div class="user-meta-item">
                            <div class="task-field-label">Role</div>
                            <div>Senior Designer</div>
                        </div>
                        
                        <div class="user-meta-item">
                            <div class="task-field-label">Active Tasks</div>
                            <div>5</div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <a href="{{ route('profile.edit') }}" class="btn btn-subtle">
                            View Full Profile
                        </a>
                        <button onclick="document.getElementById('user-modal').classList.remove('active')" class="btn btn-secondary">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header modal-header-delete">
                    <div class="modal-header-title">
                        <h3>Delete Task</h3>
                        <button onclick="document.getElementById('delete-modal').classList.remove('active')" class="modal-close-btn">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <p class="delete-warning">Are you sure you want to delete this task? This action cannot be undone.</p>
                    
                    <div class="delete-actions">
                        <button onclick="document.getElementById('delete-modal').classList.remove('active')" class="btn btn-secondary">
                            Cancel
                        </button>
                        <form action="{{ route('task.remove', $task->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Delete Task
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- Delete Confirmation Modal -->
   <div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Confirm Deletion</h3>
            <button class="modal-close" id="closeDeleteModal">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete task "<span id="taskName"></span>"?</p>
            <p>This action cannot be undone.</p>
            
            <h4>Parent Task:</h4>
            <ul id="taskList"></ul>
        </div>
        <div class="modal-footer">
            <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
            <form id="deleteForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Task</button>
            </form>
        </div>
    </div>
</div>


    <script>
         // Delete confirmation modal
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const taskNameSpan = document.getElementById('taskName');
    const cancelDelete = document.getElementById('cancelDelete');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const taskList = document.getElementById('taskList');
    const taskIdsInput = document.getElementById('taskIds');
    let selectedIds=[];

    deleteButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        const parentId = button.getAttribute('data-id');
        const parentName = button.getAttribute('data-name');
        document.getElementById('taskName').textContent = parentName;
        const taskList = document.getElementById('taskList');
        const taskIdsInput = document.getElementById('taskIdsInput'); // Ensure this hidden input exists

        taskList.innerHTML = ''; // Clear previous list
        selectedIds = [parentId]; // Start with the parent task ID
        // Show loading overlay
        const loadingOverlay = document.createElement('div');
        loadingOverlay.id = 'loadingOverlay';
        loadingOverlay.innerHTML = `<div class="spinner"></div> Loading...`;
        document.body.appendChild(loadingOverlay);
        document.body.style.pointerEvents = "none"; // Disable clicks

        // Create Parent Task Checkbox
        const parentTaskItem = document.createElement('li');
        parentTaskItem.innerHTML = `
            <input type="checkbox" checked data-id="${parentId}" class="task-checkbox"> 
            ${parentName} (Parent)
        `;
        taskList.appendChild(parentTaskItem);
        console.log(selectedIds);

        // Fetch Child Tasks
        fetch(`/task/${parentId}/children`)
            .then(response => response.json())
            .then(children => {

                if (children.length > 0) {
                    const childHeading = document.createElement('h4');
                    childHeading.textContent = "Child Tasks:";
                    taskList.appendChild(childHeading);
                    children.forEach(child => {
                        const listItem = document.createElement('li');
                        listItem.innerHTML = `
                            <input type="checkbox" checked data-id="${child.id}" class="task-checkbox"> 
                            ${child.name} (Child)
                        `;
                        taskList.appendChild(listItem);
                        selectedIds.push(String(child.id));
                        console.log(selectedIds);
                    });
                } else {
                    const noChildItem = document.createElement('li');
                    noChildItem.textContent = "No child tasks associated with this task.";
                    taskList.appendChild(noChildItem);
                }
                // Enable interaction again
                document.body.style.pointerEvents = "auto";
                loadingOverlay.remove(); // Remove loading effect

                // Attach event listener AFTER checkboxes are created
                taskList.addEventListener('change', (event) => {
                    const checkbox = event.target;
                    const taskId = checkbox.getAttribute('data-id');

                    if (checkbox.checked) {
                        if (!selectedIds.includes(taskId)) {
                            selectedIds.push(taskId);
                            console.log(selectedIds);
                        }
                    } else {
                        selectedIds = selectedIds.filter(id => id !== taskId);
                        console.log(selectedIds);
                    }

                    taskIdsInput.value = selectedIds.join(','); // Update hidden input
                    console.log("Updated selected IDs:", selectedIds);
                });
            })
            .catch(error => console.error("Error fetching child tasks:", error));

        console.log("final",selectedIds);
        // Set form action
       // deleteForm.action = "{{ route('task.remove') }}" + "?id=" + selectedIds.join(',');

        // Show modal
        deleteModal.style.display = 'flex';
        setTimeout(() => {
            deleteModal.classList.add('active');
        }, 10);
    });
});
deleteForm.addEventListener("submit", function (e) {
    e.preventDefault();

        fetch("{{ route('task.remove') }}", {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ id: selectedIds }), 
    })
        .then(response => response.json())
        .then(data => {
            console.log("Success:", data);
            location.reload();
        })
        .catch(error => console.error("Error:", error));

});


    function closeDeleteDialog() {
        deleteModal.classList.remove('active');
        setTimeout(() => {
            deleteModal.style.display = 'none';
        }, 300);
    }

    cancelDelete.addEventListener('click', closeDeleteDialog);
    closeDeleteModal.addEventListener('click', closeDeleteDialog);

    deleteModal.addEventListener('click', (e) => {
        if (e.target === deleteModal) {
            closeDeleteDialog();
        }
    });
        // Toggle dropdown
        function toggleDropdown() {
            document.getElementById('dropdown-menu').classList.toggle('show');
        }
        
        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown button')) {
                var dropdowns = document.getElementsByClassName('dropdown-menu');
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>