@extends('layouts.app', ['nav_search'=>true, 'black_footer'=>true, 'page'=>'browse'])
@section('content')
    <div class="container margin-top-40">

        <h2 class="text-center" id="saved-title"></h2>
        <p class="text-center" id="saved-body"></p>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            let url_array = window.location.href.split("/")
            let category = url_array[url_array.length-2];
            let paragraph = url_array[url_array.length-1];

            $('#saved-title').append((localStorage.getItem('title')||'')+'<br>'+(localStorage.getItem('name')||'')+'<br><span>|</span>');

            let body_data = JSON.parse(localStorage.getItem('body'));
            let data = '';
            if ((category in body_data)) {
                if ((paragraph in body_data[category])) {
                    for (let index in body_data[category][paragraph]) {
                        for (let key in body_data[category][paragraph][index]) {
                            data = data + body_data[category][paragraph][index][key] + " ";
                        }
                    }
                }
            }

            $('#saved-body').append(data);
        })
    </script>
@endsection
