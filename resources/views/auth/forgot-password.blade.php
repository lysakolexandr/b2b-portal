@extends('layouts.clear')
@section('content')

<section class="padding-y bg-login-img vh-100">
	<div class="container">
		<div class="card shadow mx-auto" style="max-width:400px; margin-top:150px;">
			<div class="card-body">
				<h2 class="text-center text-primary text-uppercase mb-4">
					{{ __('l.change_password_request') }}
				</h2>
				<form class="validate-form" method="POST" action="{{ route('send-password') }}">
                {{-- <form class="validate-form" method="POST" action="{{ route('login') }}"> --}}
				@csrf
					<div class="mb-4">
						<input id="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{__('l.email')}}" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
						@error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>
					<div class="mb-4">
						<button type="submit" class="btn btn-primary w-100">{{__('l.send')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
@endsection
