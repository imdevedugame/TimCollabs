@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Judul Halaman -->
    <div class="mb-6 flex items-center space-x-3">
        <i class="fas fa-tasks text-3xl text-gray-700"></i>
        <h1 class="text-3xl font-bold text-gray-900">Detail Tugas</h1>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Informasi Tugas -->
        <div class="bg-white shadow-lg rounded-lg p-6 border">
    <h2 class="text-2xl font-semibold text-gray-900">{{ $task->title }}</h2>
    <p class="text-gray-700 mt-2 leading-relaxed">{{ $task->description }}</p>

    <div class="mt-4 text-sm text-gray-600">
        <p><i class="fas fa-flag text-gray-500"></i> <strong>Prioritas:</strong> {{ ucfirst($task->priority) }}</p>
        <p><i class="far fa-calendar-alt text-gray-500"></i> <strong>Deadline:</strong> 
            {{ $task->deadline ? $task->deadline->format('d M Y H:i') : '-' }}
        </p>
    </div>

    @php
        $totalSubtasks = $task->subtasks->count();
        $doneSubtasks = $task->subtasks->where('is_done', true)->count();
        $progress = $totalSubtasks > 0 ? ($doneSubtasks / $totalSubtasks) * 100 : 0;

        // Status Berdasarkan Progress
        if ($progress == 100) {
            $statusText = "✅ Selesai";
            $progressColor = "bg-green-600";
        } elseif ($progress >= 70) {
            $statusText = "🚀 Hampir Selesai";
            $progressColor = "bg-blue-600";
        } elseif ($progress >= 30) {
            $statusText = "⏳ Dalam Proses";
            $progressColor = "bg-yellow-500";
        } else {
            $statusText = "❌ Baru Dimulai";
            $progressColor = "bg-red-500";
        }
    @endphp

    <!-- Progress Section -->
    <div class="mt-6">
        <label class="block font-medium text-gray-700 mb-2">Progress Tugas:</label>

        <div class="relative w-full bg-gray-200 rounded-full h-6">
            <div class="absolute left-0 h-6 rounded-full transition-all duration-700 ease-in-out {{ $progressColor }}" 
                 style="width: {{ $progress }}%;">
            </div>
            <span class="absolute inset-0 flex items-center justify-center text-sm font-semibold text-white">
                {{ (int)$progress }}%
            </span>
        </div>

        <p class="mt-2 text-gray-700 text-sm text-center font-medium">
            <i class="fas fa-info-circle"></i> {{ $statusText }}
        </p>
    </div>
</div>


        <!-- Kolaborasi Tim -->
        <div class="bg-white shadow-lg rounded-lg p-6 border">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-users text-gray-600 mr-2"></i> Kolaborasi Tim
            </h3>
            <form action="{{ route('tasks.invite', $task->id) }}" method="POST" class="mt-4">
                @csrf
                <input type="email" name="email" placeholder="Masukkan email anggota" required class="w-full border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-300">
                <select name="role" class="w-full border-gray-300 rounded-lg p-2 mt-2">
                    <option value="member">Member</option>
                    <option value="owner">Owner</option>
                    <option value="observer">Observer</option>
                </select>
                <button type="submit" class="w-full mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                    <i class="fas fa-user-plus"></i> Undang
                </button>
            </form>
            
            <div class="mt-4">
                <h5 class="text-sm font-medium text-gray-800">Anggota:</h5>
                <ul class="text-sm text-gray-600">
                    @foreach($task->users as $user)
                        <li class="mt-1"><i class="fas fa-user-circle text-gray-500"></i> {{ $user->name }} ({{ $user->pivot->role }})</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Subtasks -->
    <div class="bg-white shadow-lg rounded-lg mt-6 p-6 border">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-tasks text-gray-600 mr-2"></i> Subtasks
        </h3>
        <ul class="mt-3 divide-y divide-gray-200">
            @foreach($task->subtasks as $subtask)
                <li class="py-3 flex items-center space-x-2">
                    <form action="{{ route('subtasks.update', $subtask->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="checkbox" name="is_done" 
                               onChange="this.form.submit()" 
                               {{ $subtask->is_done ? 'checked' : '' }} 
                               class="mr-2">
                    </form>
                    <span class="{{ $subtask->is_done ? 'line-through text-gray-500' : 'text-gray-800' }}">
                        {{ $subtask->title }}
                    </span>
                </li>
            @endforeach
        </ul>

        <form action="{{ route('subtasks.store', $task->id) }}" method="POST" class="mt-4">
            @csrf
            <input type="text" name="title" placeholder="Tambah subtask" class="w-full border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-300">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all mt-2">
                <i class="fas fa-plus-circle"></i> Tambah Subtask
            </button>
        </form>
    </div>

    <!-- Diskusi / Komentar -->
    <div class="bg-white shadow-lg rounded-lg mt-6 p-6 border">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-comments text-gray-600 mr-2"></i> Diskusi
        </h3>
        <div id="commentsList" class="space-y-4 mt-4">
            @foreach($task->comments as $comment)
                <div class="p-3 border rounded-lg bg-gray-50">
                    <strong class="text-gray-800"><i class="fas fa-user text-gray-500"></i> {{ $comment->user->name }}</strong>
                    <p class="text-gray-600">{{ $comment->message }}</p>
                    <small class="text-gray-500"><i class="far fa-clock"></i> {{ $comment->created_at->diffForHumans() }}</small>
                </div>
            @endforeach
        </div>

        <form action="{{ route('comments.store', ['task' => $task->id]) }}" method="POST" class="mt-4">
            @csrf
            <textarea name="message" placeholder="Tulis komentar..." class="w-full border-gray-300 rounded-lg p-2 focus:ring focus:ring-green-300"></textarea>
            <button type="submit" class="w-full mt-3 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">
                <i class="fas fa-paper-plane"></i> Kirim Komentar
            </button>
        </form>
    </div>

    <!-- Tombol Kembali -->
    <div class="mt-6">
        <a href="{{ route('tasks.index') }}" class="inline-block px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition-all">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
