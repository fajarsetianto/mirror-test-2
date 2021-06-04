@extends('layouts.full')

@section('site-title','Manajemen Non Satuan Pendidikan')

@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/tables/datatables/extensions/responsive.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/pnotify.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/sweet_alert.min.js')}}"></script>
	<script>
		$(document).ready(function(){
			
			instanceDatatable = $('.datatable').DataTable({
					pageLength : 10,
					lengthMenu: [[5, 10, 20], [5, 10, 20]],
					processing: true,
					serverSide: true,
					responsive: true,
					ajax: '{!! route("institution.non-satuan.data",['$form->id']) !!}',
					columns: [
					{ "data": null,"sortable": false,
						render: function (data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						}
					},
					{data: 'name', name: 'name'},
					{data: 'npsn', name: 'npsn'},
                    {data: 'email', name: 'email'},
					{data: 'address', name: 'address'},
					{data: 'province', name: 'province'},
                    {data: 'city', name: 'city'},
                    {data: 'headmaster', name: 'headmaster'},
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
		function component(y){

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

				$.get(y, function(data){
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
			function destroy(y){
				let csrf_token = "{{csrf_token()}}"
				Swal.fire({
						title: 'Are you sure?',
						text: "You won't be able to revert this!",
						type: 'warning',
						showCancelButton: true,
						confirmButtonClass: 'btn btn-primary',
						cancelButtonClass: 'btn btn-light',
						confirmButtonText: 'Yes, delete it!'
					})
					.then((result) => {
						// console.log(result.value) 
						if (result.value) {
							$.ajax({
								url: y,
								type : "DELETE",
								data : {'_method' : 'DELETE', '_token' : csrf_token},
								success:function(data){
									
									instanceDatatable.ajax.reload();
									
																			
									new PNotify({
										title: data.title,
										text: data.msg,
										addclass: 'bg-success border-success',
									});
								},
								error:function(data){
									new PNotify({
										title: data.statusText,
										text: data.responseJSON.msg,
										addclass: 'bg-danger border-danger',
									});
								}
							});
						}
				});
			}
		
	</script>
@endpush
@section('page-header')
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Manajemen Lembaga</span> - Non Satuan Pendidikan</h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>
		</div>
		{{ Breadcrumbs::render('admin.institution.non-satuan') }}				
	</div>
@endsection
@section('content')
<div class="card">
	<div class="card-header header-elements-inline">
		<h6 class="card-title font-weight-semibold">Daftar Lembaga Non Satuan Pendidikan</h6>
		<div class="header-elements">
			<button class="btn bg-purple-400" onclick="component('{{route('institution.non-satuan.create')}}')"><i class="icon-add-to-list"></i> Buat Lembaga Baru</button>
		</div>
	</div>
	<hr class="m-0">
	<div class="card-body">
		<table class="table datatable">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>NPSN</th>
					<th>Email</th>
					<th>Alamat</th>
                    <th>Provinsi</th>
                    <th>Kabupaten / Kota</th>
                    <th>Nama Kepala Lembaga</th>
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