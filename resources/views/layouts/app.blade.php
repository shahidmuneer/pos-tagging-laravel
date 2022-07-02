<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pos tagging') }}</title>

    <!-- Styles -->

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- BootStrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <!-- Custom -->
    @isset($page)
        @if($page=='assignment.write' || $page=='assignment.show')
            <link rel="stylesheet" href="{{ asset('css/assignment/index.css') }}">
        @else
            <link rel="stylesheet" href="{{ asset('css/index.css') }}">
        @endif
    @endisset

    @yield('styles')
</head>

<body>
<div id="app">
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}">
                   <img src="/logo.png" alt="logo" class="logo-img">

                </a>
                @isset($nav_search)
                    <ul class="navbar-nav">
                        <form method="get" action="{{ route($page, $category->id) }}">
                            <div class="input-group" style="margin-top: 5px; margin-bottom: 5px;">
                                <input type="text" class="form-control br-0"  placeholder="Search by Author, Title, or Keyword" name="search" value="{{ old('search') }}">
                                <div class="dropdown">
                                    <button type="button" class="btn" data-toggle="dropdown" style="border-radius: 0; border-left: 0;">
                                        | {{ $category->display_name }}
                                    </button>
                                    <div class="dropdown-menu">
                                    @isset($categories)
                                        @foreach($categories as $key=>$value)
                                            <a class="dropdown-item" href="{{ route($page=='write'?'browse':$page, $value->id) }}">{{ $value->display_name}}
                                            </a>
                                        @endforeach
                                    @endisset
                                    </div>
                                </div>

                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search" style="color: #F7898E;"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </ul>
                @endisset
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-list-7">
                    <span><i class="fa fa-bars"></i> </span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbar-list-7">
                    <ul class="navbar-nav"></ul>
                    <span class="navbar-text">
                        <ul class="navbar-nav" id="navbar-categories">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Writers
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @isset($categories)
                                        @foreach($categories as $key=>$value)
                                            <a @if($category->id==$value->id)style="color: blue; font-weight: normal;"@endif class="dropdown-item nav-link log-in" href="{{ route($page=='write'?'browse':$page, $value->id) }}">{{ $value->display_name }}</a>
                                        @endforeach
                                    @endisset
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Educators
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="nav-link log-in" href='/assignment/write'>Create Worksheet</a>
                                </div>
                            </li>
                            @guest()
                                <li class="nav-item dropdown">
                                    <a class="nav-link log-in" href="{{ url('') }}/en/blog">Blogs</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Account
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="nav-link log-in" href="{{ route('register') }}">Register</a>
                                        <a class="nav-link log-in" href="{{ route('login') }}">Login</a>
                                    </div>
                                </li>
                            @endguest
                            @auth
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Blog
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="nav-link log-in" href="{{ url('') }}/blog_admin">Admin</a>
                                        <a class="nav-link log-in" href="{{ url('') }}/en/blog">Blogs</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Account
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none"> @csrf </form>
                                    </div>
                                </li>
                            @endauth
                        </ul>
                    </span>
                </div>
            </div>
        </nav>
    </header>

    <main class="py-4">
        @yield('content')
    </main>

    @isset($black_footer)
        <div class="footer-black">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-10 col-md-9">
                        <p>Lybr, inc , copyright 2022</p>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <div><a href="#" style="color: white">Help</a> | <a href="#" style="color: white">Privacy</a> | <a href="#" style="color: white">Terms</a></div>
                    </div>
                </div>
            </div>
        </div>
    @endisset
    @isset($page)
        <footer class="mr-3 ml-3">
            <div class="position">Lybr, inc , copyright 2022<br><a href="#">Help</a> | <a href="#">Privacy</a> | <a href="#">Terms</a></div>
        </footer>
    @endisset
    @yield('modals')

</div>

<!-- Scripts -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
</script>
@yield('scripts')

</body>

</html>
