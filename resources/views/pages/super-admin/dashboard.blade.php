@extends('layouts.super-admin.full')

@section('site-title','Dashboard')

@push('scripts-top')
    <script src="{{asset('assets/global/js/plugins/visualization/d3/d3.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/visualization/d3/d3_tooltip.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/forms/styling/switchery.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/ui/moment/moment.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/pickers/daterangepicker.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_pages/dashboard.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_charts/pages/dashboard/light/streamgraph.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_charts/pages/dashboard/light/sparklines.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_charts/pages/dashboard/light/lines.js')}}"></script>	
	<script src="{{asset('assets/global/js/demo_charts/pages/dashboard/light/areas.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_charts/pages/dashboard/light/donuts.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_charts/pages/dashboard/light/bars.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_charts/pages/dashboard/light/progress.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_charts/pages/dashboard/light/heatmaps.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_charts/pages/dashboard/light/pies.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_charts/pages/dashboard/light/bullets.js')}}"></script>
@endpush

@section('content')
			<div class="content">
				
			</div>
@endsection