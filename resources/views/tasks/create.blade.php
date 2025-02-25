@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Buat Tugas Baru</h1>

    @if ($errors->any())
       <div class="alert alert-danger">
          <ul>
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
             @endforeach
          </ul>
       </div>
    @endif

    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" 
                   value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="priority" class="form-label">Prioritas</label>
            <select name="priority" class="form-select">
                <option value="low"    {{ old('priority')=='low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ old('priority')=='medium' ? 'selected' : '' }}>Medium</option>
                <option value="high"   {{ old('priority')=='high' ? 'selected' : '' }}>High</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="datetime-local" name="deadline" class="form-control" 
                   value="{{ old('deadline') }}">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
