@extends('layouts.full')

@section('site-title','Dashboard')

@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/tables/datatables/extensions/responsive.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/pnotify.min.js')}}"></script>
	<script>
		$(document).ready(function(){
			(function(){
				$('.datatable').DataTable({
					responsive: true
				});
			})()

			
		});
		function component(x){
				switch(x){
					case 'add':
						var url = "{{route('monev.form.create')}}";
						break;
				}

				$.blockUI({ 
					message: '<i class="icon-spinner4 spinner"></i>',
					overlayCSS: {
						backgroundColor: '#1b2024',
						opacity: 0.8,
						zIndex: 1200,
						cursor: 'wait'
					},
					css: {
						border: 0,
						color: '#fff',
						padding: 0,
						zIndex: 1201,
						backgroundColor: 'transparent'
					},
				});

				$.get(url, function(data){
					$('.modal').html(data);
				}).done(function() {
					$('.modal').modal('show');
				})
				.fail(function() {
					alert( "Terjadi Kesalahan" );
				})
				.always(function() {
					$.unblockUI();
				});
			}
		
	</script>
@endpush

@section('content')
<div class="card">
	<div class="card-header header-elements-inline">
		<h6 class="card-title">Daftar Form Instrument Monitoring dan Evaluasi</h6>
		<div class="header-elements">
			<button class="btn btn-success" onclick="component('add')"><i class=""></i> Add Form</button>
		</div>
	</div>
	<hr class="m-0">
	<div class="card-body">
		<table class="table datatable">
			<thead>
				<tr>
					<th>Name</th>
					<th>Position</th>
					<th>Age</th>
					<th>Start date</th>
					<th>Salary</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
@endsection
@push('scripts-bottom')
	<x-modal></x-modal>
@endpush