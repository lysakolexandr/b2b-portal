@extends('layouts.app')
@section('scripts')
<script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('js/filter.js') }}"></script>
@endsection
@section('content')
	<header class="section-header sticky-top">
		@include('layouts.header_top')
		@include('layouts.header_battom')
	</header>
	<section class="padding-y-sm bg-light">
		<div class="container">
			<div class="row">
                @include('layouts.aside_filter')
				@include('layouts.aside_filter_mobile')
				{{-- @include('layouts.aside_wishlists')--}}
				@include('layouts.wishlists')
				{{-- @include('layouts.aside_wishlists_list_mobile') --}}
			</div>
		</div>
	</section>
	{{-- @include('layouts.mobile_footer_wishlists_menu') --}}
    {{-- @include('layouts.aside_filter_mobile') --}}
	@include('layouts.footer')
@endsection
