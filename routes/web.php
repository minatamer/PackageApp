<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/provider', function () {
    return view('provider');
});

Route::get('/provider', [UserController::class, 'viewSchedule'])-> name('viewSchedule');

Route::get('/create-session', function () {
    return view('create-session');
});

Route::post('/create-session', [UserController::class, 'createSession'])->name('create-session');
Route::get('/delete-session/{id}', [UserController::class, 'removeSession'])->name('delete-session');

Route::get('/seeker', [UserController::class, 'viewUnreserved'])-> name('viewUnreserved');
Route::get('/reserved-sessions', [UserController::class, 'viewReserved'])-> name('viewReserved');
Route::get('/book-session/{id}', [UserController::class, 'book'])->name('book-session');

Route::get('pay/callback', [UserController::class, 'handlePaymobCallback']);

Route::get('/logout', [UserController::class, 'logout'])-> name('logout');

require __DIR__ . '/authRoute.php';
