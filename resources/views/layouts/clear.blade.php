<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

   <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/script.js?v=2.0') }}"></script> 

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.css?v=2.0') }}" rel="stylesheet">    
    <link href="{{ asset('css/ui.css?v=2.0') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css?v=2.0') }}" rel="stylesheet">
</head>
<body>
    <main class="">
        @yield('content')
    </main>
</body>
</html>
