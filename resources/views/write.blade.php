@extends('layouts.app', ['page'=>'write'])
@section('content')
    <div class="container-fluid">
        <div class="row mb-5">

            <div class="col-lg-6 col-md-6 div-black">
                @isset($result['original'])
                    @foreach($result['original'] as $key=>$value)
                        <div style="display: flex;">
                            <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                            <p style="cursor: pointer;" id="original_{{ $key }}">{{ $value }}</p>
                        </div>
                    @endforeach
                @endisset
            </div>
            <div class="col-lg-6 col-md-6 mt-50 padding-60">
                <p><span style="color: #F7898E;">Structured Edit</span> | Free Edit</p>
                <div class="hyphenated-sentence"></div>
                <ul class="pagination" style="margin-top: 30px;">
                    <li class="page-item"><p class="page-link" id="previous-sentence">&laquo; Previous Sentence</p></li>
                    <li class="page-item"><p class="page-link cursor-pointer" id="view-all">View all</p></li>
                    <li class="page-item"><p class="page-link @if(sizeof($result['original'])>1){{'cursor-pointer'}}@endif" id="next-sentence">Next Sentence &raquo;</p></li>
                </ul>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        let first_sentence = parseInt($('.div-black p:first').attr('id').replace('original_', ''));
        let last_sentence = parseInt($('.div-black p:last').attr('id').replace('original_', ''));
        $('.div-black p').on('click', function () {
            let element = this;
            $.ajax({
                type: "POST",
                url: "{{ route('get-hyphenated-data') }}",
                dataType: "json",
                data: { '_token': '{{ csrf_token() }}', 'data':  $(this).text()}
            }).done(function( hyphenated ) {
                $('.hyphenated-sentence').empty().append(hyphenated);

                $('.div-black p').removeClass('sentence-active');
                $(element).addClass('sentence-active');

                let current_sentence = $('.div-black p.sentence-active').attr('id').replace('original_', '');
                if (current_sentence == first_sentence) $('#previous-sentence').removeClass('cursor-pointer');
                else $('#previous-sentence').addClass('cursor-pointer');
                if (current_sentence == last_sentence) $('#next-sentence').removeClass('cursor-pointer');
                else $('#next-sentence').addClass('cursor-pointer');
            });
        });

        $('.pagination .page-link').on('click', function () {
            let current_sentence = -1;
            if($('.div-black p.sentence-active').length != 0)
                current_sentence = parseInt($('.div-black p.sentence-active').attr('id').replace('original_', ''));
            if ($(this).attr('id') == 'previous-sentence') {
                if (current_sentence == first_sentence)
                    return false;
                else {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('get-hyphenated-data') }}",
                        dataType: "json",
                        data: { '_token': '{{ csrf_token() }}', 'data':  $('#original_'+(current_sentence-1)).text()}
                    }).done(function( hyphenated ) {
                        $('.hyphenated-sentence').empty().append(hyphenated);

                        $('.div-black p').removeClass('sentence-active');
                        $('#original_'+(current_sentence-1)).addClass('sentence-active');

                        if (current_sentence == first_sentence+1) $('#previous-sentence').removeClass('cursor-pointer');
                        if (current_sentence == last_sentence) $('#next-sentence').addClass('cursor-pointer');
                    });
                }
            }
            if($(this).attr('id') == 'view-all') {
                let data = $('.div-black p').map( function(){
                    return $(this).text();
                }).get().join();
                $.ajax({
                    type: "POST",
                    url: "{{ route('get-hyphenated-data') }}",
                    dataType: "json",
                    data: { '_token': '{{ csrf_token() }}', 'data': data }
                }).done(function( hyphenated ) {
                    $('.hyphenated-sentence').empty().append(hyphenated);

                    $('.div-black p').addClass('sentence-active');
                    $('#previous-sentence').removeClass('cursor-pointer');
                    $('#next-sentence').removeClass('cursor-pointer');
                });
            }
            if($(this).attr('id') == 'next-sentence') {
                if (current_sentence == last_sentence || $('.div-black p.sentence-active').length>1)
                    return false;
                else {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('get-hyphenated-data') }}",
                        dataType: "json",
                        data: { '_token': '{{ csrf_token() }}', 'data':  $('#original_'+(current_sentence+1)).text()}
                    }).done(function( hyphenated ) {
                        $('.hyphenated-sentence').empty().append(hyphenated);

                        $('.div-black p').removeClass('sentence-active');
                        $('#original_'+(current_sentence+1)).addClass('sentence-active');

                        if (current_sentence == first_sentence) $('#previous-sentence').addClass('cursor-pointer');
                        if (current_sentence == last_sentence-1) $('#next-sentence').removeClass('cursor-pointer');
                    });
                }
            }
        })
    </script>
@endsection
