@extends('layouts.full',['breadcrumb' => 'home'])

@section('site-title','Dashboard')

@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/loaders/blockui.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/pnotify.min.js')}}"></script>
	<script>
		$(document).ready(function(){
			
				instanceDatatable = $('.datatable').DataTable({
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
			
		});
        
	</script>
@endpush

@section('content')
<div class="card">
	<div class="card-body">
		<form action="" id="instrument-form">
            @csrf
            <fieldset>
                <legend class="font-weight-semibold text-uppercase font-size-sm">Instrument Form</legend>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Group Pertanyaan</label>
                    <div class="col-md-10">
                        <input type="text" required class="form-control" name="name" placeholder="Nama Group Pertanyaan">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">
                        Deskripsi Form <br>
                        <small>Optional</small>
                    </label>
                    <div class="col-md-10">
                        <textarea name="description" class="form-control" cols="30" rows="5" placeholder="Deskripsi Form Monitoring dan Evaluasi"></textarea>
                    </div>
                </div>
            </fieldset>
            <div class="d-flex align-items-center justify-content-end">
                <button class="btn bg-success" type="submit"><i class="icon-checkmark3 font-size-base mr-1"></i> Tambahkan</button>
            </div>
        </form>
	</div>
</div>

<div class="card">
	<div class="card-header header-elements-inline">
		<h6 class="card-title">Instrument Form Monitoring dan Evaluasi</h6>
		<div class="header-elements">
			<button class="btn btn-success" onclick="getForm('add')"><i class=""></i> Add Form</button>
		</div>
	</div>
	<hr class="m-0">
	<div class="card-body">
		<table class="table datatable">
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
@endsection
@push('scripts-bottom')
	<x-modal></x-modal>
    <script>
        $("#instrument-form").on('submit', function (e) {
            e.preventDefault();
            var el = $(this);
            var context = this;
            el.prop('disabled', true);

            setTimeout(function(){el.prop('disabled', false); }, 3000);

            $('button[type="submit"]', this).html('<i class="icon-spinner2 spinner"></i> Please wait...');

            $.ajax({
                url: "{{route('monev.form.instrument.store',[$form->id])}}",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (data) {
                    instanceDatatable.ajax.reload();
                    new PNotify({
                        title: data.title,
                        text: data.msg,
                        addclass: 'bg-success border-success',
                    });
                    $('button[type="submit"]', context).html('Save <i class="icon-paperplane ml-2"></i> ');
                },
                error: function (data) {
                    if(data.status == 422){
                        $('.text-help').remove();
                        $.each( data.responseJSON.errors, function( key, value) {
                            $('[name="'+key+'"]').parent().append(
                                $('<small class="text-help text-danger d-block">').html(value[0])
                            )
                        });
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
                    $('button[type="submit"]', context).html('Save <i class="icon-paperplane ml-2"></i> ');

                }
            })

            return false;

        });
    </script>
@endpush