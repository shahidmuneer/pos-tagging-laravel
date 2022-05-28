@extends('layouts.app', ['nav_search'=>true, 'black_footer'=>true, 'page'=>'browse'])
@section('content')
    <div class="container margin-top-40">

        <ul class="nav nav-tabs">
            @foreach($categories as $key=>$value)
                <li class="nav-item">
                    <a class="nav-link nav-link1 @if($category->id==$value->id){{'active'}}@endif" data-toggle="tab" href="#">{{ $value->type_name }}</a>
                </li>
            @endforeach
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" style="margin-top: 30px;">
            <div class="tab-pane container active" id="home">
                @isset($result)
                    @if($result->count() > 0)
                        <p>Search Results</p>
                        @foreach($result as $key=>$value)
                            <div class="container">
                                <h3 style="color: #F7898E">{{ $value->{'str'. $category->type_name .'_title'} }}</h3>
                                <p>{{ $value->{'str'. $category->type_name .'_body'} }}</p>
                                <p>Author: <span style="color: #F7898E">{{ $value->{'str'. $category->type_name .'_author'} }}</span></p>
                            </div>
                        @endforeach
                        {{ $result->appends($_GET)->links() }}
                    @else
                        <p>No Result Found</p>
                    @endif
                @endisset
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $.get(
                '{{ route('get-navbar-categories') }}',
                function(data) {
                    let categories_main_html = '';
                    for (let i=0;i<data.length;i++) {
                        categories_main_html += '<a class="dropdown-item" href="{{ url('browse') }}/'+ data[i]['id'] +'">'+ data[i]['type_name'] +'</a>'
                    }
                    $('#search-categories-main').empty().append(categories_main_html);
                }
            );
        });
    </script>
@endsection
