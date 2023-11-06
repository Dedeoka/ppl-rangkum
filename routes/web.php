<?php

use Illuminate\Support\Facades\Route;

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
return view('auth.login');
})->middleware('guest');

Route::middleware('auth')->group(function(){

    Route::get('/perhitungan-akar-api', function () {
        return view('data-perhitungan-api');
    })->name('data-api');

    Route::get('/perhitungan-akar-plsql', function () {
        return view('data-perhitungan-plsql');
    })->name('data-plsql');

    Route::get('/data-user', function () {
        return view('data-user');
    })->name('data-user');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
