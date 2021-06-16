@extends('layouts.officer.full')

@section('site-title','Dashboard')

@push('scripts-top')
    <script src="{{asset('assets/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('assets/global/js/plugins/tables/datatables/extensions/responsive.min.js')}}"></script>
    <script>
		$(document).ready(function(){
			respondentTabel = $('#respondent-table').DataTable({
				pageLength : 10,
				lengthMenu: [[5, 10, 20], [5, 10, 20]],
				responsive: true,
				processing: true,
				serverSide: true,
				ajax: '{!! route("officer.dashboard.data.respondent") !!}',
				columns: [
                    { "data": null,"sortable": false, searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: 'name', name: 'form.name'},
                    {data: 'target_name', name: 'name'},
                    {data: 'category', name: 'form.category'},
                    {data: 'expired_date', searchable: false},
                    {data: 'status', name: 'form.status', searchable: false},
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
            officerTabel = $('#officer-table').DataTable({
				pageLength : 10,
				lengthMenu: [[5, 10, 20], [5, 10, 20]],
				responsive: true,
				processing: true,
				serverSide: true,
				ajax: '{!! route("officer.dashboard.data.officer") !!}',
				columns: [
                    { "data": null,"sortable": false, searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: 'name', name: 'form.name'},
                    {data: 'target_name', name: 'name'},
                    {data: 'category', name: 'form.category'},
                    {data: 'expired_date', searchable: false},
                    {data: 'status', name: 'form.status', searchable: false},
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
				<h4><span class="font-weight-semibold">Sistem Monitoring dan Evaluasi Pembangunan Gedung</span></h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>
		</div>
		{{ Breadcrumbs::render('admin.management-user') }}				
	</div>
@endsection
@section('content')
<div class="content">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-semibold">Aktivitas Form Monitoring dan Evaluasi</h6>
        </div>
        <hr class="m-0">
        <div class="card-body">
            <div class="row">
				<div class="col-sm-6 col-xl-3">
					<div class="card card-body has-bg-image bg-teal-600">
						<div class="media">
							<div class="mr-3 align-self-center">
								<i class="icon-enter6 icon-3x opacity-75"></i>
							</div>

							<div class="media-body text-right">
								<h6 class="mb-0">{{$withRespondentCount}}</h6>
								<span class="text-uppercase font-size-xs">Form Monitoring & Evaluasi</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-xl-3">
					<div class="card card-body has-bg-image bg-purple-600">
						<div class="media">
							<div class="mr-3 align-self-center">
								<i class="icon-enter6 icon-3x opacity-75"></i>
							</div>
							<div class="media-body text-right">
								<h6 class="mb-0">{{$officerCount}}</h6>
								<span class="text-uppercase font-size-xs">Form Monitoring & Evaluasi</span>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-semibold">Daftar Pemeriksaan Monitoring dan Evaluasi</h6>
        </div>
        <hr class="m-0">
        <div class="card-body">
            <table class="table datatable" id="respondent-table">
				<thead>
					<tr>
						<th>No</th>
						<th>Judul Form</th>
						<th>Sasaran Monitoring</th>
						<th>Kategori Satuan Pendidikan</th>
						<th>Batas Waktu</th>
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
            <h6 class="card-title font-weight-semibold">Daftar Pengisian Form Monitoring dan Evaluasi</h6>
        </div>
        <hr class="m-0">
        <div class="card-body">
            <table class="table datatable" id="officer-table">
				<thead>
					<tr>
						<th>No</th>
						<th>Judul Form</th>
						<th>Sasaran Monitoring</th>
						<th>Kategori Satuan Pendidikan</th>
						<th>Batas Waktu</th>
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