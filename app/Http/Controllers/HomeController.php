<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function get_date(): array
    {
        $data = [];
        $data['categories'] = DB::table('stc_category_types')->get();
        $data['pos_tags'] = DB::table('stc_pos_tags')->get();
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
                foreach ($data['categories'] as $category) {
                    if ($data['category']->table_name != $category->table_name) {
                        $data['categories_result'][]['type'] = $category;
                        $data['categories_result'][sizeof($data['categories_result'])-1]['result'] = DB::table($category->table_name)
                            ->orWhere('str'. $category->type_name .'_author', 'like', '%'.$request->input('search').'%' )
                            ->orWhere('str'. $category->type_name .'_title', 'like', '%'.$request->input('search').'%' )
                            ->orWhere('str'. $category->type_name .'_keywords', 'like', '%'.$request->input('search').'%' )
                            ->groupBy([$category->type_name .'_id', 'str'. $category->type_name .'_author', 'str'. $category->type_name .'_title', 'str'. $category->type_name .'_keywords', 'str'. $category->type_name .'_body'])
                            ->orderBy('str'. $category->type_name .'_author')
                            ->select($category->type_name .'_id', 'str'. $category->type_name .'_author', 'str'. $category->type_name .'_title', 'str'. $category->type_name .'_keywords', 'str'. $category->type_name .'_body')
                            ->limit(10)->get();
                    }
                }
                $data['result'] = DB::table($data['category']->table_name)
                    ->orWhere('str'. $data['category']->type_name .'_author', 'like', '%'.$request->input('search').'%' )
                    ->orWhere('str'. $data['category']->type_name .'_title', 'like', '%'.$request->input('search').'%' )
                    ->orWhere('str'. $data['category']->type_name .'_keywords', 'like', '%'.$request->input('search').'%' )
                    ->groupBy([$data['category']->type_name .'_id', 'str'. $data['category']->type_name .'_author', 'str'. $data['category']->type_name .'_title', 'str'. $data['category']->type_name .'_keywords', 'str'. $data['category']->type_name .'_body'])
                    ->orderBy('str'. $data['category']->type_name .'_author')
                    ->select($data['category']->type_name .'_id', 'str'. $data['category']->type_name .'_author', 'str'. $data['category']->type_name .'_title', 'str'. $data['category']->type_name .'_keywords', 'str'. $data['category']->type_name .'_body')
                    ->paginate(10);
            }
            else {
                foreach ($data['categories'] as $category) {
                    if ($data['category']->table_name != $category->table_name) {
                        $data['categories_result'][]['type'] = $category;
                        $data['categories_result'][sizeof($data['categories_result'])-1]['result'] = DB::table($category->table_name)
                            ->groupBy([$category->type_name .'_id', 'str'. $category->type_name .'_author', 'str'. $category->type_name .'_title', 'str'. $category->type_name .'_keywords', 'str'. $category->type_name .'_body'])
                            ->orderBy('str'. $category->type_name .'_author')
                            ->select($category->type_name .'_id', 'str'. $category->type_name .'_author', 'str'. $category->type_name .'_title', 'str'. $category->type_name .'_keywords', 'str'. $category->type_name .'_body')
                            ->limit(10)->get();
                    }
                }
                $data['result'] = DB::table($data['category']->table_name)
                    ->groupBy([$data['category']->type_name .'_id', 'str'. $data['category']->type_name .'_author', 'str'. $data['category']->type_name .'_title', 'str'. $data['category']->type_name .'_keywords', 'str'. $data['category']->type_name .'_body'])
                    ->orderBy('str'. $data['category']->type_name .'_author')
                    ->select($data['category']->type_name .'_id', 'str'. $data['category']->type_name .'_author', 'str'. $data['category']->type_name .'_title', 'str'. $data['category']->type_name .'_keywords', 'str'. $data['category']->type_name .'_body')
                    ->paginate(10);
            }
        }
        else
            abort(404);

        session()->flashInput($request->input());

        return view('browse')->with($data);
    }

    public function write(Request $request, $category, $id)
    {
        $data = $this->get_date();
        if (isset($category))
            $data['category'] = DB::table('stc_category_types')->find($category);
        else
            $data['category'] = DB::table('stc_category_types')->get()->first();

        if (isset($data['category']))
            $result = DB::table($data['category']->table_name)->where($data['category']->type_name.'_id', $id)->get()->first();
        else abort(404);
        if (isset($result)) {
            $data['result']['original'] = array_filter(explode('.', strip_tags($result->{'str'. $data['category']->type_name .'_body'})));
//            $data['result']['original'] = [$data['result']['original'][0]];
            foreach ($data['result']['original'] as $key=>$original) {
                $request = Request::create('api/pos-tagging', 'GET', ['input'=>$original]);
                \Illuminate\Support\Facades\Request::replace($request->input());
                $instance = json_decode(Route::dispatch($request)->getContent());
                $data['result']['tagged'][] = $instance->output;

                $request = Request::create('api/syllable-hyphenation', 'GET', ['input'=>$instance->output]);
                \Illuminate\Support\Facades\Request::replace($request->input());
                $instance = json_decode(Route::dispatch($request)->getContent());
                $data['result']['hyphenated'][] = $instance->output;
            }
        }
        else abort(404);

        $data['detail'] = array_combine(array_column($data['pos_tags']->toArray(), 'tag'), array_column($data['pos_tags']->toArray(), 'detail'));
        $data['color'] = array_combine(array_column($data['pos_tags']->toArray(), 'tag'), array_column($data['pos_tags']->toArray(), 'color'));

        return view('write')->with($data);
    }
}
