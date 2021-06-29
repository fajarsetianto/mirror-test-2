@extends('layouts.full')

@section('site-title','Pengaturan')

@push('scripts-top')
    
@endpush

@section('page-header')
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Pengaturan</span></h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>
		</div>
		{{ Breadcrumbs::render('admin.monev.forms') }}				
	</div>
@endsection

@section('content')
@if(session()->has('success'))
	<div class="alert alert-success border-0 alert-dismissible">
		<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
		{{ session()->get('success') }}
	</div>
@endif
<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h6 class="card-title font-weight-semibold">Pengaturan Dasar</h6>
			</div>
			<hr class="m-0">
			<div class="card-body">
				<form action="{{route('admin.setting.update')}}" method="POST">
					@csrf
					@isset($item)
						@method('PUT')
					@endisset
					
					<div class="form-group row">
						<label class="col-md-3 col-form-label">Nama</label>
						<div class="col-md-9">
							<input type="text" required class="form-control" value="{{$item->name}}" name="name" placeholder="Nama">
							@error('name')
								<small class="text-danger">
									<strong>{{ $message }}</strong>
								</small>
							@enderror
						</div>
						
					</div>
					<div class="d-flex align-items-center">
						<button class="btn bg-warning" type="submit"><i class="icon-pencil font-size-base mr-1"></i> Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h6 class="card-title font-weight-semibold">Pengaturan Keamanan</h6>
			</div>
			<hr class="m-0">
			<div class="card-body">
				<form action="{{route('admin.setting.update')}}" method="POST" >
					@csrf
					@isset($item)
						@method('PUT')
					@endisset
					
					<div class="form-group row">
						<label class="col-md-3 col-form-label">Password Lama</label>
						<div class="col-md-9">
							<input type="password" required class="form-control" name="old_password" placeholder="Password Lama">
							@error('old_password')
								<small class="text-danger">
									<strong>{{ $message }}</strong>
								</small>
							@enderror
						</div>
						
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-form-label">Password Baru</label>
						<div class="col-md-9">
							<input type="password" required class="form-control" name="password" placeholder="Password Baru">
							@error('password')
								<small class="text-danger">
									<strong>{{ $message }}</strong>
								</small>
							@enderror
						</div>
						
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-form-label">Konfirmasi Password Baru</label>
						<div class="col-md-9">
							<input type="password" required class="form-control" name="password_confirmation" placeholder="Konfirmasi Password Baru">
						</div>
					</div>
					<div class="d-flex align-items-center">
						<button class="btn bg-warning" type="submit"><i class="icon-pencil font-size-base mr-1"></i> Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>



@endsection