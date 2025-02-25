@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Buat Komentar Baru</h1>

    @if ($errors->any())
       <div class="alert alert-danger">
          <ul>
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
             @endforeach
          </ul>
       </div>
    @endif

    <form action="{{ route('comments.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Title (Opsional)</label>
            <input type="text" name="title" class="form-control" 
                   value="{{ old('title') }}">
        </div>
        <div class="mb-3">
            <label>Message</label>
            <textarea name="message" class="form-control" rows="3" required>
                {{ old('message') }}
            </textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('comments.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
