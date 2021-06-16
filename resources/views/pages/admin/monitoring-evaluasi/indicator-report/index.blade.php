@extends('layouts.full')

@section('site-title','Laporan Indikator')

@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/tables/datatables/extensions/responsive.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/pnotify.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/pickers/pickadate/picker.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/pickers/pickadate/picker.date.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/sweet_alert.min.js')}}"></script>
	<script>
		$(document).ready(function(){
			
			instanceDatatable = $('.datatable').DataTable({
					pageLength : 10,
					lengthMenu: [[5, 10, 20], [5, 10, 20]],
					processing: true,
					serverSide: true,
					responsive: true,
					ajax: '{!! route("monev.indicator-report.data") !!}',
					columns: [
					{ "data": null,"sortable": false, searchable: false,
						render: function (data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						}
					},
					{data: 'name', name: 'name'},
					{data: 'target', name: 'target'},
					{data: 'category', name: 'category'},
					{data: 'status', name: 'status'},
					{data: 'actions', name: 'actions', className: "text-center", orderable: false, searchable: false}
					],
					autoWidth: false,
					dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
					language: {
						search: '<span>Filter:</span> _INPUT_',
						lengthMenu: '<span>Show:</span> _MENU_',
						paginate: { 'first': 'First', 'last': 'Last', 'next': '→', 'previous': '←' }
					}
				});
		});
		
	</script>
@endpush
@section('page-header')
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Laporan Indikator</span></h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>
		</div>
		{{ Breadcrumbs::render('admin.monev.indicator-report') }}				
	</div>
@endsection
@section('content')
<div class="card">
	<div class="card-header header-elements-inline">
		<h6 class="card-title font-weight-semibold">Laporan Indikator Form Instrument Monitoring dan Evaluasi</h6>
	</div>
	<hr class="m-0">
	<div class="card-body">
		<table class="table datatable">
			<thead>
				<tr>
					<th>No</th>
					<th>Judul Form</th>
					<th>Sasaran Monitoring</th>
					<th>Kategori Satuan Pendidikan</th>
					<th>Status</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
@endsection