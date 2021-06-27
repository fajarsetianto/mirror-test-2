@extends('layouts.clear')

@section('site-title', 'Check Point')

@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
	<script src="{{asset('assets/js/app.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_pages/login.js')}}"></script>
@endpush
@section('navbar')
	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-dark fixed-top">
		<div class="navbar-brand">
			<a href="index.html" class="d-inline-block">
				<img src="{{asset('assets/global/images/logo_light.png')}}" alt="">
			</a>
		</div>

		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse " id="navbar-mobile">
		</div>
	</div>
	<!-- /main navbar -->
@endsection
@section('content')
<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">
				<!-- Login card -->
				<form class="login-form" style="width: auto" method="POST" action="{{ route('respondent.checkpoint.store') }}">
					@csrf
					</h3>
					<div class="card mb-0">
						
						<div class="card-header bg-teal-400 text-white text-center px-5">
							<i class="icon-profile icon-2x text-white border-white border-3 rounded-round p-3 mb-3"></i><br>
							Masukan Nama Lengkap Anda
						</div>
						<div class="card-body">
							<div class="alert alert-warning alert-styled-left">
								<span class="font-weight-semibold font-size-xs">Anda tidak diperbolehkan merubah nama anda lagi setelah tahapan ini selesai</small>
							</div>
							<div class="form-group">
								<input type="text" placeholder="Nama Lengkap" class="form-control @error('name') is-invalid @enderror"  name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
								@error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-warning btn-block">Lanjutkan <i class="icon-circle-right2 ml-2"></i></button>
							</div>
						</div>
					</div>
				</form>
				<!-- /login card -->

			</div>
			<!-- /content area -->
    
@endsection

@section('footer')
	@include('layouts.parts.footer')
@endsection