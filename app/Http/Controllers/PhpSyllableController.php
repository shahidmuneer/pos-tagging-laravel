<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Vanderlee\Syllable\Syllable;

class PhpSyllableController extends Controller
{
    public function syllable_hyphenation(Request $request) {
        // $include=base_path() . '\vendor\vanderlee\syllable\src\Syllable.php';
        // include $include;
        // echo  $include;
        $syllable = new Syllable('en-us',true);

        // Set the directory where the .tex files are stored
        $syllable->getSource()->setPath(base_path() . '/hyphenisation/languages');

        // Set the directory where Syllable can store cache files
        $syllable->getCache()->setPath(base_path() . '/hyphenisation/cache');

        // Set the hyphen style. In this case, the &shy; HTML entity
        // for HTML (falls back to '-' for text)
        // $syllable->setHyphen(new \Vanderlee\Syllable_Hyphen_Soft);

        // Set the treshold (sensitivity)
        // $syllable->setTreshold(Vanderlee\Syllable\Syllable::TRESHOLD_MOST);

        // Output hyphenated text
        $output=$syllable->hyphenateText($request->input("input")??"");

        return response()->json(["output"=>str_replace("1","-",$output)]);
    }
}
