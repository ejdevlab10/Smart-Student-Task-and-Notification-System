<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');
// Tasks
Route::get('/tasks', [TaskController::class, 'index'])
    ->name('tasks.index');

Route::get('/tasks/create', [TaskController::class, 'create'])
    ->name('tasks.create');

Route::post('/tasks', [TaskController::class, 'store'])
    ->name('tasks.store');

Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])
    ->name('tasks.edit');

Route::put('/tasks/{task}', [TaskController::class, 'update'])
    ->name('tasks.update');

Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])
    ->name('tasks.complete');

Route::get('/tasks/{task}', [TaskController::class, 'show'])
    ->name('tasks.show');

Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
    ->name('tasks.destroy');

// Announcement
Route::get('/announcements', [AnnouncementController::class, 'index'])
    ->name('announcements.index');

Route::get('/announcements/create', [AnnouncementController::class, 'create'])
    ->name('announcements.create');

Route::post('/announcements', [AnnouncementController::class, 'store'])
    ->name('announcements.store');

Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])
    ->name('announcements.show');

Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])
    ->name('announcements.edit');

Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])
    ->name('announcements.update');

Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])
    ->name('announcements.destroy');