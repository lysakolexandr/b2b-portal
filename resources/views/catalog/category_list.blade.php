@extends('layouts.app')
@section('scripts')
    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
@endsection
@section('content')
    <header class="section-header sticky-top">
        @include('layouts.header_top')
        @include('layouts.header_battom')
    </header>
    <section class="padding-y-sm bg-light">
        <div class="container">
            @include('catalog.category_list_body')
        </div>
    </section>

    @include('layouts.footer')
@endsection
