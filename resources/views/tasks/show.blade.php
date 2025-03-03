@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Tugas</h1>
                <p class="text-sm text-gray-500">Mengelola dan memantau progress tugas</p>
            </div>
        </div>
        <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Task Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $task->title }}</h2>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-gray-700">
                        {{ $task->description }}
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Prioritas</p>
                                <p class="text-sm text-gray-500">{{ ucfirst($task->priority) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Deadline</p>
                                <p class="text-sm text-gray-500">
                                    {{ $task->deadline ? $task->deadline->format('d M Y H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @php
                        $totalSubtasks = $task->subtasks->count();
                        $doneSubtasks = $task->subtasks->where('is_done', true)->count();
                        $progress = $totalSubtasks > 0 ? ($doneSubtasks / $totalSubtasks) * 100 : 0;

                        if ($progress == 100) {
                            $statusText = "Selesai";
                            $progressColor = "bg-green-600";
                            $statusBadgeColor = "bg-green-100 text-green-800";
                            $icon = "check-circle";
                        } elseif ($progress >= 70) {
                            $statusText = "Hampir Selesai";
                            $progressColor = "bg-blue-600";
                            $statusBadgeColor = "bg-blue-100 text-blue-800";
                            $icon = "trending-up";
                        } elseif ($progress >= 30) {
                            $statusText = "Dalam Proses";
                            $progressColor = "bg-yellow-500";
                            $statusBadgeColor = "bg-yellow-100 text-yellow-800";
                            $icon = "clock";
                        } else {
                            $statusText = "Baru Dimulai";
                            $progressColor = "bg-red-500";
                            $statusBadgeColor = "bg-red-100 text-red-800";
                            $icon = "alert-circle";
                        }
                    @endphp

                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-900">Progress</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadgeColor }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M{{ $icon }}" />
                                </svg>
                                {{ $statusText }}
                            </span>
                        </div>
                        <div class="relative">
                            <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                                <div class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $progressColor }}" 
                                     style="width: {{ $progress }}%">
                                </div>
                            </div>
                            <div class="text-right mt-1">
                                <span class="text-sm font-semibold text-gray-900">{{ (int)$progress }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subtasks -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900">Subtasks</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $doneSubtasks }}/{{ $totalSubtasks }} selesai
                    </span>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($task->subtasks as $subtask)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                            <div class="flex items-center">
                                <form action="{{ route('subtasks.update', $subtask->id) }}" method="POST" class="flex-shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <input type="checkbox" name="is_done" 
                                           onChange="this.form.submit()" 
                                           {{ $subtask->is_done ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                </form>
                                <span class="ml-3 text-sm {{ $subtask->is_done ? 'line-through text-gray-500' : 'text-gray-900' }}">
                                    {{ $subtask->title }}
                                </span>
                            </div>
                            @if($subtask->is_done)
                                <span class="text-xs text-gray-500">Selesai</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="p-4 bg-gray-50">
                    <form action="{{ route('subtasks.store', $task->id) }}" method="POST" class="flex space-x-3">
                        @csrf
                        <input type="text" name="title" placeholder="Tambah subtask baru..." required
                               class="flex-1 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Comments -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-900">Diskusi</h2>
                </div>
                <div class="p-6 space-y-6">
                    @foreach($task->comments as $comment)
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-600">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1 bg-gray-50 rounded-lg px-4 py-3 sm:px-6">
                                <div class="flex justify-between items-center mb-1">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</h3>
                                    <time class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</time>
                                </div>
                                <p class="text-sm text-gray-700">{{ $comment->message }}</p>
                            </div>
                        </div>
                    @endforeach

                    <form action="{{ route('comments.store', ['task' => $task->id]) }}" method="POST" class="mt-6">
                        @csrf
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-600">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <textarea name="message" rows="3" required
                                          class="shadow-sm block w-full focus:ring-blue-500 focus:border-blue-500 sm:text-sm border border-gray-300 rounded-md"
                                          placeholder="Tulis komentar..."></textarea>
                                <div class="mt-3 flex justify-end">
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        Kirim
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Team Collaboration -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-900">Tim</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('tasks.invite', $task->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" required
                                   class="mt-1 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   placeholder="nama@email.com">
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="member">Member</option>
                                <option value="owner">Owner</option>
                                <option value="observer">Observer</option>
                            </select>
                        </div>
                        <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Undang Anggota
                        </button>
                    </form>

                    <div class="mt-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Anggota Tim</h3>
                        <ul class="mt-3 space-y-3">
                            @foreach($task->users as $user)
                                <li class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-md">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">
                                                    {{ substr($user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->pivot->role }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <button type="button" class="text-gray-400 hover:text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Task Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-900">Aksi</h2>
                </div>
                <div class="p-6 space-y-4">
                    <a href="{{ route('tasks.edit', $task->id) }}"
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Tugas
                    </a>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Tugas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
