@extends('layouts.app')
@section('scripts')
    <script src="{{ asset('js/api.js') }}"></script>
@endsection
@section('content')
    <header class="section-header sticky-top">
        @include('layouts.header_top')
        @include('layouts.header_battom')
    </header>
    <section class="padding-y-sm bg-light">
        <div class="container">
            <div class="row">
                @include('api.aside')
                <main class="col-lg-9">
                    <article class="card mb-2">
                        <div class="card-body">
                            @include('api.body_manual_ru')
                        </div>
                    </article>
                </main>
            </div>
        </div>
    </section>
    {{-- @include('layouts.mobile_footer_menu') --}}
    @include('layouts.footer')
@endsection
