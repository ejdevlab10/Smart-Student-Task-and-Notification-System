<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TaskSubmissionController;
use App\Http\Controllers\SettingsController;

// ========================
// AUTHENTICATED ROUTES
// ========================

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index');

    // ========================
    // CALENDAR
    // ========================

    Route::get('/calendar', [CalendarController::class, 'index'])
        ->name('calendar.index');

    Route::get('/calendar/day/{date}', [CalendarController::class, 'day'])
        ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
        ->name('calendar.day');


    // ========================
    // ACCOUNT SETTINGS
    // ========================

    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings.index');

    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])
        ->name('settings.profile.update');

    // ========================
    // TASKS
    // ========================

    // All authorized users can access the task list.
    Route::get('/tasks', [TaskController::class, 'index'])
        ->name('tasks.index');

    // Teachers and admins can create tasks.
    // IMPORTANT: Keep /tasks/create before /tasks/{task}.
    Route::middleware('role:teacher,admin')->group(function () {

        Route::get('/tasks/create', [TaskController::class, 'create'])
            ->name('tasks.create');

        Route::post('/tasks', [TaskController::class, 'store'])
            ->name('tasks.store');

    });

    // View an individual task.
    Route::get('/tasks/{task}', [TaskController::class, 'show'])
        ->name('tasks.show');

    // Teachers and admins can edit, update, and delete tasks.
    Route::middleware('role:teacher,admin')->group(function () {

        Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])
            ->name('tasks.edit');

        Route::put('/tasks/{task}', [TaskController::class, 'update'])
            ->name('tasks.update');

        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
            ->name('tasks.destroy');

    });

    // Students can mark assigned tasks as completed.
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])
        ->middleware('role:student')
        ->name('tasks.complete');

    // ========================
    // TASK SUBMISSIONS
    // ========================

    Route::post('/tasks/{task}/submissions', [TaskSubmissionController::class, 'store'])
        ->name('tasks.submissions.store');

    Route::get('/tasks/{task}/submissions', [TaskSubmissionController::class, 'index'])
        ->name('tasks.submissions.index');

    Route::patch('/task-submissions/{submission}/review', [TaskSubmissionController::class, 'review'])
        ->name('task-submissions.review');

    Route::get('/task-submissions/{submission}/download', [TaskSubmissionController::class, 'download'])
        ->name('task-submissions.download');

    // ========================
    // ANNOUNCEMENTS
    // ========================

    Route::resource('announcements', AnnouncementController::class);

    // ========================
    // NOTIFICATIONS
    // ========================

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    // ========================
    // ADMIN: STUDENT MANAGEMENT
    // ========================

    Route::middleware('role:admin')->group(function () {

        Route::get('/admin/students', [StudentController::class, 'index'])
            ->name('admin.students.index');

        // ========================
        // ADMIN: TEACHER MANAGEMENT
        // ========================

        Route::get('/admin/teachers', [TeacherController::class, 'index'])
            ->name('admin.teachers.index');

        // ========================
        // ADMIN: CLASS MANAGEMENT
        // ========================

        Route::get('/admin/classes', [ClassController::class, 'index'])
            ->name('admin.classes.index');

        Route::get('/admin/classes/create', [ClassController::class, 'create'])
            ->name('admin.classes.create');

        Route::post('/admin/classes', [ClassController::class, 'store'])
            ->name('admin.classes.store');

        Route::get('/admin/classes/{class}/edit', [ClassController::class, 'edit'])
            ->name('admin.classes.edit');

        Route::put('/admin/classes/{class}', [ClassController::class, 'update'])
            ->name('admin.classes.update');

        Route::get('/admin/classes/{class}', [ClassController::class, 'show'])
            ->name('admin.classes.show');

        Route::post('/admin/classes/{class}/students', [ClassController::class, 'enroll'])
            ->name('admin.classes.enroll');

        Route::delete('/admin/classes/{class}/students/{student}', [ClassController::class, 'removeStudent'])
            ->name('admin.classes.students.remove');

    });

});

// ========================
// PUBLIC REGISTRATION
// ========================

Route::get('/register', [RegistrationController::class, 'index'])
    ->name('register');

Route::get('/register/{role}', [RegistrationController::class, 'create'])
    ->whereIn('role', ['student', 'teacher'])
    ->name('register.create');

Route::post('/register/{role}', [RegistrationController::class, 'store'])
    ->whereIn('role', ['student', 'teacher'])
    ->name('register.store');

// ========================
// AUTHENTICATION
// ========================

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');