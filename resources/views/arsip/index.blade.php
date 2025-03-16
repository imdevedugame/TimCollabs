@extends('layouts.app')

@section('content')
<div class="container py-6">
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Arsip Tugas</h1>
                <p class="text-gray-600">Daftar tugas yang telah melewati deadline</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <select id="filter-status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="all">Semua Status</option>
                        <option value="completed">Selesai</option>
                        <option value="in_progress">Belum Selesai</option>
                    </select>
                </div>
                <div class="relative">
                    <select id="filter-priority" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="all">Semua Prioritas</option>
                        <option value="high">Tinggi</option>
                        <option value="medium">Sedang</option>
                        <option value="low">Rendah</option>
                    </select>
                </div>
                <div class="relative">
                    <select id="filter-time" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="all">Semua Waktu</option>
                        <option value="week">Minggu Terakhir</option>
                        <option value="month">Bulan Terakhir</option>
                        <option value="quarter">3 Bulan Terakhir</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <!-- Archive Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @php
            // Assuming $archivedTasks contains all tasks that have passed their deadlines
            $totalArchived = $archivedTasks->count();
            $completedArchived = $archivedTasks->where('status', 'completed')->count();
            $incompleteArchived = $totalArchived - $completedArchived;
            
            $highPriorityArchived = $archivedTasks->where('priority', 'high')->count();
            
            $completionRate = $totalArchived > 0 ? ($completedArchived / $totalArchived * 100) : 0;
            $incompletionRate = $totalArchived > 0 ? ($incompleteArchived / $totalArchived * 100) : 0;
            $highPriorityRate = $totalArchived > 0 ? ($highPriorityArchived / $totalArchived * 100) : 0;
        @endphp
        
        <!-- Total Archived Tasks -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-semibold text-gray-700">Total Arsip</h2>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Tugas Melewati Deadline</span>
                        <span class="text-2xl font-bold">{{ $totalArchived }}</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-between text-sm mb-1">
                <span class="text-green-600">Selesai: {{ $completedArchived }}</span>
                <span class="text-red-600">Belum Selesai: {{ $incompleteArchived }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-green-500 h-2.5 rounded-l-full" style="width: {{ $completionRate }}%"></div>
            </div>
        </div>

        <!-- Incomplete Archived Tasks -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 p-3 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-semibold text-gray-700">Belum Selesai</h2>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Melewati Deadline</span>
                        <span class="text-2xl font-bold">{{ $incompleteArchived }}</span>
                    </div>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ $incompletionRate }}%"></div>
            </div>
            <div class="mt-2 text-sm text-gray-500 text-right">
                {{ round($incompletionRate) }}% dari total arsip
            </div>
        </div>

        <!-- High Priority Archived Tasks -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="bg-amber-100 p-3 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-semibold text-gray-700">Prioritas Tinggi</h2>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Melewati Deadline</span>
                        <span class="text-2xl font-bold">{{ $highPriorityArchived }}</span>
                    </div>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-amber-500 h-2.5 rounded-full" style="width: {{ $highPriorityRate }}%"></div>
            </div>
            <div class="mt-2 text-sm text-gray-500 text-right">
                {{ round($highPriorityRate) }}% dari total arsip
            </div>
        </div>
    </div>

    <!-- Archived Tasks List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-8">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Tugas Melewati Deadline</h2>
            <div class="relative">
                <input type="text" id="search-archive" placeholder="Cari tugas..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Judul</th>
                        <th class="px-6 py-3 text-left">Prioritas</th>
                        <th class="px-6 py-3 text-left">Deadline</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Keterlambatan</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($archivedTasks as $task)
                    @php
                        $deadlineDate = \Carbon\Carbon::parse($task->deadline);
                        $daysLate = (int)($deadlineDate->diffInDays(now(), false));
