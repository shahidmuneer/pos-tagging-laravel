@extends('layouts.app', ['page'=>'write'])
@section('content')
    <div class="container-fluid">
        <div class="row mb-5">

            <div class="col-lg-6 col-md-6 scroll-1">
                <div class="div-black">
                    @isset($result['original'])
                        @foreach($result['original'] as $key=>$value)
                            <div style="display: flex;">
                                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                                <p style="cursor: pointer;" class="original-sentence" id="original_{{ $key }}">{!! $value !!}</p>
                            </div>
                        @endforeach
                    @endisset
                    <div style="float: right;">
                        <p id="track_name" style="margin-bottom: 0;">{{ $title }}</p>
                        <p id="artist_name">{{ $name }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mt-50 padding-60">
                <div class="hyphenated-sentence">
                Click any sentence to show results here
                <i id='loading' style='display:none;' class='fa fa-spinner fa-spin'></i>
                </div>
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
        localStorage.setItem('title', $('#track_name').text());
        localStorage.setItem('name', $('#artist_name').text());
        let first_sentence = parseInt($('.div-black .original-sentence:first').attr('id').replace('original_', ''));
        let last_sentence = parseInt($('.div-black .original-sentence:last').attr('id').replace('original_', ''));
        $('.div-black .original-sentence').on('click', function () {
            $("#loading").show();
            if ($('.div-black .original-sentence.sentence-active').attr('id') == $(this).attr('id'))
                return false;
            let data = '';
            let empty = true;
            $('.hyphenated-sentence input').each(function(){
                if ($(this).val() != '')
                    empty = false;
                data = data + ($(this).val()==''?'________':$(this).val()) + " ";
            });
            if (!empty)
                localStorage.setItem('body', (localStorage.getItem('body')||'') + '\n' + data);

            let element = this;
            $.ajax({
                type: "POST",
                url: "{{ route('get-hyphenated-data') }}",
                dataType: "json",
                data: { '_token': '{{ csrf_token() }}', 'data':  $(this).text()}
            }).done(function( hyphenated_data ) {
                $("#loading").hide();
                let hyphenated_html = '<ul class="line-height-40">'
                for (let i=0; i<hyphenated_data.value.length; i++) {
                    hyphenated_html += '<li> <span class="'+ hyphenated_data.color[i] +'"><input style="border: none; border-color: transparent;" class="form-control" placeholder="'+ hyphenated_data.value[i] +'"></span> <p class="line-yellow-1">'+ hyphenated_data.detail[i] +'</p></li>'
                }
                hyphenated_html += '</ul>';
                $('.hyphenated-sentence').empty().append(hyphenated_html);

                $('.div-black .original-sentence').removeClass('sentence-active');
                $(element).addClass('sentence-active');

                let current_sentence = $('.div-black .original-sentence.sentence-active').attr('id').replace('original_', '');
                if (current_sentence == first_sentence) $('#previous-sentence').removeClass('cursor-pointer');
                else $('#previous-sentence').addClass('cursor-pointer');
                if (current_sentence == last_sentence) $('#next-sentence').removeClass('cursor-pointer');
                else $('#next-sentence').addClass('cursor-pointer');
            });
        });

        $('.pagination .page-link').on('click', function () {
            $("#loading").show();
            let current_sentence = -1;

            if($('.div-black .original-sentence.sentence-active').length != 0)
                current_sentence = parseInt($('.div-black .original-sentence.sentence-active').attr('id').replace('original_', ''));
            if ($(this).attr('id') == 'previous-sentence') {
                if (current_sentence == first_sentence)
                    return false;
                else {
                    let data = '';
                    let empty = true;
                    $('.hyphenated-sentence input').each(function(){
                        if ($(this).val() != '')
                            empty = false;
                        data = data + ($(this).val()==''?'________':$(this).val()) + " ";
                    });
                    if (!empty)
                        localStorage.setItem('body', (localStorage.getItem('body')||'') + '\n' + data);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('get-hyphenated-data') }}",
                        dataType: "json",
                        data: { '_token': '{{ csrf_token() }}', 'data':  $('#original_'+(current_sentence-1)).text()}
                    }).done(function( hyphenated_data ) {
                        $("#loading").hide();
                        let hyphenated_html = '<ul class="line-height-40">'
                        for (let i=0; i<hyphenated_data.value.length; i++) {
                            hyphenated_html += '<li> <span class="'+ hyphenated_data.color[i] +'"><input style="border: none; border-color: transparent;" class="form-control" placeholder="'+ hyphenated_data.value[i] +'"></span> <p class="line-yellow-1">'+ hyphenated_data.detail[i] +'</p></li>'
                        }
                        hyphenated_html += '</ul>';
                        $('.hyphenated-sentence').empty().append(hyphenated_html);

                        $('.div-black .original-sentence').removeClass('sentence-active');
                        $('#original_'+(current_sentence-1)).addClass('sentence-active');

                        if (current_sentence == first_sentence+1) $('#previous-sentence').removeClass('cursor-pointer');
                        if (current_sentence == last_sentence) $('#next-sentence').addClass('cursor-pointer');
                    });
                }
            }
            if($(this).attr('id') == 'view-all') {
                let data = '';
                let empty = true;
                $('.hyphenated-sentence input').each(function(){
                    if ($(this).val() != '')
                        empty = false;
                    data = data + ($(this).val()==''?'________':$(this).val()) + " ";
                });
                if (!empty)
                    localStorage.setItem('body', (localStorage.getItem('body')||'') + '\n' + data);
                window.location.href = '{{ route('show', $category->id) }}';
            }
            if($(this).attr('id') == 'next-sentence') {
                if (current_sentence == last_sentence || $('.div-black .original-sentence.sentence-active').length>1)
                    return false;
                else {
                    let data = '';
                    let empty = true;
                    $('.hyphenated-sentence input').each(function(){
                        if ($(this).val() != '')
                            empty = false;
                        data = data + ($(this).val()==''?'________':$(this).val()) + " ";
                    });
                    if (!empty)
                        localStorage.setItem('body', (localStorage.getItem('body')||'') + '\n' + data);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('get-hyphenated-data') }}",
                        dataType: "json",
                        data: { '_token': '{{ csrf_token() }}', 'data':  $('#original_'+(current_sentence+1)).text()}
                    }).done(function( hyphenated_data ) {
                        let hyphenated_html = '<ul class="line-height-40">'
                        for (let i=0; i<hyphenated_data.value.length; i++) {
                            hyphenated_html += '<li> <span class="'+ hyphenated_data.color[i] +'"><input style="border: none; border-color: transparent;" class="form-control" placeholder="'+ hyphenated_data.value[i] +'"></span> <p class="line-yellow-1">'+ hyphenated_data.detail[i] +'</p></li>'
                        }
                        hyphenated_html += '</ul>';
                        $('.hyphenated-sentence').empty().append(hyphenated_html);

                        $('.div-black .original-sentence').removeClass('sentence-active');
                        $('#original_'+(current_sentence+1)).addClass('sentence-active');

                        if (current_sentence == first_sentence) $('#previous-sentence').addClass('cursor-pointer');
                        if (current_sentence == last_sentence-1) $('#next-sentence').removeClass('cursor-pointer');
                    });
                }
            }
        });

        $('.hyphenated-sentence').on('blur', 'input', function () {
            localStorage.setItem('body', data);
        })
    </script>
@endsection
