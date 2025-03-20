<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beautiful Due Date Picker</title>
    <style>
        :root {
            --primary: #4F46E5;
            --primary-dark: #4338CA;
            --primary-light: #EEF2FF;
            --white: #FFFFFF;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --text: #1E293B;
            --success: #10B981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #F9FAFB;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .due-date-container {
            width: 100%;
            max-width: 440px;
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .due-date-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 25px 30px;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 60%);
            opacity: 0.4;
        }

        .header h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 6px;
            position: relative;
        }

        .header p {
            font-size: 14px;
            opacity: 0.85;
            position: relative;
        }

        .date-picker-content {
            padding: 30px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .date-input-wrapper, .time-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            background-color: var(--gray-100);
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .date-input-wrapper:focus-within, .time-input-wrapper:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background-color: var(--white);
        }

        .icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            color: var(--gray-700);
        }

        .date-input, .time-input {
            width: 100%;
            border: none;
            background: transparent;
            padding: 14px 14px 14px 0;
            font-size: 15px;
            color: var(--text);
            outline: none;
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: all 0.6s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:active {
            transform: translateY(1px);
        }

        .confirmation {
            margin-top: 20px;
            padding: 16px;
            background-color: var(--primary-light);
            border-radius: 10px;
            border: 1px solid rgba(79, 70, 229, 0.2);
            display: none;
        }

        .confirmation p {
            color: var(--primary-dark);
            font-weight: 500;
            font-size: 14px;
        }

        .input-error {
            border-color: #EF4444;
            background-color: #FEF2F2;
        }

        .error-message {
            color: #EF4444;
            font-size: 12px;
            margin-top: 6px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="due-date-container">
        <div class="header">
            <h2>Set Due Date</h2>
            <p>When would you like this task to be completed?</p>
        </div>
        
        <div class="date-picker-content">
            <<form action="{{ route('tasks.update_due_date', $task->id) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <label for="datePicker">Select Date</label>
                        <div class="date-input-wrapper">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </span>
                            <input type="date" id="datePicker" name="date" class="date-input" required min="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label for="timePicker">Select Time</label>
                        <div class="time-input-wrapper">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </span>
                            <input type="time" id="timePicker" name="time" class="time-input" required>
                        </div>
                    </div>

                    <input type="hidden" id="new_due_date" name="new_due_date">

                    <button type="submit" class="submit-btn">Set Due Date</button>
                </form>

            
            <div id="confirmation" class="confirmation">
                <p id="confirmationText"></p>
            </div>
        </div>
    </div>
    <script>
    document.querySelector('form').addEventListener('submit', function() {
        const date = document.getElementById('datePicker').value;
        const time = document.getElementById('timePicker').value;
        document.getElementById('new_due_date').value = `${date}T${time}`;
    });
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('datePicker');
            const timeInput = document.getElementById('timePicker');
            const form = document.getElementById('dueDateForm');
            const confirmation = document.getElementById('confirmation');
            const confirmationText = document.getElementById('confirmationText');
            const dateError = document.getElementById('dateError');
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const formattedToday = `${year}-${month}-${day}`;
            
            dateInput.setAttribute('min', formattedToday);
            dateInput.value = formattedToday;
            
            const currentHour = today.getHours();
            const nextHour = currentHour + 1 >= 24 ? 0 : currentHour + 1;
            const formattedTime = `${String(nextHour).padStart(2, '0')}:00`;
            timeInput.value = formattedTime;
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const selectedDate = new Date(dateInput.value + 'T' + (timeInput.value || '00:00'));
                const now = new Date();
                if (selectedDate < now) {
                    dateInput.parentElement.classList.add('input-error');
                    dateError.style.display = 'block';
                    return;
                }
                
                dateInput.parentElement.classList.remove('input-error');
                dateError.style.display = 'none';
e
                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                
                const formattedDate = selectedDate.toLocaleDateString('en-US', options);
                confirmationText.textContent = `Task due on ${formattedDate}`;
                confirmation.style.display = 'block';
                

                console.log('Submitting due date:', {
                    date: dateInput.value,
                    time: timeInput.value
                });
            });
   
            dateInput.addEventListener('change', function() {
                const selectedDate = new Date(dateInput.value);
                const now = new Date();
                now.setHours(0, 0, 0, 0);
                
                if (selectedDate < now) {
                    dateInput.parentElement.classList.add('input-error');
                    dateError.style.display = 'block';
                } else {
                    dateInput.parentElement.classList.remove('input-error');
                    dateError.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>