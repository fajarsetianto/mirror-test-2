@extends('layouts.officer.full')

@section('site-title','Form Instrument Monitoring dan Evaluasi')

@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/tables/datatables/extensions/responsive.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/pnotify.min.js')}}"></script>
	<script>
		$(document).ready(function(){
				instrumentDatatable = $('#instrument-table').DataTable({
					pageLength : 10,
					processing: true,
					serverSide: true,
					responsive: true,
					retrieve: true,
    				aaSorting: [],
					ajax: '{!! route("officer.monev.inspection-history.detail.index",[$item->id])!!}',
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
	</script>
@endpush

@section('page-header')
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Sistem Monitoring dan Evaluasi</h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>
		</div>	
	</div>
	{{-- {{ Breadcrumbs::render('responden.home.form',$form) }}	 --}}
@endsection
@section('content')
<div class="content">
	<div class="card">
        <div class="card-header bg-teal-600">
            <h3 class="font-weight-bold mb-0">{{$item->target->form->name}}</h3>
        </div>
        <div class="card-body">
            <p>{{$item->target->form->description}}</p>
        </div>
        <div class="card-footer">
            <h6 class="font-weight-bold">Informasi Pengisi Form Monitoring dan Evaluasi</h6>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Kategori Sasaran Monitoring</label>
                <div class="col-md-9 col-6"><span class="badge badge-warning">{{$item->target->form->category}}</span></div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Pengisi Form Monev</label>
                <div class="col-md-9 col-6">{{$item->target->type}}</div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Sasaran Monitoring</label>
                <div class="col-md-9 col-6">{{$item->target->institutionable->name}}</div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Reponden</label>
                <div class="col-md-9 col-6"> ({{$item->target->institutionable->email}})</div>
            </div>
			@if($item->target->officers->isNotEmpty())
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Petugas Monev</label>
                <div class="col-md-9 col-6">
					@include('layouts.parts.officers',['officers' => $item->target->officers])
				</div>
            </div>
			@endif
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Waktu Mulai</label>
                <div class="col-md-9 col-6">{{$item->target->form->supervision_start_date->format('d/m/Y')}}</div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Waktu Selesai</label>
                <div class="col-md-9 col-6">{{$item->target->form->supervision_end_date->format('d/m/Y')}}
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
@if($item->target->officerLeader()->exists())
	@php
		$leader = $item->target->officerLeader()->first()->pivot;
		$leader->load('officerNote');
	@endphp
	<div class="card">
		<div class="card-header">
			<h3 class="card-title font-weight-semibold">Catatan Petugas Monev</h3>
		</div>
		<div class="card-body">
			<h6 class="font-weight-semibold">Lokasi</h6>
			@if($leader->officerNote->where('type','location')->isNotEmpty())
				{{$leader->officerNote->where('type','location')->first()->value}}
			@endif
			<h6 class="font-weight-semibold mt-3">Catatan</h6>
			<div class="p-3" style="border:1px solid rgba(0,0,0,.125);border-radius: 5px">
			@if($leader->officerNote->where('type','note')->isNotEmpty())
				{{$leader->officerNote->where('type','note')->first()->value}}
			@endif
			</div>
			<h6 class="font-weight-semibold mt-3">Lampiran</h6>
			<div class="row">
				<div class="col-md-6">
					<div class="text-muted">Foto</div>
					@foreach($leader->officerNote->where('type','photo') as $photo)
						<div class="d-flex align-items-center my-1" style="justify-content: space-between;">
							<a href="{{asset('data_file_note/'.$photo->value)}}">{{$photo->value}}</a>
							<a href="{{asset('data_file_note/'.$photo->value)}}" target="_blank" class="btn btn-primary"><i class="icon-download"></i> Unduh</a>
						</div>
					@endforeach
					
				</div>
				<div class="col-md-6">
					<div class="text-muted">File PDF</div>
					@foreach($leader->officerNote->where('type','pdf') as $pdf)
						<div class="d-flex align-items-center my-1" style="justify-content: space-between;">
							<a href="{{asset('data_file_note/'.$pdf->value)}}">{{$pdf->value}}</a>
							<a href="{{asset('data_file_note/'.$pdf->value)}}" target="_blank"  class="btn btn-primary"><i class="icon-download"></i> Unduh</a>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
@endif
@endsection