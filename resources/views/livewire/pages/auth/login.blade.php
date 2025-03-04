<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
};
?>

<div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 dark:bg-gray-900">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-md rounded-lg p-8">
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ __('Login') }}</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('Selamat Datang TIMVANZ') }}</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login">
            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="form.email" id="email" class="mt-1 block w-full" type="email" name="email" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input wire:model="form.password" id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-4">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <label for="remember" class="ml-2 block text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Remember me') }}
                </label>
            </div>

            <div class="flex items-center justify-between">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </a>

                @endif
                <div class="flex items-center justify-between">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100" href="{{ route('register') }}" wire:navigate>
                    {{ __('Dont Have account?') }}
                </a>

                <x-primary-button class="ml-4">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
