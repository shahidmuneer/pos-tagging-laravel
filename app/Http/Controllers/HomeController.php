<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function get_date(): array
    {
        $data = [];
        $data['categories'] = DB::table('stc_category_types')->get();
        return $data;
    }

    public function home(Request $request, $id=null)
    {
        $data = $this->get_date();

        if (isset($id))
            $data['category'] = DB::table('stc_category_types')->find($id);
        else
            $data['category'] = DB::table('stc_category_types')->get()->first();
        if (!isset($data['category']))
            abort(404);

        return view('home')->with($data);
    }

    public function browse(Request $request, $id)
    {
        $data = $this->get_date();

        if (isset($id))
            $data['category'] = DB::table('stc_category_types')->find($id);
        else
            $data['category'] = DB::table('stc_category_types')->get()->first();

        if (isset($data['category'])) {
            if ($request->filled('search')) {
                $data['result'] = DB::table($data['category']->table_name)
                    ->orWhere('str'. $data['category']->type_name .'_author', 'like', '%'.$request->input('search').'%' )
                    ->orWhere('str'. $data['category']->type_name .'_title', 'like', '%'.$request->input('search').'%' )
                    ->orWhere('str'. $data['category']->type_name .'_keywords', 'like', '%'.$request->input('search').'%' )
                    ->groupBy(['str'. $data['category']->type_name .'_author', 'str'. $data['category']->type_name .'_title', 'str'. $data['category']->type_name .'_keywords', 'str'. $data['category']->type_name .'_body'])
                    ->orderBy('str'. $data['category']->type_name .'_author')
                    ->select('str'. $data['category']->type_name .'_author', 'str'. $data['category']->type_name .'_title', 'str'. $data['category']->type_name .'_keywords', 'str'. $data['category']->type_name .'_body')
                    ->paginate(10);
            }
        }
        else
            abort(404);

        session()->flashInput($request->input());

        return view('browse')->with($data);
    }
}
