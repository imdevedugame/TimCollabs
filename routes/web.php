<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CommentController;
use App\Livewire\Actions\Logout;

use App\Http\Controllers\LogoutController;
use App\Http\Controllers\CalendarCardController;


    // Route untuk logout, menggunakan POST untuk keamanan

    // Route lain seperti tasks, calendar, dsb.


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public route
Route::view('/', 'welcome');

// Routes yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {

    // Dashboard (pastikan user sudah diverifikasi)
    Route::view('dashboard', 'dashboard')
         ->middleware(['verified'])
         ->name('dashboard');

 Route::get('/dashboard', [CalendarCardController::class, 'index'])
 ->name('dashboard')
 ;// Misal, hanya menggunakan index dan show

 Route::resource('calendar', CalendarController::class);
 
    // Task resource
    Route::resource('tasks', TaskController::class);

    // Invite anggota ke task (nested route)
   Route::post('/tasks/{task}/invite', [TaskController::class, 'inviteMember'])
    ->name('tasks.invite')
    ->middleware('auth');


    // Subtasks (nested di dalam task)
    Route::post('/tasks/{task}/subtasks', [SubtaskController::class, 'store'])
         ->name('subtasks.store');
    Route::patch('/subtasks/{subtask}', [SubtaskController::class, 'update'])
         ->name('subtasks.update');

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])
         ->name('calendar.index');

    // Comments (nested route, agar task_id di-bind secara otomatis)
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])
         ->name('comments.store');
    // Jika Anda ingin menyediakan view individual untuk komentar,
    // Anda bisa menambahkan route berikut:
    Route::get('/comments/{comment}', [CommentController::class, 'show'])
         ->name('comments.show');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])
         ->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
         ->name('comments.destroy');

    // Atau, jika Anda ingin resource route untuk komentar yang global,
    // hapus route nested di atas dan gunakan:
    // Route::resource('comments', CommentController::class);
    // Namun, untuk kasus komentar yang selalu terkait dengan task,
    // nested route lebih dianjurkan.
    Route::get('/logout', Logout::class)->name('logout');
    // Profile
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::view('profile', 'profile')->name('profile');
});

// Auth routes
require __DIR__.'/auth.php';
