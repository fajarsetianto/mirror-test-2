@extends('layouts.full')

@section('site-title',$instrument->name)
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
		.border-top-success{
			border-top: 15px solid #26a69a;
		}
	</style>
@endpush
@push('scripts-top')
	<script src="{{asset('assets/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/loaders/blockui.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/pnotify.min.js')}}"></script>
    <script src="{{asset('assets/global/js/plugins/pickers/color/spectrum.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/sweet_alert.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/sweet_alert.min.js')}}"></script>
	<script>
		status = true

		$(document).ready(function(){
			published()
		});

		published = () => {
			let published = {{$form->isPublished()}}
			if(published){
				$('.published').addClass('d-none')
			}
		}
		
		removeOption = (questionId,uniqId) => {
			let number = 1
			$(`#row-${uniqId}`).remove()
			$(`.remove-field-${questionId}`).addClass('d-none')
			$(`.option-question-${questionId}`).each((key, elem) => {
				$(`.option-number-${questionId}`).eq(key).text(`Opsi ${number++}`)
			})
		}

		addOptions = (icon,uniqId, valueOption=null, score=null, questionId=0) => {
			newUniqId = (new Date()).getTime()
			optionNumber = ++($(`.option-${uniqId}`).length)
			optionAnother = $(`.option-another-${uniqId}`).length
			$(`.remove-field-${uniqId}`).removeClass('d-none')

			if(optionNumber == 2){
				$(`#row-option-${uniqId}`).append(`
					<div class="col-md-1 ml-0 pl-0">
						<button type="button" onclick="removeOption(${uniqId},${uniqId})" class="remove-field-${uniqId} btn btn-icon rounded-round ${questionId != 0 ? 'd-none' : ''}"><i class="icon-cross2"></i></button>
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
								<input class="alpaca-control form-control flex-1 mr-3 option-answer-${uniqId}" ${questionId != 0  ? 'readonly' : ''}  required value="${valueOption == null ? '' : valueOption}" name="option_answer[]" placeholder="Opsi Jawaban">   
							</div>
							<div class="col-md-1 ml-0 pl-0">
								<button type="button" onclick="removeOption(${uniqId},${newUniqId})" class="remove-field-${uniqId} btn btn-icon rounded-round ${questionId != 0 ? 'd-none' : ''}"><i class="icon-cross2"></i></button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 ml-0 pl-0">
								<label  class="pt-2 control-label alpaca-control-label font-weight-bold">Bobot</label> 
								<input class="alpaca-control form-control flex-1 mr-3 score-${uniqId}" ${questionId != 0  ? 'readonly' : ''}  required value="${score == null ? '' : score}" name="score[]" placeholder="Bobot Nilai">   
							</div>
						</div>
					</div>
				
			</div>
			`

			$(`#form-group-${uniqId}`).append(data)
		}

		addOptionAnother = (icon, uniqId, score=null, questionId=0) => {
			
			$(`.remove-field-${uniqId}`).removeClass('d-none')
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
									<button type="button" onclick="removeOption(${uniqId},${newUniqId})" class="remove-field-${uniqId} btn btn-icon rounded-round ${questionId != 0 ? 'd-none' : ''}"><i class="icon-cross2"></i></button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 ml-0 pl-0">
									<label  class="pt-2 control-label alpaca-control-label font-weight-bold">Bobot</label> 
									<input class="alpaca-control form-control flex-1 mr-3 score-${uniqId}" ${questionId != 0  ? 'readonly' : ''} required value="${score == null ? '' : score}" name="score[]" placeholder="Bobot Nilai">   
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
			let urlActionDelete = `${'{{route('admin.monev.form.instrument.question.destroy',[$form->id, $instrument->id,'question-id'])}}'.replace('question-id',y)}`
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
                    if (result.value) {
                        $.ajax({
                            url: urlActionDelete,
                            type : "DELETE",
                            data : {'_method' : 'DELETE', '_token' : csrf_token},
                            success:function(data){
                                new PNotify({
                                    title: data.title,
                                    text: data.msg,
                                    addclass: 'bg-success border-success',
                                });
								cancel(x)
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

		cancel = (uniqId) => {
			status = true
			$(`#form-card-${uniqId}`).remove()

			let number = 1;
			$('.question-number').each((key, elem) => {
				$('.question-number').eq(key).text(`${number++}.`)
			})
		}

		edit = (uniqId) => {
			status = false
			$(`#save-cancel-${uniqId}`).removeClass('d-none')
			$(`#cancel-${uniqId}`).addClass('d-none')
			$(`#add-option-${uniqId}`).removeClass('d-none')
			$(`#question-input-${uniqId}`).attr('readonly',false)
			$(`.option-answer-${uniqId}`).attr('readonly',false)
			$(`.score-${uniqId}`).attr('readonly',false)
			$(`.remove-field-${uniqId}`).removeClass('d-none')
		}

		question = (typeClick, questionName = null, option =null, questionId=0) => {
			let type =''
			let questionType=''
			let addOption = ''
			let countOption = 0
			let number = $('.question-number').length + 1
			let uniqId = (new Date()).getTime()
			status = questionId == 0 ? false : true

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
					<div>
						<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
						<textarea rows="5" disabled cols="5" class="form-control" placeholder="Jawaban ${questionType}"></textarea>
					</div>
				</div>
				`
			} else if (typeClick == 'pilihan ganda' || typeClick == 'pilihan-ganda'){
				questionType = 'Pilihan Ganda'
				icon = 'icon-circle'
				countOption = 1
				type = `
				<div id="form-group-${uniqId}" class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
					<div>
						<label  class="pt-2 control-label alpaca-control-label">Opsi Jawaban</label>
						<div class="row mt-2 option-${uniqId} option-question-${uniqId}" id="row-${uniqId}">
							<div class="col-md-2 pr-0 mr-0">
								<i class="${icon}"></i> 
								<span class="option-number-${uniqId}">Opsi 1</span>
							</div>
							<div class="col-md-10 ml-0 pl-0">
								<div class="row" id="row-option-${uniqId}">
									<div class="col-md-11 ml-0 pl-0">
										<input class="alpaca-control form-control flex-1 mr-3 option-answer-${uniqId}" required name="option_answer[]" placeholder="Opsi Jawaban">   
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 ml-0 pl-0">
										<label  class="pt-2 control-label alpaca-control-label font-weight-bold">Bobot</label> 
										<input class="alpaca-control form-control flex-1 mr-3 score-${uniqId}" required name="score[]" type="number" placeholder="Bobot Nilai">   
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				`

				addOption = `
					<div class="row ${questionId != 0 ? 'd-none' : ''}" id="add-option-${uniqId}">
						<div class="col-lg-11 ml-auto mt-2">
							<span onclick="addOptions('${icon}',${uniqId})" class="text-secondary cursor"><i class="icon-plus-circle2 text-primary"></i> Tambah Opsi </span>
							atau
							<span onclick="addOptionAnother('${icon}',${uniqId})" class="text-primary cursor">tambah "Lainnya" <span>
						</div>
					</div>
				`
				
			} else if (typeClick == 'kotak' || typeClick == 'kotak centang'){
				questionType = 'Kotak Centang'
				icon = 'icon-checkbox-unchecked'
				countOption = 1
				type = `
				<div id="form-group-${uniqId}" class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
					<div>
						<label class="pt-2 control-label alpaca-control-label">Opsi Jawaban</label>
						<div class="row mt-2 option-${uniqId} option-question-${uniqId}" id="row-${uniqId}">
							<div class="col-md-2 pr-0 mr-0">
								<i class="${icon}"></i> 
								<span class="option-number-${uniqId}">Opsi 1</span>
							</div>
							<div class="col-md-10 ml-0 pl-0">
								<div class="row" id="row-option-${uniqId}">
									<div class="col-md-11 ml-0 pl-0">
										<input class="alpaca-control form-control flex-1 mr-3 option-answer-${uniqId}" required name="option_answer[]" required  placeholder="Opsi Jawaban">   
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 ml-0 pl-0">
										<label  class="pt-2 control-label alpaca-control-label font-weight-bold">Bobot</label> 
										<input class="alpaca-control form-control flex-1 mr-3 score-${uniqId}" required name="score[]" type="number" placeholder="Bobot Nilai">   
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				`

				addOption = `
						<div class="row ${questionId != 0 ? 'd-none' : ''}" id="add-option-${uniqId}">
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
					<div>
						<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
						<div class="row mt-2 option-${uniqId} option-question-${uniqId}" id="row-${uniqId}">
							<div class="col-md-2 pr-0 mr-0">
								<i class="${icon}"></i> 
								<span class="option-number-${uniqId}">Opsi 1</span>
							</div>
							<div class="col-md-10 ml-0 pl-0">
								<div class="row" id="row-option-${uniqId}">
									<div class="col-md-11 ml-0 pl-0">
										<input class="alpaca-control form-control flex-1 mr-3 option-answer-${uniqId}" required name="option_answer[]" required placeholder="Opsi Jawaban">   
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 ml-0 pl-0">
										<label  class="pt-2 control-label alpaca-control-label font-weight-bold">Bobot</label> 
										<input class="alpaca-control form-control flex-1 mr-3 score-${uniqId}" required name="score[]" type="number" placeholder="Bobot Nilai">   
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				`

				addOption = `
						<div class="row ${questionId != 0 ? 'd-none' : ''}" id="add-option-${uniqId}">
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

			let urlActionUpdate = `${'{{route('admin.monev.form.instrument.question.update',[$form->id, $instrument->id,'question-id'])}}'.replace('question-id',questionId)}?_method=PUT`
			$('#content').append(`
				<div id="form-card-${uniqId}">
				@csrf
				<input type="hidden" id="count-option-${uniqId}" name="count_option[]" value="${countOption}">
				<input type="hidden" name="question_type[]" value="${questionType}">
						<div class="card border-left-teal">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-1">
										<span class="question-number">${number}.</span>
									</div>
									<div class="col-lg-11">
										<div class="d-flex ">
											<label>Pertanyaan - ${questionType}</label>
											<div class="question-action ml-auto">
												<a onclick="edit(${uniqId})" id="edit-${uniqId}" class="${questionId == 0 ? 'd-none' : ''} published mr-2 text-dark cursor"><i class="icon-pencil"></i></a>
												<a onclick="destroy(${uniqId},'${questionId}')" id="trash-${uniqId}" class="${questionId == 0 ? 'd-none' : ''} published mr-2 text-dark cursor"><i class="icon-trash-alt"></i></a>
											</div>
										</div>
										<input id="question-input-${uniqId}" class="alpaca-control form-control flex-1 mr-3" ${questionId != 0 ? 'readonly' : ''} required name="question[]" value="${questionName == null ? '' : questionName}" placeholder="Pertanyaan - ${questionType}">
										
										${type}
									</div>
								</div>
								<div id="field-other-${uniqId}" class="row"></div>
								${addOption}
								
								<div class="row ${questionId != 0 ? 'd-none' : ''}" id="save-cancel-${uniqId}">
									<div class="col-lg-6 ml-auto text-right">
										<button class="btn bg-danger text-left mb-3" id="cancel-${uniqId}" onclick="cancel(${uniqId})">Batal</button>
										<button class="btn bg-success text-left mb-3" id="save-${uniqId}"
											onclick="return save(${uniqId}, '${urlActionUpdate}')">Simpan</button>
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
						addOptionAnother(icon,uniqId, `${element.score}`, questionId)
					} else {
						addOptions(icon,uniqId, `${element.value}`, `${element.score}`, questionId)
					}
				});
			}
			if(status){
				$(`.remove-field-${uniqId}`).addClass('d-none')
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
					new PNotify({
						title: data.title,
						text: data.msg,
						addclass: 'bg-success border-success',
					});
					location.reload();
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
		}

		save = (uniqId, url) => {
			let formData = new FormData()
			status = true
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
					new PNotify({
						title: data.title,
						text: data.msg,
						addclass: 'bg-success border-success',
					});
					$(`#trash-${uniqId}`).removeClass('d-none')
					$(`#edit-${uniqId}`).removeClass('d-none')
					$(`#save-cancel-${uniqId}`).addClass('d-none')
					$(`.remove-field-${uniqId}`).addClass('d-none')
					$(`#question-input-${uniqId}`).attr('readonly',true)
					$(`.option-answer-${uniqId}`).attr('readonly',true)
					$(`.score-${uniqId}`).attr('readonly',true)
					$(`#add-option-${uniqId}`).addClass('d-none')
					$(`#trash-${uniqId}`).attr('onclick', `destroy(${uniqId}, '${data.item.question}')`)

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
	{{ Breadcrumbs::render('admin.monev.forms.form.question',$form, $instrument) }}	
@endsection

@section('content') 
	@if (Session::has('message'))
		<div class="alert alert-info alert-styled-left alert-dismissible">
			<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
			{{ Session::get('message') }}
		</div>
	@endif

	<div class="row">
		<div class="col-lg-3 @if($form->isPublished()) d-none @endif">
			<div class="card mb-0 pb-2">
				<div class="card-header bg-teal-400 text-white header-elements-inline">
					<h6 class="card-title">ACTION</h6>
				</div>
				<div class="card-body">
					<button class="btn btn-block btn-success text-left" onclick="component('addQuestion',`{{route('admin.monev.form.instrument.question.create',[$form->id, $instrument->id])}}`)"><i class="icon-bubble-lines3 mr-2"></i> Tambah Pertanyaan</button>
				</div>
			</div>
			<div class="card mt-0">
				<div class="card-body">
					<form method="POST" action="{{route('admin.monev.form.instrument.question.changestatus',[$form->id,$instrument->id])}}" class="form-inline">
						@csrf
						<input type="hidden" name="status" value="draft">
						<button class="btn btn-block bg-purle text-left mb-3" type="submit"><i class="icon-file-text3 mr-2"></i>Tandai Sebagai Draft</button>
					</form>
					<form method="POST" action="{{route('admin.monev.form.instrument.question.changestatus',[$form->id,$instrument->id])}}" class="form-inline">
						@csrf
						<input type="hidden" name="status" value="ready">
						<button class="btn btn-block btn-primary text-left mb-5" type="submit"><i class="icon-file-text mr-2"></i>Tandai Sudah Selesai</button>
					</form>
					{{-- <button class="btn btn-block bg-purle text-left mb-3" onclick="saveDraft('{{route('admin.monev.form.instrument.question.store',[$form->id, $instrument->id])}}')"><i class="icon-file-text3 mr-2"></i> Simpan Sebagai Draft</button> --}}
					{{-- <button class="btn btn-block btn-primary text-left mb-5" onclick="component('public','{{route('admin.monev.form.instrument.create',[$form->id])}}')"><i class="icon-file-text mr-2"></i> Publikasikan</button> --}}
				</div>
			</div>
		</div>
		<div class="@if($form->isPublished()) col-lg-12 @else col-lg-9 @endif" >
		<form action="#" id="content">
			@csrf
			<div class="card border-top-success">
				<div class="page-header">
					<div class="page-header-content">
						<div class="page-title">
							<div class="instrument-header d-flex">
								<h4><span class="font-weight-semibold">{{$instrument->name}}</span></h4>
								<div class="instrument-action ml-auto">
								@if(!$form->isPublished())
									<a href="#" onclick="component('instrument',`{{route('admin.monev.form.instrument.edit',[$form->id,$instrument->id])}}`)" class="mr-"><i class="icon-pencil mr-1"></i> Edit</a>
								@endif
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
					question('{{strtolower($row->questionType->name)}}', '{{$row->content}}', '{{json_encode($row->offeredAnswer)}}', '{{$row->id}}')
				</script>
			@endforeach
		</form>
		
		</div>
	</div>

@endsection
@push('scripts-bottom')
	<x-modal></x-modal>
@endpush