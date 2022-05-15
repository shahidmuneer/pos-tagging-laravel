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
    return view('welcome');
});


Route::get("pos",function(\Illuminate\Http\Request $request){
    $model_path=base_path().'\stanford-postagger-2018-10-16\models\english-bidirectional-distsim.tagger';
    $jar_path=base_path(). '\stanford-postagger-2018-10-16\stanford-postagger.jar';
    $pos = new \StanfordTagger\POSTagger();
    $pos->setModel($model_path);
    $pos->setJarArchive($jar_path);
    $result= $pos->tag($request->input("input")??"");
    return response()->json(["output"=>$result]);
});