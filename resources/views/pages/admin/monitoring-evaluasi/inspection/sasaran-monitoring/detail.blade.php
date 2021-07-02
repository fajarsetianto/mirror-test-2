@extends('layouts.full')

@section('site-title','Form Instrument Monitoring dan Evaluasi')
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
					responsive: true,
					processing: true,
					serverSide: true,
					ajax: '{!! route("admin.monev.inspection.form.instrument.data",[$form->id, $target->id]) !!}',
					columns: [
					{ "data": null,"sortable": false, searchable: false,
						render: function (data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						}
					},
					{data: 'name', name: 'name'},
					{data: 'questions_count', searchable : false},
					{data: 'max_score', searchable : false},
					{data: 'score', searchable : false},
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Monitoring & Evaluasi</span> - Pemeriksaan</h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div> 
			<div class="header-elements d-none">
				<div class="d-flex">
					<button href="#" class="mr-3 btn bg-purple "><i class="icon-download"></i> <span>Unduh</span></button>
				</div>
			</div>
		</div>
		{{ Breadcrumbs::render('admin.monev.inspection-history.form.detail',$form, $target) }}				
	</div>
@endsection
@section('content')
<div class="card">
	<div class="card-header bg-teal-400 text-white header-elements-inline">
		<h3 class="card-title font-weight-semibold">{{ strtoupper($form->name)}}</h3>
        @isset($editable)
            <div class="header-elements">
                <button type="button" onclick="component('{{route('admin.monev.form.edit',[$form->id])}}')" class="btn bg-success-400 btn-icon"><i class="icon-pencil"></i></button>
            </div>
        @endisset
	</div>
	
	<div class="card-body">
		{{$form->description}}
	</div>
	<div class="card-body bg-white ">
		<div class="d-flex align-items-center">
			<div class="mr-4">
				<span class="font-weight-bold">Total Bobot </span>: <span class="badge badge-primary">{{$target->scores()}}</span>
			</div>
		</div>
		
	</div>
	<div class="card-footer">
		<h6 class="font-weight-bold">Informasi Pengisi Form Monitoring dan Evaluasi</h6>
		<div class="form-group row mb-0">
			<label class="col-md-3 col-6 font-weight-bold">Kategori Sasaran Monitoring</label>
			<div class="col-md-9 col-6"><span class="badge badge-warning">{{$form->category}}</span></div>
		</div>
		<div class="form-group row mb-0">
			<label class="col-md-3 col-6 font-weight-bold">Pengisi Form Monev</label>
			<div class="col-md-9 col-6">{{$target->type}}</div>
		</div>
		<div class="form-group row mb-0">
			<label class="col-md-3 col-6 font-weight-bold">Sasaran Monitoring</label>
			<div class="col-md-9 col-6">{{$target->institutionable->name}}</div>
		</div>
		<div class="form-group row mb-0">
			<label class="col-md-3 col-6 font-weight-bold">Reponden</label>
			<div class="col-md-9 col-6">{{$target->institutionable->email}}</div>
		</div>
		<div class="form-group row mb-0">
			<label class="col-md-3 col-6 font-weight-bold">Petugas Monev</label>
			<div class="col-md-9 col-6">
				@foreach ($target->officers as $officer)
					{{$loop->iteration}}. {{$officer->name}} @if($officer->pivot->is_leader) <span class="badge badge-info">Leader</span> @endif <br>
				@endforeach
			</div>
		</div>

	</div>
	
	
</div>

<div class="card">
	<div class="card-header header-elements-inline">
		<h6 class="card-title font-weight-semibold">Instrument Form Monitoring dan Evaluasi</h6>
	</div>
	<hr class="m-0">
	<div class="card-body">
		<table class="table datatable">
			<thead>
				<tr>
					<th>No</th>
					<th>Group Pertanyaan</th>
                    <th>Jumlah Pertanyaan</th>
                    <th>Maks Skor</th>
					<th>Skor</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h3 class="card-title font-weight-semibold">Catatan Petugas Monev</h3>
	</div>
	<div class="card-body">
		<h6 class="font-weight-semibold">Lokasi</h6>
		{Lokasi}
		<h6 class="font-weight-semibold mt-3">Catatan</h6>
		<div class="p-3" style="border:1px solid rgba(0,0,0,.125);border-radius: 5px">
			asdadasd
		</div>
		<h6 class="font-weight-semibold mt-3">Lampiran</h6>
		<div class="row">
			<div class="col-md-6">
				<div class="text-muted">Foto</div>
				<div class="d-flex align-items-center my-1" style="justify-content: space-between;">
					<a href="">File Format</a>
					<button class="btn btn-primary"><i class="icon-download"></i> Unduh</button>
				</div>
				<div class="d-flex align-items-center my-1" style="justify-content: space-between;">
					<a href="">File Format</a>
					<button class="btn btn-primary"><i class="icon-download"></i> Unduh</button>
				</div>
				<div class="d-flex align-items-center my-1" style="justify-content: space-between;">
					<a href="">File Format</a>
					<button class="btn btn-primary"><i class="icon-download"></i> Unduh</button>
				</div>
				<div class="d-flex align-items-center my-1" style="justify-content: space-between;">
					<a href="">File Format</a>
					<button class="btn btn-primary"><i class="icon-download"></i> Unduh</button>
				</div>
			</div>
			<div class="col-md-6">
				<div class="text-muted">File PDF</div>
				<div class="d-flex align-items-center my-1" style="justify-content: space-between;">
					<a href="">File Format</a>
					<button class="btn btn-primary"><i class="icon-download"></i> Unduh</button>
				</div>
				<div class="d-flex align-items-center my-1" style="justify-content: space-between;">
					<a href="">File Format</a>
					<button class="btn btn-primary"><i class="icon-download"></i> Unduh</button>
				</div>
				<div class="d-flex align-items-center my-1" style="justify-content: space-between;">
					<a href="">File Format</a>
					<button class="btn btn-primary"><i class="icon-download"></i> Unduh</button>
				</div>
				<div class="d-flex align-items-center my-1" style="justify-content: space-between;">
					<a href="">File Format</a>
					<button class="btn btn-primary"><i class="icon-download"></i> Unduh</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts-bottom')
	<x-modal></x-modal>
@endpush