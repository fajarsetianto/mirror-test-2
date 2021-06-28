@extends('layouts.clear')

@section('site-title', 'Login')

@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
	<script src="{{asset('assets/js/app.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_pages/login.js')}}"></script>
@endpush

@section('content')
			<div class="row min-vh-100 no-gutters">
				<div class="col-auto flex-1 d-md-block d-sm-none d-none">
					<img src="{{asset('images/bg.png')}}" class="img-fluid h-100" alt="">
				</div>
				<div class="col-lg-4 col-md-6 col-12 d-flex justify-content-center align-items-center">
					<div class="">
						<div class="p-4">
							<div class="text-center mb-3">
								<img src="{{asset('images/logo.png')}}" class="img-fluid mb-4" style="width: 125px" alt="">
								<h4 class="font-weight-bold">SISTEM MONITORING DAN EVALUASI</h4>
								<h6>KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN REPUBLIK INDONESIA</h6>
							</div>
							<form class="login-form w-100" method="POST" action="{{ route('login') }}">
								@csrf
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
											<div class="form-control-feedback">
												<i class="icon-user text-muted"></i>
											</div>
											@error('email')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
			
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
											<div class="form-control-feedback">
												<i class="icon-lock2 text-muted"></i>
											</div>
											@error('password')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
			
										<div class="form-group d-flex align-items-center">
											<div class="form-check mb-0">
												<label class="form-check-label">
													<input type="checkbox" class="form-input-styled" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
													Remember
												</label>
											</div>
											@if (Route::has('password.request'))
												<a href="{{ route('password.request') }}" class="ml-auto">Forgot password?</a>
											@endif
										</div>
			
										<div class="form-group text-center">
											<button type="submit" class="btn btn-primary">Masuk <i class="icon-circle-right2 ml-2"></i></button>
										</div>
							</form>
						</div>
					</div>
					<div class="position-absolute w-100 bottom-0">
						@include('layouts.parts.footer')
					</div>
				</div>
			</div>
			<!-- /content area -->
    
@endsection