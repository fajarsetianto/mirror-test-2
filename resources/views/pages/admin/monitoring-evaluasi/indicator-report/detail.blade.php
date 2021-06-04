@extends('layouts.full')

@section('site-title','Detail Laporan Indikator')

@push('scripts-top')
@endpush
@section('page-header')
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Laporan Indikator</span> - {{$form->name}}</h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>
		</div>
		{{ Breadcrumbs::render('admin.monev.indicator-report.detail',$form) }}				
	</div>
@endsection
@section('content')
<div class="card">
	<div class="card-header header-elements-inline">
		<h6 class="card-title font-weight-semibold">Laporan Indikator {{$form->name}}</h6>
	</div>
	<hr class="m-0">
	<div class="card-body">
		<div class="row">
			@foreach ($form->indicators as $indicator)
				<div class="col-sm-6 col-xl-3">
					<div class="card card-body has-bg-image" style="background-color:{{$indicator->color}}">
						<div class="media">
							<div class="mr-3 align-self-center">
								<i class="icon-enter6 icon-3x opacity-75"></i>
							</div>

							<div class="media-body text-right">
								<h6 class="mb-0">Minimal {{$indicator->minimum}}<br>Maksimal {{$indicator->maximum}}</h6>
								<span class="text-uppercase font-size-xs">{{$indicator->description}}</span>
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</div>
@endsection