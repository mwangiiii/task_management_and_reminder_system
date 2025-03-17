<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;
use App\Models\CompletionStatus;
use App\Models\Alert;
use App\Models\Notification;
use App\Models\Upload;
use App\Models\Recurrency;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Storage;
use App\Mail\TaskDeletedMail;
use App\Mail\TaskRestoredMail;

use App\Services\NovuService;
use Carbon\Carbon;
// use App\Jobs\SendTaskAlertNotification;


use Illuminate\Support\Facades\Mail;
use App\Mail\TaskReminderMail;
use App\Mail\TaskCreatedMail;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SendTaskReminderJob;

class TaskController extends Controller
{   
    public function index()
    {
        $categories = Category::all();
        $recurrencies = Recurrency::all();
        $completions = CompletionStatus::all();
        $tasks = Task::where('deleted', 0)->get();
        return view('Task.task-form', compact('categories', 'completions', 'recurrencies', 'tasks'));
    }


    public function viewAllTasks()
    { 
        //listing all non-deleted tasks by all users
        $tasks = Task::where('deleted', 0)->get();
        $recurrencies = Recurrency::all();
        $completionStatus = CompletionStatus::all();
        $categories = Category::all();
        
        return view('Task.all-tasks-listing', compact('categories', 'completionStatus', 'recurrencies', 'tasks'));
    }
    
