<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');


    // ========================
    // TASKS
    // ========================

    // Students can view tasks
    Route::get('/tasks', [TaskController::class, 'index'])
        ->name('tasks.index');

    // Teachers and Admins can create tasks
    Route::middleware('role:teacher,admin')->group(function () {

        Route::get('/tasks/create', [TaskController::class, 'create'])
            ->name('tasks.create');

        Route::post('/tasks', [TaskController::class, 'store'])
            ->name('tasks.store');
    });

    // View individual task
    Route::get('/tasks/{task}', [TaskController::class, 'show'])
        ->name('tasks.show');

    // Teachers and Admins can edit/delete tasks
    Route::middleware('role:teacher,admin')->group(function () {

        Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])
            ->name('tasks.edit');

        Route::put('/tasks/{task}', [TaskController::class, 'update'])
            ->name('tasks.update');

        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
            ->name('tasks.destroy');
    });

    // Students can mark tasks completed
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])
        ->middleware('role:student')
        ->name('tasks.complete');


    // Teachers and Admins can manage tasks
    Route::middleware('role:teacher,admin')->group(function () {

        Route::get('/tasks/create', [TaskController::class, 'create'])
            ->name('tasks.create');

        Route::post('/tasks', [TaskController::class, 'store'])
            ->name('tasks.store');

        Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])
            ->name('tasks.edit');

        Route::put('/tasks/{task}', [TaskController::class, 'update'])
            ->name('tasks.update');

        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
            ->name('tasks.destroy');
    });


    // Announcements
    Route::resource('announcements', AnnouncementController::class);
});


// ------------------------ AUTH ------------------------

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');