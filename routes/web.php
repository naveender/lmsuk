<?php

use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\ManualBackupManagerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\EditProfileController;

use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\ParentController as AdminParentController;

use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\PaperController as AdminPaperController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Tutor\HomeController as TutorHomeController;
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
Route::post('/register/check-username', [App\Http\Controllers\Auth\RegisterUsernameController::class, 'check'])->name('register.check-username');
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
        'tutor' => redirect('/tutor/dashboard'),
        default => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminHomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/topics', [TopicController::class, 'index'])->name('topics');
    Route::get('/admin/add-topic', [TopicController::class, 'add'])->name('add.topic');
    Route::post('/admin/topics', [TopicController::class, 'store'])->name('topics.store');
    Route::get('/admin/topics/{topic}/edit', [TopicController::class, 'edit'])->name('topics.edit');
    Route::put('/admin/topics/{topic}', [TopicController::class, 'update'])->name('topics.update');
    Route::delete('/admin/topics/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy');
    
    // Classes
    Route::resource('admin/classes', \App\Http\Controllers\Admin\ClassesController::class)->names([
        'index' => 'admin.classes.index',
        'create' => 'admin.classes.create',
        'store' => 'admin.classes.store',
        'edit' => 'admin.classes.edit',
        'update' => 'admin.classes.update',
        'destroy' => 'admin.classes.destroy',
    ]);
    Route::get('admin/classes/{class}/students', [\App\Http\Controllers\Admin\ClassesController::class, 'students'])->name('admin.classes.students');
    Route::post('admin/classes/{class}/students/add', [\App\Http\Controllers\Admin\ClassesController::class, 'addStudents'])->name('admin.classes.students.add');
    Route::post('admin/classes/{class}/students/remove/{student}', [\App\Http\Controllers\Admin\ClassesController::class, 'removeStudent'])->name('admin.classes.students.remove');

    // Year Groups
    Route::resource('admin/year-groups', \App\Http\Controllers\Admin\YearGroupController::class)->names([
        'index' => 'admin.year-groups.index',
        'create' => 'admin.year-groups.create',
        'store' => 'admin.year-groups.store',
        'edit' => 'admin.year-groups.edit',
        'update' => 'admin.year-groups.update',
        'destroy' => 'admin.year-groups.destroy',
    ]);

    // Academic Years / Sessions
    Route::resource('admin/academic-years', \App\Http\Controllers\Admin\AcademicYearController::class)->names([
        'index' => 'admin.academic-years.index',
        'create' => 'admin.academic-years.create',
        'store' => 'admin.academic-years.store',
        'edit' => 'admin.academic-years.edit',
        'update' => 'admin.academic-years.update',
        'destroy' => 'admin.academic-years.destroy',
    ]);

    // Subjects
    Route::resource('admin/subjects', \App\Http\Controllers\Admin\SubjectsController::class)->names([
        'index' => 'admin.subjects.index',
        'create' => 'admin.subjects.create',
        'store' => 'admin.subjects.store',
        'edit' => 'admin.subjects.edit',
        'update' => 'admin.subjects.update',
        'destroy' => 'admin.subjects.destroy',
    ]);

    // Announcements
    Route::get('admin/announcements/{announcement}/stats', [\App\Http\Controllers\Admin\AnnouncementController::class, 'stats'])->name('admin.announcements.stats');
    Route::get('admin/announcements/{announcement}/audit-logs', [\App\Http\Controllers\Admin\AnnouncementController::class, 'auditLogs'])->name('admin.announcements.audit-logs');
    Route::resource('admin/announcements', \App\Http\Controllers\Admin\AnnouncementController::class)->names([
        'index' => 'admin.announcements.index',
        'create' => 'admin.announcements.create',
        'store' => 'admin.announcements.store',
        'edit' => 'admin.announcements.edit',
        'update' => 'admin.announcements.update',
        'destroy' => 'admin.announcements.destroy',
    ]);

    // Users Management
    Route::resource('admin/users', AdminUsersController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    Route::resource('admin/students', AdminStudentController::class)->names([
        'index' => 'admin.students.index',
        'create' => 'admin.students.create',
        'store' => 'admin.students.store',
        'edit' => 'admin.students.edit',
        'update' => 'admin.students.update',
        'destroy' => 'admin.students.destroy',
    ]);

    Route::resource('admin/parents', AdminParentController::class)->names([
        'index' => 'admin.parents.index',
        'create' => 'admin.parents.create',
        'store' => 'admin.parents.store',
        'show' => 'admin.parents.show',
        'edit' => 'admin.parents.edit',
        'update' => 'admin.parents.update',
        'destroy' => 'admin.parents.destroy',
    ]);

    // Parent-Student hierarchy management
    Route::post('admin/parents/{parent}/unlink-student/{student}', [AdminParentController::class, 'unlinkStudent'])->name('admin.parents.unlink-student');
    Route::post('admin/parents/{parent}/link-student', [AdminParentController::class, 'linkStudent'])->name('admin.parents.link-student');

    // Questions
    Route::get('admin/questions/get-topics/{subjectId?}', [AdminQuestionController::class, 'getTopics'])->name('admin.questions.get-topics');
    Route::get('admin/questions/get-subtopics/{topicId}', [AdminQuestionController::class, 'getSubtopics'])->name('admin.questions.get-subtopics');
    Route::get('admin/questions/import', [AdminQuestionController::class, 'showImportForm'])->name('admin.questions.import-form');
    Route::post('admin/questions/import/parse', [AdminQuestionController::class, 'parseImportFile'])->name('admin.questions.import-parse');
    Route::post('admin/questions/import/process', [AdminQuestionController::class, 'processImportChunk'])->name('admin.questions.import-process');
    Route::get('admin/questions/import/sample', [AdminQuestionController::class, 'downloadSample'])->name('admin.questions.import-sample');
    Route::resource('admin/questions', AdminQuestionController::class)->names([
        'index'   => 'admin.questions.index',
        'create'  => 'admin.questions.create',
        'store'   => 'admin.questions.store',
        'edit'    => 'admin.questions.edit',
        'update'  => 'admin.questions.update',
        'destroy' => 'admin.questions.destroy',
    ]);

    // Exam Papers
    Route::resource('admin/papers', AdminPaperController::class)->names([
        'index'   => 'admin.papers.index',
        'create'  => 'admin.papers.create',
        'store'   => 'admin.papers.store',
        'edit'    => 'admin.papers.edit',
        'update'  => 'admin.papers.update',
        'destroy' => 'admin.papers.destroy',
    ]);
    Route::get('admin/papers/{paper}/assignments', [AdminPaperController::class, 'getAssignments'])->name('admin.papers.assignments');
    Route::post('admin/papers/{paper}/assign', [AdminPaperController::class, 'assign'])->name('admin.papers.assign');
});

