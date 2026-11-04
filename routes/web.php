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


Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('classes', SchoolClassController::class);

    Route::resource('students', StudentController::class)
        ->except(['index', 'show']);

    Route::resource('point-rules', PointRuleController::class)
        ->except(['show']);

    Route::resource('users', UserController::class);
});

Route::middleware(['auth', 'role:admin,teacher'])->group(function() {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [StudentController::class, 'show'] )->name('students.show');
    Route::post('/students/{student}/follow-ups', [FollowUpController::class, 'store'])->name('students.follow-ups.store');
    Route::get('/point-transactions', [PointTransactionController::class, 'index'])->name('point-transactions.index');
    Route::get('/point-transactions/create', [PointTransactionController::class, 'create'])->name('point-transactions.create');
    Route::post('/point-transactions', [PointTransactionController::class, 'store'])->name('point-transactions.store');
    Route::get('/point-transactions/{pointTransaction}', [PointTransactionController::class, 'show'])->name('point-transactions.show');
    Route::delete('/point-transactions/{pointTransaction}', [PointTransactionController::class, 'destroy'])->name('point-transactions.destroy');
});

Route::get('/my-points', [StudentPointController::class, 'index'])->middleware(['auth', 'role:student'])->name('student.points');