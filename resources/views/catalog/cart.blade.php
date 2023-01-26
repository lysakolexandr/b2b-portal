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
            <form action="{{ url('cart/make-order') }}" method="post">
                @csrf
                <div class="row  cart-items">
                    @include('layouts.cart')
                </div>

            </form>
        </div>
    </section>
    {{-- @include('layouts.mobile_footer_menu') --}}
    @include('layouts.footer')
@endsection

{{-- @extends('layouts.app')

@section('content')

<!-- breakpoints xxl -->
<section class="d-none d-xxl-block section-content pt-0">
    <header class="bg-white sticky-top">
        @include('layouts.breakpoints_xxl.header_user_xxl')
        @include('layouts.breakpoints_xxl.header_search_xxl')
        @include('layouts.breakpoints_xxl.menu_xxl')
    </header>
    <div class="container">
        <div class="row cart-items">
            {{ Form::open(array('url' => 'cart/make-order')) }}
            @include('layouts.breakpoints_xxl.cart_body_xxl')
            {{ Form::close() }}
            <main class="col-9 cart-items">
                @include('layouts.breakpoints_xxl.cart_xxl')
            </main>
             @include('layouts.breakpoints_xxl.aside_cart_xxl')
             @include('layouts.breakpoints_xxl.modal_xxl')
        </div>

    </div>
    <span class="fixed-bottom">
     @include('layouts.breakpoints_xxl.footer_xxl')
    </span>
</section>

<script>
    function edit(item){
        element = document.getElementById('selected-tab');
        element.value = item;
    }
</script>


@endsection --}}
