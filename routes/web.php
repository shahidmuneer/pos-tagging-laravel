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

Route::get('/', [\App\Http\Controllers\POSTaggingController::class, 'index']);

Route::get('/stories', [\App\Http\Controllers\POSTaggingController::class, 'stories'])->name('stories');

Route::get('/quotes', [\App\Http\Controllers\POSTaggingController::class, 'quotes'])->name('quotes');

