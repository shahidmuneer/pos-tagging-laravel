<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSTaggingController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('stories');
    }

    public function stories(Request $request)
    {
        $data = [];

        if ($request->filled('search')) {
            $data['result'] = DB::table('tblpost_shortstories')
                ->orWhere('strPost_author', 'like', '%'.$request->input('search').'%' )
                ->orWhere('strPost_title', 'like', '%'.$request->input('search').'%' )
                ->orWhere('strPost_keywords', 'like', '%'.$request->input('search').'%' )
                ->select('strPost_author', 'strPost_title', 'strPost_keywords')
                ->paginate(10);
        }

        session()->flashInput($request->input());

        return view('index')
            ->with('category', DB::table('stc_category_types')->find(4))
            ->with($data);
    }

    public function quotes(Request $request)
    {
        $data = [];

        if ($request->filled('search')) {
            $data['result'] = DB::table('tblpost_quotes')
                ->orWhere('strPost_author', 'like', '%'.$request->input('search').'%' )
                ->orWhere('strPost_title', 'like', '%'.$request->input('search').'%' )
                ->orWhere('strPost_keywords', 'like', '%'.$request->input('search').'%' )
                ->select('strPost_author', 'strPost_title', 'strPost_keywords')
                ->paginate(10);
        }

        session()->flashInput($request->input());

        return view('index')
            ->with('category', DB::table('stc_category_types')->find(3))
            ->with($data);
    }

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
