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
                <div class="table-responsive">
                    <table class="table table-borderless text-nowrap">
                        @foreach($result['hyphenated'] as $index=>$hyphenated)
                            @php $hyphenated_words = array_chunk(explode(' ', $hyphenated), '3'); @endphp
                            <tbody class="hyphenated-sentence" id="structured_{{ $index }}" @if($index != 0) hidden @endif>
                            @foreach($hyphenated_words as $hyphenated_word)
                                <tr>
                                    @foreach($hyphenated_word as $key=>$value)
                                        <td>
                                            <span class="{{ $color[explode('_', $value)[1]]??'line-empty' }}">
                                                <span>{{ $detail[explode('_', $value)[1]]??'' }}</span>
                                                {{ explode('_', $value)[0] }}
                                            </span>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        $('.div-black p').on('click', function () {
            $('.div-black p').removeClass('sentence-active');
            $(this).addClass('sentence-active');
            $('.hyphenated-sentence').attr('hidden', 'hidden');
            $('#structured_'+$(this).attr('id').replace('original_', '')).removeAttr('hidden');
        });
    </script>
@endsection
