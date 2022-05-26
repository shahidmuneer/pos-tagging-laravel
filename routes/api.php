<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("pos-tagging", [\App\Http\Controllers\POSTaggingController::class, 'pos_tagging'])->name('pos-tagging');

Route::get("syllable-hyphenation", [\App\Http\Controllers\PhpSyllableController::class, 'syllable_hyphenation'])->name('syllable-hyphenation');

