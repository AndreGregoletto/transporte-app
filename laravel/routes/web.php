<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Transporte\OlhoVivo\OlhoVivoController;
use App\Http\Controllers\Transporte\OlhoVivo\Import\FrequencyController;


Route::get('/login', [AuthController::class, 'loginView'])->name('loginView');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// proteger por ADIM
Route::post('/import-frequencies', [FrequencyController::class, 'importFrequencies'])->name('import.frequencies');
Route::get('/import-frequencies', function () {
    return view('pages.transport.olhoVivo.import.importFrequencies', [
        'title' => 'Importar Frequências',
        'subtitle' => 'Importar Frequências de Ônibus',
    ]);
})->name('import.frequencies.form');
// 

Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::get('/', function () {
        return view('teste');
    });

    Route::get('/dashboard', function () {
        session(['search' => '']);
        return view('pages.dashboard');
    })->name('dashboard.index');

    Route::controller(OlhoVivoController::class)->group(function () {
        Route::get('/testOlhoVivo',  'index')->name('olhoVivo.index');
        Route::post('/testOlhoVivo', 'search')->name('olhoVivo.search');
        Route::post('/olhoVivo/{cl}/{lc}/{lt}/{sl}/{tl}/{tp}/{ts}/{name_bus}/addLine', 'addLine')->name('olhoVivo.addLine');
        // Route::post('/addLine',      'addLine')->name('olhoVivo.addLine');
        Route::delete('/olhoVivo/{id}/{cl}/removeLine',  'removeLine')->name('olhoVivo.removeLine');
    });

});


require __DIR__.'/api.php';