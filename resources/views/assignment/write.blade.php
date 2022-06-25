@extends('layouts.app', ['black_footer'=>true, 'page'=>'assignment.write'])
@section('content')
    <div class="container margin-top-40">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <div id="assignment_link"></div>
                <h3 class="text-center">Create a Writing Talk for your Students</h3>
                <form method="post" action="" class="margin-top-40" id="assignment_write_form">
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
        var createNestedObject = function( base, names, value ) {
            var lastName = arguments.length === 3 ? names.pop() : false;
            for( var i = 0; i < names.length; i++ ) {
                base = base[ names[i] ] = base[ names[i] ] || {};
            }
            if( lastName ) base = base[ lastName ] = value;
            return base;
        };
        function setCookie(name,value,days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days*24*60*60*1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "")  + expires + "; path=/";
        }
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i=0;i < ca.length;i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            }
            return null;
        }
        function eraseCookie(name) {
            document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }

        // eraseCookie('assignments');
        console.log(getCookie('assignments'));
        $('#assignment_write_form').on('submit', function (e) {
            e.preventDefault();
            let assignments = getCookie('assignments')?JSON.parse(getCookie('assignments')):{};
            let assignment_no = Math.random().toString(16).slice(10)+Object.keys(assignments).length;

            createNestedObject(assignments, [assignment_no,'title'], $('input[name="title"]').val());
            createNestedObject(assignments, [assignment_no,'background'], $('textarea[name="background"]').val());
            createNestedObject(assignments, [assignment_no,'passage'], $('textarea[name="passage"]').val());
            createNestedObject(assignments, [assignment_no,'wrap_up'], $('textarea[name="wrap_up"]').val());

            setCookie('assignments', JSON.stringify(assignments), 7);

            $('#assignment_link').empty().append('<div class="alert alert-success" id="success-alert"> <button type="button" class="close" data-dismiss="alert">x</button><strong>Success! </strong> {{ url('assignment/show/') }}/'+assignment_no+'</div>');
        });
    </script>
@endsection
