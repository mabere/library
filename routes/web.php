<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\MailTestController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SkripsiRequestController;
use App\Http\Controllers\AdminDepartmentController;
use App\Http\Controllers\AdminUserStudentController;
use App\Http\Controllers\BebasPustakaRequestController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('pages.welcome'));
Route::view('/tentang', 'pages.about');
Route::view('/bantuan', 'pages.help');
Route::view('/contact', 'pages.contact');

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Public Verify
|--------------------------------------------------------------------------
*/
Route::get('/verify', [LetterController::class, 'verifyForm'])->name('verify.form')->middleware('throttle:30,1');
Route::get('/verify/scan', [LetterController::class, 'verifyScan'])->name('verify.scan')->middleware('throttle:30,1');
Route::post('/verify', [LetterController::class, 'verifyUpload'])->middleware('throttle:20,1');
Route::get('/letter/verify/{token}', [LetterController::class, 'verify'])->middleware('throttle:60,1');
Route::get('/letter/{token}', [LetterController::class, 'verify'])->middleware('throttle:60,1');

/*
|--------------------------------------------------------------------------
| Public Visitors
|--------------------------------------------------------------------------
*/
Route::get('/pengunjung', [VisitorController::class, 'create'])->name('visitors.form');
Route::post('/pengunjung', [VisitorController::class, 'store'])->name('visitors.store');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Letters (manual/admin)
    Route::resource('letters', LetterController::class);
    Route::get('/laporan', [LetterController::class, 'report'])->name('letters.report');
    Route::post('/laporan/cetak', [LetterController::class, 'printReport']);
    Route::post('/laporan/export-csv', [LetterController::class, 'exportReportCsv'])->name('letters.report.export');
    Route::get('/arsip-surat', [LetterController::class, 'archive'])->name('letters.archive');
    Route::get('/arsip-surat/export', [LetterController::class, 'exportArchiveCsv'])->name('letters.archive.export');
    Route::put('/arsip-surat/{id}/batal', [LetterController::class, 'cancel']);
    Route::get('/kartu/{id}', [CardController::class, 'card']);

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read_all');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');

    Route::post('/mail/test', [MailTestController::class, 'send'])
        ->middleware('role:admin')
        ->name('mail.test');

    // Download (policy controlled)
    Route::prefix('bebas-pustaka')->name('bebas_pustaka.')->group(function () {
        Route::get('{id}/download', [BebasPustakaRequestController::class, 'downloadLetter'])->name('download');
    });
    Route::prefix('skripsi')->name('skripsi.')->group(function () {
        Route::get('{id}/download', [SkripsiRequestController::class, 'downloadLetter'])->name('download');
    });

    Route::get('/bebas-pustaka/{id}', [BebasPustakaRequestController::class, 'show'])
        ->name('bebas_pustaka.show');
    Route::get('/skripsi/{id}', [SkripsiRequestController::class, 'show'])
        ->name('skripsi.show');
});

