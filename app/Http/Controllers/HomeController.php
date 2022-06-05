<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public static function get_lyric_browse($search): array
    {
        $lyric_data = [];
        $client = new \GuzzleHttp\Client();
        $response = $client->get('http://api.musixmatch.com/ws/1.1/track.search?q_track='. $search .'&f_has_lyrics=1&f_lyrics_language=en&apikey=48028391abc6e6a2cfa175efc94f6103')->getBody();
        $result = json_decode($response)->message;
        if ($result->header->status_code == 200) {
            foreach ($result->body->track_list as $track_list) {
                $lyric_data[]['lyrics_artist'] = $track_list->track->artist_name;
                $lyric_data[sizeof($lyric_data)-1]['track_id'] = $track_list->track->track_id;
                $response = $client->get('http://api.musixmatch.com/ws/1.1/track.lyrics.get?track_id='. $track_list->track->track_id .'&apikey=48028391abc6e6a2cfa175efc94f6103')->getBody();
                $result = json_decode($response)->message;
                if ($result->header->status_code == 200)
                    $lyric_data[sizeof($lyric_data)-1]['lyrics_body'] = explode('...', $result->body->lyrics->lyrics_body)[0];
            }
        }
        return $lyric_data;
    }
    public static function get_category_browse($selected_category, $search_data): \Illuminate\Database\Query\Builder
    {
        return DB::table($selected_category->table_name)
            ->orWhere('str'. $selected_category->type_name .'_author', 'like', '%'.$search_data.'%' )
            ->orWhere('str'. $selected_category->type_name .'_title', 'like', '%'.$search_data.'%' )
            ->orWhere('str'. $selected_category->type_name .'_keywords', 'like', '%'.$search_data.'%' )
            ->groupBy([$selected_category->type_name .'_id', 'str'. $selected_category->type_name .'_author', 'str'. $selected_category->type_name .'_title', 'str'. $selected_category->type_name .'_keywords', 'str'. $selected_category->type_name .'_body'])
            ->orderBy('str'. $selected_category->type_name .'_author')
            ->select($selected_category->type_name .'_id', 'str'. $selected_category->type_name .'_author', 'str'. $selected_category->type_name .'_title', 'str'. $selected_category->type_name .'_keywords', 'str'. $selected_category->type_name .'_body');
    }

    public static function get_write_data($body_string): array
    {
        $write_data['original'] = array_filter(explode('.', strip_tags($body_string)));
//        $write_data['original'] = [$write_data['original'][1]];
        foreach ($write_data['original'] as $key=>$original) {
            $request = Request::create('api/pos-tagging', 'GET', ['input'=>$original]);
            \Illuminate\Support\Facades\Request::replace($request->input());
            $instance = json_decode(Route::dispatch($request)->getContent());

            $request = Request::create('api/syllable-hyphenation', 'GET', ['input'=>$instance->output]);
            \Illuminate\Support\Facades\Request::replace($request->input());
            $instance = json_decode(Route::dispatch($request)->getContent());
            $write_data['hyphenated'][] = $instance->output;
        }
        return $write_data;
    }

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
                        if ($category->id == 5)
                            $data['categories_result'][sizeof($data['categories_result'])-1]['result'] = self::get_lyric_browse($request->input('search'));
                        else
                            $data['categories_result'][sizeof($data['categories_result'])-1]['result'] = self::get_category_browse($category, $request->input('search'))->limit(10)->get();
                    }
                }
                if ($data['category']->id == 5)
                    $data['result'] = self::get_lyric_browse($request->input('search'));
                else
                    $data['result'] = self::get_category_browse($data['category'], $request->input('search'))->paginate(10);
            }
            else
                return redirect()->route('home', $data['category']->id);
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

        if (isset($data['category'])) {
            if ($data['category']->id == 5) {
                $client = new \GuzzleHttp\Client();
                $response = $client->get('http://api.musixmatch.com/ws/1.1/track.lyrics.get?track_id='. $id .'&apikey=48028391abc6e6a2cfa175efc94f6103')->getBody();
                $result = json_decode($response)->message;
                if ($result->header->status_code == 200)
                    $data['result'] = self::get_write_data(explode('...', $result->body->lyrics->lyrics_body)[0]);
                else abort(404);
            }
            else {
                $result = DB::table($data['category']->table_name)->where($data['category']->type_name.'_id', $id)->get()->first();
                if (isset($result))
                    $data['result'] = self::get_write_data($result->{'str'. $data['category']->type_name .'_body'});
                else abort(404);
            }
        }
        else abort(404);

        $data['detail'] = array_combine(array_column($data['pos_tags']->toArray(), 'tag'), array_column($data['pos_tags']->toArray(), 'detail'));
        $data['color'] = array_combine(array_column($data['pos_tags']->toArray(), 'tag'), array_column($data['pos_tags']->toArray(), 'color'));

        return view('write')->with($data);
    }
}
