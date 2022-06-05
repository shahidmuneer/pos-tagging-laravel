@extends('layouts.app', ['nav_search'=>true, 'black_footer'=>true, 'page'=>'browse'])
@section('content')
    <div class="container margin-top-40">

        <ul class="nav nav-tabs">
            @foreach($categories as $key=>$value)
                <li class="nav-item">
                    <a class="nav-link nav-link1 @if($category->id==$value->id){{'active'}}@endif" data-toggle="tab" href="#{{ $value->table_name }}">{{ $value->type_name }}</a>
                </li>
            @endforeach
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" style="margin-top: 30px;">
            <div class="tab-pane container active" id="{{ $category->table_name }}">
                @if($category->id == 5)
                    @if(sizeof($result) > 0)
                        <p>Search Results</p>
                        @foreach($result as $key=>$value)
                            <div class="container">
                                <a href="{{ route('write', [$category->id, $value['track_id']]) }}">
                                        @php $title = []; @endphp
                                        @foreach(['.',',',':',';'] as $character)
                                            @if(str_contains($value['lyrics_body'], $character))
                                                @php $title[strlen(explode($character, $value['lyrics_body'])[0])] = explode($character, $value['lyrics_body'])[0]; @endphp
                                            @endif
                                        @endforeach
                                        @if($title)
                                            @php $pieces = explode(" ", trim($title[min(array_keys($title))])); $first_part = implode(" ", array_splice($pieces, 0, 10)); $other_part = implode(" ", array_splice($pieces, 10)); @endphp
                                            <h3 style="color: #F7898E">{{ $first_part }}</h3>
                                        @else
                                            @php $pieces = explode(" ", trim($value['lyrics_body'])); $first_part = implode(" ", array_splice($pieces, 0, 10)); $other_part = implode(" ", array_splice($pieces, 10)); @endphp
                                            <h3 style="color: #F7898E">{!! $first_part !!}</h3>
                                        @endisset
                                </a>
                                @if(isset($value['lyrics_body']))
                                    @php $pieces = explode(" ", trim($value['lyrics_body'])); $first_part = implode(" ", array_splice($pieces, 0, 150)); $other_part = implode(" ", array_splice($pieces, 150)); @endphp
                                    <p>{!! $first_part !!}</p>
                                @else
                                    <p>No Lyrics Available</p>
                                @endif
                                <p>Artist: <span style="color: #F7898E">{{ $value['lyrics_artist'] }}</span></p>
                            </div>
                        @endforeach
                    @else
                        <p>No Result Found</p>
                    @endif
                @else
                    @if($result->count() > 0)
                        <p>Search Results</p>
                        @foreach($result as $key=>$value)
                            <div class="container">
                                <a href="{{ route('write', [$category->id, $value->{$category->type_name .'_id'}]) }}">
                                    @if(isset($value->{'str'. $category->type_name .'_title'}))
                                        @php $pieces = explode(" ", trim($value->{'str'. $category->type_name .'_title'})); $first_part = implode(" ", array_splice($pieces, 0, 10)); $other_part = implode(" ", array_splice($pieces, 10)); @endphp
                                        <h3 style="color: #F7898E">{{ $first_part }}</h3>
                                    @else
                                        @php $title = []; @endphp
                                        @foreach(['.',',',':',';'] as $character)
                                            @if(str_contains($value->{'str'. $category->type_name .'_body'}, $character))
                                                @php $title[strlen(explode($character, $value->{'str'. $category->type_name .'_body'})[0])] = explode($character, $value->{'str'. $category->type_name .'_body'})[0]; @endphp
                                            @endif
                                        @endforeach
                                        @if($title)
                                            @php $pieces = explode(" ", trim($title[min(array_keys($title))])); $first_part = implode(" ", array_splice($pieces, 0, 10)); $other_part = implode(" ", array_splice($pieces, 10)); @endphp
                                            <h3 style="color: #F7898E">{{ $first_part }}</h3>
                                        @else
                                            @php $pieces = explode(" ", trim($value->{'str'. $category->type_name .'_body'})); $first_part = implode(" ", array_splice($pieces, 0, 10)); $other_part = implode(" ", array_splice($pieces, 10)); @endphp
                                            <h3 style="color: #F7898E">{!! $first_part !!}</h3>
                                        @endisset
                                    @endif
                                </a>
                                @php $pieces = explode(" ", trim($value->{'str'. $category->type_name .'_body'})); $first_part = implode(" ", array_splice($pieces, 0, 150)); $other_part = implode(" ", array_splice($pieces, 150)); @endphp
                                <p>{!! $first_part !!}</p>
                                <p>Author: <span style="color: #F7898E">{{ $value->{'str'. $category->type_name .'_author'} }}</span></p>
                            </div>
                        @endforeach
                        {{ $result->appends($_GET)->links() }}
                    @else
                        <p>No Result Found</p>
                    @endif
                @endif
            </div>

            @foreach($categories_result as $category_result)
                <div class="tab-pane container" id="{{ $category_result['type']->table_name }}">
                    @if($category_result['type']->id == 5)
                        @if(!empty($category_result['result']))
                            @if(sizeof($category_result['result']) > 0)
                                <p>Search Results</p>
                                @foreach($category_result['result'] as $key=>$value)
                                    <div class="container">
                                        <a href="{{ route('write', [$category_result['type']->id, $value['track_id']]) }}">
                                            @php $title = []; @endphp
                                            @foreach(['.',',',':',';','\n'] as $character)
                                                @if(str_contains($value['lyrics_body'], $character))
                                                    @php $title[strlen(explode($character, $value['lyrics_body'])[0])] = explode($character, $value['lyrics_body'])[0]; @endphp
                                                @endif
                                            @endforeach
                                            @if($title)
                                                @php $pieces = explode(" ", trim($title[min(array_keys($title))])); $first_part = implode(" ", array_splice($pieces, 0, 10)); $other_part = implode(" ", array_splice($pieces, 10)); @endphp
                                                <h3 style="color: #F7898E">{{ $first_part }}</h3>
                                            @else
                                                @php $pieces = explode(" ", trim($value['lyrics_body'])); $first_part = implode(" ", array_splice($pieces, 0, 10)); $other_part = implode(" ", array_splice($pieces, 10)); @endphp
                                                <h3 style="color: #F7898E">{!! $first_part !!}</h3>
                                            @endisset
                                        </a>
                                        @if(isset($value['lyrics_body']))
                                            @php $pieces = explode(" ", trim($value['lyrics_body'])); $first_part = implode(" ", array_splice($pieces, 0, 150)); $other_part = implode(" ", array_splice($pieces, 150)); @endphp
                                            <p>{!! $first_part !!}</p>
                                        @else
                                            <p>No Lyrics Available</p>
                                        @endif
                                        <p>Artist: <span style="color: #F7898E">{{ $value['lyrics_artist'] }}</span></p>
                                    </div>
                                @endforeach
                            @else
                                <p>No Result Found</p>
                            @endif
                        @endif
                    @else
                        @isset($category_result['result'])
                            @if($category_result['result']->count() > 0)
                                <p>Search Results</p>
                                @foreach($category_result['result'] as $key=>$value)
                                    <div class="container">
                                        <a href="{{ route('write', [$category_result['type']->id, $value->{$category_result['type']->type_name .'_id'}]) }}">
                                            @if(isset($value->{'str'. $category_result['type']->type_name .'_title'}))
                                                @php $pieces = explode(" ", trim($value->{'str'. $category_result['type']->type_name .'_title'})); $first_part = implode(" ", array_splice($pieces, 0, 10)); $other_part = implode(" ", array_splice($pieces, 10)); @endphp
                                                <h3 style="color: #F7898E">{{ $first_part }}</h3>
                                            @else
                                                @php $title = []; @endphp
                                                @foreach(['.',',',':',';'] as $character)
                                                    @if(str_contains($value->{'str'. $category_result['type']->type_name .'_body'}, $character))
                                                        @php $title[strlen(explode($character, $value->{'str'. $category_result['type']->type_name .'_body'})[0])] = explode($character, $value->{'str'. $category_result['type']->type_name .'_body'})[0]; @endphp
                                                    @endif
                                                @endforeach
                                                @if($title)
                                                    @php $pieces = explode(" ", trim($title[min(array_keys($title))])); $first_part = implode(" ", array_splice($pieces, 0, 10)); $other_part = implode(" ", array_splice($pieces, 10)); @endphp
                                                    <h3 style="color: #F7898E">{{ $first_part }}</h3>
                                                @else
                                                    @php $pieces = explode(" ", trim($value->{'str'. $category_result['type']->type_name .'_body'})); $first_part = implode(" ", array_splice($pieces, 0, 10)); $other_part = implode(" ", array_splice($pieces, 10)); @endphp
                                                    <h3 style="color: #F7898E">{!! $first_part !!}</h3>
                                                @endisset
                                            @endif
                                        </a>
                                        @php $pieces = explode(" ", trim($value->{'str'. $category_result['type']->type_name .'_body'})); $first_part = implode(" ", array_splice($pieces, 0, 150)); $other_part = implode(" ", array_splice($pieces, 150)); @endphp
                                        <p>{!! $first_part !!}</p>
                                        <p>Author: <span style="color: #F7898E">{{ $value->{'str'. $category_result['type']->type_name .'_author'} }}</span></p>
                                    </div>
                                @endforeach
                            @else
                                <p>No Result Found</p>
                            @endif
                        @endisset
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('scripts')
    <script>
    </script>
@endsection
