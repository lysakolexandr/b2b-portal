<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="rotting">

    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon"/>

    <!-- CSRF Token -->

    <title>{{ config('app.name', 'Geyser Portal') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>


    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>


    <script src="{{ asset('js/script.js?v=2.0') }}"></script>
	<script src="{{ asset('js/mmenu-light.js') }}"></script>

    <script src="{{ asset('js/toastr.js') }}"></script>
    {{-- <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet"> --}}

    <script src="{{ asset('js/nprogress.js') }}"></script>
    @yield('scripts')

    {{-- localization_begin --}}
    <script>var add_to_cart_text = "{{ __('l.add_to_cart_text') }}";</script>
    <script>var add_to_wishlist_text = "{{ __('l.add_to_wishlist_text') }}";</script>
    <script>var delete_wishlist_text = "{{ __('l.delete_wishlist_text') }}";</script>
    <script>var change_password_text = "{{ __('l.change_password_text') }}";</script>
    <script>var value_hide_price_was_changed_text = "{{ __('l.value_hide_price_was_changed_text') }}";</script>
    <script>var add_user_text = "{{ __('l.add_user_text') }}";</script>
    <script>var copy_link_text = "{{ __('l.copy_link_text') }}";</script>
    <script>var generate_api_key_text = "{{ __('l.generate_api_key_text') }}";</script>

    <script>var added_to_cart_text = "{{ __('l.added_to_cart') }}";</script>
    {{-- localization_end --}}

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ui.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
	<link href="{{ asset('css/nprogress.css') }}" rel="stylesheet">
	<link href="{{ asset('css/mmenu-light.css') }}" rel="stylesheet">
	<link href="{{ asset('fonts/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-treeview.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <script type="text/javascript" src='{{ asset("js/smartsearch.js") }}'></script>
    {{-- @notifyCss --}}
</head>
    <body class="d-flex flex-column min-vh-100">
        {{-- <section class="padding-y-sm bg-light">
            <div class="container">
                <div class="row">
                    @include('layouts.aside_filter')
                    @include('layouts.aside_filter_mobile')
                    @include('layouts.products_list')

                </div>
            </div>
        </section> --}}
        @yield('content')
        @include('layouts.mobile_footer_filter_menu')
    </body>
</html>

