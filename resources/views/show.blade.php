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
            $('#saved-title').append((localStorage.getItem('title')||'')+'<br>'+(localStorage.getItem('name')||'')+'<br><span>|</span>');
            $('#saved-body').append(localStorage.getItem('body')||'_____');
        })
    </script>
@endsection
