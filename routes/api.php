<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

Route::get("get-navbar-categories", function () {
    return response()->json(DB::table('stc_category_types')->get()->toArray());
})->name('get-navbar-categories');

Route::get("arrange-storage-data", function (Request $request) {
    $data = json_decode($request->body, true);
    foreach (json_decode($request->words) as $key=>$word) {
        $data[$request->category][$request->paragraph][$request->sentence][$key] = $word;
    }
    return response()->json($data);
})->name('arrange-storage-data');