$daysLateText = $daysLate > 0 ? $daysLate . ' hari' : 'Hari ini';

                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $task->title }}</div>
                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ $task->description }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($task->priority == 'high')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Tinggi
                            </span>
                            @elseif($task->priority == 'medium')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Sedang
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Rendah
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $task->deadline ? $deadlineDate->format('d M Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($task->status == 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="mr-1.5 h-2 w-2 text-green-600" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Selesai
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="mr-1.5 h-2 w-2 text-red-600" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Belum Selesai
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="text-red-600 font-medium">{{ $daysLateText }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-900" title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @if($task->status != 'completed')
                                <form action="{{ route('tasks.complete', $task->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Tandai Selesai">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('tasks.edit', $task->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                <p>Tidak ada tugas yang melewati deadline.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($archivedTasks->count() > 0)
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $archivedTasks->links() }}
        </div>
        @endif
    </div>

    <!-- Analysis Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Overdue by Category -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Analisis Keterlambatan</h3>
            
            @php
                $categories = [
                    'week' => $archivedTasks->filter(function($task) {
                        $deadline = \Carbon\Carbon::parse($task->deadline);
                        return $deadline->diffInDays(now()) <= 7;
                    })->count(),
                    'month' => $archivedTasks->filter(function($task) {
                        $deadline = \Carbon\Carbon::parse($task->deadline);
                        return $deadline->diffInDays(now()) > 7 && $deadline->diffInDays(now()) <= 30;
                    })->count(),
                    'quarter' => $archivedTasks->filter(function($task) {
                        $deadline = \Carbon\Carbon::parse($task->deadline);
                        return $deadline->diffInDays(now()) > 30 && $deadline->diffInDays(now()) <= 90;
                    })->count(),
                    'older' => $archivedTasks->filter(function($task) {
                        $deadline = \Carbon\Carbon::parse($task->deadline);
                        return $deadline->diffInDays(now()) > 90;
                    })->count(),
                ];
            @endphp
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">< 1 minggu</span>
                        <span class="text-sm text-gray-600">{{ $categories['week'] }} tugas</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $totalArchived > 0 ? ($categories['week'] / $totalArchived * 100) : 0 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">1-4 minggu</span>
                        <span class="text-sm text-gray-600">{{ $categories['month'] }} tugas</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $totalArchived > 0 ? ($categories['month'] / $totalArchived * 100) : 0 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">1-3 bulan</span>
                        <span class="text-sm text-gray-600">{{ $categories['quarter'] }} tugas</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $totalArchived > 0 ? ($categories['quarter'] / $totalArchived * 100) : 0 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">> 3 bulan</span>
                        <span class="text-sm text-gray-600">{{ $categories['older'] }} tugas</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $totalArchived > 0 ? ($categories['older'] / $totalArchived * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Completion Rate by Priority -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tingkat Penyelesaian Berdasarkan Prioritas</h3>
            
            @php
                $highPriorityTotal = $archivedTasks->where('priority', 'high')->count();
                $highPriorityCompleted = $archivedTasks->where('priority', 'high')->where('status', 'completed')->count();
                $highPriorityRate = $highPriorityTotal > 0 ? ($highPriorityCompleted / $highPriorityTotal * 100) : 0;
                
                $mediumPriorityTotal = $archivedTasks->where('priority', 'medium')->count();
                $mediumPriorityCompleted = $archivedTasks->where('priority', 'medium')->where('status', 'completed')->count();
                $mediumPriorityRate = $mediumPriorityTotal > 0 ? ($mediumPriorityCompleted / $mediumPriorityTotal * 100) : 0;
                
                $lowPriorityTotal = $archivedTasks->where('priority', 'low')->count();
                $lowPriorityCompleted = $archivedTasks->where('priority', 'low')->where('status', 'completed')->count();
                $lowPriorityRate = $lowPriorityTotal > 0 ? ($lowPriorityCompleted / $lowPriorityTotal * 100) : 0;
            @endphp
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex items-center">
                            <span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                            <span class="text-sm font-medium text-gray-700">Prioritas Tinggi</span>
                        </div>
                        <span class="text-sm text-gray-600">{{ $highPriorityCompleted }}/{{ $highPriorityTotal }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ $highPriorityRate }}%"></div>
                    </div>
                    <div class="text-right text-xs text-gray-500 mt-1">{{ round($highPriorityRate) }}% selesai</div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex items-center">
                            <span class="inline-block w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                            <span class="text-sm font-medium text-gray-700">Prioritas Sedang</span>
                        </div>
                        <span class="text-sm text-gray-600">{{ $mediumPriorityCompleted }}/{{ $mediumPriorityTotal }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ $mediumPriorityRate }}%"></div>
                    </div>
                    <div class="text-right text-xs text-gray-500 mt-1">{{ round($mediumPriorityRate) }}% selesai</div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex items-center">
                            <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                            <span class="text-sm font-medium text-gray-700">Prioritas Rendah</span>
                        </div>
                        <span class="text-sm text-gray-600">{{ $lowPriorityCompleted }}/{{ $lowPriorityTotal }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $lowPriorityRate }}%"></div>
                    </div>
                    <div class="text-right text-xs text-gray-500 mt-1">{{ round($lowPriorityRate) }}% selesai</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter functionality
        const filterStatus = document.getElementById('filter-status');
        const filterPriority = document.getElementById('filter-priority');
        const filterTime = document.getElementById('filter-time');
        const searchInput = document.getElementById('search-archive');
        
        // Add event listeners for filters
        if (filterStatus) {
            filterStatus.addEventListener('change', applyFilters);
        }
        
        if (filterPriority) {
            filterPriority.addEventListener('change', applyFilters);
        }
        
        if (filterTime) {
            filterTime.addEventListener('change', applyFilters);
        }
        
        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }
        
        function applyFilters() {
            // This would typically be handled with AJAX or form submission
            // For now, we'll just reload the page with query parameters
            const status = filterStatus ? filterStatus.value : 'all';
            const priority = filterPriority ? filterPriority.value : 'all';
            const time = filterTime ? filterTime.value : 'all';
            const search = searchInput ? searchInput.value : '';
            
            const url = new URL(window.location.href);
            url.searchParams.set('status', status);
            url.searchParams.set('priority', priority);
            url.searchParams.set('time', time);
            
            if (search) {
                url.searchParams.set('search', search);
            } else {
                url.searchParams.delete('search');
            }
            
            window.location.href = url.toString();
        }
    });
</script>
@endsection