    public function store(Request $request)
    {
        $user_id = auth()->id();
        try {
            // Validate request data
            $validated_task_data = $request->validate([
                'task_name' => 'required|string|max:255',
                'task_alerts' => 'required|array', // Validate that task_alerts is an array
                'task_alerts.*' => 'date_format:Y-m-d\TH:i', // Validate each alert in the array
                'task_repeat' => 'nullable|boolean',
                'task_description' => 'required|string',
                'task_cost' => 'required|numeric|min:0',
                'task_category' => 'required|exists:categories,id',
                'task_recurrency' => 'required|exists:recurrencies,id',
                'task_completion_status' => 'required|exists:completion__statuses,id',
                'task_start_date' => 'required|date_format:Y-m-d\TH:i',
                'task_due_date' => 'required|date_format:Y-m-d\TH:i',
                'parent_task_id' => 'nullable|exists:tasks,id',
                'budget' => 'nullable|numeric|min:0',
                'task_uploads.*' => 'nullable',
            ]);
    
            Log::info('Validated Data:', $validated_task_data);
        } catch (ValidationException $e) {
            Log::error('Validation Failed:', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        }
    
        $validated_task_data['task_repeat'] = $request->has('task_repeat') ? 1 : 0;
    
        if (isset($validated_task_data['task_category'])) {
            $validated_task_data['task_category'] = trim($validated_task_data['task_category']);
        }
    
        if (isset($validated_task_data['task_completion_status'])) {
            $validated_task_data['task_completion_status'] = trim($validated_task_data['task_completion_status']);
        }
    
        if (isset($validated_task_data['task_recurrency'])) {
            $validated_task_data['task_recurrency'] = trim($validated_task_data['task_recurrency']);
        }
    
        $is_parent_task = empty($validated_task_data['parent_task_id']);
    
        if ($is_parent_task && (!isset($validated_task_data['budget']) || $validated_task_data['budget'] <= 0)) {
            return response()->json(['error' => 'Budget is required for parent tasks.'], 400);
        } else if (!$is_parent_task) {
            $parentTask = Task::find($validated_task_data['parent_task_id']);
            if (!$parentTask) {
                return response()->json(['error' => 'Parent task not found.'], 400);
            }
    
            $existing_child_costs = Task::where('parent_task_id', $parentTask->id)
                                         ->where('deleted', 0)
                                         ->sum('cost');
            if (($existing_child_costs + $validated_task_data['task_cost']) > $parentTask->budget) {
                return response()->json([
                    'error' => 'Total cost of child tasks exceeds the parent task budget.',
                    'remaining_budget' => $parentTask->budget - $existing_child_costs
                ], 400);
            }
            unset($validated_task_data['budget']);
        }
    
        $validated_task_data['user_id'] = $user_id;
    
        $task = Task::create([
            'name' => $validated_task_data['task_name'],
            'repeat' => $validated_task_data['task_repeat'],
            'description' => $validated_task_data['task_description'],
            'category_id' => $validated_task_data['task_category'],
            'recurrency_id' => $validated_task_data['task_recurrency'],
            'cost' => $validated_task_data['task_cost'],
            'budget' => $is_parent_task ? $validated_task_data['budget'] : 0,
            'completion_status_id' => $validated_task_data['task_completion_status'],
            'start_date' => $validated_task_data['task_start_date'],
            'due_date' => $validated_task_data['task_due_date'],
            'parent_task_id' => $validated_task_data['parent_task_id'] ?? null,
            'user_id' => $validated_task_data['user_id'],
            'deleted' => 0,
        ]);
    
        // Save multiple alerts
        if (isset($validated_task_data['task_alerts'])) {
            foreach ($validated_task_data['task_alerts'] as $alert_time) {
                Alert::create([
                    'time_of_alert' => $alert_time,
                    'task_id' => $task->id,
                    'alert_sent' => false,
                ]);
            }
        }
    
        // Handle file uploads
        if ($request->hasFile('task_uploads')) {
            try {
                foreach ($request->file('task_uploads') as $upload) {
                    if ($upload->isValid()) {
                        $path_to_uploads = $upload->store('task_uploads_files', 'public');
                        Upload::create([
                            'task_id' => $task->id,
                            'paths' => $path_to_uploads,
                        ]);
                    } else {
                        Log::warning('Invalid file in upload:', ['filename' => $upload->getClientOriginalName()]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('File Upload Failed:', [
                    'error' => $e->getMessage(),
                    'task_id' => $task->id
                ]);
            }
        }
    
        // Send email notification
        Mail::to($task->user->email)->send(new TaskCreatedMail($task));
    
        // If the request was AJAX/JSON, return JSON response
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Task created successfully!', 'task' => $task], 201);
        }
    
        // Otherwise return a redirect with success message
        return redirect()->back()->with('success', 'Task created successfully!');
    }


public function update(Request $request, $id)
{
    // Log the incoming request data
    Log::info('Update Task Request:', $request->all());

    try {
        // Find the task
        $task = Task::findOrFail($id);
        $user_id = auth()->id();

        // Validate request data
        $validated_task_data = $request->validate([
            'task_name' => 'sometimes|string|max:255',
            'task_alerts' => 'nullable|array', // Validate that task_alerts is an array
            'task_alerts.*' => 'date_format:Y-m-d\TH:i', // Validate each alert in the array
            'task_repeat' => 'nullable|boolean',
            'task_description' => 'sometimes|string',
            'task_cost' => 'nullable|numeric|min:0',
            'task_category' => 'sometimes|exists:categories,id',
            'task_recurrency' => 'sometimes|exists:recurrencies,id',
            'task_completion_status' => 'sometimes|exists:completion__statuses,id',
            'task_start_date' => 'nullable|date_format:Y-m-d\TH:i',
            'task_due_date' => 'nullable|date_format:Y-m-d\TH:i',
            'parent_task_id' => 'nullable|exists:tasks,id',
            'budget' => 'nullable|numeric|min:0',
            'task_uploads.*' => 'nullable|file|max:5120',
        ]);

        // Ensure 'task_repeat' is properly handled
        $validated_task_data['task_repeat'] = $request->has('task_repeat') ? 1 : 0;

        // Trim spaces from select box values
        foreach (['task_category', 'task_completion_status', 'task_recurrency'] as $key) {
            if (isset($validated_task_data[$key])) {
                $validated_task_data[$key] = trim($validated_task_data[$key]);
            }
        }

        // Prevent task from becoming its own parent
        if (isset($validated_task_data['parent_task_id']) && $validated_task_data['parent_task_id'] == $id) {
            return response()->json(['error' => 'A task cannot be its own parent.'], 400);
        }

        // Determine if the task is a parent or child
        $is_parent_task = empty($validated_task_data['parent_task_id']);

        // Check if the parent-child relationship is changing
        $parent_relationship_changed = $task->parent_task_id != ($validated_task_data['parent_task_id'] ?? null);

        if ($is_parent_task) {
            // Ensure a budget is provided, default to 0 if missing
            $validated_task_data['budget'] = $validated_task_data['budget'] ?? 0;

            // If converting from child to parent, check if it has existing children
            if ($parent_relationship_changed && Task::where('parent_task_id', $id)->where('deleted', 0)->exists()) {
                return response()->json(['error' => 'Cannot convert a task with children to a child task.'], 400);
            }
        } else {
            // If it's a child task, validate against the parent's budget
            $parentTask = Task::find($validated_task_data['parent_task_id']);

            if (!$parentTask) {
                return response()->json(['error' => 'Parent task not found.'], 400);
            }

            // Get the total cost of existing child tasks (excluding this task if already a child)
            $existing_child_costs = Task::where('parent_task_id', $parentTask->id)
                                        ->where('id', '!=', $id)
                                        ->where('deleted', 0)
                                        ->sum('cost');

            // Ensure the new cost does not exceed the parent's budget
            if (($existing_child_costs + $validated_task_data['task_cost']) > $parentTask->budget) {
                return response()->json([
                    'error' => 'Total cost of child tasks exceeds the parent task budget.',
                    'remaining_budget' => $parentTask->budget - $existing_child_costs
                ], 400);
            }

            // Child tasks should not have a budget
            $validated_task_data['budget'] = null;
        }

        // Update the task
        $task->update([
            'name' => $validated_task_data['task_name'] ?? $task->name,
            'repeat' => $validated_task_data['task_repeat'],
            'description' => $validated_task_data['task_description'] ?? $task->description,
            'category_id' => $validated_task_data['task_category'] ?? $task->category_id,
            'recurrency_id' => $validated_task_data['task_recurrency'] ?? $task->recurrency_id,
            'cost' => $validated_task_data['task_cost'] ?? $task->cost,
            'budget' => $is_parent_task ? $validated_task_data['budget'] : 0,
            'completion_status_id' => $validated_task_data['task_completion_status'] ?? $task->completion_status_id,
            'start_date' => $validated_task_data['task_start_date'] ?? $task->start_date,
            'due_date' => $validated_task_data['task_due_date'] ?? $task->due_date,
            'parent_task_id' => $validated_task_data['parent_task_id'] ?? $task->parent_task_id,
            'user_id' => $user_id,
            // Keep the existing deleted status
        ]);

        // Handle alerts
        if (isset($validated_task_data['task_alerts'])) {
            // Delete existing alerts for the task
            Alert::where('task_id', $task->id)->delete();

            // Create new alerts
            foreach ($validated_task_data['task_alerts'] as $alert_time) {
                Alert::create([
                    'time_of_alert' => $alert_time,
                    'task_id' => $task->id,
                    'alert_sent' => false,
                ]);
            }
        }

        // Handle file uploads
        if ($request->hasFile('task_uploads')) {
            try {
                if (class_exists('\OwenIt\Auditing\Models\Audit')) {
                    \OwenIt\Auditing\Models\Audit::$recordEvents = false;
                }

                foreach ($request->file('task_uploads') as $upload) {
                    if ($upload->isValid()) {
                        $path_to_uploads = $upload->store('task_uploads_files', 'public');

                        Upload::create([
                            'task_id' => $task->id,
                            'paths' => $path_to_uploads,
                        ]);
                    } else {
                        Log::warning('Invalid file in upload:', ['filename' => $upload->getClientOriginalName()]);
                    }
                }

                if (class_exists('\OwenIt\Auditing\Models\Audit')) {
                    \OwenIt\Auditing\Models\Audit::$recordEvents = true;
                }
            } catch (\Exception $e) {
                if (class_exists('\OwenIt\Auditing\Models\Audit')) {
                    \OwenIt\Auditing\Models\Audit::$recordEvents = true;
                }

                Log::error('File Upload Failed:', [
                    'error' => $e->getMessage(),
                    'task_id' => $task->id
                ]);
            }
        }

        // Send email notification
        Mail::to($task->user->email)->send(new TaskCreatedMail($task));

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Task updated successfully!', 'task' => $task], 200);
        }

        // Redirect on success
        return redirect()->route('tasks.showOneTask', $task->id)->with('success', 'Task updated successfully!');

    } catch (ValidationException $e) {
        Log::error('Validation Failed:', $e->errors());

        if ($request->expectsJson()) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error('Error updating task:', ['task_id' => $id, 'error' => $e->getMessage()]);

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Failed to update task: ' . $e->getMessage()], 500);
        }

        return redirect()->back()->with('error', 'Failed to update task: ' . $e->getMessage())->withInput();
    }
}
    /**
     * Display a listing of all tasks.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function displayUpdateForm($id)
    {
        $task = Task::findOrFail($id);
        $categories = Category::all();
        $recurrencies = Recurrency::all();
        $completions = CompletionStatus::all();
        $tasks = Task::where('deleted', 0)->get();
    
        return view('Task.task-edit', compact('task', 'categories', 'recurrencies', 'completions', 'tasks'));
    }
    
    // Modified method to implement soft delete
    // Controller method
    public function destroy(Request $request)
    {   Log::info($request);
        $ids = $request->input('id');
        Log::info($ids);
        Task::whereIn('id', $ids)->update(['deleted' => 1]);
        return response()->json(['success' => 'Tasks deleted successfully']);
    }



    

    // Add a new method to permanently delete tasks if needed
    public function permanentlyDelete($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found.'], 404);
        }

        // Ensure the authenticated user owns the task
        if (auth()->id() !== $task->user_id) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        try {
            // Delete associated alerts
            Alert::where('task_id', $task->id)->delete();

            // Delete associated uploads
            $uploads = Upload::where('task_id', $task->id)->get();
            foreach ($uploads as $upload) {
                if ($upload->paths) {
                    Storage::disk('public')->delete($upload->paths);
                }
                $upload->delete();
            }

            // Delete the task
            $task->delete();

            return response()->json(['message' => 'Task permanently deleted.'], 200);
        } catch (\Exception $e) {
            Log::error('Task Permanent Deletion Failed:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to permanently delete task.'], 500);
        }
    }

    // Add a new method to restore deleted tasks
    public function restore($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found.'], 404);
        }

        // Ensure the authenticated user owns the task
        if (auth()->id() !== $task->user_id) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        try {
            // Mark the task as not deleted
            $task->update(['deleted' => 0]);
            
            // Log the restoration
            Log::info('Task restored:', ['task_id' => $id, 'user_id' => auth()->id()]);

            return response()->json(['message' => 'Task restored successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Task Restoration Failed:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to restore task.'], 500);
        }
    }

    // Add a method to view deleted tasks
    public function viewDeletedTasks()
    {
        $deletedTasks = Task::where('deleted', 1)
                            ->where('user_id', auth()->id())
                            ->get();
        
        return view('Task.deleted-tasks', compact('deletedTasks'));
    }


    // public function showOneTask($id)
    // {
    //     // Retrieve the task by ID
    //     $task = Task::findOrFail($id);
    
    //     // Return the task details view
    //     return view('Task.show', compact('task'));
    // }
    
    // public function showOneTask($id)
    // {
    //     // Fetch the task with its related uploads, user, completion status, and child tasks
    //     $task = Task::with(['uploads', 'user', 'completionStatus', 'childTasks.childTasks'])->findOrFail($id);
    
    //     // Pass the task data to the view
    //     return view('Task.show', compact('task'));
    // }


    public function showOneTask($id)
{
    // Fetch the task with its related uploads, user, completion status, and child tasks
    $task = Task::with(['uploads', 'user', 'completionStatus', 'childTasks.childTasks'])->findOrFail($id);
    $completionStatus = CompletionStatus::all();

    // Pass the task data to the view
    return view('Task.show', compact('task','completionStatus'));
}

  
public function createChild($parentTaskId)
    {
        // Fetch the parent task
        $parentTask = Task::findOrFail($parentTaskId);

        // Fetch other necessary data for the form (e.g., categories, completion statuses, recurrencies)
        $categories = Category::all();
        $completions = CompletionStatus::all();
        $recurrencies = Recurrency::all();

        // Pass the data to the view
        return view('Task.sub-task-create', [
            'parentTask' => $parentTask,
            'categories' => $categories,
            'completions' => $completions,
            'recurrencies' => $recurrencies,
        ]);
    }




    public function list()
    {
        try {
            $tasks = Task::with(['category', 'completionStatus', 'recurrency', 'uploads'])
                         ->where('user_id', auth()->id())
                         ->where('deleted', 0)
                         ->get();

            return request()->expectsJson()
                ? response()->json(['tasks' => $tasks], 200)
                : view('Task.task-details', compact('tasks'));
        } catch (\Exception $e) {
            Log::error('Error retrieving tasks:', ['error' => $e->getMessage()]);

            return request()->expectsJson()
                ? response()->json(['error' => 'Failed to retrieve tasks.'], 500)
                : redirect()->back()->with('error', 'Failed to retrieve tasks.');
        }
    }

    protected function scheduleAlertNotification(Task $task)
    {   
        $alertTime = Carbon::parse($task->task_alert)->subMinutes(30); // Notify 30 minutes before task_alert

        if ($alertTime->isFuture()) {
            dispatch((new SendTaskReminderJob($task))->delay($alertTime));

            Log::info("Scheduled email reminder for user: {$task->user->email} for task: {$task->title} at {$alertTime}");
        }
    }

    public function show($id)
    {
        try {
            // Find the task by ID
            $task = Task::where('deleted', 0)->findOrFail($id);
            
            // Ensure the logged-in user owns the task
            if ($task->user_id !== auth()->id()) {
                return abort(403, 'Unauthorized action.');
            }
    
            // Fetch all tasks created by the logged-in user
            $userTasks = Task::where('user_id', auth()->id())
                             ->where('deleted', 0)
                             ->get();
    
            return request()->expectsJson()
                ? response()->json(['task' => $task, 'user_tasks' => $userTasks], 200)
                : view('task-details', compact('task', 'userTasks'));
        } catch (\Exception $e) {
            Log::error('Error retrieving task details:', ['error' => $e->getMessage()]);
    
            return request()->expectsJson()
                ? response()->json(['error' => 'Failed to retrieve task details.'], 500)
                : redirect()->back()->with('error', 'Failed to retrieve task details.');
        }
    }

    public function revive($id)
{
    $task = Task::find($id);
    
    if (!$task) {
        return back()->with('error', 'Task not found.');
    }
    
    // Ensure the authenticated user owns the task
    if (auth()->id() !== $task->user_id) {
        return back()->with('error', 'Unauthorized action.');
    }
    
    try {
        // Mark the task as active
        $task->update(['deleted' => 0]);

        // Restore the previous completion status
        $task->update(['completion_status_id' => 1]);

        // Log the restoration
        Log::info('Task restored:', ['task_id' => $id, 'user_id' => auth()->id()]);

        // Send email notification
        Mail::to($task->user->email)->send(new TaskRestoredMail($task));

        return redirect()->route('viewing-all-tasks')->with('success', 'Task restored successfully.');
    } catch (\Exception $e) {
        Log::error('Task Restoration Failed:', ['error' => $e->getMessage()]);
        return back()->with('error', 'Failed to restore task.');
    }
}
    
    /**
     * Filter tasks by date range.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterByDate(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = Task::query();
        
        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('due_date', '<=', $endDate);
        }
        
        $tasks = $query->get();
        
        return response()->json(['success' => true, 'tasks' => $tasks]);
    }
    
    /**
     * Filter tasks by status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterByStatus(Request $request)
    {
        $statusIds = $request->input('status_ids', []);
        
        $query = Task::query();
        
        if (!empty($statusIds)) {
            $query->whereIn('completion_status_id', $statusIds);
        }
        
        $tasks = $query->get();
        
        return response()->json(['success' => true, 'tasks' => $tasks]);
    }
    
    /**
     * Search tasks by name.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchByName(Request $request)
    {
        $searchTerm = $request->input('search');
        
        $tasks = Task::where('name', 'LIKE', "%{$searchTerm}%")->get();
        
        return response()->json(['success' => true, 'tasks' => $tasks]);
    }


    public function searchTask(Request $request)
{
    $user_id = Auth::id();
    $user = User::find($user_id);

    if (!$user) {
        return view('welcome');
    }

    $user_name = $user->name;
    $perPage = 10;
    
    // Fetch search query
    $search = $request->input('search');

    // Sorting
    $sortBy = $request->get('sortBy', 'name');
    $sortOrder = $request->get('sortOrder', 'asc') === 'asc' ? 'desc' : 'asc';

    $validSortColumns = ['name', 'description', 'cost', 'budget', 'start_date', 'due_date', 'completion_status'];
    if (!in_array($sortBy, $validSortColumns)) {
        $sortBy = 'name';
    }

    // Fetching data
    $categories = Category::all();
    $recurrencies = Recurrency::all();
    $completionStatus = CompletionStatus::all();

    // Filtering tasks
    $tasksQuery = Task::orderBy($sortBy, $sortOrder);

    if ($search) {
        $tasksQuery = $this->applySearch($tasksQuery, $search);
    }

    $tasks = $tasksQuery->with('parentTask', 'childTasks')->paginate($perPage);

    foreach ($tasks as $task) {
        $task->parent_task_name = $task->parentTask ? $task->parentTask->name : 'Independent Task';
        $task->child_tasks = $task->childTasks()->select('id', 'name')->get(); 
        $now = Carbon::now();
        $startDate = Carbon::parse($task->start_date);
        $dueDate = Carbon::parse($task->due_date);

        if ($now->lt($startDate)) {
            $task->status = 'Pending';
        } elseif ($now->between($startDate, $dueDate)) {
            $task->status = 'In Progress';
        } else {
            $task->status = 'Incomplete';
        }
    }

    return view('Task.all-tasks-listing', compact(
        'user', 'user_id', 'sortBy', 'sortOrder', 
        'categories', 'completionStatus', 'recurrencies', 'tasks', 'search'
    ));
}
public function filterTasks(Request $request)
{
    // Fetch all completion statuses for the dropdown
    $completionStatus = CompletionStatus::all();

    $perPage = 10;

    // Fetch filter parameters from the request
    $completionStatusId = $request->input('status', 'all'); // Default to 'all' if no status is selected
    $maxBudget = $request->input('max_budget');
    $dueDate = $request->input('due_date');

    // Base query
    $tasksQuery = Task::query();

    // Apply status filter only if a specific status is selected
    if ($completionStatusId !== 'all') {
        // Filter tasks by the selected completion_status_id
        $tasksQuery->where('completion_status_id', $completionStatusId);
    }

    // Apply budget filter
    if ($maxBudget) {
        $tasksQuery->where('budget', '<=', $maxBudget);
    }

    // Apply due date filter
    if ($dueDate) {
        $tasksQuery->where('due_date', '<=', $dueDate);
    }

    // Fetch tasks with pagination and eager load related completion status
    $tasks = $tasksQuery->with(['parentTask', 'childTasks', 'completionStatus'])->paginate($perPage);

    // Calculate task status (Pending, In Progress, Incomplete)
    foreach ($tasks as $task) {
        $task->parent_task_name = $task->parentTask ? $task->parentTask->name : 'Independent Task';
        $task->child_tasks = $task->childTasks()->select('id', 'name')->get(); 
        $now = Carbon::now();
        $startDate = Carbon::parse($task->start_date);
        $dueDate = Carbon::parse($task->due_date);

        if ($now->lt($startDate)) {
            $task->status = 'Pending';
        } elseif ($now->between($startDate, $dueDate)) {
            $task->status = 'In Progress';
        } else {
            $task->status = 'Incomplete';
        }
    }

    // Fetch additional data for the view
    $categories = Category::all();
    $recurrencies = Recurrency::all();

    return view('Task.all-tasks-listing', compact(
        'categories', 'completionStatus', 'recurrencies', 'tasks'
    ));
}


    public function completedTasks(Request $request)
{
    // Fetch tasks where `deleted_at` is not null AND `deleted` is not 0
    $completedTasks = Task::where('deleted', 1)->paginate(10);

    return view('Task.deleted-tasks', compact('completedTasks'));
}


public function updateBudget(Request $request, $parentTaskId)
{
    try {
        // Find the parent task
        $parentTask = Task::find($parentTaskId);

        // Check if the parent task exists
        if (!$parentTask) {
            return redirect()->back()->with('error', 'Parent task not found.');
        }

        // Validate the request
        $request->validate([
            'additionalBudget' => 'required|numeric|min:0.01',
        ]);

        // Get the additional budget from the request
        $additionalBudget = $request->input('additionalBudget');

        // Store the original budget for logging
        $originalBudget = $parentTask->budget;

        // Update the parent task's budget
        $parentTask->budget += $additionalBudget;
        $parentTask->save();

        // Log the budget update
        Log::info('Parent Task Budget Updated:', [
            'parent_task_id' => $parentTaskId,
            'original_budget' => $originalBudget,
            'additional_budget' => $additionalBudget,
            'new_budget' => $parentTask->budget,
            'updated_by' => auth()->id() ?? 'System',
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Budget updated successfully.')->with('new_budget', $parentTask->budget);

    } catch (\Exception $e) {
        // Log the error
        Log::error('Error updating budget:', [
            'error' => $e->getMessage(),
            'parent_task_id' => $parentTaskId,
        ]);

        // Redirect back with an error message
        return redirect()->back()->with('error', 'An error occurred while updating the budget.');
    }
}
public function getChildren($id)
{
    $task = Task::find($id);

    if (!$task) {
        return response()->json(['error' => 'Task not found'], 404);
    }

    // Fetch child tasks using the relationship
    $children = $task->childTasks()->select('id', 'name')->get();

    return response()->json($children);
}


    
}