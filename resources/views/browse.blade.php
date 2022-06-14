@extends('layouts.app', ['nav_search'=>true, 'black_footer'=>true, 'page'=>'browse'])
@section('content')
    <div class="container margin-top-40">

        <ul class="nav nav-tabs">
            @foreach($categories as $key=>$value)
                <li class="nav-item">
                    <a class="nav-link nav-link1 @if($category->id==$value->id){{'active'}}@endif" href="{{ route('browse', $value->id) }}?search={{ old('search') }}">{{ $value->display_name }}</a>
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
                                    <h3 style="color: #F7898E">{{ $value['title'] }}</h3>
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
                                        <h3 style="color: #F7898E">{{ preg_split('/(?<!Mr.|Ms.|Mrs.|Dr.)(?<=[.?!;:,’\'"])\s+/', strip_tags($value->{'str'. $category->type_name .'_body'}), -1, PREG_SPLIT_NO_EMPTY)[0] }}</h3>
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

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {

        })
    </script>
@endsection
