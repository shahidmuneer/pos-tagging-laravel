@extends('layouts.app', ['black_footer'=>true, 'page'=>'assignment.write'])
@section('content')
    <div class="container margin-top-40">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <h3 class="text-center">Create a Writing Talk for your Students</h3>
                <form method="post" action="{{ route('assignment.show') }}" class="margin-top-40" id="assignment_write_form">
                    @csrf
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                    <div class="form-group">
                        <label for="background">Background/Imstructions(Give your students instructions or background that will help them):</label>
                        <textarea class="form-control height-60" rows="5" name="background" id="background" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="passage">Text(this is the text that they will analyze and use)</label>
                        <textarea class="form-control height-80" rows="5" name="passage" id="passage" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="wrap_up">Wrap-up(anything you would like the student to consider after the writing process):</label>
                        <textarea class="form-control" rows="5" name="wrap_up" id="wrap_up" required></textarea>
                    </div>

                    <div class="text-center margin-top-40"><button type="submit" class="btn btn-primary btn-blue-sky">GET WORKSHEET</button></div>
                </form>
            </div>
            <div class="col-lg-2"></div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
    </script>
@endsection
