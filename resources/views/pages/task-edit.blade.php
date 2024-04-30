<x-dashboard title="Edit task: {{ $cachedTask->title }}">
    @if($cachedTask->status != "Deployed")
    <form id="task-update">
        @csrf
    @endif
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                        <div class="mt-2">
                            <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 sm:max-w-md">
                                <input value="{{ $cachedTask->title }}" class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label for="about" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                        <div class="mt-2">
                            <textarea rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" disabled>{{ $cachedTask->description }}</textarea>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <div class="">
                            <label for="country" class="block text-sm font-medium leading-6 text-gray-900">Priority</label>
                            <div class="mt-2">
                                <input value="{{ $cachedTask->priority }}" class="block flex-1 border bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" disabled>
                            </div>
                        </div>
                        <div class="">
                            <label for="country" class="block text-sm font-medium leading-6 text-gray-900">Assignee</label>
                            <div class="mt-2">
                                <input value="{{ $cachedTask->assignee }}" class="block flex-1 border bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" disabled>
                            </div>
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                            @if($cachedTask->status == "Deployed")
                                <div class="mt-2">
                                    <input value="{{ $cachedTask->status }}" class="block flex-1 border bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" disabled>
                                </div>
                            @else
                                <div class="mt-2">
                                    <select id="status" name="status" autocomplete="status" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        @foreach(array('New', 'In Progress', 'Testing', 'Deployed') as $status)
                                            <option value="{{ $status }}" @if($cachedTask->status == $status) selected @endif>
                                                {{$status}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-900">Images</label>
                        <div class="flex space-x-2">
                            @foreach($cachedTask->images as $image)
                                <img src="{{ asset($image) }}" alt="" srcset="" width="100">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @if($cachedTask->status != "Deployed")
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="submit" id="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Update</button>
        </div>
    </form>

    <script>
        $(document).ready(function (){
            $('#task-update').submit(function(event) {
                event.preventDefault();
                let formData = $(this).serialize();
                const token = '{{ session('user_token') }}';
                const headers = {
                    'Authorization': 'Bearer ' + token
                };
                formData += '&token=' + encodeURIComponent(token);
                $.ajax({
                    type: 'PUT',
                    url: '/api/task/{{ $cachedTask->id }}',
                    data: formData,
                    headers: headers,
                    success: function(response) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        });
                    },
                    error: function(xhr, status, error) {
                        const response = JSON.parse(xhr.responseText);
                        console.log(response)
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "error",
                            title: response.message
                        });
                    }
                })
            })
        })
    </script>
    @endif
</x-dashboard>
