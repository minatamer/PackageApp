<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/login', function () {return view('index');});
Route::get('/register', function () {return view('signup');});
Route::get('/forgot-password', function () {return view('forgot-password');});

Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('forgot-password', [UserController::class, 'forgotPassword'])->name('forgot-password');
// Facebook Authentication Routes
Route::get('auth/facebook',[UserController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('auth/facebook/callback', [UserController::class, 'handleFacebookCallback']);

// Apple Authentication Routes
Route::get('auth/apple',[UserController::class, 'redirectToApple'])->name('apple.login');
Route::get('auth/apple/callback', [UserController::class, 'handleAppleCallback']);

// Gmail Authentication Routes
Route::get('auth/google',[UserController::class, 'redirectToGmail'])->name('gmail.login');
Route::get('auth/google/callback', [UserController::class, 'handleGmailCallback']);
