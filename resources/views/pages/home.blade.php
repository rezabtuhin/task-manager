<x-dashboard title="Create Task">
    <form id="task-create" enctype="multipart/form-data">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                        <div class="mt-2">
                            <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 sm:max-w-md">
                                <input type="text" name="title" id="title" class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label for="about" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                        <div class="mt-2">
                            <textarea id="description" name="description" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                    <div class="columns-2">
                        <div class="sm:col-span-6">
                            <label for="country" class="block text-sm font-medium leading-6 text-gray-900">Priority</label>
                            <div class="mt-2">
                                <select id="priority" name="priority" autocomplete="country-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="country" class="block text-sm font-medium leading-6 text-gray-900">Assignee</label>
                            <div class="mt-2">
                                <select id="assignee" name="assignee" autocomplete="country-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                    @foreach($usersArray as $userToken => $userName)
                                        <option value="{{ $userToken }}">{{ $userName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-900">Images</label>
                        <input id="images" type="file" name="images[]" multiple>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="submit" id="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Create</button>
        </div>
    </form>
    <script>
        $(document).ready(function (e){
            $('#task-create').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const token = '{{ session('user_token') }}';
                formData.append('token', token);
                const headers = {
                    'Authorization': 'Bearer ' + token
                };
                $.ajax({
                    type: 'POST',
                    url: '{{ url('api/create-task') }}',
                    data: formData,
                    headers: headers,
                    processData: false,
                    contentType: false,
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
                        document.getElementById('task-create').reset();
                    },
                    error: function(xhr, status, error) {
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
                            title: error
                        });
                    }
                });
            })
        })
    </script>

</x-dashboard>
