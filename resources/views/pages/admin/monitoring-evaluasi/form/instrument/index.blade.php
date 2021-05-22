@extends('layouts.full',['breadcrumb' => 'home'])

@section('site-title','Dashboard')
@push('css-top')
	<style>
		.sp-container{
			z-index: 9999;
		}
	</style>
@endpush
@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/loaders/blockui.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/pnotify.min.js')}}"></script>
    <script src="{{asset('assets/global/js/plugins/pickers/color/spectrum.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/sweet_alert.min.js')}}"></script>
	<script>
		$(document).ready(function(){
				instrumentDatatable = $('#instrument-table').DataTable({
					pageLength : 10,
					lengthMenu: [[5, 10, 20], [5, 10, 20]],
					processing: true,
					serverSide: true,
					ajax: '{!! route("monev.form.instrument.data",[$form->id]) !!}',
					columns: [
					{ "data": null,"sortable": false,
						render: function (data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						}
					},
					{data: 'name', name: 'name'},
					{data: 'description', name: 'description'},
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
                indicatorDatatable = $('#indicator-table').DataTable({
					pageLength : 10,
					lengthMenu: [[5, 10, 20], [5, 10, 20]],
					processing: true,
					serverSide: true,
					ajax: '{!! route("monev.form.indicator.data",[$form->id]) !!}',
					columns: [
					{ "data": null,"sortable": false,
						render: function (data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						}
					},
                    {data: 'minimum', name: 'minimum'},
                    {data: 'maximum', name: 'maximum'},
                    {data: 'color', name: 'color'},
					{data: 'description', name: 'description'},
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
		function component(x, y){
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
        function destroy(x,y){
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
                                
								if(x =="instrument"){
									instrumentDatatable.ajax.reload()
								}else{
									indicatorDatatable.ajax.reload();
								}
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - Dashboard</h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex">
					<button href="#" class="btn btn-success "><i class="icon-eye"></i> <span>Preview</span></button>
					<button href="#" class="mx-3 btn bg-purple-400 mx-y"><i class="icon-calculator"></i> <span>Simpan Draft</span></button>
					<button href="#" class="btn btn-info"><i class="icon-calendar5"></i> <span>Publish</span></button>
				</div>
			</div>
		</div>	
	</div>
@endsection

@section('content')

<div class="card">
	<div class="card-header header-elements-inline">
		<h6 class="card-title">Instrument Form Monitoring dan Evaluasi</h6>
		<div class="header-elements">
			<button class="btn btn-success" onclick="component('add','{{route('monev.form.instrument.create',[$form->id])}}')"><i class=""></i> Tambah Group Pertanyaan</button>
		</div>
	</div>
	<hr class="m-0">
	<div class="card-body">
		<table class="table" id="instrument-table">
			<thead>
				<tr>
					<th>No</th>
					<th>Group Pertanyaan</th>
					<th>Description</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<div class="card">
	<div class="card-header header-elements-inline">
		<h6 class="card-title">Manajemen Indikator</h6>
		<div class="header-elements">
			<button class="btn btn-success" onclick="component('add','{{route('monev.form.indicator.create',[$form->id])}}')"><i class=""></i> Tambah Indikator</button>
		</div>
	</div>
	<hr class="m-0">
	<div class="card-body">
		<table class="table" id="indicator-table">
			<thead>
				<tr>
					<th>No</th>
					<th>Bobot Nilai Minimum</th>
                    <th>Bobot Nilai Maximum</th>
                    <th>Warna</th>
					<th>Deskripsi</th>
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