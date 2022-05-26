@extends('layouts.app', ['page'=>$category->route])
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 text-center">
                <h3 class="margin-top-150 color-red">Learn to Write Well</h3>

                <form method="get" action="{{ route($category->route) }}">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search by Author, Title, or Keyword | {{ $category->type_name }}" value="{{ old('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit">
                                <i class="fa fa-search color-black"></i>
                            </button>
                        </div>
                    </div>
                </form>
                @if(!isset($result))
                    <p class="margin-bottom-0">Practice the pace and structure <span class="display-block">of masters to find your own style</span></p>
                @endif
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            </div>
        </div>

        @if(isset($result))
            @if($result->count() > 0)
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <div class="table-responsive text-nowrap">
                            <!--Table-->
                            <table class="table table-striped">

                                <!--Table head-->
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Author</th>
                                    <th>Title</th>
                                    <th>Keywords</th>
                                </tr>
                                </thead>
                                <!--Table head-->

                                <!--Table body-->
                                <tbody>
                                @foreach($result as $key=>$value)
                                    <tr>
                                        <th scope="row">{{ $key+1 }}</th>
                                        <td>{{ $value->strPost_author }}</td>
                                        <td>{{ $value->strPost_title }}</td>
                                        <td>{{ $value->strPost_keywords }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <!--Table body-->


                            </table>
                        </div>
                        <div class="text-center">
                            {{ $result->appends($_GET)->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center mb-5">
                    <p><b>Record not found</b></p>
                </div>
            @endif
        @else
            <div class="mt-50 text-center "> <a href="#" class="">Learn more <br><i class="fa fa-angle-down fa-2x" aria-hidden="true"></i></a></div>
        @endif

    </div>
@endsection
