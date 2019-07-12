<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('images/24x7_fav.png')}}" >
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CareService') }}</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    @guest
    <link href="{{ asset('admin/assets/css/pages/auth-light.css') }}" rel="stylesheet" />
    @endguest
    <link href="{{ asset('admin/assets/css/main.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    @auth
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @endauth
</head>  
<body class="@auth fixed-navbar has-animation @endauth @guest bg-silver-300 login-page @endguest">
    <div class="@auth page-wrapper @endauth">
        @auth
            @include('partials.top-menu')
            @include('partials.left-menu')
        @endauth

        @yield('content')
        <footer class="page-footer">      
            <div class="to-top"><i class="fa fa-angle-double-up"></i></div>
        </footer>
    </div>
    @yield('footer-scripts')
   
    <!-- Left Menu -->
    <script src="{{ asset('admin/assets/vendors/metisMenu/dist/metisMenu.min.js') }}" type="text/javascript"></script>
    <!-- CORE SCRIPTS-->
    <script src="{{ asset('admin/assets/js/app.min.js') }}" type="text/javascript"></script>
    <script>
        $('#flash-overlay-modal').modal();
    </script> 
</body>
</html>
