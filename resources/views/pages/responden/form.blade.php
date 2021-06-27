@extends('layouts.responden.full')

@section('site-title','Sistem Monitoring dan Evaluasi - '. $form->name)
@push('css-top')
	<style>
		.sp-container{
			z-index: 9999;
		}
	</style>
@endpush
@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/tables/datatables/extensions/responsive.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/pnotify.min.js')}}"></script>
	<script>
		$(document).ready(function(){
				instrumentDatatable = $('#instrument-table').DataTable({
					pageLength : 10,
					// lengthMenu: [[5, 10, 20], [5, 10, 20]],
					processing: true,
					serverSide: true,
					responsive: true,
					retrieve: true,
    				aaSorting: [],
					ajax: '{!! route("respondent.form.data")!!}',
					columns: [
						{ "data": null,"sortable": false, searchable: false,
							render: function (data, type, row, meta) {
								return meta.row + meta.settings._iDisplayStart + 1;
							}
						},
						{data: 'name', name: 'name'},
						{data: 'question', name: 'question'},
						{data: 'status', name: 'status'},
						{data: 'actions', name: 'actions', className: "text-center", orderable: false, searchable: false}
					],
					autoWidth: false,
					dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
					language: {
						search: '<span>Filter:</span> _INPUT_',
						lengthMenu: '<span>Show:</span> _MENU_',
						paginate: { 'first': 'First', 'last': 'Last', 'next': '→', 'previous': '←' }
					},
					rowReorder: {
						selector: 'tr'
					}
				});

				instrumentDatatable.on('row-reorder', function (e, details) {
					if(details.length) {
						let rows = [];
						details.forEach(element => {
							rows.push({
								id: $(element.node).data('entry-id'),
								position: element.newData
							});
						});

						console.log(rows)
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
	</script>
@endpush

@section('page-header')
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Sistem Monitoring dan Evaluasi</h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex">
					<button onclick="component('{{route('respondent.form.publish')}}')" class="btn btn-info"><i class="mi-assignment"></i> <span>Kirim</span></button>
				</div>
			</div>
		</div>	
	</div>
	{{ Breadcrumbs::render('responden.home.form',$form) }}	
@endsection

@section('content')
<div class="content">
    <div class="card">
        <div class="card-header bg-teal-600">
            <h3 class="font-weight-bold mb-0">{{$user->target->form->name}}</h3>
        </div>
        <div class="card-body">
            <p>{{$user->target->form->description}}</p>
        </div>
        <div class="card-footer">
            <h6 class="font-weight-bold">Informasi Pengisi Form Monitoring dan Evaluasi</h6>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Kategori Sasaran Monitoring</label>
                <div class="col-md-9 col-6"><span class="badge badge-warning">{{$user->target->form->category}}</span></div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Pengisi Form Monev</label>
                <div class="col-md-9 col-6">{{$user->target->type}}</div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Sasaran Monitoring</label>
                <div class="col-md-9 col-6">{{$user->target->institutionable->name}}</div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Reponden</label>
                <div class="col-md-9 col-6">{{$user->name}} ({{$user->target->institutionable->email}})</div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Petugas Monev</label>
                <div class="col-md-9 col-6">{{$user->target->officerName()}}</div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Waktu Mulai</label>
                <div class="col-md-9 col-6">{{$user->target->form->supervision_start_date->format('d/m/Y')}}</div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Waktu Selesai</label>
                <div class="col-md-9 col-6">{{$user->target->form->supervision_end_date->format('d/m/Y')}} <span class="badge badge-danger">Sisa Waktu : {{$user->target->form->supervisionDaysRemaining()}} Hari</span></div>
            </div>
    
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h6 class="card-title">Instrument Form Monitoring dan Evaluasi</h6>
        </div>
        <hr class="m-0">
        <div class="card-body">
            <table class="table" id="instrument-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Group Pertanyaan</th>
                        <th>Jumlah Pertanyaan</th>
						<th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection
@push('scripts-bottom')
	<x-modal></x-modal>
@endpush