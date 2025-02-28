@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800">📅 Kalender Tugas</h1>
    <!-- Pastikan ID container sesuai dengan yang dipakai JS -->
    <div id="taskCalendarContainer" class="mt-4"></div>
</div>
@endsection

@section('scripts')
<script>
    window.calendarEvents = @json($events ?? []);
</script>

@vite([
    'resources/js/app.js',
    'resources/js/calendar.js'
])
@endsection
