@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
                <p>Selamat datang, {{ auth()->user()->name }}!</p>

                <!-- Contoh isi ringkasan; Anda bebas menambahkan hal lain -->
                <div class="mt-6">
                    <p>Daftar tugas Anda bisa diakses melalui menu <strong>Tugas</strong> di navigasi atas, 
                    atau klik tombol di bawah ini:</p>
                    <a href="{{ route('tasks.index') }}"
                       class="inline-block mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                       Lihat Daftar Tugas
                    </a>
                </div>
                <h3>Tugas Saya</h3>
<ul>
@foreach(auth()->user()->tasks as $task)
   <li>
       <a href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
       ({{ $task->pivot->role }})
   </li>
@endforeach
</ul>

            </div>
        </div>
    </div>
</div>
@endsection
