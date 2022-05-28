@extends('layouts.app', ['page'=>'home'])
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 text-center">
                <h3 class="margin-top-150 color-red">Learn to Write Well</h3>
                <form method="get" action="{{ route('browse', $category->id) }}">
                    <div class="input-group">
                        <input type="text" class="form-control br-0" placeholder="Search by Author, Title, or Keyword " value="{{ old('search') }}" name="search">
                        <div class="dropdown">
                            <button type="button" class="btn" data-toggle="dropdown" style="border-radius: 0; border-left: 0;">
                                | {{ $category->type_name }}
                            </button>
                            <div class="dropdown-menu">
                                @foreach($categories as $key=>$value)
                                    <a class="dropdown-item" href="{{ route('home', $value->id) }}">{{ $value->type_name}}</a>
                                @endforeach
                            </div>
                        </div>

                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit">
                                <i class="fa fa-search color-black"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <p class="margin-bottom-0">Practice the pace and structure <span class="display-block">of masters to find your own style</span></p>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            </div>
        </div>
        <div class="mt-50 text-center "> <a href="#" class="">Learn more <br><i class="fa fa-angle-down fa-2x" aria-hidden="true"></i></a></div>
    </div>
@endsection
@section('scripts')
    <script>
    </script>
@endsection
