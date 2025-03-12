

<div class="container">
    <form method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data" class="task-form">
        @csrf
        <div class="horizontal-container">
            <div>
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="task_name" required />
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <input type="text" name="task_description" required />
                </div>
                <div class="form-group">
                    <label>Alert:</label>
                    <input type="datetime-local" name="task_alert" id="task_alert" required />
                </div>
                <div class="form-group">
                    <label>Cost:</label>
                    <input type="number" name="task_cost" id="task_cost" min="0" required />
                </div>
                <div class="form-group">
                    <label>Category:</label>
                    <select name="task_category" required>
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label>Recurrency:</label>
                    <select name="task_recurrency" required>
                        <option value="">Select recurrency</option>
                        @foreach($recurrencies as $recurrency)
                            <option value="{{ $recurrency->id }}">{{ $recurrency->frequency }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Completion Status:</label>
                    <select name="task_completion_status" required>
                        <option value="">Select status</option>
                        @foreach($completions as $completion)
                            <option value="{{ $completion->id }}">{{ $completion->status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Start Date:</label>
                    <input type="datetime-local" name="task_start_date" id="start_date" required />
                </div>
                <div class="form-group">
                    <label>Due Date:</label>
                    <input type="datetime-local" name="task_due_date" id="due_date" required />
                </div>
                <div class="form-group">
                    <label>Repeat Task:</label>
                    <input type="checkbox" name="task_repeat" value="1" />
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label>Parent Task:</label>
                    <select name="parent_task_id" id="parent_task">
                        <option value="">No Parent Task</option>
                        @foreach($tasks as $taskItem)
                            <option value="{{ $taskItem->id }}">{{ $taskItem->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Uploads:</label>
                    <input type="file" name="task_uploads[]" multiple />
                </div>
                <div class="form-group" id="budget_container">
                    <label>Budget:</label>
                    <input type="number" name="budget" id="budget" min="0" step="0.01" />
                </div>
            </div>
        </div>
        <button class="submit-button" type="submit">Create Task</button>
    </form>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const now = new Date().toISOString().slice(0, 16);
    const alertInput = document.getElementById("task_alert");
    const startDateInput = document.getElementById("start_date");
    const dueDateInput = document.getElementById("due_date");
    const parentTaskSelect = document.getElementById("parent_task");
    const budgetField = document.getElementById("budget");
    const budgetContainer = document.getElementById("budget_container");

    alertInput.min = now;
    startDateInput.min = now;

    startDateInput.addEventListener("change", function () {
        dueDateInput.min = startDateInput.value;
    });

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
});
</script>

<style>
.task-form {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
.horizontal-container {
  display: flex;
  gap: 10px;
}
.horizontal-container > div {
  flex: 1; 
}
.form-group {
    margin-bottom: 15px;
}
label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}
input, select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.submit-button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}
.submit-button:hover {
    background-color: #0056b3;
}
</style>