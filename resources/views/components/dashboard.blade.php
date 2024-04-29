<x-layout title="{{ $title }}">
    <div class="min-h-full">
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="flex-shrink-0">
                            <a href="/home" class="text-white font-bold text-2xl">TM</a>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="/tasks" class="text-white p-4 hover:bg-gray-700 hover:underline transition">View Tasks</a>
                        </div>
                    </div>
                    <div>
                        <div class="ml-4 flex items-center space-x-2">
                            <div class="text-white">
                                @if (Auth::check())
                                    Welcome, <span class="font-black">{{ Auth::user()->name }}</span>
                                @endif
                            </div>
                            <div>
                                <form action="/logout" method="post">
                                    @csrf
                                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $title }}</h1>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

</x-layout>
