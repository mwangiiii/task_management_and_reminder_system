<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>
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
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
            text-align: center;
        }

        .modal-content .close {
            float: right;
            cursor: pointer;
        }

        .modal-content button {
            margin: 10px 5px;
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
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .card {
                padding: 20px;
            }
        }

        /* From Uiverse.io by cosnametv */ 
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
    </style>
</head>
<body>
    <div class="card">
        <h1>{{ isset($parentTask) ? 'Create Child Task' : 'Create New Task' }}</h1>
        
        <form method="POST" action="{{ isset($parentTask) ? route('tasks.storeChild', $parentTask->id) : route('tasks.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="task_name">Task Name</label>
                    <input type="text" id="task_name" name="task_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="task_description">Description</label>
                    <input type="text" id="task_description" name="task_description" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="task_category">Category</label>
                    <select id="task_category" name="task_category" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->type }}</option>
                        @endforeach
                        <option value="add_new">+ Add New Category</option>
                    </select>
                    <div id="newCategoryInput" style="display: none; margin-top: 10px;">
                        <input type="text" id="newCategoryName" class="form-control" placeholder="Enter new category">
                        <button type="button" id="addCategoryBtn" class="btn btn-success btn-sm mt-2">Add</button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="datetime-local" id="start_date" name="task_start_date" class="start_date form-control">
                </div>
                
                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="datetime-local" id="due_date" name="task_due_date" class="form-control">
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
                
                <div class="form-group">
                    <label for="task_cost">Cost</label>
                    <input type="number" id="task_cost" name="task_cost" min="0" step="0.01" class="form-control" value=0>
                </div>

                
                <div class="form-group" id="budget_container" style="{{ isset($parentTask) ? 'display: none;' : 'display: block;' }}">
                    <label for="budget">Budget</label>
                    <input type="number" id="budget" name="budget" min="0" step="0.01" class="form-control" value=0>
                </div>
                
                <div class="form-group">
                    <label for="task_completion_status">Status</label>
                    <select id="task_completion_status" name="task_completion_status" class="form-control" required>
                        <option value="{{ $default_completion_status_in_view->id }}" selected>
                            {{ $default_completion_status_in_view->status }}
                        </option>
                        @foreach($other_completion_status_in_view as $completion)                         
                            <option value="{{ $completion->id }}">{{ $completion->status }}</option>
                        @endforeach
                    </select>

                </div>
                
                <div class="form-group">
                    <label for="task_recurrency">Recurrency</label>
                    <select id="task_recurrency" name="task_recurrency" class="form-control" required>
                        @foreach($recurrencies as $recurrency)
                            <option value="{{ $recurrency->id }}">{{ $recurrency->frequency }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="task_uploads">Uploads</label>
                    <label for="task_uploads" class="file-upload">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Click to upload files</span>
                        <input type="file" id="task_uploads" name="task_uploads[]" multiple>
                    </label>
                </div>
                <div class="form-group">
                    <label for="currency">Choose Currency</label>
                    <select id="currency" name="currency" class="form-control" required>
                        <option value="KES">Kenyan Shilling (KES)</option>
                        <option value="USD">US Dollar (USD)</option>
                        <option value="EUR">Euro (EUR)</option>
                        <option value="GBP">British Pound (GBP)</option>
                        <option value="NGN">Nigerian Naira (NGN)</option>
                    </select>
                </div>
            </div>
            
            <div class="checkbox-wrapper">
                <input type="checkbox" id="task_repeat" name="task_repeat" value="1">
                <label for="task_repeat">Repeat Task</label>
            </div>
            
            <div class="form-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ isset($parentTask) ? 'Create Child Task' : 'Create Task' }}
                </button>

                <a href="{{ route('viewing-all-tasks') }}">
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                        <i class="fas fa-arrow-left"></i> Go Back
                    </button>
                </a>
            </div>
        </form>
    </div>

    <!-- Modal for Adding Alerts -->
    <div id="alertModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Add Alert</h2>
            <input type="datetime-local" id="newAlert" class="start_date form-control">
            <button type="button" onclick="addAlert()" class="btn btn-primary mt-4">
                <i class="fas fa-plus"></i> Add Alert
            </button>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function () {
    $('#task_recurrency').change(function () {
        if ($(this).val() === 'add_new_r') {
            $('#newRecurrencyInput').show(); 
        } else {
            $('#newRecurrencyInput').hide(); 
        }
    });

    $('#addRecurrencyBtn').click(function () {
        var recurrencyName = $('#newRecurrencyName').val().trim();

        if (recurrencyName === '') {
            alert('Please enter a recurrency name.');
            return;
        }

        $.ajax({
            url: "{{ route('recurrency.store') }}",
            type: "POST",
            data: {
                frequency: recurrencyName, 
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
            $('#task_recurrency option[value="add_new_r"]').before(
                $('<option>', {
                    value: response.id,
                    text: response.frequency, 
                    selected: true
                })
            );

                $('#newRecurrencyInput').hide();
                $('#newRecurrencyName').val('');
            },
            error: function (xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });
});


</script>
<script>
$(document).ready(function () {
    $('#task_category').change(function () {
        if ($(this).val() === 'add_new') {
            $('#newCategoryInput').show();  
        } else {
            $('#newCategoryInput').hide(); 
        }
    });

    $('#addCategoryBtn').click(function () {
        var categoryName = $('#newCategoryName').val().trim();

        if (categoryName === '') {
            alert('Please enter a category name.');
            return;
        }

        $.ajax({
            url: "{{ route('categories.store') }}",
            type: "POST",
            data: {
                type: categoryName, 
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                $('#task_category option[value="add_new"]').before(
                    $('<option>', {
                        value: response.id,
                        text: response.type,
                        selected: true
                    })
                );
                $('#newCategoryInput').hide();
                $('#newCategoryName').val('');
            },
            error: function (xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });
});


</script>

    <!-- Offer Modal for Due Date Alert -->
    <div id="offerModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeOfferModal()">&times;</span>
            <p id="offerModalMessage"></p>
            <button id="offerModalYes" class="btn btn-primary">Yes</button>
            <button id="offerModalNo" class="btn btn-secondary">No</button>
        </div>
    </div>

    <!-- Loader -->
    <div id="loader" class="loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    let startDateInputs = document.getElementsByClassName("start_date");

    function setMinDateTime() {
        let now = new Date();
        let year = now.getFullYear();
        let month = String(now.getMonth() + 1).padStart(2, "0");
        let day = String(now.getDate()).padStart(2, "0");
        let hours = String(now.getHours()).padStart(2, "0");
        let minutes = String(now.getMinutes()).padStart(2, "0");

        let minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

        // Loop through all elements and set min attribute
        for (let input of startDateInputs) {
            input.min = minDateTime;
        }
    }

    setMinDateTime();
    
    document.getElementById('taskForm').addEventListener('submit', function (event) {
            handleGoBack(event);
        // Show loading overlay
        document.getElementById('loadingOverlay').style.display = 'flex';

        // Disable button to prevent multiple submissions
        document.getElementById('submitButton').disabled = true;
    });
});
            function handleGoBack(event) {
        event.preventDefault(); // Prevent default navigation

        // Show loading overlay
        document.getElementById('loadingOverlay').style.display = 'flex';

        // Redirect to the route after a short delay to show the effect
        setTimeout(() => {
            window.location.href = "{{ route('viewing-all-tasks') }}";
        }, 500);
    }
        // Open Modal
        function openModal() {
            document.getElementById('alertModal').style.display = 'block';
        }

        // Close Modal
        function closeModal() {
            document.getElementById('alertModal').style.display = 'none';
        }

        // Open Offer Modal for Due Date Alert
        function openOfferModal(dueDateTime) {
            const offerModal = document.getElementById('offerModal');
            const offerModalMessage = document.getElementById('offerModalMessage');
            
            // Set the message in the modal
            offerModalMessage.textContent = `Would you like to add an alert for the task due date (${formatDateTime(dueDateTime)})?`;
            
            // Set up the "Yes" button to add the due date alert
            const yesButton = document.getElementById('offerModalYes');
            yesButton.onclick = function() {
                addDueDateAlert(dueDateTime);
                closeOfferModal();
            };
            
            // Set up the "No" button to simply close the modal
            const noButton = document.getElementById('offerModalNo');
            noButton.onclick = closeOfferModal;
            
            // Display the modal
            offerModal.style.display = 'block';
        }

        // Close Offer Modal
        function closeOfferModal() {
            document.getElementById('offerModal').style.display = 'none';
        }

        // Add Due Date Alert
        function addDueDateAlert(dueDateTime) {
            const alertsContainer = document.getElementById('alerts-container');
            
            // Create alert item container
            const alertElement = document.createElement('div');
            alertElement.className = 'alert-item bg-light p-2 rounded border d-flex justify-content-between align-items-center mt-2';
            
            alertElement.innerHTML = `
                <div>
                    <span class="badge bg-danger">Due Time</span>
                    <span>Alert at ${formatDateTime(dueDateTime)}</span>
                </div>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeAlert(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add hidden input for form submission
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'task_alerts[]';
            
            // Format the date-time value as Y-m-d\TH:i
            const formattedDateTime = formatDateTimeForSubmission(dueDateTime);
            hiddenInput.value = formattedDateTime;
            
            alertElement.appendChild(hiddenInput);
            alertsContainer.appendChild(alertElement);
            
            // Schedule notification
            const now = new Date();
            const timeUntilAlert = dueDateTime.getTime() - now.getTime();
            if (timeUntilAlert > 0) {
                setTimeout(() => {
                    const taskNameElement = document.querySelector('input[name="task_name"]');
                    const taskName = taskNameElement ? taskNameElement.value : 'Your task';
                    
                    if (Notification.permission === "granted") {
                        showNotification(taskName, dueDateTime, "Task Due Now");
                    } else if (Notification.permission !== "denied") {
                        Notification.requestPermission().then(permission => {
                            if (permission === "granted") {
                                showNotification(taskName, dueDateTime, "Task Due Now");
                            }
                        });
                    }
                }, timeUntilAlert);
            }
        }

        // Add custom alert from modal
        function addAlert() {
            const newAlert = document.getElementById('newAlert').value;
            const dueDateInput = document.getElementById('due_date');
            
            if (newAlert) {
                const alertDateTime = new Date(newAlert);
                const dueDateTime = dueDateInput.value ? new Date(dueDateInput.value) : null;
                
                // Check if alert time is after due date and confirm with user
                if (dueDateTime && alertDateTime > dueDateTime) {
                    const confirmPost = confirm("This alert is set after the task due date. Do you want to continue?");
                    if (!confirmPost) {
                        return; // User canceled, exit the function
                    }
                }
                
                const alertsContainer = document.getElementById('alerts-container');
                
                // Create alert item container
                const alertElement = document.createElement('div');
                alertElement.className = 'alert-item bg-light p-2 rounded border d-flex justify-content-between align-items-center mt-2';
                
                // Determine if this is a post-due alert
                const isPostDue = dueDateTime && alertDateTime > dueDateTime;
                const badgeClass = isPostDue ? "bg-warning" : "bg-primary";
                const badgeText = isPostDue ? "Post-Due" : "Custom";
                
                alertElement.innerHTML = `
                    <div>
                        <span class="badge ${badgeClass}">${badgeText}</span>
                        <span>Alert at ${formatDateTime(alertDateTime)}</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeAlert(this)">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                // Add hidden input for form submission
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'task_alerts[]';
                
                // Format the date-time value as Y-m-d\TH:i
                const formattedDateTime = formatDateTimeForSubmission(alertDateTime);
                hiddenInput.value = formattedDateTime;
                
                alertElement.appendChild(hiddenInput);
                alertsContainer.appendChild(alertElement);
                closeModal();
                
                // Schedule the notification
                const now = new Date();
                const timeUntilAlert = alertDateTime.getTime() - now.getTime();
                if (timeUntilAlert > 0) {
                    scheduleCustomNotification(alertDateTime, isPostDue);
                }
            }
        }

        // Remove an alert
        function removeAlert(button) {
            const alertItem = button.closest('.alert-item');
            if (alertItem) {
                alertItem.remove();
            }
        }

        // Schedule a custom notification
        function scheduleCustomNotification(alertDateTime, isPostDue) {
            const now = new Date();
            const timeUntilAlert = alertDateTime.getTime() - now.getTime();
            
            if (timeUntilAlert > 0) {
                setTimeout(() => {
                    const taskNameElement = document.querySelector('input[name="task_name"]');
                    const taskName = taskNameElement ? taskNameElement.value : 'Your task';
                    
                    if (Notification.permission === "granted") {
                        showNotification(taskName, alertDateTime, isPostDue ? "Post-due reminder" : "Custom alert");
                    } else if (Notification.permission !== "denied") {
                        Notification.requestPermission().then(permission => {
                            if (permission === "granted") {
                                showNotification(taskName, alertDateTime, isPostDue ? "Post-due reminder" : "Custom alert");
                            }
                        });
                    }
                }, timeUntilAlert);
            }
        }

        // Show a notification
        function showNotification(taskName, dateTime, alertType) {
            const notification = new Notification("Task Reminder", {
                body: `${alertType}: ${taskName} (${formatDateTime(dateTime)})`,
                icon: "/path/to/notification-icon.png" // Replace with your icon path
            });
            
            notification.onclick = function() {
                window.focus();
                this.close();
            };
        }

        // Format date-time for submission (Y-m-d\TH:i)
        function formatDateTimeForSubmission(dateObj) {
            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0'); // Months are 0-based
            const day = String(dateObj.getDate()).padStart(2, '0');
            const hours = String(dateObj.getHours()).padStart(2, '0');
            const minutes = String(dateObj.getMinutes()).padStart(2, '0');
            
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        // Format date for display
        function formatDateTime(dateObj) {
            return dateObj.toLocaleString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: '2-digit', 
                minute: '2-digit'
            });
        }

        // Set up automatic alerts 5 minutes before the chosen start time
        function setupTaskAlerts() {
            // Get the start date input element
            const startDateInput = document.getElementById('start_date');
            
            // Add an event listener for when the start date changes
            startDateInput.addEventListener('change', function() {
                // Get the selected start date and time
                const startDateTime = new Date(this.value);
                
                // Calculate the alert time (5 minutes before start time)
                const alertDateTime = new Date(startDateTime.getTime() - (5 * 60 * 1000));
                
                // Create an alert entry and add it to the alerts container
                addAlertToContainer(alertDateTime, startDateTime);
            });
            
            // Also add listener for due date to offer a due date alert
            const dueDateInput = document.getElementById('due_date');
            if (dueDateInput) {
                dueDateInput.addEventListener('change', function() {
                    const dueDateTime = new Date(this.value);
                    openOfferModal(dueDateTime); // Use modal instead of confirm
                });
            }
        }

        // Add 5-minute before alert to the container
        function addAlertToContainer(alertDateTime, startDateTime) {
            // Format dates for display
            const alertTimeFormatted = formatDateTime(alertDateTime);
            const startTimeFormatted = formatDateTime(startDateTime);
            
            // Create the alert element
            const alertElement = document.createElement('div');
            alertElement.className = 'alert-item bg-light p-2 rounded border d-flex justify-content-between align-items-center mt-2';
            alertElement.innerHTML = `
                <div>
                    <span class="badge bg-info">5 min before</span>
                    <span>Alert at ${alertTimeFormatted}</span>
                </div>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeAlert(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add a hidden input to store the alert data for form submission
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'task_alerts[]';
            
            // Format the date-time value as Y-m-d\TH:i
            const formattedDateTime = formatDateTimeForSubmission(alertDateTime);
            hiddenInput.value = formattedDateTime;
            
            alertElement.appendChild(hiddenInput);
            
            // Add the alert to the container
            const alertsContainer = document.getElementById('alerts-container');
            
            // Check if this alert already exists
            const existingAlerts = alertsContainer.querySelectorAll('.alert-item');
            let alertExists = false;
            
            existingAlerts.forEach(alert => {
                if (alert.querySelector('span').textContent.includes(alertTimeFormatted)) {
                    alertExists = true;
                }
            });
            
            if (!alertExists) {
                alertsContainer.appendChild(alertElement);
                
                // Schedule notification
                scheduleNotification(alertDateTime, startDateTime);
            }
        }

        // Schedule notification for 5 minutes before start time
        function scheduleNotification(alertDateTime, startDateTime) {
            const now = new Date();
            const timeUntilAlert = alertDateTime.getTime() - now.getTime();
            
            if (timeUntilAlert > 0) {
                setTimeout(() => {
                    const taskNameElement = document.querySelector('input[name="task_name"]');
                    const taskName = taskNameElement ? taskNameElement.value : 'Your task';
                    
                    if (Notification.permission === "granted") {
                        showNotification(taskName, startDateTime, "Starting in 5 minutes");
                    } else if (Notification.permission !== "denied") {
                        Notification.requestPermission().then(permission => {
                            if (permission === "granted") {
                                showNotification(taskName, startDateTime, "Starting in 5 minutes");
                            }
                        });
                    }
                }, timeUntilAlert);
            }
        }

        // File Input Enhancement and other initialization
        document.addEventListener("DOMContentLoaded", function () {
            // Set up the automatic 5-min alert functionality
            setupTaskAlerts();
            
            // File input enhancement
            const fileInput = document.getElementById('task_uploads');
            if (fileInput) {
                const fileLabel = document.querySelector('.file-upload span');
                
                fileInput.addEventListener('change', function() {
                    fileLabel.textContent = fileInput.files.length > 0 
                        ? `${fileInput.files.length} file(s) selected` 
                        : 'Click to upload files';
                });
            }
            
            // DateTime Constraints
            const now = new Date().toISOString().slice(0, 16);
            const startDateInput = document.getElementById("start_date");
            const dueDateInput = document.getElementById("due_date");
            
            if (startDateInput && dueDateInput) {
                startDateInput.min = now;

                startDateInput.addEventListener("change", function () {
                    dueDateInput.min = startDateInput.value;
                });
            }
            
            // Parent task budget logic
            const parentTaskSelect = document.getElementById("parent_task");
            const budgetField = document.getElementById("budget");
            const budgetContainer = document.getElementById("budget_container");

            if (parentTaskSelect && budgetField && budgetContainer) {
                parentTaskSelect.addEventListener("change", function () {
                    if (parentTaskSelect.value) {
                        budgetContainer.style.display = "none";
                        budgetField.value = "";
                        budgetField.required = false;
                    } else {
                        budgetContainer.style.display = "block";
                        budgetField.required = true;
                    }
                });
            }
            
            // Form submission loading indicator
            const form = document.querySelector("form");
const loader = document.getElementById("loader");

if (form && loader) {
    form.addEventListener("submit", function (event) {
        // Prevent the default form submission
        event.preventDefault();

        // Show the loader
        loader.style.display = "grid";

        // Optionally, disable the submit button to prevent multiple submissions
        const submitButton = form.querySelector("button[type='submit']");
        if (submitButton) {
            submitButton.disabled = true;
        }

        // Submit the form using Fetch API
        fetch(form.action, {
            method: form.method,
            body: new FormData(form),
            headers: {
                Accept: "application/json", // Ensure the server knows we expect JSON
            },
        })
            .then((response) => {
                // Check if the response is OK (status code 200-299)
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json(); // Parse the JSON response
            })
            .then((data) => {
                // Handle the response data (optional)
                console.log("Task created successfully:", data);
            })
            .finally(() => {
                // Hide the loader
                loader.style.display = "none";

                // Re-enable the submit button
                if (submitButton) {
                    submitButton.disabled = false;
                }
            });
    });
}

            // Request notification permission on page load
            if (Notification.permission !== "granted" && Notification.permission !== "denied") {
                Notification.requestPermission();
            }
        });
    </script>
</body>
</html>