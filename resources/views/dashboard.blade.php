@extends('layouts.app')

@section('content')
<!-- Header Full-Width -->
<header class="w-full bg-blue-600 text-white py-6">
  <div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold">selamat Datang, {{ auth()->user()->name }}!</h1>
    <p class="mt-2 text-lg">Berikut Semua Tugas yang kamu buat</p>
  </div>
</header>

<main class="container mx-auto px-4 py-8">
  @php
    // Gabungkan semua tugas: milik user (ownedTasks) dan yang diikuti (tasks)
    $allTasks = auth()->user()->ownedTasks->merge(auth()->user()->tasks);

    // Filter tugas yang masih aktif: jika tidak ada deadline, atau deadline di masa depan
    $activeAllTasks = $allTasks->filter(function($task) {
      if (!$task->deadline) {
          return true;
      }
      return \Carbon\Carbon::parse($task->deadline)->isFuture();
    });

    // Hitung statistik berdasarkan tugas aktif
    $totalTasks = $activeAllTasks->count();
    $completedTasks = $activeAllTasks->filter(function($task) {
      return $task->subtasks->where('is_done', true)->count() === $task->subtasks->count()
          && $task->subtasks->count() > 0;
    })->count();
    $inProgressTasks = $totalTasks - $completedTasks;
    $urgentTasks = $activeAllTasks->where('priority', 'high')->count();

    $completionPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks * 100) : 0;
    $inProgressPercentage = $totalTasks > 0 ? ($inProgressTasks / $totalTasks * 100) : 0;
    $urgentPercentage = $totalTasks > 0 ? ($urgentTasks / $totalTasks * 100) : 0;
  @endphp

  <!-- Baris pertama: Statistik Umum Tugas -->
  <div class="grid gap-6 mb-8 md:grid-cols-3">
    <!-- Total Tasks Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
            <!-- Icon -->
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Tugas</h3>
        </div>
        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTasks }}</span>
      </div>
      <div class="space-y-2">
        <div class="flex justify-between text-sm">
          <span class="text-gray-500 dark:text-gray-400">Prosess</span>
          <span class="text-gray-700 dark:text-gray-300">
            {{ $totalTasks > 0 ? round($completionPercentage) : 0 }}%
          </span>
        </div>
        <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700">
          <div class="h-2 bg-blue-600 rounded-full" 
               style="width: {{ $totalTasks > 0 ? $completionPercentage : 0 }}%"></div>
        </div>
      </div>
    </div>

    <!-- In Progress Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Prosess</h3>
        </div>
        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $inProgressTasks }}</span>
      </div>
      <div class="space-y-2">
        <div class="flex justify-between text-sm">
          <span class="text-gray-500 dark:text-gray-400">Tugas Berlangsung</span>
          <span class="text-gray-700 dark:text-gray-300">
            {{ $totalTasks > 0 ? round($inProgressPercentage) : 0 }}%
          </span>
        </div>
        <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700">
          <div class="h-2 bg-yellow-500 rounded-full" 
               style="width: {{ $totalTasks > 0 ? $inProgressPercentage : 0 }}%"></div>
        </div>
      </div>
    </div>

    <!-- Urgent Tasks Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg">
            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tugas Mendesak</h3>
        </div>
        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $urgentTasks }}</span>
      </div>
      <div class="space-y-2">
        <div class="flex justify-between text-sm">
          <span class="text-gray-500 dark:text-gray-400">High Priority</span>
          <span class="text-gray-700 dark:text-gray-300">
            {{ $totalTasks > 0 ? round($urgentPercentage) : 0 }}%
          </span>
        </div>
        <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700">
          <div class="h-2 bg-red-500 rounded-full" 
               style="width: {{ $totalTasks > 0 ? $urgentPercentage : 0 }}%"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Baris kedua: Kalender dan Info Tasks Bulan Ini -->
  <div class="grid gap-6 mb-8 md:grid-cols-2">
    <!-- Card Kalender (tinggi diperkecil menjadi 250px) -->
    <div class="card shadow rounded p-4 bg-white">
      <h2 class="text-lg font-bold mb-2">Kalender Tugas</h2>
      <div id="taskCalendarCard" style="height: 250px; position: relative;"></div>
    </div>

    <!-- Kolom Info: Grid dengan 2 baris card -->
    <div class="grid gap-6">
      <!-- Card Info Tasks Bulan Ini -->
      @php
        $currentMonth = \Carbon\Carbon::now()->month;
        $currentYear = \Carbon\Carbon::now()->year;
        $tasksThisMonth = $activeAllTasks->filter(function($task) use ($currentMonth, $currentYear) {
            return $task->deadline 
              && \Carbon\Carbon::parse($task->deadline)->month == $currentMonth 
              && \Carbon\Carbon::parse($task->deadline)->year == $currentYear;
        });
        $totalThisMonth = $tasksThisMonth->count();
        $urgentThisMonth = $tasksThisMonth->where('priority', 'high')->count();
      @endphp
      <div class="card shadow rounded p-4 bg-white">
        <div class="flex justify-between items-center mb-2">
          <h2 class="text-lg font-bold">Tugas Bulan Ini</h2>
          <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z"/>
          </svg>
        </div>
        <p class="text-sm text-gray-600 mb-2">Total Tugas Deadline Bulan Ini: <span class="font-bold">{{ $totalThisMonth }}</span></p>
        <div class="flex justify-between items-center text-sm text-gray-600">
          <span>Urgent Tasks:</span>
          <span class="font-bold text-red-500">{{ $urgentThisMonth }}</span>
        </div>
        @if($totalThisMonth > 0)
        <div class="mt-2 h-2 bg-gray-200 rounded-full">
          <div class="h-2 bg-red-500 rounded-full" 
               style="width: {{ ($urgentThisMonth / $totalThisMonth) * 100 }}%"></div>
        </div>
        @endif
      </div>

      <!-- Card Info Additional: Upcoming Deadlines -->
      @php
        // Ambil 3 tugas terdekat dari tugas aktif
        $upcomingTasks = $activeAllTasks->sortBy('deadline')->take(3);
      @endphp
      <div class="card shadow rounded p-4 bg-white">
        <div class="flex justify-between items-center mb-2">
          <h2 class="text-lg font-bold">Deadline Terdekat</h2>
          <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        @if($upcomingTasks->count() > 0)
        <ul class="text-sm text-gray-600">
          @foreach($upcomingTasks as $task)
            <li class="mb-1">
              <span class="font-semibold">{{ $task->title }}</span> - Deadlines: {{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}
            </li>
          @endforeach
        </ul>
        @else
        <p class="text-sm text-gray-500">Belum ada tugas terdekat.</p>
        @endif
      </div>
    </div>
  </div>

  <!-- Baris ketiga: Grid Detail Tugas -->
  <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
    @foreach($activeAllTasks as $task)
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
                      <span class="text-gray-500 dark:text-gray-400">Prosess</span>
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
                     Lihat Tugas →
                  </a>
              </div>
          </div>
      </div>
    @endforeach
  </div>
</main>
@endsection

@section('scripts')
<script>
  console.log("Calendar events:", window.calendarEvents);

   window.calendarEvents = @json($events);
</script>
@vite(['resources/js/app.js','resources/js/calendarCard.js'])
@endsection
