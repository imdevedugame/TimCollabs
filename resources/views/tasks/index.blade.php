@extends('layouts.app')

@section('content')
<div class="container py-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Task Management</h1>
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Tugas Baru
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @php
        // Asumsikan $tasks adalah collection dari semua tugas
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'completed')->count();
        $inProgressTasks = $tasks->where('status', 'in_progress')->count();
        $urgentTasks = $tasks->where('priority', 'high')->count();
        
        $completionPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks * 100) : 0;
        $inProgressPercentage = $totalTasks > 0 ? ($inProgressTasks / $totalTasks * 100) : 0;
        $urgentPercentage = $totalTasks > 0 ? ($urgentTasks / $totalTasks * 100) : 0;
        
        // Filter tugas yang masih aktif (deadline belum lewat atau tidak ada deadline)
        $activeTasks = $tasks->filter(function($task) {
            // Jika tidak ada deadline, dianggap aktif
            if (!$task->deadline) {
                return true;
            }
            // Jika deadline masih di masa depan, maka aktif
            return $task->deadline->isFuture();
        });
    @endphp

    <!-- Task Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Tasks Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-semibold text-gray-700">Total Tugas</h2>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Prosess</span>
                        <span class="text-2xl font-bold">{{ $totalTasks }}</span>
                    </div>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $completionPercentage }}%"></div>
            </div>
            <div class="mt-2 text-sm text-gray-500 text-right">
                {{ $completedTasks }} dari {{ $totalTasks }} selesai ({{ round($completionPercentage) }}%)
            </div>
        </div>

        <!-- In Progress Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="bg-yellow-100 p-3 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-semibold text-gray-700">Dalam Proses</h2>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Tugas Berlangsung</span>
                        <span class="text-2xl font-bold">{{ $inProgressTasks }}</span>
                    </div>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-yellow-400 h-2.5 rounded-full" style="width: {{ $inProgressPercentage }}%"></div>
            </div>
            <div class="mt-2 text-sm text-gray-500 text-right">
                {{ $inProgressTasks }} dari {{ $totalTasks }} dalam proses ({{ round($inProgressPercentage) }}%)
            </div>
        </div>

        <!-- Urgent Tasks Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 p-3 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-semibold text-gray-700">Tugas Mendesak</h2>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">High Priority</span>
                        <span class="text-2xl font-bold">{{ $urgentTasks }}</span>
                    </div>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ $urgentPercentage }}%"></div>
            </div>
            <div class="mt-2 text-sm text-gray-500 text-right">
                {{ $urgentTasks }} dari {{ $totalTasks }} mendesak ({{ round($urgentPercentage) }}%)
            </div>
        </div>
    </div>

    <!-- Task List and Calendar Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Task List -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Tugas</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left">Judul</th>
                            <th class="px-6 py-3 text-left">Prioritas</th>
                            <th class="px-6 py-3 text-left">Deadline</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            // Ambil tugas aktif (yang belum lewat deadline)
                            $displayTasks = $activeTasks;
                        @endphp
                        @forelse ($displayTasks as $task)
                        @php
                            // Ambil role user dari pivot pada task ini
                            $currentUser = auth()->user();
                            $userTask = $task->users->firstWhere('id', $currentUser->id);
                            $role = $userTask ? $userTask->pivot->role : null;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $task->title }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($task->priority == 'high')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    High
                                </span>
                                @elseif($task->priority == 'medium')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Medium
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Low
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $task->deadline ? $task->deadline->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-900" title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if($role !== 'member')
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus" onclick="return confirm('Yakin hapus tugas ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p>Belum ada tugas.</p>
                                    <a href="{{ route('tasks.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                        Buat Tugas Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Upcoming Deadlines -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Deadline Mendatang</h2>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="p-6">
                @php
                    $upcomingTasks = $tasks->where('deadline', '>=', now())->sortBy('deadline')->take(5);
                @endphp
                
                @if($upcomingTasks->count() > 0)
                    @foreach($upcomingTasks as $task)
                    <div class="mb-4 pb-4 border-b border-gray-100 last:border-0 last:mb-0 last:pb-0">
                        <div class="flex items-start">
                            @if($task->priority == 'high')
                            <span class="flex-shrink-0 w-2 h-2 rounded-full bg-red-500 mt-2 mr-3"></span>
                            @elseif($task->priority == 'medium')
                            <span class="flex-shrink-0 w-2 h-2 rounded-full bg-yellow-500 mt-2 mr-3"></span>
                            @else
                            <span class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500 mt-2 mr-3"></span>
                            @endif
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                <p class="text-sm text-gray-500">Due: {{ $task->deadline ? $task->deadline->format('d M Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-6 text-gray-500">
                        <p>Tidak ada deadline mendatang.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Task Progress Cards -->
    @php
        // Filter tugas untuk progress card: hanya ambil tugas aktif (deadline belum lewat atau tidak ada deadline)
        $recentTasks = $tasks->filter(function($task) {
            if (!$task->deadline) {
                return true;
            }
            return $task->deadline->isFuture();
        })->sortByDesc('created_at')->take(2);
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($recentTasks as $task)
        @php
            // Hitung progress berdasarkan subtasks
            $totalSubtasks = $task->subtasks->count();
            $completedSubtasks = $task->subtasks->where('is_done', true)->count();
            $taskProgress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
        @endphp
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-start mb-4">
                @if($task->priority == 'high')
                <span class="flex-shrink-0 w-3 h-3 rounded-full bg-red-500 mt-1 mr-3"></span>
                @elseif($task->priority == 'medium')
                <span class="flex-shrink-0 w-3 h-3 rounded-full bg-yellow-500 mt-1 mr-3"></span>
                @else
                <span class="flex-shrink-0 w-3 h-3 rounded-full bg-green-500 mt-1 mr-3"></span>
                @endif
                <div class="flex-grow">
                    <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                    <p class="text-sm text-gray-500">Due: {{ $task->deadline ? $task->deadline->format('d M Y') : '-' }}</p>
                </div>
                <div class="bg-gray-100 text-gray-700 text-xs font-medium px-2 py-1 rounded">
                    {{ $task->status == 'completed' ? 'Completed' : 'In Progress' }}
                </div>
            </div>
            
            <div class="mb-4">
                <div class="flex justify-between text-sm mb-1">
                    <span>Prosess</span>
                    <span>{{ $taskProgress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $taskProgress }}%"></div>
                </div>
            </div>
            
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    <span>{{ $completedSubtasks }}/{{ $totalSubtasks }} subtasks</span>
                </div>
                <a href="{{ route('tasks.show', $task->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                    Lihat Tugas →
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
