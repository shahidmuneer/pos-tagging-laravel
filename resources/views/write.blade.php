@extends('layouts.app', ['page'=>'write'])
@section('content')
    <div class="container-fluid">
    <div class="row">

        <div class="col-lg-6 col-md-6 div-black">
            @foreach($result['original'] as $key=>$value)
                <div style="display: flex;">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    <p style="cursor: pointer; @if($key == 0) font-weight: bold; @endif" id="original_{{ $key }}">{{ $value }}.</p>
                </div>
            @endforeach
        </div>
        <div class="col-lg-6 col-md-6 mt-50 padding-60">
            <p><span style="color: #F7898E;">Structured Edit</span> | Free Edit</p>
            @foreach($result['hyphenated'] as $index=>$hyphenated)
                @php $hyphenated_words = explode(' ', $hyphenated); $tagged_words = explode(' ', $result['tagged'][$index]); @endphp
                <p class="line-height-40" @if($index != 0) hidden @endif id="structured_{{ $index }}">
                    @foreach($hyphenated_words as $key=>$value)
                        <span class="{{ $color[explode('_', $tagged_words[$key])[1]] }}">
                            <span>{{ $detail[explode('_', $tagged_words[$key])[1]] }}</span>
                            {{ explode('_', $value)[0] }}
                        </span>
                    @endforeach
                </p>
            @endforeach
        </div>
    </div>

</div>
@endsection
@section('scripts')
    <script>
        $('.div-black p').on('click', function () {
            $('.div-black p').css('font-weight', '')
            $(this).css('font-weight', 'bold');
            $('.line-height-40').attr('hidden', 'hidden');
            $('#structured_'+$(this).attr('id').replace('original_', '')).removeAttr('hidden');
        })
    </script>
@endsection
