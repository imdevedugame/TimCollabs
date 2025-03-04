@extends('layouts.app')

@section('content')
<!-- Header Section -->
<header class="w-full bg-blue-600 text-white py-6">
  <div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold">{{ __('Profile Settings') }}</h1>
    <p class="mt-2 text-lg">{{ __('Manage your account settings and preferences') }}</p>
  </div>
</header>

<main class="container mx-auto px-4 py-8">
  <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
    <!-- Sidebar Navigation -->
    <div class="md:col-span-2">
      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <nav class="space-y-1 p-4">
          <a href="#profile-info" class="flex items-center px-3 py-2 text-sm font-medium rounded-md bg-primary/10 text-primary dark:text-primary-foreground">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            {{ __('Profile Information') }}
          </a>
          <a href="#security" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            {{ __('Security') }}
          </a>
          <a href="#danger" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            {{ __('Danger Zone') }}
          </a>
        </nav>
      </div>

      <!-- Profile Stats -->
      <div class="mt-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ __('Account Overview') }}
        </h3>
        <dl class="mt-4 grid grid-cols-1 gap-4">
          <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-5 sm:rounded-lg sm:p-6">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
              {{ __('Member Since') }}
            </dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
              {{ auth()->user()->created_at->format('M d, Y') }}
            </dd>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-5 sm:rounded-lg sm:p-6">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
              {{ __('Last Updated') }}
            </dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
              {{ auth()->user()->updated_at->diffForHumans() }}
            </dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="md:col-span-4 space-y-6">
      <!-- Profile Information Form -->
      <div id="profile-info" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="p-4 sm:p-8">
          <div class="max-w-xl">
            <livewire:profile.update-profile-information-form />
          </div>
        </div>
      </div>

      <!-- Security Form -->
      <div id="security" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="p-4 sm:p-8">
          <div class="max-w-xl">
            <livewire:profile.update-password-form />
          </div>
        </div>
      </div>

      <!-- Danger Zone / Delete Account -->
      <div id="danger" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="p-4 sm:p-8">
          <div class="max-w-xl">
            <livewire:profile.delete-user-form />
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Success Toast -->
@if (session('status'))
<div x-data="{ show: true }"
     x-show="show"
     x-transition
     x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-4 right-4">
  <div class="bg-green-500 text-white text-sm rounded-lg shadow-lg p-4 flex items-center space-x-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M5 13l4 4L19 7" />
    </svg>
    <p>{{ session('status') }}</p>
  </div>
</div>
@endif
@endsection

@section('scripts')
@vite(['resources/js/app.js'])
@endsection
