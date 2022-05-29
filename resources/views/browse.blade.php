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
                @if($result->count() > 0)
                    <p>Search Results</p>
                    @foreach($result as $key=>$value)
                        <div class="container">
                            <a href="{{ route('write', [$category->id, $value->{$category->type_name .'_id'}]) }}">
                                @if(isset($value->{'str'. $category->type_name .'_title'}))
                                    <h3 style="color: #F7898E">{{ $value->{'str'. $category->type_name .'_title'} }}</h3>
                                @else
                                    @php $title = []; @endphp
                                    @foreach(['.',',',':',';'] as $character)
                                        @if(str_contains($value->{'str'. $category->type_name .'_body'}, $character))
                                            @php $title[strlen(explode($character, $value->{'str'. $category->type_name .'_body'})[0])] = explode($character, $value->{'str'. $category->type_name .'_body'})[0]; @endphp
                                        @endif
                                    @endforeach
                                    @if($title)
                                        <h3 style="color: #F7898E">{{ $title[min(array_keys($title))] }}</h3>
                                    @else
                                        <h3 style="color: #F7898E">{!! $value->{'str'. $category_result['type']->type_name .'_body'} !!}</h3>
                                    @endisset
                                @endif
                            </a>
                            <p>{!! $value->{'str'. $category->type_name .'_body'} !!}</p>
                            <p>Author: <span style="color: #F7898E">{{ $value->{'str'. $category->type_name .'_author'} }}</span></p>
                        </div>
                    @endforeach
                    {{ $result->appends($_GET)->links() }}
                @else
                    <p>No Result Found</p>
                @endif
            </div>

            @foreach($categories_result as $category_result)
                <div class="tab-pane container" id="{{ $category_result['type']->table_name }}">
                    @isset($category_result['result'])
                        @if($category_result['result']->count() > 0)
                            <p>Search Results</p>
                            @foreach($category_result['result'] as $key=>$value)
                                <div class="container">
                                    <a href="{{ route('write', [$category_result['type']->id, $value->{$category_result['type']->type_name .'_id'}]) }}">
                                        @if(isset($value->{'str'. $category_result['type']->type_name .'_title'}))
                                            <h3 style="color: #F7898E">{{ $value->{'str'. $category_result['type']->type_name .'_title'} }}</h3>
                                        @else
                                            @php $title = []; @endphp
                                            @foreach(['.',',',':',';'] as $character)
                                                @if(str_contains($value->{'str'. $category_result['type']->type_name .'_body'}, $character))
                                                    @php $title[strlen(explode($character, $value->{'str'. $category_result['type']->type_name .'_body'})[0])] = explode($character, $value->{'str'. $category_result['type']->type_name .'_body'})[0]; @endphp
                                                @endif
                                            @endforeach
                                            @if($title)
                                                <h3 style="color: #F7898E">{{ $title[min(array_keys($title))] }}</h3>
                                            @else
                                                <h3 style="color: #F7898E">{!! $value->{'str'. $category_result['type']->type_name .'_body'} !!}</h3>
                                            @endisset
                                        @endif
                                    </a>
                                    <p>{!! $value->{'str'. $category_result['type']->type_name .'_body'} !!}</p>
                                    <p>Author: <span style="color: #F7898E">{{ $value->{'str'. $category_result['type']->type_name .'_author'} }}</span></p>
                                </div>
                            @endforeach
                        @else
                            <p>No Result Found</p>
                        @endif
                    @endisset
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('scripts')
    <script>
    </script>
@endsection
