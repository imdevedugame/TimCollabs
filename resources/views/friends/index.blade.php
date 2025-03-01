@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Undangan Masuk</h2>
    @if($pendingInvitations->isEmpty())
        <p>Tidak ada undangan masuk.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Pengirim</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingInvitations as $invitation)
                <tr>
                    <td>{{ $invitation->sender->name }}</td>
                    <td>{{ $invitation->sender->email }}</td>
                    <td>
                        <form action="{{ route('friends.accept', $invitation->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-success">Terima</button>
                        </form>
                        <form action="{{ route('friends.reject', $invitation->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Tolak</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>

    <h2>Daftar Teman</h2>
    @if($friends->isEmpty())
        <p>Belum ada teman.</p>
    @else
        <ul class="list-group">
            @foreach($friends as $friend)
            <li class="list-group-item">
                {{ $friend->name }} ({{ $friend->email }})
            </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
