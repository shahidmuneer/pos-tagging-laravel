@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 text-center">
                <h3 class="margin-top-150 color-red">Learn to Write Well</h3>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search by Author, Title, or Keyword | Stories">
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="button">
                            <i class="fa fa-search color-black"></i>
                        </button>
                    </div>
                </div>

                <p class="margin-bottom-0">Practice the pace and structure <span class="display-block">of masters to find your own style</span></p>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            </div>
        </div>
        <div class="mt-50 text-center "> <a href="#" class="">Learn more <br><i class="fa fa-angle-down fa-2x" aria-hidden="true"></i></a></div>
    </div>
@endsection
