@extends('layouts.full',['breadcrumb' => 'home'])

@section('site-title','Dashboard')
@push('css-top')
	<style>
		.sp-container{
			z-index: 9999;
		}
		.cursor{
			cursor: pointer;
		}
		.bg-purle {
			background-color: #5C6BC0;
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
		number = 1;
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
		
    
		removeOption = (questionId,uniqId) => {
			let number = 1
			$(`#row-${uniqId}`).remove()
			$(`.option-question-${questionId}`).each((key, elem) => {
				$(`.option-number-${questionId}`).eq(key).text(`Opsi ${number++}`)
			})
		}

		addOptions = (icon,uniqId, valueOption=null, score=null) =>{
			newUniqId = (new Date()).getTime()
			optionNumber = ++($(`.option-${uniqId}`).length)
			optionAnother = $(`.option-another-${uniqId}`).length

			if(optionNumber == 2){
				$(`#row-option-${uniqId}`).append(`
					<div class="col-md-1 ml-0 pl-0">
						<button type="button" id="remove-field-${uniqId}" onclick="removeOption(${uniqId},${uniqId})" class="btn btn-icon rounded-round"><i class="icon-cross2"></i></button>
					</div>
				`)
			}

			$(`#count-option-${uniqId}`).val(optionNumber)

			data = `
			<div class="row mt-4 option-${uniqId} option-question-${uniqId}" id="row-${newUniqId}">
					<div class="col-md-2 pr-0 mr-0">
						<i class="${icon}"></i> 
						<span class="option-number-${uniqId}">Opsi ${optionNumber-optionAnother}</span>
					</div>
					<div class="col-md-10 ml-0 pl-0">
						<div class="row">
							<div class="col-md-11 ml-0 pl-0">
								<input class="alpaca-control form-control flex-1 mr-3" required value="${valueOption == null ? '' : valueOption}" name="option_answer[]" placeholder="Opsi Jawaban">   
							</div>
							<div class="col-md-1 ml-0 pl-0">
								<button type="button" id="remove-field-${newUniqId}" onclick="removeOption(${uniqId},${newUniqId})" class="btn btn-icon rounded-round"><i class="icon-cross2"></i></button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 ml-0 pl-0">
								<label  class="pt-2 control-label alpaca-control-label font-weight-bold">Bobot</label> 
								<input class="alpaca-control form-control flex-1 mr-3" required value="${score == null ? '' : score}" name="score[]" placeholder="Bobot Nilai">   
							</div>
						</div>
					</div>
				
			</div>
			`

			$(`#form-group-${uniqId}`).append(data)
		}

		addOptionAnother = (icon, uniqId, score=null) => {
			if($(`.option-another-${uniqId}`).length < 1){
				$(`#count-option-${uniqId}`).val(parseInt($(`#count-option-${uniqId}`).val())+1)
			}
			newUniqId = (new Date()).getTime()
			$(`#field-other-${uniqId}`).html(`
				<div class="col-md-11 ml-auto mt-2 option-${uniqId} option-another-${uniqId}" id="row-${newUniqId}">
					<div class="row">
						<div class="col-md-2 pr-0 mr-0">
							<i class="${icon}"></i> Opsi Lainnya
						</div>
						<div class="col-md-10 ml-0 pl-0">
							<div class="row">
								<div class="col-md-11 ml-0 pl-0">
									<input readonly class="alpaca-control form-control flex-1 mr-3" name="option_answer[]" value="Lainnya" placeholder="Lainnya">   
								</div>
								<div class="col-md-1 ml-0 pl-0">
									<button type="button" id="remove-field-${newUniqId}" onclick="removeOption(${uniqId},${newUniqId})" class="btn btn-icon rounded-round"><i class="icon-cross2"></i></button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 ml-0 pl-0">
									<label  class="pt-2 control-label alpaca-control-label font-weight-bold">Bobot</label> 
									<input class="alpaca-control form-control flex-1 mr-3" required value="${score == null ? '' : score}" name="score[]" placeholder="Bobot Nilai">   
								</div>
							</div>
						</div>
					</div>
				</div>
			`)
    	}

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

		cancel = (uniqId) =>{
			$(`#form-card-${uniqId}`).remove()

			let number = 1;
			$('.question-number').each((key, elem) => {
				$('.question-number').eq(key).text(number++)
			})
			this.number = number
		}

		question = (typeClick, questionName = null, option =null, $idQuestion=null) => {
			type =''
			questionType=''
			addOption = ''
			countOption = 0
			number = 1
			uniqId = (new Date()).getTime()
			if(typeClick == 'singkat'){
				questionType = 'Singkat'
				
				type = `
					<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
						<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
						<input type="text" disabled id="alpaca5" class="alpaca-control form-control" placeholder="Jawaban ${questionType}"  autocomplete="off">
					</div>
				`
			} else if (typeClick == 'paraghraf'){
					questionType = 'Paraghraf'

					type = `
					<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
						<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
						<textarea rows="5" disabled cols="5" class="form-control" placeholder="Jawaban ${questionType}"></textarea>
					</div>
					
					`
			} else if (typeClick == 'ganda'){
				questionType = 'Ganda'
				icon = 'icon-circle'
				countOption = 1
				type = `
				<div id="form-group-${uniqId}" class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
						<label  class="pt-2 control-label alpaca-control-label">Opsi Jawaban</label>
						<div class="row mt-2 option-${uniqId} option-question-${uniqId}" id="row-${uniqId}">
							<div class="col-md-2 pr-0 mr-0">
								<i class="${icon}"></i> 
								<span class="option-number-${uniqId}">Opsi 1</span>
							</div>
							<div class="col-md-10 ml-0 pl-0">
								<div class="row" id="row-option-${uniqId}">
									<div class="col-md-11 ml-0 pl-0">
										<input class="alpaca-control form-control flex-1 mr-3" required name="option_answer[]" placeholder="Opsi Jawaban">   
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 ml-0 pl-0">
										<label  class="pt-2 control-label alpaca-control-label font-weight-bold">Bobot</label> 
										<input class="alpaca-control form-control flex-1 mr-3" required name="score[]" type="number" placeholder="Bobot Nilai">   
									</div>
								</div>
							</div>
						</div>
					</div>
				`

				addOption = `
						<div class="row">
							<div class="col-lg-11 ml-auto mt-2">
								<span onclick="addOptions('${icon}',${uniqId})" class="text-secondary cursor"><i class="icon-plus-circle2 text-primary"></i> Tambah Opsi </span>
								atau
								<span onclick="addOptionAnother('${icon}',${uniqId})" class="text-primary cursor">tambah "Lainnya" <span>
							</div>
						</div>
				`
				
			} else if (typeClick == 'multiple' || typeClick == 'multiple choice'){
				questionType = 'Multiple Choice'
				icon = 'icon-checkbox-unchecked'
				countOption = 1
				type = `
				<div id="form-group-${uniqId}" class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
						<label class="pt-2 control-label alpaca-control-label">Opsi Jawaban</label>
						<div class="row mt-2 option-${uniqId} option-question-${uniqId}" id="row-${uniqId}">
							<div class="col-md-2 pr-0 mr-0">
								<i class="${icon}"></i> 
								<span class="option-number-${uniqId}">Opsi 1</span>
							</div>
							<div class="col-md-10 ml-0 pl-0">
								<div class="row" id="row-option-${uniqId}">
									<div class="col-md-11 ml-0 pl-0">
										<input class="alpaca-control form-control flex-1 mr-3" required name="option_answer[]" required  placeholder="Opsi Jawaban">   
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 ml-0 pl-0">
										<label  class="pt-2 control-label alpaca-control-label font-weight-bold">Bobot</label> 
										<input class="alpaca-control form-control flex-1 mr-3" required name="score[]" type="number" placeholder="Bobot Nilai">   
									</div>
								</div>
							</div>
						</div>
					</div>
				`

				addOption = `
						<div class="row">
							<div class="col-lg-11 ml-auto mt-2">
								<span onclick="addOptions('${icon}',${uniqId})" class="text-secondary cursor"><i class="icon-plus-circle2 text-primary"></i> Tambah Opsi </span>
								atau
								<span onclick="addOptionAnother('${icon}',${uniqId})"  class="text-primary cursor">tambah "Lainnya" <span>
							</div>
						</div>
				`
			} else if (typeClick == 'dropdown') {
				questionType = 'Dropdown'
				icon = 'icon-circle-down2'
				countOption = 1

				type = `
				<div id="form-group-${uniqId}" class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
						<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
						<div class="row mt-2 option-${uniqId} option-question-${uniqId}" id="row-${uniqId}">
							<div class="col-md-2 pr-0 mr-0">
								<i class="${icon}"></i> 
								<span class="option-number-${uniqId}">Opsi 1</span>
							</div>
							<div class="col-md-10 ml-0 pl-0">
								<div class="row" id="row-option-${uniqId}">
									<div class="col-md-11 ml-0 pl-0">
										<input class="alpaca-control form-control flex-1 mr-3" required name="option_answer[]" required placeholder="Opsi Jawaban">   
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 ml-0 pl-0">
										<label  class="pt-2 control-label alpaca-control-label font-weight-bold">Bobot</label> 
										<input class="alpaca-control form-control flex-1 mr-3" required name="score[]" type="number" placeholder="Bobot Nilai">   
									</div>
								</div>
							</div>
						</div>
					</div>
				`

				addOption = `
						<div class="row">
							<div class="col-lg-11 ml-auto mt-2">
								<span onclick="addOptions('${icon}',${uniqId})" class="text-secondary cursor"><i class="icon-plus-circle2 text-primary"></i> Tambah Opsi </span>
								atau
								<span onclick="addOptionAnother('${icon}',${uniqId})"  class="text-primary cursor">tambah "Lainnya" <span>
							</div>
						</div>
				`
			} else if (typeClick == 'file-upload' || typeClick == 'file upload'){
				questionType = 'File Upload'
				type = `
					<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
						<label class="pt-2 control-label d-block alpaca-control-label">Berkas File Upload</label>
						<button disabled class="btn btn-light text-left mb-3 text-primary"><i class="icon-upload4 mr-2"></i>File Upload</button>
					</div>
				`
			}
			$('#content').append(`
				<div id="form-card-${uniqId}">
				@csrf
				<input type="hidden" id="count-option-${uniqId}" name="count_option[]" value="${countOption}">
				<input type="hidden" name="question_type[]" value="${questionType}">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-1">
										<span class="question-number">${number}.</span>
									</div>
									<div class="col-lg-11">
										<div class="d-flex ">
											<label>Pertanyaan - ${questionType}</label>
											<div class="question-action ml-auto align-self-center">
												<a href="#" class="mr-2 text-dark"><i class="icon-pencil"></i></a>
												<a href="#" class="mr-2 text-dark"><i class="icon-trash-alt"></i></a>
											</div>
										</div>
										<input class="alpaca-control form-control flex-1 mr-3" required name="question[]" value="${questionName == null ? '' : questionName}" placeholder="Pertanyaan - ${questionType}">
										
										${type}
									</div>
								</div>
								<div id="field-other-${uniqId}" class="row"></div>
								${addOption}
								<div class="row">
									<div class="col-lg-6 ml-auto text-right">
										<button class="btn bg-danger text-left mb-3" onclick="cancel(${uniqId})">Batal</button>
										<button class="btn bg-success text-left mb-3" onclick="return save(${uniqId}, '{{route('monev.form.instrument.question.store',[$form->id, $instrument->id, $idQuestion])}}')">Simpan</button>
									</div>
								</div>
							</div>
						</div>
				</div>
			`)

			if(option != null){
				dataOption = JSON.parse(option.replace(/&quot;/g, '\"'))
				$(`.option-${uniqId}`).remove()
				dataOption.forEach(element => {
					if(element.value == 'Lainnya'){
						addOptionAnother(icon,uniqId, `${element.score}`)
					} else {
						addOptions(icon,uniqId, `${element.value}`, `${element.score}`)
					}
				});
			}

			number++;
		}

		saveDraft = (url) => {
			$.ajax({
				url: url,
				type: "POST",
				data: new FormData($('form')[1]),
				processData: false,
				contentType: false,
				success: function (data) {
					
					$('.modal').modal('hide');
					instrumentDatatable.ajax.reload();
					new PNotify({
						title: data.title,
						text: data.msg,
						addclass: 'bg-success border-success',
					});

				},
				error: function (data) {
					if(data.status == 422){
						$('.text-help').remove();
						$.each( data.responseJSON.errors, function( key, value) {
							$('[name="'+key+'"]').parent().append(
								$('<small class="text-help text-danger d-block w-100">').html(value[0])
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
		}

		save = (uniqId, url) =>{
			let formData = new FormData()
			$(`#form-card-${uniqId} input`).serializeArray().forEach(function(elem){
				formData.append(elem.name, elem.value)
			})
			$.ajax({
				url: url,
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function (data) {
					
					$('.modal').modal('hide');
					instrumentDatatable.ajax.reload();
					new PNotify({
						title: data.title,
						text: data.msg,
						addclass: 'bg-success border-success',
					});

				},
				error: function (data) {
					if(data.status == 422){
						$('.text-help').remove();
						$.each( data.responseJSON.errors, function( key, value) {
							$('[name="'+key+'"]').parent().append(
								$('<small class="text-help text-danger d-block w-100">').html(value[0])
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
			return false
		}
	</script>
@endpush

@section('page-header')
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">{{$form->name}}</span> - {{$instrument->name}}</h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>
		</div>	
	</div>
@endsection

@section('content')

	<div class="row">
		<div class="col-lg-3">
			<div class="card mb-0 pt-2 pb-2">
				<div class="card-body">
					<button class="btn btn-block btn-success text-left" onclick="component('addQuestion','{{route('monev.form.instrument.question.create',[$form->id, $instrument->id])}}')"><i class="icon-bubble-lines3 mr-2"></i> Tambah Pertanyaan</button>
				</div>
			</div>
			<div class="card mt-0">
				<div class="card-body">
					<button class="btn btn-block bg-purle text-left mb-3" onclick="saveDraft('{{route('monev.form.instrument.question.store',[$form->id, $instrument->id])}}')"><i class="icon-file-text3 mr-2"></i> Simpan Sebagai Draft</button>
					<button class="btn btn-block btn-primary text-left mb-5" onclick="component('public','{{route('monev.form.instrument.create',[$form->id])}}')"><i class="icon-file-text mr-2"></i> Publikasikan</button>
				</div>
			</div>
		</div>
		<div class="col-lg-9" >
		<form action="#" id="content">
			@csrf
			<div class="card">
				<div class="page-header">
					<div class="page-header-content">
						<div class="page-title">
							<div class="instrument-header d-flex">
								<h4><span class="font-weight-semibold">{{$instrument->name}}</span></h4>
								<div class="instrument-action ml-auto">
									<a href="#" class="mr-"><i class="icon-pencil mr-1"></i> Edit</a>
								</div>
							</div>
							<div class="instrument-description">
								<p class="text-secondary">{{$instrument->description }}</p>
							</div>
						</div>
					</div>	
				</div>
			</div>
			@foreach($data as $row)
			<script>
				question('{{strtolower($row->questionType->name)}}', '{{$row->content}}', '{{json_encode($row->offeredAnswer)}}')
			</script>
				
			@endforeach
		</form>
		
		</div>
	</div>

@endsection
@push('scripts-bottom')
	<x-modal></x-modal>
@endpush