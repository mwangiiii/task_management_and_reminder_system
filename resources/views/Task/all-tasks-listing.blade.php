<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Task Management</h1>
            <p>Organize and track your tasks efficiently</p>
        </div>
        

        <div class="add-task-btn">
            <a href="{{ route('creating-form') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Task
            </a>
        </div>
        
        <div class="filter-sort-controls">
    <div class="filter-controls">
    <form action="{{ route('tasks.filter') }}" method="GET">
        <label for="filterStatus">Filter by Status:</label>
        <select id="filterStatus" name="status">
    <option value="all">All</option>
    @foreach($completionStatus as $status)
        <option value="{{ $status->id }}">{{ $status->status }}</option>
    @endforeach
</select>

        <label for="filterBudget">Filter by Budget (≤):</label>
        <input type="number" id="filterBudget" name="max_budget" placeholder="Enter max budget">

        <label for="filterDueDate">Filter by Due Date (≤):</label>
        <input type="date" id="filterDueDate" name="due_date">

        <button type="submit" class="btn btn-primary">Apply Filters</button>
    </form>
    

        <a href="{{ route('showing-completed-tasks') }}" class="btn btn-secondary">
            <i class="fas fa-trash-restore"></i> Show Completed Tasks
        </a>
    </div>

    <div class="sort-controls">
        <button id="sortByName">Sort by Name</button>
        <button id="sortByBudget">Sort by Budget</button>
        

        <a href="{{ route('tasks.searchByName') }}"> 
        <button id="sortByDueDate">Sort by Due name</button>
    </a>
        
    </div>
