<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return view('teste');
});

Route::get('/dash', function () {
    // return view('welcome');
    return view('pages.dashboard');
});

require __DIR__.'/api.php';