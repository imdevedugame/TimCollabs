@extends('layouts.app') 
<!-- layouts.app dari Breeze -->

@section('content')
<div class="container">
    <h1>Daftar Tugas</h1>

    @if (session('success'))
       <div class="alert alert-success">
           {{ session('success') }}
       </div>
    @endif

    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Buat Tugas Baru</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Prioritas</th>
                <th>Deadline</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>
                    @if($task->priority == 'high')
                        <span class="badge bg-danger">High</span>
                    @elseif($task->priority == 'medium')
                        <span class="badge bg-warning text-dark">Medium</span>
                    @else
                        <span class="badge bg-success">Low</span>
                    @endif
                </td>
                <td>
                    {{ $task->deadline ? $task->deadline->format('d M Y H:i') : '-' }}
                </td>
                <td>
                    <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info btn-sm">
                        Detail
                    </a>
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">
                        Edit
                    </a>
                    <form action="{{ route('tasks.destroy', $task->id) }}" 
                          method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" 
                                onclick="return confirm('Yakin hapus tugas ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Belum ada tugas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
