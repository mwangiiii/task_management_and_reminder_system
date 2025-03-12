<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
</head>
<body>

    <h2>Task Details</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <td>{{ $task->name }}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $task->description }}</td>
        </tr>
        <tr>
            <th>Alert Time</th>
            <td>{{ \Carbon\Carbon::parse($task->task_alert)->format('F j, Y g:i A') }}</td>
        </tr>
        <tr>
            <th>Cost</th>
            <td>${{ number_format($task->cost, 2) }}</td>
        </tr>
        <tr>
            <th>Category</th>
            <td>{{ $task->category->type ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Recurrency</th>
            <td>{{ $task->recurrency->frequency ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Completion Status</th>
            <td>{{ $task->completionStatus->status ?? 'Pending' }}</td>
        </tr>
        <tr>
            <th>Start Date</th>
            <td>{{ \Carbon\Carbon::parse($task->start_date)->format('F j, Y g:i A') }}</td>
        </tr>
        <tr>
            <th>Due Date</th>
            <td>{{ \Carbon\Carbon::parse($task->due_date)->format('F j, Y g:i A') }}</td>
        </tr>
        <tr>
    <th>Uploads</th>
    <td>
        @if ($task->uploads && $task->uploads->isNotEmpty())
            <ul>
                @foreach ($task->uploads as $upload)
                    <li>
                        <a href="{{ asset('storage/uploads/' . $upload->filename) }}" target="_blank">
                            {{ $upload->filename }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            No uploads available.
        @endif
    </td>
</tr>

    </table>

    <hr>

    <h2>Your Tasks</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Start Date</th>
            <th>Due Date</th>
            <th>Actions</th>
        </tr>
        @foreach ($userTasks as $userTask)
            <tr>
                <td>{{ $userTask->name }}</td>
                <td>{{ $userTask->description }}</td>
                <td>{{ \Carbon\Carbon::parse($userTask->start_date)->format('F j, Y g:i A') }}</td>
                <td>{{ \Carbon\Carbon::parse($userTask->due_date)->format('F j, Y g:i A') }}</td>
                <td>
                    <a href="{{ route('tasks.show', $userTask->id) }}">View</a>
                </td>
            </tr>
        @endforeach
    </table>

</body>
</html>
