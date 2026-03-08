<?php

use App\Http\Controllers\ExportController;
use App\Livewire\Admin\ElectionDashboard;
use App\Livewire\Admin\ElectionForm;
use App\Livewire\Admin\ElectionIndex;
use App\Livewire\Counting\BallotEntry;
use App\Livewire\Counting\Dashboard as CountingDashboard;
use App\Livewire\Counting\Reporter;
use App\Livewire\Counting\ResultsDisplay;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Trang chủ
Route::view('/', 'welcome')->name('home');

// Routes yêu cầu đăng nhập
Route::middleware(['auth'])->group(function () {
    // Dashboard chung
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin routes - chỉ admin truy cập được
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard admin
        Route::get('/', ElectionIndex::class)->name('dashboard');

        // Elections
        Route::get('/elections', ElectionIndex::class)->name('elections.index');
        Route::get('/elections/create', ElectionForm::class)->name('elections.create');
        Route::get('/elections/{election}', ElectionForm::class)->name('elections.edit');

        // Positions (embedded trong election edit)
        Route::get('/positions/{position}', \App\Livewire\Admin\CandidateManager::class)->name('positions.edit');

        // Dashboard thống kê tổng hợp
        Route::get('/elections/{election}/dashboard', ElectionDashboard::class)->name('elections.dashboard');

        // Quản lý nhân sự
        Route::get('/users', \App\Livewire\Admin\UserManager::class)->name('users');
    });

    // Vote counting routes - admin và vote_counter đều truy cập được
    Route::middleware(['role:admin,vote_counter'])->prefix('counting')->name('counting.')->group(function () {
        Route::get('/', CountingDashboard::class)->name('dashboard');
        Route::get('/{election}', BallotEntry::class)->name('entry');
        Route::get('/{election}/report', Reporter::class)->name('report');

        // Export routes - cả admin và vote_counter đều được export
        Route::get('/{election}/export/excel', [ExportController::class, 'excel'])->name('export.excel');
        Route::get('/{election}/export/pdf', [ExportController::class, 'pdf'])->name('export.pdf');
    });

    // Export routes cho admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/elections/{election}/export/excel', [ExportController::class, 'excel'])->name('elections.export.excel');
        Route::get('/elections/{election}/export/pdf', [ExportController::class, 'pdf'])->name('elections.export.pdf');
    });
});
