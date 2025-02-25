@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Komentar</h1>

    @if (session('success'))
       <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('comments.create') }}" class="btn btn-primary mb-3">
        Buat Komentar Baru
    </a>

    @foreach($comments as $comment)
        <div class="mb-4 p-3 border rounded">
            <h5>{{ $comment->title ?? 'No Title' }}</h5>
            <p>{{ $comment->message }}</p>
            <small>Oleh: {{ $comment->user->name ?? 'Anon' }} 
                   | {{ $comment->created_at->diffForHumans() }}</small><br>

            <a href="{{ route('comments.show', $comment->id) }}" class="btn btn-sm btn-info">
                Detail
            </a>
        </div>
    @endforeach

    {{ $comments->links() }}
</div>
@endsection
