@extends('layouts.officer.full')

@section('site-title','Form Instrument Monitoring dan Evaluasi')
@push('css-top')
	<style>
		.not-allowed {
			cursor: not-allowed !important;
		}
		.not-allowed:target {
			border: 0 !important;
		}
	</style>
@endpush
@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/tables/datatables/extensions/responsive.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/pnotify.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_pages/form_inputs.js')}}"></script>
	<script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
	<script>
		let formData = new FormData()
		$(document).ready(function(){
				instrumentDatatable = $('#instrument-table').DataTable({
					pageLength : 10,
					processing: true,
					serverSide: true,
					responsive: true,
					retrieve: true,
    				aaSorting: [],
					ajax: '{!! route("officer.monev.inspection.do.data",[$item->id])!!}',
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
					}

				});
			
			isLeader()
		});

		save = () => {
			$('button[type="submit"]', this).html('<i class="icon-spinner2 spinner"></i> Please wait...')
			let csrf_token = "{{csrf_token()}}"
			formData.delete('_token')
			formData.append('_token', csrf_token)
			$(`.input`).serializeArray().forEach(function(elem){
				formData.delete(elem.name)
				formData.append(elem.name, elem.value)
			})

			$.ajax({
				url: "{{$url}}",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function (data) {
					new PNotify({
						title: data.title,
						text: data.msg,
						addclass: 'bg-success border-success',
					});

				},
				error: function (data) {
					if(data.status == 422){
						new PNotify({
							title: data.responseJSON.message,
							text: 'please check your input',
							addclass: 'bg-danger border-danger',
						});
					}else{
						new PNotify({
							title: data.statusText,
							text: data.responseJSON.message,
							addclass: 'bg-danger border-danger',
						});
					}

				}
			})

			return false

		}

		upload = (name) => {
			let file = $(`#${name}`).prop('files')[0]
			let indexName = name.replace("-", "_")
			console.log(name)
			formData.delete(`${indexName}`)
			formData.append(`${indexName}`, file)
		}
		isLeader = () => {
			let status = "{{$item->is_leader}}"
			if(status != '1'){
			console.log(status)
				$(".leader").prop('disabled', true);
			}
		}

		download = (fileName) => {
        	window.open(`{{$urlDownload}}?file=${fileName}`, '_blank');
		}

		function locations(){
			$('#geolocation').val(`${geoplugin_latitude()}, ${geoplugin_longitude()}`)
			$('#ip-addr').val(geoplugin_request())
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
					
					<button href="#" onclick="save()" class="mr-3 btn bg-purple-400 mx-y"><i class="mi-description"></i> <span>Simpan</span></button>
					<button href="#" class="btn btn-info"><i class="mi-assignment"></i> <span>Kirim</span></button>
				</div>
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
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Petugas Monev</label>
                <div class="col-md-9 col-6">
					@foreach ($item->target->officers as $officer)
						{{$loop->iteration}}. {{$officer->name}} @if($officer->pivot->is_leader) <span class="badge badge-info">Leader</span> @endif
					@endforeach
				</div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Waktu Mulai</label>
                <div class="col-md-9 col-6">{{$item->target->form->supervision_start_date->format('d/m/Y')}}</div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-md-3 col-6 font-weight-bold">Waktu Selesai</label>
                <div class="col-md-9 col-6">{{$item->target->form->supervision_end_date->format('d/m/Y')}} <span class="badge badge-danger">Sisa Waktu : {{$item->target->form->supervisionDaysRemaining()}} Hari</span></div>
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
    <div class="card">
        <div class="card-header">
            <h6 class="card-title font-weight-bold">Catatan Petugas Monev</h6>
        </div>
        <hr class="m-0">
        <div class="card-body">
			<p class="font-weight-bold">Ambil Lokasi Terkini</p>
			<div class="d-flex">
				<button onclick="locations()" class="btn btn-success mr-2 leader">Ambil Lokasi</button>
				<input name="location" class="d-flex align-self-center m-0 text-secondary border-0 not-allowed input" id="geolocation" value="@isset($item->officerNote[7]->value){{$item->officerNote[7]->value}}@endisset" readonly>
			</div>
			<div class="mt-2 form-group">
				<label for="noted" class="py-2 font-weight-bold">Tambah Catatan</label>
				<textarea rows="3" name="note" cols="3" class="form-control input leader" id="noted" placeholder="Tambah Catatan">@isset($item->officerNote[0]->value){{$item->officerNote[0]->value}}@endisset</textarea>
			</div>
			<div class="mt-3 form-group">
				<label for="noted" class="font-weight-bold">Upload Foto</label>
				<p class="text-secondary pb-1">Tambahkan 5 foto</p>
				<div class="form-group d-flex">
					<div class="@isset($item->officerNote[1]->value) col-md-11 @else col-md-12 @endisset p-0 m-0">
						<input type="file" name="photo_1" class="form-control-uniform my-1 leader" id="photo-1" onchange="upload('photo-1')" data-fouc>
					</div>
					@isset($item->officerNote[1]->value)
						<div class="p-0 m-0 col-md-1 pl-1" style="justify-content: space-between;">
							<button type="button" onclick="download({{$item->officerNote[1]->id}})" class="btn btn-block btn-primary"><i class="icon-download"></i></button>
						</div>
					@endisset
				</div>
				<div class="form-group d-flex">
					<div class="@isset($item->officerNote[2]->value) col-md-11 @else col-md-12 @endisset p-0 m-0">
						<input type="file" name="photo_2" class="form-control-uniform my-1 leader" id="photo-2" onchange="upload('photo-2')" data-fouc>
					</div>
					@isset($item->officerNote[2]->value)
						<div class="p-0 m-0 col-md-1 pl-1" style="justify-content: space-between;">
							<button type="button" onclick="download({{$item->officerNote[2]->id}})" class="btn btn-block btn-primary"><i class="icon-download"></i></button>
						</div>
					@endisset
				</div>
				<div class="form-group d-flex">
					<div class="@isset($item->officerNote[3]->value) col-md-11 @else col-md-12 @endisset p-0 m-0">
						<input type="file" name="photo_3" class="form-control-uniform my-1 leader" id="photo-3" onchange="upload('photo-3')" data-fouc>
					</div>
					@isset($item->officerNote[3]->value)
						<div class="p-0 m-0 col-md-1 pl-1" style="justify-content: space-between;">
							<button type="button" onclick="download({{$item->officerNote[3]->id}})" class="btn btn-block btn-primary"><i class="icon-download"></i></button>
						</div>
					@endisset
				</div>
				<div class="form-group d-flex">
					<div class="@isset($item->officerNote[4]->value) col-md-11 @else col-md-12 @endisset p-0 m-0">
						<input type="file" name="photo_4" class="form-control-uniform my-1 leader" id="photo-4" onchange="upload('photo-4')" data-fouc>
					</div>
					@isset($item->officerNote[4]->value)
						<div class="p-0 m-0 col-md-1 pl-1" style="justify-content: space-between;">
							<button type="button" onclick="download({{$item->officerNote[4]->id}})" class="btn btn-block btn-primary"><i class="icon-download"></i></button>
						</div>
					@endisset
				</div>
				<div class="form-group d-flex">
					<div class="@isset($item->officerNote[5]->value) col-md-11 @else col-md-12 @endisset p-0 m-0">
						<input type="file" name="photo_5" class="form-control-uniform my-1 leader" id="photo-5" onchange="upload('photo-5')" data-fouc>
					</div>
					@isset($item->officerNote[5]->value)
						<div class="p-0 m-0 col-md-1 pl-1" style="justify-content: space-between;">
							<button type="button" onclick="download({{$item->officerNote[5]->id}})" class="btn btn-block btn-primary"><i class="icon-download"></i></button>
						</div>
					@endisset
				</div>
			</div>
			<div class="mt-3 form-group">
				<label for="noted" class="font-weight-bold">Tambahkan File PDF</label>
				<p class="text-secondary pb-1">Tambahkan file PDF</p>
				<div class="form-group d-flex">
					<div class="@isset($item->officerNote[6]->value) col-md-11 @else col-md-12 @endisset p-0 m-0">
						<input type="file" name="pdf_1" class="form-control-uniform my-1 leader" id="pdf-1" onchange="upload('pdf-1')" data-fouc>
					</div>
					@isset($item->officerNote[6]->value)
						<div class="p-0 m-0 col-md-1 pl-1" style="justify-content: space-between;">
							<button type="button" onclick="download({{$item->officerNote[6]->id}})" class="btn btn-block btn-primary"><i class="icon-download"></i></button>
						</div>
					@endisset
				</div>
			</div>
			<input type="hidden" name="ipaddr" value="@isset($item->officerNote[8]->value){{$item->officerNote[8]->value}}@endisset" class="input" id="ip-addr">
        </div>

    </div>
</div>

@endsection