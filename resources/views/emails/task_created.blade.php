@component('mail::message')
# New Task Created

A new task has been created for you!

**Task ID:** {{ $task->id }}  
**Title:** {{ $task->title }}  
**Description:** {{ $task->description }}  
**Due Date:** {{ $task->due_date }}

@component('mail::button', ['url' => route('tasks.showOneTask', ['id' => $task->id])])  



View Task
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent