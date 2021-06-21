@extends('layouts.full')

@section('site-title','Monitoring & Evaluasi - '. $form->name)
@push('css-top')
	<style>
		.sp-container{
			z-index: 9999;
		}
		.btn-group-toggle label.btn.btn-primary:not(.active) {
			background-color: #fff;
			border: 1px solid #ddd;
			color: inherit;
		}
	</style>
@endpush
@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{asset('assets/global/plugins/tables/datatables/extensions/row_reorder.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/tables/datatables/extensions/responsive.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/pnotify.min.js')}}"></script>
    <script src="{{asset('assets/global/js/plugins/pickers/color/spectrum.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/sweet_alert.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/pickers/pickadate/picker.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/pickers/pickadate/picker.date.js')}}"></script>
	<script>
		$(document).ready(function(){
				instrumentDatatable = $('#instrument-table').DataTable({
					pageLength : 10,
					processing: true,
					serverSide: true,
					responsive: true,
					ajax: '{!! route("admin.monev.form.instrument.data",[$form->id]) !!}',
					columns: [
						{ data: 'position', name: 'position'},
						{ data: 'id', name: 'id',visible: false, searchable: false},
						// { "data": null,"sortable": false, searchable: false,
						// 	render: function (data, type, row, meta) {
						// 		return meta.row + meta.settings._iDisplayStart + 1;
						// 	}
						// },
						{data: 'name', name: 'instruments.name',searchable: true},
						{data: 'questions', name: 'questions',searchable: false},
						{data: 'max_score', name: 'max_score',searchable: false},
						{data: 'status', name: 'status',searchable: false},
						{data: 'actions', name: 'actions', className: "text-center", orderable: false, searchable: false}
					],
					order: [[ 0, 'asc' ]],
					autoWidth: false,
					rowReorder: {
						selector: 'tr',
						dataSrc: 'position'
					},
				});
				instrumentDatatable.on('row-reorder', function (e, details, edit) {
					if(details.length) {
						console.log(details)
						let rows = [];
						details.forEach(element => {
							console.log(element);
							rows.push({
								id: instrumentDatatable.row(element.node).data().id,
								position: element.newData
							});
						});
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
						$.ajax({
							method: 'POST',
							url: "{!! route("monev.form.instrument.reorder",[$form->id]) !!}",
							data: {
								'_token' : '{{csrf_token()}}',
								'data' : rows 
							}
						})
						.done(function () { 
							instrumentDatatable.ajax.reload()
							$.unblockUI();
						});
					}
				});

                indicatorDatatable = $('#indicator-table').DataTable({
					pageLength : 10,
					lengthMenu: [[5, 10, 20], [5, 10, 20]],
					processing: true,
					serverSide: true,
					responsive: true,
					ajax: '{!! route("admin.monev.form.indicator.data",[$form->id]) !!}',
					columns: [
					{ "data": null,"sortable": false, searchable: false,
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Monitoring & Evaluasi</span> - Form</h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex">
					<a href="{{route('admin.monev.form.instrument.preview',[$form->id])}}" class="mr-3 btn btn-success "><i class="mi-visibility"></i> <span>Preview</span></a>
					<button onclick="component('{{route('admin.monev.form.target.summary',[$form->id])}}')" class="mr-3 btn bg-orange "><i class="mi-assignment-ind"></i> <span>Sasaran Monitoring</span></button>
					@if($form->isEditable())
						<button href="#" class="mr-3 btn bg-purple-400 mx-y"><i class="mi-description"></i> <span>Simpan Draft</span></button>
						<button onclick="component('{{route('admin.monev.form.publish',[$form->id])}}')" class="btn btn-info"><i class="mi-assignment"></i> <span>Publish</span></button>
					@endif
				</div>
			</div>
		</div>	
	</div>
	{{ Breadcrumbs::render('admin.monev.forms.form',$form) }}	
@endsection

@section('content')
@if (Session::has('message'))
		<div class="alert alert-info alert-styled-left alert-dismissible">
			<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
			{{ Session::get('message') }}
		</div>
	@endif
@include('pages.admin.monitoring-evaluasi.form.parts.header',['editable' => true])

<div class="card">
	<div class="card-header header-elements-inline">
		<h6 class="card-title">Daftar Group Pertanyaan</h6>
		@if($form->isEditable())
			<div class="header-elements">
				<button class="btn bg-purple-400" onclick="component('{{route('admin.monev.form.instrument.create',[$form->id])}}')"><i class="mi-assignment-turned-in"></i> Tambah Group Pertanyaan</button>
			</div>
		@endif
	</div>
	<hr class="m-0">
	<div class="card-body">
		<table class="table" id="instrument-table">
			<thead>
				<tr>
					<th>No</th>
					<th>id</th>
					<th>Group Pertanyaan</th>
					<th>Jumlah Pertanyaan</th>
					<th>Total Maksimal Skor</th>
					<th>Status</th>
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
		@if($form->isEditable())
			<div class="header-elements">
				<button class="btn bg-purple-400" onclick="component('{{route('admin.monev.form.indicator.create',[$form->id])}}')"><i class="mi-info"></i> Tambah Indikator</button>
			</div>
		@endif
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