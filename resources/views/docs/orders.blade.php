
@extends('layouts.app')
@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('js/orders.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.ua.min.js') }}" charset="UTF-8"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.ru.min.js') }}" charset="UTF-8"></script>
@endsection

@section('content')
	<header class="section-header sticky-top">
		@include('layouts.header_top')
		@include('layouts.header_battom')
	</header>
	<section class="padding-y-sm bg-light">
		<div class="container">
			<div class="row">
				@include('docs.order_list')
			</div>
		</div>
	</section>
	{{-- @include('layouts.mobile_footer_menu') --}}
	@include('layouts.footer')
@endsection
