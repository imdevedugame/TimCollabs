@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Komentar</h1>

    <div class="border p-3 mb-3">
        <h3>{{ $comment->title ?? 'No Title' }}</h3>
        <p>{{ $comment->message }}</p>
        <small>Oleh: {{ $comment->user->name ?? 'Anon' }} 
               | {{ $comment->created_at->diffForHumans() }}</small>
    </div>

    <a href="{{ route('comments.edit', $comment->id) }}" class="btn btn-warning">Edit</a>
    
    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" onclick="return confirm('Yakin hapus?')">
            Hapus
        </button>
    </form>

    <a href="{{ route('comments.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
