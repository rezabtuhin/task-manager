@php use App\Models\User; @endphp
<x-dashboard title="Task List">
    @foreach ($tasks as $task)
        <div class="p-3 mb-2 border-2 text-[20px] flex items-center justify-between">
            <div>
                <p><span class="font-black text-blue-700">Title:</span> {{ $task->title }}</p>
                <p><span class="font-black text-blue-700">Priority:</span> {{ $task->priority }}</p>
                <p><span class="font-black text-blue-700">Status:</span> {{ $task->status }}</p>
                <p><span
                        class="font-black text-blue-700">Assignee:</span> {{ $task->assignee ? User::find($task->assignee)['name'] : "N/A" }}
                </p>
            </div>
            <div class="space-x-2">
                <a href="/task/{{ $task->id }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                    View Task
                </a>
                <a href="/task/{{ $task->id }}/edit" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 hover:border-blue-500 rounded">
                    Edit Task
                </a>
            </div>
        </div>
    @endforeach

    {{ $tasks->links() }}
</x-dashboard>
