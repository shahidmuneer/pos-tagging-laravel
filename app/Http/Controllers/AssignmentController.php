<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class AssignmentController extends Controller
{
    public function get_date(): array
    {
        $data = [];
        $data['categories'] = DB::table('stc_category_types')->get();
        $data['pos_tags'] = DB::table('stc_pos_tags')->get();
        return $data;
    }

    public function write(Request $request) {
        $data = $this->get_date();
        if (isset($id))
            $data['category'] = DB::table('stc_category_types')->find($id);
        else
            $data['category'] = DB::table('stc_category_types')->get()->first();

        return view('assignment.write')->with($data);
    }

    public function show(Request $request) {
        $data = $this->get_date();
        if (isset($id))
            $data['category'] = DB::table('stc_category_types')->find($id);
        else
            $data['category'] = DB::table('stc_category_types')->get()->first();

        $data['assignment'] = $request->except('_token');

        $request = Request::create('api/pos-tagging', 'GET', ['input'=>$data['assignment']['passage']]);
        \Illuminate\Support\Facades\Request::replace($request->input());
        $instance = json_decode(Route::dispatch($request)->getContent());
        $tag_words = explode(' ', $instance->output);

        $data['assignment']['passage'] = $tag_words;

        $data['detail'] = array_combine(array_column($data['pos_tags']->toArray(), 'tag'), array_column($data['pos_tags']->toArray(), 'detail'));
        $data['color'] = array_combine(array_column($data['pos_tags']->toArray(), 'tag'), array_column($data['pos_tags']->toArray(), 'color'));

        return view('assignment.show')->with($data);
    }
}
