<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .gradient-header {
            background: linear-gradient(to right, #3b82f6, #4f46e5);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4 sm:p-6 md:p-8">
    <div class="w-full max-w-4xl shadow-lg rounded-lg bg-white">
        <div class="space-y-1 gradient-header text-white rounded-t-lg p-6">
            <h3 class="text-2xl font-bold tracking-tight text-center">Edit Task</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('tasks.update', $task->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Task Name -->
                    <div class="space-y-2">
                        <label for="task_name" class="text-sm font-medium">
                            Name
                        </label>
                        <input
                            id="task_name"
                            name="task_name"
                            value="{{ old('task_name', $task->name) }}"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <!-- Category -->
                    <div class="space-y-2">
                        <label for="task_category" class="text-sm font-medium">
                            Category
                        </label>
                        <select
                            id="task_category"
                            name="task_category"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $task->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Description - Full Width -->
                    <div class="space-y-2 md:col-span-2">
                        <label for="task_description" class="text-sm font-medium">
                            Description
                        </label>
                        <input
                            id="task_description"
                            name="task_description"
                            value="{{ old('task_description', $task->description) }}"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <!-- Start Date -->
                    <div class="space-y-2">
                        <label for="task_start_date" class="text-sm font-medium flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Start Date
                        </label>
                        <input
                            id="task_start_date"
                            name="task_start_date"
                            type="datetime-local"
                            value="{{ old('task_start_date', $task->start_date) }}"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <!-- Due Date -->
                    <div class="space-y-2">
                        <label for="task_due_date" class="text-sm font-medium flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Due Date
                        </label>
                        <input
                            id="task_due_date"
                            name="task_due_date"
                            type="datetime-local"
                            value="{{ old('task_due_date', $task->due_date) }}"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <!-- Alert -->
                    <div class="space-y-2">
                        <label for="task_alert" class="text-sm font-medium flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Alert
                        </label>
                        <input
                            id="task_alert"
                            name="task_alert"
                            type="datetime-local"
                            value="{{ old('task_alert', $task->alert) }}"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>

                    <!-- Cost -->
                    <div class="space-y-2">
                        <label for="task_cost" class="text-sm font-medium flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Cost
                        </label>
                        <input
                            id="task_cost"
                            name="task_cost"
                            type="number"
                            min="0"
                            step="0.01"
                            value="{{ old('task_cost', $task->cost) }}"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <!-- Parent Task -->
                    <div class="space-y-2">
                        <label for="parent_task_id" class="text-sm font-medium">
                            Parent Task
                        </label>
                        <select
                            id="parent_task_id"
                            name="parent_task_id"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">No Parent (Main Task)</option>
                            @foreach($tasks as $parent_task)
                                <option value="{{ $parent_task->id }}" {{ $task->parent_task_id == $parent_task->id ? 'selected' : '' }}>
                                    {{ $parent_task->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Budget -->
                    <div class="space-y-2">
                        <label for="budget" class="text-sm font-medium">
                            Budget (For Parent Tasks Only)
                        </label>
                        <input
                            id="budget"
                            name="budget"
                            type="number"
                            min="0"
                            step="0.01"
                            value="{{ old('budget', $task->budget ?? 0) }}"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>

                    <!-- Repeat Task - Checkbox -->
                    <div class="flex items-center space-x-2 md:col-span-2">
                        <input
                            id="task_repeat"
                            name="task_repeat"
                            type="checkbox"
                            value="1"
                            {{ old('task_repeat', $task->repeat) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        />
                        <label for="task_repeat" class="text-sm font-medium cursor-pointer">
                            Repeat Task
                        </label>
                    </div>

                    <!-- File Uploads - Full Width -->
                    <div class="space-y-2 md:col-span-2">
                        <label for="task_uploads" class="text-sm font-medium flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            File Uploads
                        </label>
                        <div class="border border-gray-300 rounded-md p-2 bg-gray-50">
                            <input
                                id="task_uploads"
                                name="task_uploads[]"
                                type="file"
                                multiple
                                class="w-full"
                            />
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-gray-200" />

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full gradient-header hover:bg-blue-600 text-white font-medium py-2 px-4 rounded"
                >
                    Update Task
                </button>
            </form>
        </div>
    </div>

    <script>
        // You can add any JavaScript here if needed
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Form loaded successfully');
        });
    </script>
</body>
</html>