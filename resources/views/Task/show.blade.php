<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
    <style>
        /* Your existing CSS styles remain unchanged */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #f5f0ff 100%);
            color: #333;
            min-height: 100vh;
            line-height: 1.6;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(255 255 255 / 0.1)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        /* Animations and transitions */
        .task-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .task-card:hover {
            transform: translateY(-5px);
        }
        
        .status-badge {
            transition: all 0.3s ease;
        }
        
        .status-badge:hover {
            transform: scale(1.05);
        }
        
        .floating-label {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }
        
        .task-field:hover .floating-label {
            opacity: 1;
            transform: translateY(0);
        }
        
        .task-actions button, .task-actions a {
            transition: all 0.2s ease;
        }
        
        .task-actions button:hover, .task-actions a:hover {
            transform: translateY(-2px);
        }
        
        /* Modal styles */
        .modal {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .modal.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-content {
            transform: translateY(20px);
            transition: all 0.3s ease;
            background-color: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 28rem;
            width: 100%;
            margin: 0 1rem;
        }
        
        .modal.active .modal-content {
            transform: translateY(0);
        }
        
        /* Priority indicators */
        .priority-indicator {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid white;
        }
        
        .priority-high {
            background-color: #ef4444;
        }
        
        .priority-medium {
            background-color: #f59e0b;
        }
        
        .priority-low {
            background-color: #10b981;
        }
        
        /* Layout */
        .container {
            min-height: 100vh;
            padding: 3rem 1rem;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(147, 51, 234, 0.05) 100%);
        }
        
        .content-wrapper {
            max-width: 64rem;
            margin: 0 auto;
        }
        
        /* Toast */
        #success-toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background-color: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            z-index: 50;
            animation: fadeInDown 0.5s ease forwards;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-1rem);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Card and content */
        .task-card {
            backdrop-filter: blur(16px);
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(to right, #4f46e5, #9333ea);
            padding: 1.5rem;
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
            border-radius: 0.375rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
            color: #374151;
            transition: background-color 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f3f4f6;
        }
        
        .dropdown-item.danger {
            color: #dc2626;
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
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            transition: transform 0.3s ease;
        }
        
        .task-field:hover {
            transform: translateY(-2px);
        }
        
        .task-field-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }
        
        .task-field-value {
            display: flex;
            align-items: center;
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
        }
        
        .task-field-value svg {
            margin-right: 0.5rem;
            flex-shrink: 0;
        }
        
        .task-field-value svg path {
            stroke: #6366f1;
        }
        
        .task-field-hint {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        .task-field-hint.overdue {
            color: #ef4444;
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
            background-color: #ef4444;
        }
        
        .priority-medium-inline {
            background-color: #f59e0b;
        }
        
        .priority-low-inline {
            background-color: #10b981;
        }
        
        .avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 9999px;
            background-color: #6366f1;
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
            border-top: 1px solid #e5e7eb;
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
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        
        .btn svg {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
        }
        
        .btn-outline-primary {
            border: 1px solid #6366f1;
            color: #6366f1;
        }
        
        .btn-outline-primary:hover {
            background-color: rgba(99, 102, 241, 0.05);
        }
        
        .btn-outline-success {
            border: 1px solid #10b981;
            color: #10b981;
        }
        
        .btn-outline-success:hover {
            background-color: rgba(16, 185, 129, 0.05);
        }
        
        .btn-primary {
            background: linear-gradient(to right, #4f46e5, #9333ea);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.25);
        }
        
        .btn-primary:hover {
            background: linear-gradient(to right, #4338ca, #7e22ce);
        }
        
        /* Modal specific styles */
        .modal-header {
            padding: 1.5rem;
            color: white;
        }
        
        .modal-header-user {
            background: linear-gradient(to right, #4f46e5, #9333ea);
        }
        
        .modal-header-delete {
            background-color: #dc2626;
        }
        
        .modal-header-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .modal-header-title h3 {
            font-size: 1.25rem;
            font-weight: 700;
        }
        
        .modal-close-btn {
            padding: 0.25rem;
            border-radius: 9999px;
            transition: background-color 0.2s;
            background: none;
            border: none;
            cursor: pointer;
            color: white;
        }
        
        .modal-close-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .user-avatar-large {
            width: 4rem;
            height: 4rem;
            border-radius: 9999px;
            background-color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .user-details h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }
        
        .user-details p {
            color: #6b7280;
        }
        
        .user-meta {
            margin-bottom: 2rem;
        }
        
        .user-meta-item {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .user-meta-item:first-child {
            margin-top: 0;
            padding-top: 0;
            border-top: none;
        }
        
        .modal-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }
        
        .btn-subtle {
            color: #6366f1;
            transition: background-color 0.2s;
        }
        
        .btn-subtle:hover {
            background-color: rgba(99, 102, 241, 0.05);
        }
        
        .btn-secondary {
            background-color: #e5e7eb;
            color: #374151;
        }
        
        .btn-secondary:hover {
            background-color: #d1d5db;
        }
        
        .btn-danger {
            background-color: #dc2626;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #b91c1c;
        }
        
        .delete-warning {
            color: #4b5563;
            margin-bottom: 1.5rem;
        }
        
        .delete-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
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
                            <div class="floating-label">ID: {{ $task->id }}</div>
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

    <script>
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