
<div class="container">
    <h1>Delete Child Tasks for "{{ $parentTask->name }}"</h1>
    <form action="{{ route('delete-child-tasks', $parentTask->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <ul>
            @foreach ($childTasks as $childTask)
                <li>
                    <input type="checkbox" name="child_tasks[]" value="{{ $childTask->id }}">
                    {{ $childTask->name }}
                </li>
            @endforeach
        </ul>
        <button type="submit" class="btn btn-danger">Delete Selected Tasks</button>
    </form>
</div>