// Student
Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
     Route::get('/student/dashboard', [StudentHomeController::class, 'index'])->name('student.dashboard');

     Route::get('/student/video-lessons-categories', [StudentLessonsController::class, 'index'])->name('student.videolessonscategories');
     Route::get('/student/video-lessons-list', [StudentLessonsController::class, 'lessionlist'])->name('student.videolessonslist');

     Route::get('/student/analytics', [StudentAnalyticsController::class, 'index'])->name('student.analytics');

     Route::get('/student/assessments', [StudentAssessmentsController::class, 'index'])->name('student.assessments');
     Route::get('/student/assessments/{subject}/topics', [StudentAssessmentsController::class, 'topics'])->name('student.assessments.topics');
     Route::get('/student/topics/{topic}/subtopics', [StudentAssessmentsController::class, 'subtopics'])->name('student.topics.subtopics');
     Route::post('/student/papers/{paper}/start', [StudentAssessmentsController::class, 'startTest'])->name('student.papers.start');
     Route::get('/student/attempts/{attempt}', [StudentAssessmentsController::class, 'takeTest'])->name('student.attempts.take');
     Route::post('/student/attempts/{attempt}/save', [StudentAssessmentsController::class, 'saveTest'])->name('student.attempts.save');
     Route::post('/student/attempts/{attempt}/pause', [StudentAssessmentsController::class, 'pauseTest'])->name('student.attempts.pause');
     Route::post('/student/attempts/{attempt}/submit', [StudentAssessmentsController::class, 'submitTest'])->name('student.attempts.submit');
     Route::get('/student/attempts/{attempt}/result', [StudentAssessmentsController::class, 'result'])->name('student.attempts.result');
     Route::get('/student/focus-areas', [StudentFocusAreasController::class, 'index'])->name('student.focusareas');
     Route::get('/student/announcements', [StudentAnnouncementsController::class, 'index'])->name('student.announcements');
     Route::post('/student/announcements/{announcement}/view', [StudentAnnouncementsController::class, 'view'])->name('student.announcements.view');
     Route::get('/student/centretestscores', [StudentCentreTestScoreController::class, 'index'])->name('student.centretestscores');
   
});

// Parent
Route::middleware(['auth', 'verified', 'role:parent'])->group(function () {
    Route::get('/parent/dashboard', function () {
        return view('parent.dashboard');
    })->name('parent.dashboard');
});
// Tutor
Route::middleware(['auth', 'verified', 'role:tutor'])->group(function () {
    Route::get('/tutor/dashboard', [TutorHomeController::class, 'index'])->name('tutor.dashboard');
    Route::get('/tutor/announcements', [\App\Http\Controllers\Tutor\AnnouncementsController::class, 'index'])->name('tutor.announcements');
    Route::post('/tutor/announcements/{announcement}/view', [\App\Http\Controllers\Tutor\AnnouncementsController::class, 'view'])->name('tutor.announcements.view');
});

Route::get('/change-theme', [HomeController::class, 'toggleTheme'])->name('change.theme');