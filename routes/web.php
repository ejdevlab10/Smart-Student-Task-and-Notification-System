<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\RegistrationController;

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

        // ========================
        // TEACHER MANAGEMENT
        // Admin Only
        // ========================

        Route::middleware('role:admin')->group(function () {

            Route::get('/admin/teachers', [TeacherController::class, 'index'])
                ->name('admin.teachers.index');

        });
            
        
    });


    // Announcements
    Route::resource('announcements', AnnouncementController::class);


    // Student Management - Admin Only
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/students', [StudentController::class, 'index'])
            ->name('admin.students.index');
    });


    // ========================
    // CLASS MANAGEMENT
    // Admin Only
    // ========================

    Route::middleware('role:admin')->group(function () {
    // Class directory
    Route::get('/admin/classes', [ClassController::class, 'index'])
            ->name('admin.classes.index');

        // Create class
        Route::get('/admin/classes/create', [ClassController::class, 'create'])
            ->name('admin.classes.create');

        Route::post('/admin/classes', [ClassController::class, 'store'])
            ->name('admin.classes.store');

        // Edit class
        Route::get('/admin/classes/{class}/edit', [ClassController::class, 'edit'])
            ->name('admin.classes.edit');

        // Update class
        Route::put('/admin/classes/{class}', [ClassController::class, 'update'])
            ->name('admin.classes.update');

        // View class details and enrolled students
        Route::get('/admin/classes/{class}', [ClassController::class, 'show'])
            ->name('admin.classes.show');

        // Enroll a student
        Route::post('/admin/classes/{class}/students', [ClassController::class, 'enroll'])
            ->name('admin.classes.enroll');

        // Remove a student
        Route::delete('/admin/classes/{class}/students/{student}', [ClassController::class, 'removeStudent'])
            ->name('admin.classes.students.remove');
    });
});


// ------------------------ REGISTRATION ------------------------

Route::get('/register', [RegistrationController::class, 'index'])
    ->name('register');

Route::get('/register/{role}', [RegistrationController::class, 'create'])
    ->whereIn('role', ['student', 'teacher'])
    ->name('register.create');

Route::post('/register/{role}', [RegistrationController::class, 'store'])
    ->whereIn('role', ['student', 'teacher'])
    ->name('register.store');


    
// ------------------------ AUTH ------------------------

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');
    
Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('notifications.index');

Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.read');

// ----------------------- Calendar -----------------------
Route::get('/calendar', [CalendarController::class, 'index'])
    ->middleware('auth')
    ->name('calendar.index');