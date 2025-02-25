@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Komentar</h1>

    @if ($errors->any())
       <div class="alert alert-danger">
          <ul>
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
             @endforeach
          </ul>
       </div>
    @endif

    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Title (Opsional)</label>
            <input type="text" name="title" class="form-control" 
                   value="{{ old('title', $comment->title) }}">
        </div>
        <div class="mb-3">
            <label>Message</label>
            <textarea name="message" class="form-control" rows="3" required>
                {{ old('message', $comment->message) }}
            </textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('comments.show', $comment->id) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
