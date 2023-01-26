@extends('layouts.app')
@section('content')
	<header class="section-header sticky-top">
		@include('layouts.header_top')
		@include('layouts.header_battom')
	</header>
	<section class="padding-y-sm bg-light">
		<div class="container">
			<div class="row">
				@include('layouts.profile')
			</div>
		</div>
	</section>
	{{-- @include('layouts.mobile_footer_menu') --}}
	@include('layouts.footer')
@endsection
