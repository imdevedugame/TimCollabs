@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Tugas</h1>

    @if ($errors->any())
       <div class="alert alert-danger">
          <ul>
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
             @endforeach
          </ul>
       </div>
    @endif

    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text" name="title" class="form-control"
                   value="{{ old('title', $task->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control">
                {{ old('description', $task->description) }}
            </textarea>
        </div>

        <div class="mb-3">
            <label for="priority" class="form-label">Prioritas</label>
            <select name="priority" class="form-select">
                <option value="low" 
                  {{ old('priority', $task->priority)=='low' ? 'selected' : '' }}>Low
                </option>
                <option value="medium" 
                  {{ old('priority', $task->priority)=='medium' ? 'selected' : '' }}>Medium
                </option>
                <option value="high" 
                  {{ old('priority', $task->priority)=='high' ? 'selected' : '' }}>High
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="datetime-local" name="deadline" class="form-control"
                   value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '') }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