/*
|--------------------------------------------------------------------------
| Role: Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboardAdmin'])->name('dashboard');

    Route::prefix('bebas-pustaka')->name('bebas_pustaka.')->group(function () {
        Route::get('/', [BebasPustakaRequestController::class, 'indexKepala'])->name('index');
        Route::put('{id}/setujui', [BebasPustakaRequestController::class, 'approveKepala'])->name('approve');
    });

    Route::prefix('skripsi')->name('skripsi.')->group(function () {
        Route::get('/', [SkripsiRequestController::class, 'indexKepala'])->name('index');
        Route::put('{id}/setujui', [SkripsiRequestController::class, 'approveKepala'])->name('approve');
    });

    Route::prefix('users')->name('users.')->middleware('can:manage-users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('{id}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('{id}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('{id}', [AdminUserController::class, 'destroy'])->name('destroy');
        Route::post('{id}/test-whatsapp', [AdminUserController::class, 'testWhatsapp'])->name('test_whatsapp');
    });

    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [AdminDepartmentController::class, 'index'])->name('index');
        Route::get('create', [AdminDepartmentController::class, 'create'])->name('create');
        Route::post('/', [AdminDepartmentController::class, 'store'])->name('store');
        Route::get('{id}/edit', [AdminDepartmentController::class, 'edit'])->name('edit');
        Route::put('{id}', [AdminDepartmentController::class, 'update'])->name('update');
        Route::delete('{id}', [AdminDepartmentController::class, 'destroy'])->name('destroy');
    });

    Route::get('activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity_logs.index');

    Route::get('pengunjung', [VisitorController::class, 'index'])->name('visitors.index');
    Route::get('pengunjung/export', [VisitorController::class, 'exportCsv'])->name('visitors.export');
    Route::get('pengunjung/export-pdf', [VisitorController::class, 'exportPdf'])->name('visitors.export_pdf');

    Route::prefix('user-student')->name('user_student.')->group(function () {
        Route::get('/', [AdminUserStudentController::class, 'index'])->name('index');
        Route::post('{user}', [AdminUserStudentController::class, 'update'])->name('update');
    });

});

Route::middleware(['auth', 'role:admin|staf'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('students', StudentController::class)->except(['show']);
        Route::post('students/{student}/assign-user', [StudentController::class, 'assignUser'])->name('students.assign_user');
});


/*
|--------------------------------------------------------------------------
| Role: Kaprodi
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kaprodi'])->prefix('kaprodi')->name('kaprodi.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboardKaprodi'])->name('dashboard');

    Route::prefix('bebas-pustaka')->name('bebas_pustaka.')->group(function () {
        Route::get('/', [BebasPustakaRequestController::class, 'indexKaprodi'])->name('index');
    });
    Route::prefix('skripsi')->name('skripsi.')->group(function () {
        Route::get('/', [SkripsiRequestController::class, 'indexKaprodi'])->name('index');
    });
});

/*
|--------------------------------------------------------------------------
| Role: Mahasiswa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboardMahasiswa'])->name('dashboard');

    Route::prefix('bebas-pustaka')->name('bebas_pustaka.')->group(function () {
        Route::get('/', [BebasPustakaRequestController::class, 'indexMahasiswa'])->name('index');
        Route::get('ajukan', [BebasPustakaRequestController::class, 'create'])->name('create');
        Route::post('/', [BebasPustakaRequestController::class, 'store'])->name('store');
    });

    Route::prefix('skripsi')->name('skripsi.')->group(function () {
        Route::get('/', [SkripsiRequestController::class, 'indexMahasiswa'])->name('index');
        Route::get('ajukan', [SkripsiRequestController::class, 'create'])->name('create');
        Route::post('/', [SkripsiRequestController::class, 'store'])->name('store');
    });
});

/*
|--------------------------------------------------------------------------
| Role: Staf
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staf'])->prefix('staf')->name('staf.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboardStaf'])->name('dashboard');

    Route::prefix('bebas-pustaka')->name('bebas_pustaka.')->group(function () {
        Route::get('/', [BebasPustakaRequestController::class, 'indexStaf'])->name('index');
        Route::put('{id}/verifikasi', [BebasPustakaRequestController::class, 'verifyStaf'])->name('verify');
        Route::put('{id}/tolak', [BebasPustakaRequestController::class, 'rejectStaf'])->name('reject');
    });

    Route::prefix('skripsi')->name('skripsi.')->group(function () {
        Route::get('/', [SkripsiRequestController::class, 'indexStaf'])->name('index');
        Route::put('{id}/verifikasi', [SkripsiRequestController::class, 'verifyStaf'])->name('verify');
        Route::put('{id}/tolak', [SkripsiRequestController::class, 'rejectStaf'])->name('reject');
    });

    Route::prefix('user-student')->name('user_student.')->group(function () {
        Route::get('/', [AdminUserStudentController::class, 'index'])->name('index');
        Route::post('{user}', [AdminUserStudentController::class, 'update'])->name('update');
    });

    Route::get('pengunjung', [VisitorController::class, 'index'])->name('visitors.index');
    Route::get('pengunjung/export', [VisitorController::class, 'exportCsv'])->name('visitors.export');
    Route::get('pengunjung/export-pdf', [VisitorController::class, 'exportPdf'])->name('visitors.export_pdf');
});

/*
|--------------------------------------------------------------------------
| Role: Kepala
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kepala'])->prefix('kepala')->name('kepala.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboardKepala'])->name('dashboard');

    Route::prefix('bebas-pustaka')->name('bebas_pustaka.')->group(function () {
        Route::get('/', [BebasPustakaRequestController::class, 'indexKepala'])->name('index');
        Route::put('{id}/setujui', [BebasPustakaRequestController::class, 'approveKepala'])->name('approve');
    });

    Route::prefix('skripsi')->name('skripsi.')->group(function () {
        Route::get('/', [SkripsiRequestController::class, 'indexKepala'])->name('index');
        Route::put('{id}/setujui', [SkripsiRequestController::class, 'approveKepala'])->name('approve');
    });

    Route::get('users', [AdminUserController::class, 'indexReadOnly'])->name('users.index');

    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [AdminDepartmentController::class, 'index'])->name('index');
        Route::get('create', [AdminDepartmentController::class, 'create'])->name('create');
        Route::post('/', [AdminDepartmentController::class, 'store'])->name('store');
        Route::get('{id}/edit', [AdminDepartmentController::class, 'edit'])->name('edit');
        Route::put('{id}', [AdminDepartmentController::class, 'update'])->name('update');
        Route::delete('{id}', [AdminDepartmentController::class, 'destroy'])->name('destroy');
    });
});
