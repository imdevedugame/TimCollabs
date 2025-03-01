<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ 
          sidebarOpen: true,
          darkMode: localStorage.getItem('darkMode') === 'true',
          toggleSidebar() { this.sidebarOpen = !this.sidebarOpen },
          toggleDarkMode() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('darkMode', this.darkMode);
              document.documentElement.classList.toggle('dark');
          }
      }"
      :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard - TIMVANZ')</title>
    <!-- Sertakan styles dan script sesuai kebutuhan -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    {{-- @livewireStyles --}}
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="font-sans antialiased bg-white dark:bg-gray-900">
    <div class="flex w-full min-h-screen">
        <!-- SIDEBAR -->
        <aside class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300
                      bg-gradient-to-b from-blue-600 to-blue-800 dark:from-gray-800 dark:to-gray-900
                      text-white"
               :class="sidebarOpen ? 'w-64' : 'w-20'">

            <div class="flex h-16 items-center justify-between px-4">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    <span class="text-xl font-bold transition-all duration-300"
                          :class="!sidebarOpen && 'hidden'">
                        TIMVANZ
                    </span>
                </a>
                <!-- Toggle Sidebar -->
                <button @click="toggleSidebar" class="rounded-lg p-1.5 text-white/80 hover:bg-white/10">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              :d="sidebarOpen ? 'M15 19l-7-7 7-7' : 'M9 19l7-7-7-7'"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 space-y-1 px-3 py-4">
                <a href="{{ route('dashboard') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2 text-white/90 hover:bg-white/10
                          {{ request()->routeIs('dashboard') ? 'bg-white/10' : '' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span :class="!sidebarOpen && 'hidden'">Dashboard</span>
                </a>

                <a href="{{ route('tasks.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2 text-white/90 hover:bg-white/10
                          {{ request()->routeIs('tasks.*') ? 'bg-white/10' : '' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span :class="!sidebarOpen && 'hidden'">Tasks</span>
                </a>

                <a href="{{ route('calendar.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2 text-white/90 hover:bg-white/10
                          {{ request()->routeIs('calendar.*') ? 'bg-white/10' : '' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span :class="!sidebarOpen && 'hidden'">Calendar</span>
                </a>

                <!-- Menu Baru: Frends -->
                <a href="{{ route('friends.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2 text-white/90 hover:bg-white/10
                          {{ request()->routeIs('friends.*') ? 'bg-white/10' : '' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <!-- Icon Users / Group -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2h5m6-10a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span :class="!sidebarOpen && 'hidden'">Frends</span>
                </a>
            </nav>

            <!-- User Profile & Settings -->
            <div class="border-t border-white/10 px-3 py-4">
                <div class="flex items-center gap-3 rounded-lg px-3 py-2 text-white/90">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" 
                         class="h-8 w-8 rounded-full" alt="Profile">
                    <div :class="!sidebarOpen && 'hidden'">
                        <div class="font-medium">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="text-sm text-white/60">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                </div>

                <div class="mt-3 space-y-1" :class="!sidebarOpen && 'hidden'">
                    <!-- Dark Mode Toggle -->
                    <button @click="toggleDarkMode" 
                            class="w-full rounded-lg px-3 py-2 text-left text-white/90 hover:bg-white/10">
                        <div class="flex items-center gap-3">
                            <svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <svg x-show="darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                            <span>Dark Mode</span>
                        </div>
                    </button>

                    <!-- Profile Link -->
                    <a href="{{ route('profile') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-white/90 hover:bg-white/10">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Profile</span>
                    </a>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-white/90 hover:bg-white/10">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="transition-all duration-300 flex-1" :class="sidebarOpen ? 'ml-64' : 'ml-20'">
            <div class="w-full min-h-screen p-4">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- @livewireScripts --}}
    @yield('scripts')
</body>
</html>
