@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

    <!-- Judul Halaman -->
    <div class="mb-3">
        <h1 class="text-2xl font-bold">Detail Tugas</h1>
    </div>

    <!-- Informasi Task -->
    <div class="bg-white shadow rounded mb-4 p-4">
        <h2 class="text-xl font-semibold mb-2">{{ $task->title }}</h2>
        <p class="text-gray-700 mb-2">{{ $task->description }}</p>
        <p class="mb-1"><strong>Prioritas:</strong> {{ $task->priority }}</p>
        <p class="mb-2"><strong>Deadline:</strong> 
           {{ $task->deadline ? $task->deadline->format('d M Y H:i') : '-' }}
        </p>

        @php
            $totalSubtasks = $task->subtasks->count();
            $doneSubtasks = $task->subtasks->where('is_done', true)->count();
            $progress = $totalSubtasks > 0 ? ($doneSubtasks / $totalSubtasks) * 100 : 0;
        @endphp

        <!-- Progress Bar Subtasks (Tailwind style) -->
        <div class="my-3">
            <label class="block mb-1">Progress Subtask: {{ $doneSubtasks }}/{{ $totalSubtasks }}</label>
            <div class="w-full bg-gray-200 rounded h-4">
                <div class="bg-blue-500 h-4 rounded"
                     style="width: {{ $progress }}%;">
                     <!-- menampilkan persentase di dalam bar -->
                     <span class="text-white text-xs pl-2">
                         {{ (int)$progress }}%
                     </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Subtasks Section -->
    <div class="bg-white shadow rounded mb-4">
        <div class="border-b px-4 py-3">
            <h3 class="text-lg font-semibold mb-0">Subtasks</h3>
        </div>
        <div class="p-4">
            @if ($task->subtasks->count())
                <ul class="divide-y divide-gray-200">
                    @foreach($task->subtasks as $subtask)
                        <li class="py-2 flex items-center space-x-2">
                            <form action="{{ route('subtasks.update', $subtask->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" name="is_done"
                                       onChange="this.form.submit()"
                                       {{ $subtask->is_done ? 'checked' : '' }}
                                       class="mr-2">
                            </form>
                            <span>{{ $subtask->title }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">Belum ada subtask.</p>
            @endif

            <hr class="my-4">

            <!-- Form Tambah Subtask Baru -->
            <h4 class="text-md font-medium mb-2">Tambah Subtask</h4>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-2 rounded mb-3">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('subtasks.store', $task->id) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block font-medium mb-1">Judul Subtask</label>
                    <input type="text" name="title" class="w-full border-gray-300 rounded" required>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Tambah
                </button>
            </form>
        </div>
    </div>

    <!-- Invite Anggota Section -->
    <div class="bg-white shadow rounded mb-4">
    <div class="border-b px-4 py-3">
        <h3 class="text-lg font-semibold mb-0">Kolaborasi Tim</h3>
    </div>
    <div class="p-4">
        <form action="{{ route('tasks.invite', $task->id) }}" method="POST" class="space-y-3 mb-4">
            @csrf
            <div>
                <label class="block font-medium mb-1">Email Anggota</label>
                <input type="email" name="email" class="w-full border-gray-300 rounded" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Role</label>
                <select name="role" class="w-full border-gray-300 rounded">
                    <option value="member">Member</option>
                    <option value="owner">Owner</option>
                    <option value="observer">Observer</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Undang
            </button>
        </form>

        <hr class="my-4">

        <h5 class="text-md font-medium mb-2">Anggota Tugas</h5>
        <ul class="list-disc list-inside">
            @foreach($task->users as $user)
                <li>{{ $user->name }} (role: {{ $user->pivot->role }})</li>
            @endforeach
        </ul>
    </div>
</div>

    <!-- Diskusi / Komentar Section -->
    <div class="bg-white shadow rounded mb-4">
        <div class="border-b px-4 py-3">
            <h3 class="text-lg font-semibold mb-0">Diskusi / Komentar</h3>
        </div>
        <div class="p-4">

            <!-- Daftar komentar -->
            <div id="commentsList" class="space-y-4">
                @foreach($task->comments as $comment)
                    <div class="p-3 border rounded">
                        <strong>{{ $comment->user->name }}</strong><br>
                        <span>{{ $comment->message }}</span>
                        <div><small class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</small></div>
                    </div>
                @endforeach
            </div>

            <!-- Form Tambah Komentar -->
            <form action="{{ route('comments.store', ['task' => $task->id]) }}"  method="POST" id="commentForm" class="mt-4 space-y-3">
                @csrf
                <div>
                    <label for="message" class="block font-medium mb-1">Tulis Komentar</label>
                    <textarea name="message" id="message" rows="2" required
                              class="w-full border-gray-300 rounded"></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Kirim
                </button>
            </form>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ route('tasks.index') }}" class="inline-block px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400">
            Kembali
        </a>
    </div>
</div>
@endsection

{{-- Real-Time Comment Listener --}}
@section('scripts-after')
<script>
    let taskId = {{ $task->id }};

    // Subscribe channel private: "task.{taskId}"
    window.Echo.private(`task.${taskId}`)
        .listen('.new-comment', (e) => {
            console.log("Komentar baru diterima:", e.comment);

            let commentsList = document.getElementById('commentsList');
            // Buat elemen div untuk komentar baru
            let commentDiv = document.createElement('div');
            commentDiv.classList.add('p-3', 'border', 'rounded');
            commentDiv.innerHTML = `
                <strong>${e.comment.user.name}</strong><br>
                <span>${e.comment.message}</span>
                <div><small class="text-gray-500">Baru saja</small></div>
            `;
            // Sisipkan ke paling bawah daftar komentar
            commentsList.appendChild(commentDiv);
        });

    // Submit Komentar via AJAX (opsional)
    let commentForm = document.getElementById('commentForm');
    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("{{ route('comments.store', $task->id) }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Komentar berhasil tersimpan:', data);
            // Kita tidak menambah komentar ke UI di sini
            // karena real-time broadcast akan menambahkannya
            // di callback .listen('.new-comment')
            // Reset input
            document.getElementById('message').value = '';
        })
        .catch(err => console.error(err));
    });
</script>
@endsection
