@extends('layouts.app', ['page'=>'write'])
@section('content')
    <div class="container-fluid">
    <div class="row">

        <div class="col-lg-6 col-md-6 div-black">
            @foreach(explode('.', $result['original']) as $key=>$value)
                <div style="display: flex;">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    <p>{{ $value }}.</p>
                </div>
            @endforeach
        </div>
        <div class="col-lg-6 col-md-6 mt-50 padding-60">
            <p><span style="color: #F7898E;">Structured Edit</span> | Free Edit</p>
            @foreach(explode('.', $result['hyphenated']) as $key=>$value)
                <p class="line-height-40">"{{ $value }}"</p>
            @endforeach
            @foreach(explode('.', $result['tagged']) as $key=>$value)
                <p class="line-height-40">"{{ $value }}"</p>
            @endforeach
{{--            <p class="line-height-40">"<span class="line-gray"><span>Noun</span> When-ev-er </span> <span class="line-yellow"><span>Pronoun</span>you</span> <span class="line-green"><span>verb</span>feel </span><span class="line-purple"><span>Prep</span>like</span> <span class="line-green"><span>verb</span>crit-i-ciz-ing</span> <span class="line-gray"><span>Noun</span>an-y-one</span>,"<span class="line-yellow"><span>Pronoun</span> he</span>--}}
{{--                <span class="line-green"><span>verb</span>told</span>  <span class="line-yellow"><span>Pronoun</span>me</span>, "<span class="line-green"><span>verb</span>just</span> <span class="line-green"><span>verb</span>re-mem-eber</span> <span class="line-purple"><span>Prep</span>that</span> <span class="line-pink"><span>det</span>all</span>  <span class="line-pink"><span>det</span>the</span> <span class="line-gray"><span>Noun</span>peo-ple</span> <span class="line-purple"><span>Prep</span>in</span> <span class="line-purple"><span>Prep</span>this</span> <span class="line-gray"><span>Noun</span>world</span> <span class="line-green"><span>verb</span>hav-en't</span><span class="line-green"><span>verb</span> had</span> <span class="line-purple"><span>Prep</span>the</span> <span class="line-gray"><span>Noun</span>ad-van-tag-es</span> <span class="line-purple"><span>Prep</span>that</span><span class="line-yellow"><span>Pronoun</span> you</span><span class="line-green"><span>verb</span>'ve</span> <span class="line-green"><span>verb</span>had</span>."--}}
{{--            </p>--}}
        </div>
    </div>

</div>
@endsection
@section('scripts')
    <script>
    </script>
@endsection
