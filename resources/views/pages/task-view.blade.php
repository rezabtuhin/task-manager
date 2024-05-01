<x-dashboard title="Task: {{ $cachedTask->title }}">
    <div class="flex justify-between">
        <div class="space-y-4">
            <div>
                <h1 class="text-[20px] font-black">Description</h1>
                <p class="max-w-4xl" style="word-break: break-word">{{ $cachedTask->description }}</p>
            </div>
            <div>
                <h1 class="text-[20px] font-black">Priority</h1>
                <p>{{ $cachedTask->priority }}</p>
            </div>
            <div>
                <h1 class="text-[20px] font-black">Assigned To</h1>
                <p>{{ $cachedTask->assignee }}</p>
            </div>
            <div>
                <h1 class="text-[20px] font-black">Current Status</h1>
                <p>{{ $cachedTask->status }}</p>
            </div>
            <div>
                <h1 class="text-[20px] font-black">Images</h1>
                <div class="flex space-x-2">
                    @php
                        $imageFound = false;
                    @endphp
                    @foreach($cachedTask->images as $image)
                        @if (strpos($image, '.') !== false)
                            @php
                                $extension = pathinfo($image, PATHINFO_EXTENSION);
                            @endphp
                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp']))
                                @php
                                    $imageFound = true;
                                @endphp
                                <img src="{{ asset($image) }}" alt="" srcset="" width="100">
                            @endif
                        @endif
                    @endforeach
                    @if (!$imageFound)
                        <p>No images provided</p>
                    @endif
                </div>
            </div>
            <div>
                <h1 class="text-[20px] font-black">Files</h1>
                <ol class="list-decimal">
                    @php
                        $fileFound = false;
                    @endphp
                    @foreach($cachedTask->images as $image)
                        @php
                            $fileName = basename($image);
                        @endphp
                        @if (strpos($image, '.') !== false)
                            @php
                                $extension = pathinfo($image, PATHINFO_EXTENSION);
                            @endphp
                            @if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp']))
                                @php
                                    $fileFound = true;
                                @endphp
                                <li><a class="text-blue-700 hover:underline" href="{{ asset($image) }}" download>{{ $fileName }}</a></li>
                            @endif
                        @else
                            @php
                                $fileFound = true;
                            @endphp
                            <li><a class="text-blue-700 hover:underline" href="{{ asset($image) }}" download>{{ $fileName }}</a></li>
                        @endif
                    @endforeach
                    @if (!$fileFound)
                        <p>No files provided</p>
                    @endif
                </ol>
            </div>

        </div>
        <div class="space-x-2">
            <a href="/task/{{ $cachedTask->id }}/edit" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 hover:border-blue-500 rounded text-[19px]">
                Edit Task
            </a>
            <button id="deleteTaskBtn" data-task-id="{{ $cachedTask->id }}" class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 hover:border-blue-500 rounded">
                Delete Task
            </button>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#deleteTaskBtn').click(function() {
                const taskId = $(this).data('task-id');
                if(confirm('Are you sure you want to delete this task?')){
                    const token = '{{ session('user_token') }}';
                    const headers = {
                        'Authorization': 'Bearer ' + token
                    };
                    $.ajax({
                        url: '{{ url('api/task/delete/') }}/' + taskId,
                        type: 'DELETE',
                        headers: headers,
                        success: function(response) {
                            alert('Task deleted successfully');
                            window.location.href = '/tasks';
                        },
                        error: function(xhr, status, error) {
                            alert(error)
                        }
                    });
                }
            })
        })
    </script>
</x-dashboard>
