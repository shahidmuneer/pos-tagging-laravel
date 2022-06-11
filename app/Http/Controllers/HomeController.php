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

    public function get_hyphenated_data(Request $request)
    {
        $data = $this->get_date();

        $request = Request::create('api/pos-tagging', 'GET', ['input'=>$request->data]);
        \Illuminate\Support\Facades\Request::replace($request->input());
        $instance = json_decode(Route::dispatch($request)->getContent());

        $request = Request::create('api/syllable-hyphenation', 'GET', ['input'=>$instance->output]);
        \Illuminate\Support\Facades\Request::replace($request->input());
        $instance = json_decode(Route::dispatch($request)->getContent());

        $output = '';
        $detail = array_combine(array_column($data['pos_tags']->toArray(), 'tag'), array_column($data['pos_tags']->toArray(), 'detail'));
        $color = array_combine(array_column($data['pos_tags']->toArray(), 'tag'), array_column($data['pos_tags']->toArray(), 'color'));
        $hyphenated_words = array_chunk(explode(' ', $instance->output), '3');
        $output .= '<ul class="line-height-40">';
        foreach($hyphenated_words as $hyphenated_word) {
            foreach($hyphenated_word as $key=>$value) {
                $output .= '<li> <span class="'. (isset($color[explode('_', $value)[1]])?$color[explode('_', $value)[1]]:'line-empty') .'">'. explode('_', $value)[0] .'</span> <p class="line-yellow-1">'. (isset($detail[explode('_', $value)[1]])?$detail[explode('_', $value)[1]]:' ') .'</p></li>';
            }
        }
        $output .= '</ul>';

        return response()->json($output);
    }

    public static function get_write_data($body_string): array
    {
        $write_data['original'] = array_filter(explode('.', strip_tags($body_string)));
        if (false) {
            $write_data['original'] = [$write_data['original'][1]];
            foreach ($write_data['original'] as $key=>$original) {
                $request = Request::create('api/pos-tagging', 'GET', ['input'=>$original]);
                \Illuminate\Support\Facades\Request::replace($request->input());
                $instance = json_decode(Route::dispatch($request)->getContent());

                $request = Request::create('api/syllable-hyphenation', 'GET', ['input'=>$instance->output]);
                \Illuminate\Support\Facades\Request::replace($request->input());
                $instance = json_decode(Route::dispatch($request)->getContent());
                $write_data['hyphenated'][] = $instance->output;
            }
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
                if ($result->header->status_code == 200) {
                    $data['result'] = self::get_write_data(explode('...', $result->body->lyrics->lyrics_body)[0]);
                    $response = $client->get('http://api.musixmatch.com/ws/1.1/track.get?track_id='. $id .'&apikey=48028391abc6e6a2cfa175efc94f6103')->getBody();
                    $data['title'] = json_decode($response)->message->body->track->track_name;
                    $data['name'] = json_decode($response)->message->body->track->artist_name;
                }
                else abort(404);
            }
            else {
                $result = DB::table($data['category']->table_name)->where($data['category']->type_name.'_id', $id)->get()->first();
                if (isset($result)) {
                    $data['result'] = self::get_write_data($result->{'str'. $data['category']->type_name .'_body'});
                    $data['title'] = $result->{'str'. $data['category']->type_name .'_title'};
                    if (!isset($data['title'])) {
                        $title = [];
                        foreach(['.',',',':',';',"'"] as $character) {
                            if(str_contains($result->{'str'. $data['category']->type_name .'_body'}, $character))
                                $title[strlen(explode($character, $result->{'str'. $data['category']->type_name .'_body'})[0])] = explode($character, $result->{'str'. $data['category']->type_name .'_body'})[0];
                        }
                        if (!empty($title))
                            $pieces = explode(" ", trim($title[min(array_keys($title))]));
                        else
                            $pieces = explode(" ", trim($result->{'str'. $data['category']->type_name .'_body'}));
                        $first_part = implode(" ", array_splice($pieces, 0, 10));
                        $data['title'] = $first_part;
                    }
                    $data['name'] = $result->{'str'. $data['category']->type_name .'_author'};
                }
                else abort(404);
            }
        }
        else abort(404);

        return view('write')->with($data);
    }

    public function show(Request $request, $id=null)
    {
        $data = $this->get_date();

        if (isset($id))
            $data['category'] = DB::table('stc_category_types')->find($id);
        else
            $data['category'] = DB::table('stc_category_types')->get()->first();
        if (!isset($data['category']))
            abort(404);

        return view('show')->with($data);
    }

}
