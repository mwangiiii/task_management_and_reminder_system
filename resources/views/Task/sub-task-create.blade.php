<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Child Task</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 900px;
            padding: 30px;
            overflow: hidden;
        }
        
        h1 {
            font-size: 1.5rem;
            color: #4338ca;
            margin-bottom: 15px;
            text-align: center;
            position: relative;
        }
        
        h1::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #4338ca;
            border-radius: 3px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        
        .form-group {
            margin-bottom: 12px;
            position: relative;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #4b5563;
        }
        
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.8rem;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            border-color: #4338ca;
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1);
        }
        
        select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 8px center;
            background-repeat: no-repeat;
            background-size: 16px 12px;
            padding-right: 28px;
            appearance: none;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 15px;
        }
        
        .checkbox-wrapper input {
            width: 16px;
            height: 16px;
            accent-color: #4338ca;
        }
        
        .checkbox-wrapper label {
            margin-bottom: 0;
            font-size: 0.8rem;
        }
        
        .file-upload {
            border: 1px dashed #d1d5db;
            border-radius: 6px;
            padding: 5px;
            text-align: center;
            cursor: pointer;
            background-color: #f9fafb;
            font-size: 0.75rem;
            color: #6b7280;
        }
        
        .file-upload i {
            display: block;
            font-size: 1rem;
            margin-bottom: 2px;
            color: #4338ca;
        }
        
        .file-upload input {
            display: none;
        }
        
        .form-footer {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        
        .btn-primary {
            background: #4338ca;
            color: white;
        }
        
        .btn-primary:hover {
            background: #3730a3;
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: #1f2937;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
        /* Loader Styles */
        .loader {
            --color: #a5a5b0;
            --size: 70px;
            width: var(--size);
            height: var(--size);
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            display: none; /* Initially hidden */
        }
        .loader span {
            width: 100%;
            height: 100%;
            background-color: var(--color);
            animation: keyframes-blink 0.6s alternate infinite linear;
        }
        .loader span:nth-child(1) {
            animation-delay: 0ms;
        }
        .loader span:nth-child(2) {
            animation-delay: 200ms;
        }
        .loader span:nth-child(3) {
            animation-delay: 300ms;
        }
        .loader span:nth-child(4) {
            animation-delay: 400ms;
        }
        .loader span:nth-child(5) {
            animation-delay: 500ms;
        }
        .loader span:nth-child(6) {
            animation-delay: 600ms;
        }
        @keyframes keyframes-blink {
            0% {
                opacity: 0.3;
                transform: scale(0.5) rotate(5deg);
            }
            50% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div id="loader" class="loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="card">
        <h1>Create Child Task</h1>
        
        <form method="POST" action="{{ route('tasks.store', $parentTask->id) }}" enctype="multipart/form-data" onsubmit="return validateBudget(event)" id="taskForm">
            @csrf
            
            <div class="form-grid">
                <!-- Task Name -->
                <div class="form-group">
                    <label for="task_name">Task Name</label>
                    <input type="text" id="task_name" name="task_name" class="form-control" value="{{ old('task_name') }}" required>
                </div>
                
                <!-- Task Description -->
                <div class="form-group">
                    <label for="task_description">Description</label>
                    <input type="text" id="task_description" name="task_description" class="form-control" value="{{ old('task_description') }}">
                </div>
                
                <!-- Task Category -->
                <div class="form-group">
                    <label for="task_category">Category</label>
                    <select id="task_category" name="task_category" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('task_category') == $category->id ? 'selected' : '' }}>
                                {{ $category->type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Start Date -->
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="datetime-local" id="start_date" name="task_start_date" class="form-control" value="{{ old('task_start_date') }}">
                </div>
                
                <!-- Due Date -->
                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="datetime-local" id="due_date" name="task_due_date" class="form-control" value="{{ old('task_due_date') }}">
                </div>
                
                <!-- Alert Section -->
                <div class="form-group">
                    <label for="task_alert">Alert</label>
                    <div id="alerts-container" class="space-y-2">
                        <!-- Alerts will be dynamically added here -->
                    </div>
                    <button type="button" onclick="openModal()" class="btn btn-secondary w-full mt-2">
                        <i class="fas fa-bell"></i> Add Alert
                    </button>
                </div>
                
                <!-- Task Cost -->
                <div class="form-group">
                    <label for="task_cost">Cost</label>
                    <input type="number" id="task_cost" name="task_cost" min="0" step="0.01" class="form-control" value=0>
                </div>
                
                <!-- Task Completion Status -->
                <div class="form-group">
                    <label for="task_completion_status">Status</label>
                    <select id="task_completion_status" name="task_completion_status" class="form-control" required>
                        @foreach($completions as $completion)
                            <option value="{{ $completion->id }}" {{ old('task_completion_status') == $completion->id ? 'selected' : '' }}>
                                {{ $completion->status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Task Recurrency -->
                <div class="form-group">
                    <label for="task_recurrency">Recurrency</label>
                    <select id="task_recurrency" name="task_recurrency" class="form-control" required>
                        @foreach($recurrencies as $recurrency)
                            <option value="{{ $recurrency->id }}" {{ old('task_recurrency') == $recurrency->id ? 'selected' : '' }}>
                                {{ $recurrency->frequency }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Parent Task Field (Automatically Embedded) -->
                <input type="hidden" name="parent_task_id" value="{{ $parentTask->id }}">
                <input type="hidden" id="parent_budget" value="{{ $parentTask->budget }}">
                <input type="hidden" id="new_budget" value="{{ session('new_budget') }}">

                <div class="form-group">
                    <label for="parent_task">Parent Task</label>
                    <input type="text" id="parent_task" class="form-control" value="{{ $parentTask->name }}" disabled>
                </div>
                
                <!-- Task Uploads -->
                <div class="form-group">
                    <label for="task_uploads">Uploads</label>
                    <label for="task_uploads" class="file-upload">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Click to upload files</span>
                        <input type="file" id="task_uploads" name="task_uploads[]" multiple>
                    </label>
                </div>
                <div class="form-group">
    <label for="parent_currency">Currency</label>
    <input type="text" id="parent_currency" class="form-control" name="currency" value="{{ $parentTask->currency }}" readonly>
</div>
            </div>
            
            <!-- Task Repeat Checkbox -->
            <div class="checkbox-wrapper">
                <input type="checkbox" id="task_repeat" name="task_repeat" value="1" {{ old('task_repeat') ? 'checked' : '' }}>
                <label for="task_repeat">Repeat Task</label>
            </div>
            
            <!-- Form Footer -->
            <div class="form-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Child Task
                </button>
                <a href="{{ route('viewing-all-tasks') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Go Back
                </a>
            </div>
        </form>
    </div>

    <!-- Modal for Adding Alerts -->
    <div id="alertModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Add Alert</h2>
            <input type="datetime-local" id="newAlert" class="form-control">
            <button type="button" onclick="addAlert()" class="btn btn-primary mt-4">
                <i class="fas fa-plus"></i> Add Alert
            </button>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeErrorModal()">&times;</span>
            <h2>Budget Exceeded</h2>
            <p id="errorMessage"></p>
            
            <form action="{{ route('user-updating-budget', ['parentTaskId' => $parentTask->id]) }}" method="POST">
                @csrf <!-- Add CSRF token for security -->
                
                <div class="form-group">
                    <label for="additionalBudget">Additional Budget Required:</label>
                    <input type="number" id="additionalBudget" min="0" name="additionalBudget" step="0.01" class="form-control" placeholder="Enter additional amount">
                </div>
                
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Add to Budget
                    </button>
                    
                    <button type="button" onclick="closeErrorModal()" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
    // Open Modal for Alerts
function openModal() {
    document.getElementById('alertModal').style.display = 'block';
}

// Close Modal for Alerts
function closeModal() {
    document.getElementById('alertModal').style.display = 'none';
}

// Add Alert
function addAlert() {
    const newAlert = document.getElementById('newAlert').value;
    if (newAlert) {
        const alertsContainer = document.getElementById('alerts-container');
        const newAlertInput = document.createElement('input');
        newAlertInput.type = 'datetime-local';
        newAlertInput.name = 'task_alerts[]';
        newAlertInput.value = newAlert;
        newAlertInput.className = 'form-control w-full mt-2';
        alertsContainer.appendChild(newAlertInput);
        closeModal();
    }
}

// Open Error Modal
function openErrorModal(errorMessage) {
    document.getElementById('errorMessage').textContent = errorMessage;
    document.getElementById('errorModal').style.display = 'block';
}

// Close Error Modal
function closeErrorModal() {
    document.getElementById('errorModal').style.display = 'none';
}

// Update Parent Task Budget
function updateBudget() {
    // Get the additional budget value from the input field
    const additionalBudget = parseFloat(document.getElementById('additionalBudget').value);

    // Validate the additional budget input
    if (isNaN(additionalBudget)) {
        alert("Please enter a valid amount.");
        return;
    }

    // Get the parent task ID from the hidden input field
    const parentTaskId = document.querySelector('input[name="parent_task_id"]').value;

    // Construct the URL for the API endpoint
    const url = `/tasks/${parentTaskId}/update-budget`;

    // Send a POST request using Fetch API
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ additionalBudget })
    })
    .then(response => {
        if (response.redirected) {
            // If the response is a redirect, reload the page to handle the session flash messages
            window.location.href = response.url;
        } else {
            throw new Error("Network response was not ok.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorPopup("An error occurred. Please try again.");
    });
}

// Function to check for session messages and show popups
function checkSessionMessages() {
    const successMessage = "{{ session('success') }}";
    const errorMessage = "{{ session('error') }}";
    const newBudget = parseFloat(document.getElementById('new_budget').value);

    if (successMessage) {
        showConfirmationPopup(`Budget successfully updated to $${newBudget.toFixed(2)}!`);
    }

    if (errorMessage) {
        showErrorPopup(errorMessage);
    }
}

// Validate Budget
function validateBudget(event) {
    event.preventDefault(); // Prevent form submission

    const taskCost = parseFloat(document.getElementById('task_cost').value);
    const parentBudget = parseFloat(document.getElementById('parent_budget').value);

    if (taskCost > parentBudget) {
        const errorMessage = `The total cost of child tasks exceeds the parent task budget. Remaining budget: $${parentBudget}.`;
        openErrorModal(errorMessage);
    } else {
        // Show the loader
        document.getElementById('loader').style.display = 'grid';

        // Submit the form using Fetch API
        fetch(event.target.action, {
            method: event.target.method,
            body: new FormData(event.target),
            headers: {
                'Accept': 'application/json' // Ensure server knows we expect JSON
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            console.log("Server Response:", data); // Debugging step
            if (data.task && data.task.id) {
                window.location.href = `/view/tasks/${data.task.id}`;
            } else if (data.id) {
                window.location.href = `/view/tasks/${data.id}`;
            } else {
                console.error("Task ID is missing in the response.");
                // Hide the loader if there's an error
                document.getElementById('loader').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Hide the loader if there's an error
            document.getElementById('loader').style.display = 'none';
            alert("An error occurred while creating the task. Please try again.");
        });
    }
}

// File Input Enhancement
document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.getElementById('task_uploads');
    const fileLabel = document.querySelector('.file-upload span');

    fileInput.addEventListener('change', function () {
        fileLabel.textContent = fileInput.files.length > 0
            ? `${fileInput.files.length} file(s) selected`
            : 'Click to upload files';
    });

    // DateTime Constraints
    const now = new Date().toISOString().slice(0, 16);
    const startDateInput = document.getElementById("start_date");
    const dueDateInput = document.getElementById("due_date");

    startDateInput.min = now;

    startDateInput.addEventListener("change", function () {
        dueDateInput.min = startDateInput.value;
    });

    // Check for session messages when the page loads
    checkSessionMessages();
});

</script>
</body>
</html>