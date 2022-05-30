@extends('layouts.app', ['page'=>'write'])
@section('content')
    <div class="container-fluid">
    <div class="row">

        <div class="col-lg-6 col-md-6 div-black">
            @foreach($result['original'] as $key=>$value)
                <div style="display: flex;">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    <p>{{ $value }}.</p>
                </div>
            @endforeach
        </div>
        <div class="col-lg-6 col-md-6 mt-50 padding-60">
            <p><span style="color: #F7898E;">Structured Edit</span> | Free Edit</p>
            @foreach($result['hyphenated'] as $index=>$hyphenated)
                @php $hyphenated_words = explode(' ', $hyphenated); $tagged_words = explode(' ', $result['tagged'][$index]);  @endphp
                <p class="line-height-40">"
                    @foreach($hyphenated_words as $key=>$value)
                        <span class="{{ $color[explode('_', $tagged_words[$key])[1]] }}">
                            <span>{{ $detail[explode('_', $tagged_words[$key])[1]] }}</span>
                            {{ $value }}
                        </span>
                    @endforeach
                "</p>
            @endforeach
        </div>
    </div>

</div>
@endsection
@section('scripts')
    <script>
    </script>
@endsection
