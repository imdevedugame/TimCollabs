@extends('layouts.app')

@section('content')
<div class="card shadow rounded p-4 bg-white">
    <h2 class="text-lg font-bold mb-2">Kalender Tugas</h2>
    <div id="taskCalendarCard" style="height: 300px;"></div>
</div>
@endsection

@section('scripts')
<script>
    window.calendarEvents = @json($events ?? []);
</script>
@vite(['resources/js/app.js', 'resources/js/calendarCard.js'])
@endsection
