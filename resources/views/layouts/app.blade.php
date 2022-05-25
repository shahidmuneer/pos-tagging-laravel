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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Custom -->
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

    @yield('styles')
</head>

<body>
    <div id="app">
        <header>
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <!--    <img src="assets/images/BILLIONAERS%20LOGO%2002.png" alt="logo" class="logo-img">-->
                        Logo
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-list-7">
                        <span><i class="fa fa-bars"></i> </span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbar-list-7">
                        <ul class="navbar-nav">
                            <!--
                                    <li class="nav-item"><a href="#" class="nav-link">Home</a>

                                    </li>
                                      <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Dropdown link
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                      <a class="dropdown-item" href="#">Action</a>
                                      <a class="dropdown-item" href="#">Another action</a>
                                      <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                  </li>
                                     <li class="nav-item"><a href="#" class="nav-link">Home2</a></li>
                                     <li class="nav-item"><a href="#" class="nav-link">Home3</a></li>
                            -->
                        </ul>
                        <span class="navbar-text">
      <ul class="navbar-nav">

         <li class="nav-item"><a href="#" class="nav-link sign-up">Stories</a></li>
         <li class="nav-item"><a href="#" class="nav-link log-in">Quotes</a></li>
           <li class="nav-item"><a href="#" class="nav-link sign-up">Poems</a></li>
         <li class="nav-item"><a href="#" class="nav-link log-in">Songs</a></li>
           <li class="nav-item"><a href="#" class="nav-link sign-up">Jokes</a></li>
         <li class="nav-item"><a href="#" class="nav-link log-in">Essays</a></li>
    </ul>
    </span>
                    </div>
                </div>
            </nav>
        </header>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="mr-3 ml-3">
            <div class="position">Lybr, inc , copyright 2022<br><a href="#">Help</a> | <a href="#">Privacy</a> | <a href="#">Terms</a></div>
        </footer>
        @yield('modals')

    </div>

    <!-- Scripts -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    @yield('scripts')

</body>

</html>
