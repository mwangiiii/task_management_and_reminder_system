


<div class="container mx-auto py-8 px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Available Tasks</h2>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                        <th class="w-10 py-3 px-4 text-left"></th>
                        <th class="py-3 px-4 text-left">Name</th>
                        <th class="py-3 px-4 text-left">Description</th>
                        <th class="py-3 px-4 text-left">Budget</th>
                        <th class="py-3 px-4 text-left">Category</th>
                        <th class="py-3 px-4 text-left">Recurrency</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Start Date</th>
                        <th class="py-3 px-4 text-left">Due Date</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($tasks as $task)
                        @if(!$task->parent_task_id)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-2 px-4 border-r border-gray-200">
                                    @if($tasks->where('parent_task_id', $task->id)->count() > 0)
                                        <button class="toggle-btn w-6 h-6 flex items-center justify-center rounded hover:bg-gray-200 transition-colors" data-id="{{ $task->id }}">
                                            <span class="transition-transform transform rotate-0">▶</span>
                                        </button>
                                    @endif
                                </td>
                                <td class="py-2 px-4 font-medium text-gray-900">{{ $task->name }}</td>
                                <td class="py-2 px-4 text-gray-600 italic">{{ $task->description }}</td>
                                <td class="py-2 px-4">{{ $task->budget ?? 'N/A' }}</td>
                                <td class="py-2 px-4">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if(isset($categories->firstWhere('id', $task->category_id)->type))
                                            @switch($categories->firstWhere('id', $task->category_id)->type)
                                                @case('Work')
                                                    bg-blue-100 text-blue-800
                                                    @break
                                                @case('Personal')
                                                    bg-green-100 text-green-800
                                                    @break
                                                @case('Study')
                                                    bg-purple-100 text-purple-800
                                                    @break
                                                @case('Health')
                                                    bg-red-100 text-red-800
                                                    @break
                                                @default
                                                    bg-gray-100 text-gray-800
                                            @endswitch
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $categories->firstWhere('id', $task->category_id)->type ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td class="py-2 px-4">{{ $recurrencies->firstWhere('id', $task->recurrency_id)->frequency ?? 'Unknown' }}</td>
                                <td class="py-2 px-4">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if(isset($completions->firstWhere('id', $task->completion_status_id)->status))
                                            @switch($completions->firstWhere('id', $task->completion_status_id)->status)
                                                @case('Completed')
                                                    bg-green-100 text-green-800
                                                    @break
                                                @case('In Progress')
                                                    bg-yellow-100 text-yellow-800
                                                    @break
                                                @case('Not Started')
                                                    bg-gray-100 text-gray-800
                                                    @break
                                                @case('On Hold')
                                                    bg-orange-100 text-orange-800
                                                    @break
                                                @default
                                                    bg-gray-100 text-gray-800
                                            @endswitch
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $completions->firstWhere('id', $task->completion_status_id)->status ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td class="py-2 px-4 text-sm">{{ \Carbon\Carbon::parse($task->start_date)->format('M d, Y g:i A') }}</td>
                                <td class="py-2 px-4 text-sm">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y g:i A') }}</td>
                                <td class="py-2 px-4">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('tasks.edit', ['id' => $task->id]) }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded">
                                            Edit
                                        </a>
                                        <form action="{{ route('tasks.destroy', ['id' => $task->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded" onclick="return confirm('Are you sure you want to delete this task?')">
                                                Delete
                                            </button>
                                        </form>
                                        <a href="{{ route('tasks.create', ['parent_id' => $task->id]) }}" class="px-2 py-1 bg-green-500 hover:bg-green-600 text-white rounded flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            @foreach($tasks->where('parent_task_id', $task->id) as $child)
                                <tr class="child-task child-of-{{ $task->id }} hover:bg-gray-50 transition-colors bg-gray-50" style="display:none;">
                                    <td class="py-2 px-4 border-r border-gray-200"></td>
                                    <td class="py-2 px-4 font-medium text-gray-900">
                                        <div class="flex items-center">
                                            <span class="text-gray-300 mr-2">└</span>
                                            {{ $child->name }}
                                        </div>
                                    </td>
                                    <td class="py-2 px-4 text-gray-600 italic">{{ $child->description }}</td>
                                    <td class="py-2 px-4">{{ $child->budget ?? 'N/A' }}</td>
                                    <td class="py-2 px-4">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if(isset($categories->firstWhere('id', $child->category_id)->type))
                                                @switch($categories->firstWhere('id', $child->category_id)->type)
                                                    @case('Work')
                                                        bg-blue-100 text-blue-800
                                                        @break
                                                    @case('Personal')
                                                        bg-green-100 text-green-800
                                                        @break
                                                    @case('Study')
                                                        bg-purple-100 text-purple-800
                                                        @break
                                                    @case('Health')
                                                        bg-red-100 text-red-800
                                                        @break
                                                    @default
                                                        bg-gray-100 text-gray-800
                                                @endswitch
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $categories->firstWhere('id', $child->category_id)->type ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4">{{ $recurrencies->firstWhere('id', $child->recurrency_id)->frequency ?? 'Unknown' }}</td>
                                    <td class="py-2 px-4">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if(isset($completions->firstWhere('id', $child->completion_status_id)->status))
                                                @switch($completions->firstWhere('id', $child->completion_status_id)->status)
                                                    @case('Completed')
                                                        bg-green-100 text-green-800
                                                        @break
                                                    @case('In Progress')
                                                        bg-yellow-100 text-yellow-800
                                                        @break
                                                    @case('Not Started')
                                                        bg-gray-100 text-gray-800
                                                        @break
                                                    @case('On Hold')
                                                        bg-orange-100 text-orange-800
                                                        @break
                                                    @default
                                                        bg-gray-100 text-gray-800
                                                @endswitch
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $completions->firstWhere('id', $child->completion_status_id)->status ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 text-sm">{{ \Carbon\Carbon::parse($child->start_date)->format('M d, Y g:i A') }}</td>
                                    <td class="py-2 px-4 text-sm">{{ \Carbon\Carbon::parse($child->due_date)->format('M d, Y g:i A') }}</td>
                                    <td class="py-2 px-4">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('tasks.edit', ['id' => $child->id]) }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded">
                                                Edit
                                            </a>
                                            <form action="{{ route('tasks.destroy', ['id' => $child->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded" onclick="return confirm('Are you sure you want to delete this task?')">
                                                    Delete
                                                </button>
                                            </form>
                                            <a href="{{ route('tasks.create', ['parent_id' => $child->id]) }}" class="px-2 py-1 bg-green-500 hover:bg-green-600 text-white rounded flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <a href="{{ route('tasks.create') }}" class="inline-block px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-md">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Task
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".toggle-btn").forEach(button => {
            button.addEventListener("click", function () {
                const taskId = this.getAttribute("data-id");
                const childRows = document.querySelectorAll(".child-of-" + taskId);
                const icon = this.querySelector("span");
                
                childRows.forEach(row => {
                    if (row.style.display === "none") {
                        row.style.display = "table-row";
                        icon.classList.add("rotate-90");
                    } else {
                        row.style.display = "none";
                        icon.classList.remove("rotate-90");
                    }
                });
            });
        });
    });
</script>

<style>
    /* Additional Styles */
    .toggle-btn {
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .toggle-btn span {
        display: inline-block;
        transition: transform 0.2s ease;
    }
    
    .toggle-btn:hover {
        background-color: #f3f4f6;
    }
    
    .child-task {
        transition: all 0.3s ease;
    }
    
    /* Responsive styles */
    @media (max-width: 768px) {
        table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }
</style>
