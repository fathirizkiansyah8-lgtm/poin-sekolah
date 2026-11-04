<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\PointRuleController;
use App\Http\Controllers\PointTransactionController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPointController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::middleware(['auth', 'single.session'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'single.session', 'role:admin'])->group(function () {
    Route::get('/students/import-template', [StudentController::class, 'downloadTemplate'])->name('students.template');
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/point-transactions/export', [PointTransactionController::class, 'export'])->name('point-transactions.export');

    Route::resource('classes', SchoolClassController::class);

    Route::resource('students', StudentController::class)
        ->except(['index', 'show']);

    Route::resource('point-rules', PointRuleController::class)
        ->except(['show']);

    Route::resource('users', UserController::class);
});

Route::middleware(['auth', 'single.session', 'role:admin,teacher'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::post('/students/{student}/follow-ups', [FollowUpController::class, 'store'])->name('students.follow-ups.store');
    Route::get('/point-transactions', [PointTransactionController::class, 'index'])->name('point-transactions.index');
    Route::get('/point-transactions/create', [PointTransactionController::class, 'create'])->name('point-transactions.create');
    Route::post('/point-transactions', [PointTransactionController::class, 'store'])->name('point-transactions.store');
    Route::get('/point-transactions/{pointTransaction}', [PointTransactionController::class, 'show'])->name('point-transactions.show');
    Route::delete('/point-transactions/{pointTransaction}', [PointTransactionController::class, 'destroy'])->name('point-transactions.destroy');
});

Route::middleware(['auth', 'single.session', 'role:student'])->group(function () {
    Route::get('/my-points', [StudentPointController::class, 'index'])->name('student.points');
    Route::get('/my-points/password', [StudentPointController::class, 'editPassword'])->name('student.password.edit');
    Route::put('/my-points/password', [StudentPointController::class, 'updatePassword'])->name('student.password.update');
});
