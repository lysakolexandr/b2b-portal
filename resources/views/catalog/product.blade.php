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
        <div class="row">
            @include('layouts.breakpoints_xxl.aside_product_xxl')
            <main class="col-9">
                @include('layouts.breakpoints_xxl.product_xxl')
            </main>
        </div>
    </div>
       @include('layouts.breakpoints_xxl.footer_xxl')
</section>


@endsection --}}
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
			<div class="row">
				@include('layouts.product')
			</div>
		</div>
	</section>
	{{-- @include('layouts.mobile_footer_filter_menu') --}}
	@include('layouts.footer')
@endsection
