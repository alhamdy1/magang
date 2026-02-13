<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\Guest\PermitController as GuestPermitController;
use App\Http\Controllers\Operator\PermitController as OperatorPermitController;
use App\Http\Controllers\Kasi\PermitController as KasiPermitController;
use App\Http\Controllers\Kabid\PermitController as KabidPermitController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public tracking routes (accessible by anyone) - with rate limiting
Route::prefix('tracking')->name('tracking.')->middleware('throttle:30,1')->group(function () {
    Route::get('/', [TrackingController::class, 'index'])->name('index');
    Route::post('/search', [TrackingController::class, 'search'])->name('search');
    Route::get('/{trackingNumber}', [TrackingController::class, 'show'])->name('show');
    Route::post('/{trackingNumber}/verify', [TrackingController::class, 'verify'])->name('verify');
});

// Guest permit submission (without login) - with rate limiting to prevent abuse
Route::prefix('guest')->name('guest.')->middleware('throttle:10,1')->group(function () {
    Route::get('/permits/create', [GuestPermitController::class, 'create'])->name('permits.create');
    Route::post('/permits', [GuestPermitController::class, 'store'])->middleware('throttle:5,60')->name('permits.store');
    Route::get('/permits/success', [GuestPermitController::class, 'success'])->name('permits.success');
});

// Auth routes (login/register) - with rate limiting to prevent brute force
Route::middleware(['guest', 'throttle:10,1'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:3,1');
});

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// User routes (regular users/applicants)
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
    Route::get('/permits', [PermitController::class, 'index'])->name('permits.index');
    Route::get('/permits/create', [PermitController::class, 'create'])->name('permits.create');
    Route::post('/permits', [PermitController::class, 'store'])->name('permits.store');
    Route::get('/permits/{permit}', [PermitController::class, 'show'])->name('permits.show');
    Route::get('/permits/{permit}/track', [PermitController::class, 'track'])->name('permits.track');
});

// Operator routes
Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'operatorDashboard'])->name('dashboard');
    Route::get('/permits', [OperatorPermitController::class, 'index'])->name('permits.index');
    Route::get('/permits/my', [OperatorPermitController::class, 'myPermits'])->name('permits.my');
    Route::post('/permits/{permit}/claim', [OperatorPermitController::class, 'claim'])->name('permits.claim');
    Route::post('/permits/{permit}/release', [OperatorPermitController::class, 'release'])->name('permits.release');
    Route::get('/permits/{permit}', [OperatorPermitController::class, 'show'])->name('permits.show');
    Route::post('/permits/{permit}/approve', [OperatorPermitController::class, 'approve'])->name('permits.approve');
    Route::post('/permits/{permit}/reject', [OperatorPermitController::class, 'reject'])->name('permits.reject');
});

// Kasi routes
Route::middleware(['auth', 'role:kasi'])->prefix('kasi')->name('kasi.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'kasiDashboard'])->name('dashboard');
    Route::get('/permits', [KasiPermitController::class, 'index'])->name('permits.index');
    Route::get('/permits/{permit}', [KasiPermitController::class, 'show'])->name('permits.show');
    Route::post('/permits/{permit}/approve', [KasiPermitController::class, 'approve'])->name('permits.approve');
    Route::post('/permits/{permit}/reject', [KasiPermitController::class, 'reject'])->name('permits.reject');
});

// Kabid routes
Route::middleware(['auth', 'role:kabid'])->prefix('kabid')->name('kabid.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'kabidDashboard'])->name('dashboard');
    Route::get('/permits', [KabidPermitController::class, 'index'])->name('permits.index');
    Route::get('/permits/{permit}', [KabidPermitController::class, 'show'])->name('permits.show');
    Route::post('/permits/{permit}/approve', [KabidPermitController::class, 'approve'])->name('permits.approve');
    Route::post('/permits/{permit}/reject', [KabidPermitController::class, 'reject'])->name('permits.reject');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    Route::resource('/users', UserController::class);
});
