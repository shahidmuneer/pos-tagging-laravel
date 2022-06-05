@extends('layouts.app', ['page'=>'write'])
@section('content')
    <div class="container-fluid">
        <div class="row mb-5">

            <div class="col-lg-6 col-md-6 div-black">
                @foreach($result['original'] as $key=>$value)
                    <div style="display: flex;">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        <p style="cursor: pointer;" class="@if($key == 0){{'sentence-active'}}@endif" id="original_{{ $key }}">{{ $value }}.</p>
                    </div>
                @endforeach
            </div>
            <div class="col-lg-6 col-md-6 mt-50 padding-60">
                <p><span style="color: #F7898E;">Structured Edit</span> | Free Edit</p>
                @foreach($result['hyphenated'] as $index=>$hyphenated)
                    @php $hyphenated_words = array_chunk(explode(' ', $hyphenated), '3'); @endphp
                    <div class="hyphenated-sentence" id="structured_{{ $index }}" @if($index != 0) hidden @endif>
                        @foreach($hyphenated_words as $hyphenated_word)
                            <ul style="display: flex;" class="line-height-40">
                                @foreach($hyphenated_word as $key=>$value)
                                    <li> <span class="{{ $color[explode('_', $value)[1]]??'line-empty' }}">{{ explode('_', $value)[0] }}</span> <p class="line-yellow-1">{{ $detail[explode('_', $value)[1]]??'' }}</p></li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                @endforeach
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
            $('.div-black p').removeClass('sentence-active');
            $(this).addClass('sentence-active');
            $('.hyphenated-sentence').attr('hidden', 'hidden');
            $('#structured_'+$(this).attr('id').replace('original_', '')).removeAttr('hidden');

            let current_sentence = $('.div-black p.sentence-active').attr('id').replace('original_', '');
            if (current_sentence == first_sentence) $('#previous-sentence').removeClass('cursor-pointer');
            else $('#previous-sentence').addClass('cursor-pointer');
            if (current_sentence == last_sentence) $('#next-sentence').removeClass('cursor-pointer');
            else $('#next-sentence').addClass('cursor-pointer');
        });

        $('.pagination .page-link').on('click', function () {
            let current_sentence = parseInt($('.div-black p.sentence-active').attr('id').replace('original_', ''));
            if ($(this).attr('id') == 'previous-sentence') {
                if (current_sentence == first_sentence)
                    return false;
                else {
                    $('.div-black p').removeClass('sentence-active');
                    $('.hyphenated-sentence').attr('hidden', 'hidden');
                    $('#original_'+(current_sentence-1)).addClass('sentence-active');
                    $('#structured_'+(current_sentence-1)).removeAttr('hidden');

                    if (current_sentence == first_sentence+1) $('#previous-sentence').removeClass('cursor-pointer');
                    if (current_sentence == last_sentence) $('#next-sentence').addClass('cursor-pointer');
                }
            }
            if($(this).attr('id') == 'view-all') {
                $('.div-black p').addClass('sentence-active');
                $('.hyphenated-sentence').removeAttr('hidden');
                $('#previous-sentence').removeClass('cursor-pointer');
                $('#next-sentence').removeClass('cursor-pointer');
            }
            if($(this).attr('id') == 'next-sentence') {
                if (current_sentence == last_sentence || $('.div-black p.sentence-active').length>1)
                    return false;
                else {
                    $('.div-black p').removeClass('sentence-active');
                    $('.hyphenated-sentence').attr('hidden', 'hidden');
                    $('#original_'+(current_sentence+1)).addClass('sentence-active');
                    $('#structured_'+(current_sentence+1)).removeAttr('hidden');

                    if (current_sentence == first_sentence) $('#previous-sentence').addClass('cursor-pointer');
                    if (current_sentence == last_sentence-1) $('#next-sentence').removeClass('cursor-pointer');
                }
            }
        })
    </script>
@endsection
