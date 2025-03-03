@extends('layouts.app')

@section('content')
<div class="container max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <!-- Header -->
        <div class="border-b border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">Buat Tugas Baru</h1>
                    <p class="mt-1 text-sm text-gray-500">Tambahkan detail tugas yang ingin dibuat</p>
                </div>
                <div class="flex-shrink-0">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                        <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="p-4 border-b border-gray-100">
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat {{ $errors->count() }} kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6 p-6">
            @csrf
            
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">
                    Judul Tugas
                </label>
                <div class="mt-1">
                    <input type="text" name="title" id="title" 
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                           value="{{ old('title') }}" required>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">
                    Deskripsi
                </label>
                <div class="mt-1">
                    <textarea name="description" id="description" rows="4" 
                              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                </div>
                <p class="mt-2 text-sm text-gray-500">
                    Jelaskan detail tugas yang perlu dikerjakan
                </p>
            </div>

            <!-- Priority and Deadline -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700">
                        Prioritas
                    </label>
                    <div class="mt-1">
                        <select name="priority" id="priority" 
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>
                                Low Priority
                            </option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>
                                Medium Priority
                            </option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                High Priority
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Deadline -->
                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700">
                        Deadline
                    </label>
                    <div class="mt-1">
                        <input type="datetime-local" name="deadline" id="deadline" 
                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               value="{{ old('deadline') }}">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                <a href="{{ route('tasks.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Tugas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
