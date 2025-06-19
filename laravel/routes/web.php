<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    // return view('welcome');
    return view('teste');
});

// Route::get('/dash', function () {
//     return view('pages.dashboard');
// });

Route::get('/login', [AuthController::class, 'loginView'])->name('loginView');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');
});


require __DIR__.'/api.php';