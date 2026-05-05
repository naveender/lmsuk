<?php

use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\ManualBackupManagerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\EditProfileController;

use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Student\HomeController as StudentHomeController;
use App\Http\Controllers\Student\LessonsController as StudentLessonsController;
use App\Http\Controllers\Student\AnalyticsController as StudentAnalyticsController;
use App\Http\Controllers\Student\AssessmentController as StudentAssessmentsController;
use App\Http\Controllers\Student\FocusAreasController as StudentFocusAreasController;
use App\Http\Controllers\Student\AnnouncementsController as StudentAnnouncementsController;
use App\Http\Controllers\Student\CentreTestScoreController as StudentCentreTestScoreController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('auth.login');
});
// Route::get('/register', function () {
//     return view('auth.register');
// });

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/logs', [LogsController::class, 'index'])->name('logs');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/edit-profile', [SettingsController::class, 'profile'])->name('edit-profile');
});

// Dashboard redirect after login
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (! $user) {
        return redirect('/login');
    }

    return match ($user->role) {
        'admin' => redirect('/admin/dashboard'),
        'student' => redirect('/student/dashboard'),
        'parent' => redirect('/parent/dashboard'),
        default => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminHomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/topics', [TopicController::class, 'index'])->name('topics');
    Route::get('/admin/add-topic', [TopicController::class, 'add'])->name('add.topic');
});

// Student
Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
     Route::get('/student/dashboard', [StudentHomeController::class, 'index'])->name('student.dashboard');

     Route::get('/student/video-lessons-categories', [StudentLessonsController::class, 'index'])->name('student.videolessonscategories');
     Route::get('/student/video-lessons-list', [StudentLessonsController::class, 'lessionlist'])->name('student.videolessonslist');

     Route::get('/student/analytics', [StudentAnalyticsController::class, 'index'])->name('student.analytics');

     Route::get('/student/assessments', [StudentAssessmentsController::class, 'index'])->name('student.assessments');
     Route::get('/student/focus-areas', [StudentFocusAreasController::class, 'index'])->name('student.focusareas');
     Route::get('/student/announcements', [StudentAnnouncementsController::class, 'index'])->name('student.announcements');
     Route::get('/student/centretestscores', [StudentCentreTestScoreController::class, 'index'])->name('student.centretestscores');
   
});

// Parent
Route::middleware(['auth', 'verified', 'role:parent'])->group(function () {
    Route::get('/parent/dashboard', function () {
        return view('parent.dashboard');
    })->name('parent.dashboard');
});

Route::get('/change-theme', [HomeController::class, 'toggleTheme'])->name('change.theme');