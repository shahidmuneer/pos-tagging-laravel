<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function show(Request $request, $id)
    {
        $data = [];

        if ($id)
            $data['category'] = DB::table('stc_category_types')->find($id);
        else
            $data['category'] = DB::table('stc_category_types')->find(4);

        if (isset($data['category'])) {
            if ($request->filled('search')) {
                $data['result'] = DB::table($data['category']->table_name)
                    ->orWhere('str'. $data['category']->type_name .'_author', 'like', '%'.$request->input('search').'%' )
                    ->orWhere('str'. $data['category']->type_name .'_title', 'like', '%'.$request->input('search').'%' )
                    ->orWhere('str'. $data['category']->type_name .'_keywords', 'like', '%'.$request->input('search').'%' )
                    ->select('str'. $data['category']->type_name .'_author', 'str'. $data['category']->type_name .'_title', 'str'. $data['category']->type_name .'_keywords')
                    ->paginate(10);
            }

        }

        session()->flashInput($request->input());

        return view('index')
            ->with($data);
    }
}
