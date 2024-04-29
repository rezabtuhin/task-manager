<h2>
    Title: {{ $task->title }}
</h2>
<p>
    New Task has been assigned to you
</p>

<p>
    View the Task <a href="{{ config('app.url') }}/task/{{ $task->id }}">Here</a>
</p>
