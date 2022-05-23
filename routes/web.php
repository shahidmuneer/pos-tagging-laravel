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


Route::get("pos-tagging",function(\Illuminate\Http\Request $request){
    $model_path=base_path().'\stanford-postagger-2018-10-16\models\english-bidirectional-distsim.tagger';
    $jar_path=base_path(). '\stanford-postagger-2018-10-16\stanford-postagger.jar';
    $pos = new \StanfordTagger\POSTagger();
    $pos->setModel($model_path);
    $pos->setJarArchive($jar_path);
    $result= $pos->tag($request->input("input")??"");
    return response()->json(["output"=>$result]);
});


Route::get("/syllable-hyphenation",function(\Illuminate\Http\Request $request){
    // $include=base_path() . '\vendor\vanderlee\syllable\src\Syllable.php';
    // include $include;
    // echo  $include;
    $syllable = new Vanderlee\Syllable\Syllable('en-us',true);

// Set the directory where the .tex files are stored
$syllable->getSource()->setPath(base_path() . '/hyphenisation/languages');

// Set the directory where Syllable can store cache files
$syllable->getCache()->setPath(__DIR__ . '\hyphenisation\cache');

// Set the hyphen style. In this case, the &shy; HTML entity
// for HTML (falls back to '-' for text)
// $syllable->setHyphen(new \Vanderlee\Syllable_Hyphen_Soft);

// Set the treshold (sensitivity)
// $syllable->setTreshold(Vanderlee\Syllable\Syllable::TRESHOLD_MOST);

// Output hyphenated text
$output=$syllable->hyphenateText('Hyphenation is often used with text in columns and when the text is fully justified, to allow dividing the text into lines of approximately even length. Hyphenation does not change the actual text, just its rendering across different lines.');

return response()->json(["output"=>str_replace("1","_",$output)]);
});
