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
					<a href="{{route('admin.monev.indicator-report.detail.indicator',[$form->id, $indicator->id])}}">
						<div class="card card-body has-bg-image" style="background-color:{{$indicator->color}}">
							<div class="media">
								<div class="align-self-center text-center">
									<span class="mi-2x">{{$indicator->targets()->byInstrument($indicator->minimum, $indicator->maximum)->count()}}</span>
								</div>
								<div class="media-body text-right">
									<h6 class="mb-0">Minimal {{$indicator->minimum}}<br>Maksimal {{$indicator->maximum}}</h6>
									<span class="text-uppercase font-size-xs">{{$indicator->description}}</span>
								</div>
							</div>
						</div>
					</a>
				</div>
			@endforeach
		</div>
	</div>
</div>
@endsection