@extends('layouts.clear')

@section('site-title', 'Forgot Password')

@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
	<script src="{{asset('assets/js/app.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_pages/login.js')}}"></script>
@endpush

@section('content')
<!-- Content area --> 
			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">
				@if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
				<!-- Password recovery form -->
				<form class="login-form" method="POST" action="{{ route('password.email') }}">
					@csrf
					<div class="card mb-0">
						<div class="card-body">
							<div class="text-center mb-3">
								<i class="icon-spinner11 icon-2x text-warning border-warning border-3 rounded-round p-3 mb-3 mt-1"></i>
								<h5 class="mb-0">Password recovery</h5>
								<span class="d-block text-muted">We'll send you instructions in email</span>
							</div>

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="email" placeholder="Your email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
								<div class="form-control-feedback">
									<i class="icon-mail5 text-muted"></i>
								</div>
								@error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>

							<div class="form-group">
								<button type="submit" class="btn bg-blue btn-block"><i class="icon-spinner11 mr-2"></i> Reset password</button>
							</div>
							
							<div class="form-group text-center text-muted content-divider">
								<span class="px-2">or sign in</span>
							</div>

							<div class="form-group">
								<a href="{{route('login')}}" class="btn btn-light btn-block">Sign in</a>
							</div>
						</div>
					</div>
				</form>
				<!-- /password recovery form -->

			</div>
			<!-- /content area -->
    
@endsection