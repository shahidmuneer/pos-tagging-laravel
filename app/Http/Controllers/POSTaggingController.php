<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSTaggingController extends Controller
{
    public function pos_tagging(Request $request) {
        $model_path=base_path().'\stanford-postagger-2018-10-16\models\english-bidirectional-distsim.tagger';
        $jar_path=base_path(). '\stanford-postagger-2018-10-16\stanford-postagger.jar';
        $pos = new \StanfordTagger\POSTagger();
        $pos->setModel($model_path);
        $pos->setJarArchive($jar_path);
        $result= $pos->tag($request->input("input")??"");
        return response()->json(["output"=>$result]);
    }

}
