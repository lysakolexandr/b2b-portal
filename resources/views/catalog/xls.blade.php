@extends('layouts.app')
@section('scripts')
<script src="{{ asset('js/prices.js') }}"></script>
@endsection
@section('content')
	<header class="section-header sticky-top">
		@include('layouts.header_top')
		@include('layouts.header_battom')
	</header>
	<section class="padding-y-sm bg-light">
		<div class="container">
			<div class="row">
				@include('layouts.aside_xls')
				@include('layouts.xls')

			</div>
		</div>
	</section>
	{{-- @include('layouts.mobile_footer_filter_menu') --}}
	@include('layouts.footer')
@endsection
