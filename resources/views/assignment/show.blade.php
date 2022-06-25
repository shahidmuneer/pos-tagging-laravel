@extends('layouts.app', ['black_footer'=>true, 'page'=>'assignment.show'])
@section('content')
    <div class="container margin-top-40">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <h3 class="text-center">{{ $assignment['title'] }}</h3>
                <label>Background</label>
                <p style="font-weight: 500;">{{ $assignment['background'] }}</p>
                <label class="margin-top-40">The passage</label>
                <div>
                    @foreach($assignment['passage'] as $key=>$value)
                        <span class="{{ isset($color[explode('_', $value)[1]])?str_replace('line','bg',$color[explode('_', $value)[1]]):'|' }}">{{ explode('_', $value)[0] }}</span>
                    @endforeach
                </div>
                <br>
                <label class="margin-top-40">Your thoughts about the passage</label>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <label>Re-write the passage in the manner described by your instructor</label>
                <div class="table-responsive text-nowrap">
                    @php $tag_word_chunks = array_chunk($assignment['passage'], 4) @endphp
                    @foreach($tag_word_chunks as $tag_words)
                        <table class="table table-borderless margin-top-40">
                            <tbody>
                            <tr>
                                @foreach($tag_words as $key=>$value)
                                    <td class="table-head"><div class="{{ isset($color[explode('_', $value)[1]])?str_replace('line','border',$color[explode('_', $value)[1]]):'|' }}">{{ explode('_', $value)[0] }}</div></td>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach($tag_words as $key=>$value)
                                    <td class="font-12">{{ isset($detail[explode('_', $value)[1]])?$detail[explode('_', $value)[1]]:'|' }}</td>
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                    @endforeach
                </div>
                <label class="margin-top-40">Wrap-up</label>
                <p style="font-weight: 500;">{{ $assignment['wrap_up'] }}</p>
            </div>
        </div>

        <div class="col-lg-2"></div>
    </div>
@endsection
@section('scripts')
    <script>
    </script>
@endsection
