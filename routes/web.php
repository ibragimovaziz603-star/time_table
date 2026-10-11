<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StarostaController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\TimetableController;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Support\Facades\Route;

// ==================== OMMAVIY SAHIFA ====================
Route::get('/', [TimetableController::class, 'index'])->name('timetable');

// ==================== AJAX ====================
Route::get('/api/group/{group}',     [TimetableController::class, 'byGroup'])->name('api.group');
Route::get('/api/room/{room}',       [TimetableController::class, 'byRoom'])->name('api.room');
Route::get('/api/teacher/{teacher}', [TimetableController::class, 'byTeacher'])->name('api.teacher');

// ==================== AUTH ====================
Route::get('/login',   [LoginController::class, 'showForm'])->name('login');
Route::post('/login',  [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==================== REGISTER ====================
Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ==================== ADMIN PANEL ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard — 3 ta statistika
    Route::get('/', function () {
        return view('admin.dashboard', [
            'stats' => [
                'students' => User::where('role_id', 3)->count(),
                'teachers' => User::where('role_id', 2)->count(),
                'visits'   => Visit::count(),
            ],
        ]);
    })->name('dashboard');

    // CRUD
    Route::resource('schedules', ScheduleController::class);
    Route::resource('teachers',  TeacherController::class);
    Route::resource('subjects',  SubjectController::class);
    Route::resource('rooms',     RoomController::class);
    Route::resource('groups',    GroupController::class);
    Route::resource('starostas', StarostaController::class);

    // Guruhga talaba qo'shish/olib tashlash
    Route::post('groups/{group}/add-student',
        [GroupController::class, 'addStudent'])->name('groups.add-student');
    Route::delete('groups/{group}/remove-student/{student}',
        [GroupController::class, 'removeStudent'])->name('groups.remove-student');

    // Analitika
    Route::get('analytics',                   [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/group/{group}',     [AnalyticsController::class, 'group'])->name('analytics.group');
    Route::get('analytics/teacher/{teacher}', [AnalyticsController::class, 'teacher'])->name('analytics.teacher');
});

// ==================== O'QITUVCHI PANELI ====================
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/', [TeacherDashboard::class, 'index'])->name('dashboard');
    Route::get('/my-schedule', [TeacherDashboard::class, 'mySchedule'])->name('my-schedule');
    Route::get('/journal', [TeacherDashboard::class, 'journal'])->name('journal');
    Route::get('/journal/{group}/{subject}', [TeacherDashboard::class, 'journalEdit'])->name('journal.edit');
    Route::post('/journal/{group}/{subject}', [TeacherDashboard::class, 'journalSave'])->name('journal.save');
});

// ==================== STAROSTA PANELI ====================
Route::middleware(['auth', 'starosta'])->prefix('starosta')->name('starosta.')->group(function () {
    Route::get('/journal', [\App\Http\Controllers\Starosta\DashboardController::class, 'journal'])->name('journal');
    Route::post('/journal', [\App\Http\Controllers\Starosta\DashboardController::class, 'save'])->name('journal.save');
});