@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Kalender Tugas</h1>
    <div id="taskCalendar"></div>
</div>
@endsection

@section('scripts')
<script>
    window.calendarEvents = @json($events);
</script>

@vite([
    'resources/js/app.js',
    'resources/js/calendar.js'
])
@endsection
