@component('mail::message')
# Task Deleted

Hello {{ $task->user->name }},

We wanted to let you know that the task "{{ $task->name }}" has been deleted.

If you have any questions, feel free to reach out.

Thanks,<br>
{{ config('app.name') }}
@endcomponent