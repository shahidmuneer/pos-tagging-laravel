<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
})->name('index');
Route::get('/home/{id?}', [\App\Http\Controllers\HomeController::class, 'home'])->where('id', '[0-9]')->name('home');

Route::get('/browse/{id}', [\App\Http\Controllers\HomeController::class, 'browse'])->where('id', '[0-9]+')->name('browse');

Route::get('/write/{category}/{id}', [\App\Http\Controllers\HomeController::class, 'write'])->where('category', '[0-9]+')->where('id', '[0-9]+')->name('write');

Route::get('/show/{id}/{paragraph}', [\App\Http\Controllers\HomeController::class, 'show'])->where('id', '[0-9]+')->where('paragraph', '[0-9]+')->name('show');

Route::post('/get-hyphenated-data', [\App\Http\Controllers\HomeController::class, 'get_hyphenated_data'])->name('get-hyphenated-data');