</div>

        @if (session('success'))
            <div id="successToast" class="toast">
                <i class="fas fa-check-circle"></i>
                <span class="toast-message">{{ session('success') }}</span>
            </div>
        @endif
        
        <div class="card">
            <div class="responsive-table">
            <table class="task-table">
    <thead>
        <tr>
            <th>Name:</th>
            <th>Status:</th>
            <th>Budget: / Cost</th>
            <th>Due Date: </th>
            <th>Actions: </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
            <tr class="task-row" data-id="{{ $task->id }}">
                <td class="truncate">
                    @if ($task->parent)
                        <span style="margin-left: 20px;">↳ {{ $task->name }}</span>
                    @else
                        {{ $task->name }}
                    @endif
                </td>
                <td>
                    @php
                        $status = collect($completionStatus)->firstWhere('id', $task->completion_status_id)['status'] ?? 'Unknown';
                        $statusClass = strtolower($status) === 'complete' ? 'status-complete' : 
                                      (strtolower($status) === 'in progress' ? 'status-in-progress' : 'status-incomplete');
                    @endphp
                    <span class="task-status {{ $statusClass }}">{{ $status }}</span>
                </td>
                <td>${{ number_format($task->budget, 2) }} / ${{ number_format($task->cost, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</td>
                <td class="actions">
                    <a href="{{ route('tasks.showOneTask', ['id' => $task->id]) }}">
                        <button class="btn btn-secondary btn-sm view-task-btn" data-id="{{ $task->id }}">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </a>
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $task->id }}" data-name="{{ $task->name }}">
        <i class="fas fa-trash"></i>
    </button>

                    
                </td>
            </tr>
            @foreach (optional($task->children) as $child)
                <tr class="task-row" data-id="{{ $child->id }}">
                    <td class="truncate" style="padding-left: 40px;">↳ {{ $child->name }}</td>
                    <td>
                        @php
                            $status = collect($completionStatus)->firstWhere('id', $child->completion_status_id)['status'] ?? 'Unknown';
                            $statusClass = strtolower($status) === 'complete' ? 'status-complete' : 
                                          (strtolower($status) === 'in progress' ? 'status-in-progress' : 'status-incomplete');
                        @endphp
                        <span class="task-status {{ $statusClass }}">{{ $status }}</span>
                    </td>
                    <td>${{ number_format($child->budget, 2) }} / ${{ number_format($child->cost, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($child->due_date)->format('M d, Y') }}</td>
                    <td class="actions">
                        <a href="{{ route('tasks.showOneTask', ['id' => $child->id]) }}">
                            <button class="btn btn-secondary btn-sm view-task-btn" data-id="{{ $child->id }}">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </a>
                        <a href="{{ route('tasks.edit', $child->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('task.remove', ['id' => $child->id]) }}">
                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $child->id }}" data-name="{{ $child->name }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
            </div>
        </div>
    </div>

    <!-- Task Details Modal -->
    <div id="taskDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Task Details</h3>
                <button class="modal-close" id="closeTaskDetails">&times;</button>
            </div>
            <div class="modal-body" id="taskDetailsBody">
                <div class="detail-row">
                    <div class="detail-label">Name:</div>
                    <div class="detail-value" id="detailName"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Description:</div>
                    <div class="detail-value" id="detailDescription"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value" id="detailStatus"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Budget:</div>
                    <div class="detail-value" id="detailBudget"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Cost:</div>
                    <div class="detail-value" id="detailCost"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Start Date:</div>
                    <div class="detail-value" id="detailStartDate"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Due Date:</div>
                    <div class="detail-value" id="detailDueDate"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Parent Task:</div>
                    <div class="detail-value" id="detailParent"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="closeTaskDetailsBtn" class="btn btn-secondary">Close</button>
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
        </div>
        <div class="modal-footer">
            <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
            
            <!-- Properly configured form with dynamic action and DELETE method -->
            <form id="deleteForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Task</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Task details functionality
    const viewButtons = document.querySelectorAll('.view-task-btn');
    const taskRows = document.querySelectorAll('.task-row');
    const taskDetailsModal = document.getElementById('taskDetailsModal');
    const closeTaskDetails = document.getElementById('closeTaskDetails');
    const closeTaskDetailsBtn = document.getElementById('closeTaskDetailsBtn');

    // Task data lookup function
    function getTaskData(taskId) {
        for (let i = 0; i < tasks.length; i++) {
            if (tasks[i].id == taskId) {
                const task = tasks[i];
                const status = completionStatus.find(s => s.id === task.completion_status_id)?.status || 'Unknown';
                const parent = tasks.find(t => t.id === task.parent_id)?.name || 'No parent';
                const children = tasks.filter(t => t.parent_id === task.id).map(t => t.name).join(', ') || 'No children';

                return {
                    ...task,
                    status: status,
                    parent: parent,
                    children: children,
                };
            }
        }
        return null;
    }

    function showTaskDetails(taskId) {
        const task = getTaskData(taskId);
        if (!task) return;

        document.getElementById('detailName').textContent = task.name;
        document.getElementById('detailDescription').textContent = task.description;
        document.getElementById('detailStatus').textContent = task.status;
        document.getElementById('detailBudget').textContent = '$' + parseFloat(task.budget).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('detailCost').textContent = '$' + parseFloat(task.cost).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('detailStartDate').textContent = new Date(task.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        document.getElementById('detailDueDate').textContent = new Date(task.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        document.getElementById('detailParent').textContent = task.parent;
        document.getElementById('detailChildren').textContent = task.children;

        taskDetailsModal.style.display = 'flex';
        setTimeout(() => {
            taskDetailsModal.classList.add('active');
        }, 10);
    }

    viewButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const taskId = button.getAttribute('data-id');
            showTaskDetails(taskId);
        });
    });

    taskRows.forEach(row => {
        row.addEventListener('click', () => {
            const taskId = row.getAttribute('data-id');
            showTaskDetails(taskId);
        });
    });

    function closeTaskModal() {
        taskDetailsModal.classList.remove('active');
        setTimeout(() => {
            taskDetailsModal.style.display = 'none';
        }, 300);
    }

    closeTaskDetails.addEventListener('click', closeTaskModal);
    closeTaskDetailsBtn.addEventListener('click', closeTaskModal);
    taskDetailsModal.addEventListener('click', (e) => {
        if (e.target === taskDetailsModal) {
            closeTaskModal();
        }
    });

    // Delete confirmation modal
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const taskNameSpan = document.getElementById('taskName');
    const cancelDelete = document.getElementById('cancelDelete');
    const closeDeleteModal = document.getElementById('closeDeleteModal');

    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const taskId = button.getAttribute('data-id');
            const taskName = button.getAttribute('data-name');
            deleteForm.action = "{{ route('task.remove', '') }}/" + taskId;
            taskNameSpan.textContent = taskName;
            deleteModal.style.display = 'flex';
            setTimeout(() => {
                deleteModal.classList.add('active');
            }, 10);
        });
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

    // Toast notification
    const successToast = document.getElementById('successToast');
    if (successToast) {
        successToast.classList.add('active');
        setTimeout(() => {
            successToast.classList.remove('active');
        }, 4000);
    }

    // Add Child Task Button Functionality
    const addChildButtons = document.querySelectorAll('.add-child-btn');
    addChildButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const taskId = button.getAttribute('data-id');
            window.location.href = "{{ route('tasks.createChild', '') }}" + taskId;
        });
    });

    // Function to filter tasks based on status, budget, or due date
    function filterTasks(filterBy, value) {
        const tasks = document.querySelectorAll('.task-row');
        const showDeleted = document.getElementById('filterDeleted').checked;

        tasks.forEach(task => {
            const taskStatus = task.querySelector('.task-status').textContent.toLowerCase();
            const taskBudget = parseFloat(task.querySelector('td:nth-child(3)').textContent.replace(/[^0-9.]/g, ''));
            const taskDueDate = new Date(task.querySelector('td:nth-child(4)').textContent);
            const isDeleted = task.getAttribute('data-deleted') === '1';

            let shouldDisplay = true;

            // Filter by status
            if (filterBy === 'status') {
                if (value === 'all') {
                    shouldDisplay = !isDeleted;
                } else {
                    shouldDisplay = taskStatus === value.toLowerCase() && !isDeleted;
                }
            }

            // Filter by budget
            if (filterBy === 'budget') {
                shouldDisplay = taskBudget <= parseFloat(value) && !isDeleted;
            }

            // Filter by due date
            if (filterBy === 'dueDate') {
                const filterDate = new Date(value);
                shouldDisplay = taskDueDate <= filterDate && !isDeleted;
            }

            // Override for showing deleted tasks
            if (showDeleted) {
                shouldDisplay = isDeleted;
            }

            task.style.display = shouldDisplay ? '' : 'none';
        });
    }

    // Event listeners for filter controls
    document.getElementById('filterStatus').addEventListener('change', (e) => {
        filterTasks('status', e.target.value);
    });

    document.getElementById('filterBudget').addEventListener('change', (e) => {
        filterTasks('budget', e.target.value);
    });

    document.getElementById('filterDueDate').addEventListener('change', (e) => {
        filterTasks('dueDate', e.target.value);
    });

    document.getElementById('filterDeleted').addEventListener('change', (e) => {
        const filterStatus = document.getElementById('filterStatus').value;
        filterTasks('status', filterStatus);
    });

    // Sorting functionality
    function sortTasks(sortBy, order = 'asc') {
        const tbody = document.querySelector('.task-table tbody');
        const tasks = Array.from(tbody.querySelectorAll('.task-row'));

        tasks.sort((a, b) => {
            let aValue, bValue;

            if (sortBy === 'name') {
                aValue = a.querySelector('td:nth-child(1)').textContent.toLowerCase();
                bValue = b.querySelector('td:nth-child(1)').textContent.toLowerCase();
            } else if (sortBy === 'budget') {
                aValue = parseFloat(a.querySelector('td:nth-child(3)').textContent.replace(/[^0-9.]/g, ''));
                bValue = parseFloat(b.querySelector('td:nth-child(3)').textContent.replace(/[^0-9.]/g, ''));
            } else if (sortBy === 'dueDate') {
                aValue = new Date(a.querySelector('td:nth-child(4)').textContent);
                bValue = new Date(b.querySelector('td:nth-child(4)').textContent);
            }

            if (order === 'asc') {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });

        tbody.innerHTML = '';
        tasks.forEach(task => tbody.appendChild(task));
    }

    document.getElementById('sortByName').addEventListener('click', () => {
        sortTasks('name');
    });

    document.getElementById('sortByBudget').addEventListener('click', () => {
        sortTasks('budget');
    });

    document.getElementById('sortByDueDate').addEventListener('click', () => {
        sortTasks('dueDate');
    });
</script>
</body>
</html>