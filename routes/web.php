<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\ManualBackupManagerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\EditProfileController;
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
    Route::get('/inventory-manager', [InventoryController::class, 'index'])->name('inventory');
    Route::get('/logs', [LogsController::class, 'index'])->name('logs');
    Route::get('/manual-backup-manager', [ManualBackupManagerController::class, 'index'])->name('manual-backup-manager');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/edit-profile', [SettingsController::class, 'profile'])->name('edit-profile');

    // ðŸ“¦ File Download (Wasabi â†’ Local)
    Route::get('/inventory/download/{file}', [InventoryController::class, 'download'])
        ->name('inventory.download');


    // Serve the locally restored file produced by the queued job batch
    Route::get('restore/download/{batch}', [InventoryController::class, 'serveRestore'])
        ->name('restore.download');
        
    Route::get('/manual-backup', [ManualBackupManagerController::class, 'index'])->name('backup.index');
    Route::post('/manual-backup/run', [ManualBackupManagerController::class, 'runBackup'])->name('backup.run');
    Route::get('/manual-backup/stream', [ManualBackupManagerController::class, 'stream'])->name('backup.stream');
   
    Route::get('/backup/download/{filename}', [ManualBackupManagerController::class, 'download'])->name('backup.download');
    Route::get('/backup/downloadZip/{filename}', [ManualBackupManagerController::class, 'downloadZip'])->name('backup.downloadZip');

    
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
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Student
Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});

// Parent
Route::middleware(['auth', 'verified', 'role:parent'])->group(function () {
    Route::get('/parent/dashboard', function () {
        return view('parent.dashboard');
    })->name('parent.dashboard');
});