@extends('layouts.clear')

@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
	<script src="{{asset('assets/js/app.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_pages/login.js')}}"></script>
@endpush

@section('content')
<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				<!-- Registration form -->
				<form class="login-form" method="POST" action="{{ route('password.update') }}">
					@csrf
					<div class="card mb-0">
						<div class="card-body">
							<div class="text-center mb-3">
								<h5 class="mb-0">Reset Password</h5>
								<span class="d-block text-muted">All fields are required</span>
							</div>

							<div class="form-group text-center text-muted content-divider">
								<span class="px-2">Your credentials</span>
							</div>

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="email" placeholder="Username" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
								<div class="form-control-feedback">
									<i class="icon-user-check text-muted"></i>
								</div>
								@error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>

							<div class="form-group text-center text-muted content-divider">
								<span class="px-2">Your New Password</span>
							</div>

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="password"  placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
								<div class="form-control-feedback">
									<i class="icon-user-lock text-muted"></i>
								</div>
								@error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="password"  placeholder="Password" class="form-control" name="password_confirmation" required autocomplete="new-password">
								<div class="form-control-feedback">
									<i class="icon-user-lock text-muted"></i>
								</div>
							</div>

							<button type="submit" class="btn bg-teal-400 btn-block">Reset Password <i class="icon-circle-right2 ml-2"></i></button>
						</div>
					</div>
				</form>
				<!-- /registration form -->

			</div>
			<!-- /content area -->
@endsection