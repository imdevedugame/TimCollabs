@extends('layouts.app')

@section('content')
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Welcome back, {{ auth()->user()->name }}!
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Here's your task overview for today
                    </p>
                </div>
                <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Task
                </button>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Task Statistics -->
        <div class="grid gap-6 mb-8 md:grid-cols-3">
        @php
    // Menggabungkan semua tugas (milik user dan yang diikuti)
    $allTasks = auth()->user()->ownedTasks->merge(auth()->user()->tasks);

    // Total semua tugas
    $totalTasks = $allTasks->count();

    // Menghitung jumlah tugas yang sudah selesai (semua subtasks selesai)
    $completedTasks = $allTasks->filter(function($task) {
        return $task->subtasks->where('is_done', true)->count() === $task->subtasks->count() 
            && $task->subtasks->count() > 0;
    })->count();

    // Menghitung jumlah tugas yang sedang dalam progres
    $inProgressTasks = $totalTasks - $completedTasks;

    // Menghitung jumlah tugas dengan prioritas tinggi
    $urgentTasks = $allTasks->where('priority', 'high')->count();
@endphp

            
            <!-- Total Tasks Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Tasks</h3>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTasks }}</span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Progress</span>
                            <span class="text-gray-700 dark:text-gray-300">
                                {{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                            <div class="h-2 bg-blue-600 rounded-full" 
                                 style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- In Progress Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">In Progress</h3>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $inProgressTasks }}</span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Active Tasks</span>
                            <span class="text-gray-700 dark:text-gray-300">
                                {{ $totalTasks > 0 ? round(($inProgressTasks / $totalTasks) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                            <div class="h-2 bg-yellow-500 rounded-full" 
                                 style="width: {{ $totalTasks > 0 ? ($inProgressTasks / $totalTasks) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Urgent Tasks Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Urgent Tasks</h3>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $urgentTasks }}</span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">High Priority</span>
                            <span class="text-gray-700 dark:text-gray-300">
                                {{ $totalTasks > 0 ? round(($urgentTasks / $totalTasks) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                            <div class="h-2 bg-red-500 rounded-full" 
                                 style="width: {{ $totalTasks > 0 ? ($urgentTasks / $totalTasks) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @php
    $allTasks = auth()->user()->ownedTasks->merge(auth()->user()->tasks);
@endphp

@foreach($allTasks as $task)
    @php
        $completedSubtasks = $task->subtasks->where('is_done', true)->count();
        $totalSubtasks = $task->subtasks->count();
        $progress = $totalSubtasks > 0 ? ($completedSubtasks / $totalSubtasks) * 100 : 0;

        $priorityColor = match($task->priority) {
            'high' => 'red',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray'
        };
    @endphp
                
                <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-colors">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-{{ $priorityColor }}-100 dark:bg-{{ $priorityColor }}-900/50">
                                    <span class="w-3 h-3 rounded-full bg-{{ $priorityColor }}-500"></span>
                                </span>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $task->title }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Due {{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex -space-x-2">
                                @foreach($task->users->take(3) as $user)
                                    <img 
                                        src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" 
                                        alt="{{ $user->name }}"
                                        class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800"
                                        title="{{ $user->name }}"
                                    >
                                @endforeach
                                @if($task->users->count() > 3)
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full border-2 border-white dark:border-gray-800 bg-gray-100 dark:bg-gray-700">
                                        <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                            +{{ $task->users->count() - 3 }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <p class="mb-4 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                            {{ $task->description }}
                        </p>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Progress</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ round($progress) }}%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                                <div class="h-2 bg-{{ $priorityColor }}-500 rounded-full transition-all" 
                                     style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ $completedSubtasks }}/{{ $totalSubtasks }} subtasks
                            </div>
                            <a href="{{ route('tasks.show', $task->id) }}" 
                               class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
@endsection
