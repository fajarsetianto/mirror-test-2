@extends('layouts.responden.full')

@section('site-title','Sistem Monitoring dan Evaluasi - '. $form->name)
@push('css-top')
	<style>
		.sp-container{
			z-index: 9999;
		}
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
	<script src="{{asset('assets/global/js/plugins/tables/datatables/extensions/responsive.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/notifications/pnotify.min.js')}}"></script>
	<script src="{{asset('assets/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
	<script src="{{asset('assets/global/js/demo_pages/form_checkboxes_radios.js')}}"></script>
	<script>
		let formData = new FormData()
		let number = 1
		let countQuestion = [];
		save = () => {
			let csrf_token = "{{csrf_token()}}"
			formData.delete('_token')
			formData.append('_token', csrf_token)
			$(`.form-control`).serializeArray().forEach(function(elem){
				formData.delete(elem.name)
				formData.append(elem.name, elem.value)
			})
			$.ajax({
				url: '{{$url}}',
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
		}

        question = (id,typeClick, questionName, option, answer) => {
			let tempDataOption = ''
			let checked = ''
			let uniqId = (new Date()).getTime()+(Math.floor(Math.random() * 10000))
			let nameAnswer = ''
			let offerId = ''
			let fileName = ''
			
			if(option != null){
				dataOption = JSON.parse(option.replace(/&quot;/g, '\"'))
			}
			if(answer != 'null'){
				dataAnswer 	= JSON.parse(answer.replace(/&quot;/g, '\"'))
				nameAnswer 	= dataAnswer.answer
				offerId 	= dataAnswer.offered_answer_id
				fileName 	= typeof nameAnswer.split('-')[2] != undefined ? nameAnswer.split('-')[2] : ''
			}

			if(countQuestion.indexOf(id) !== -1 && id != null){
				if(typeClick == 'kotak centang') {
					checkboxHandling(offerId)
				} 
				if (offerId == null && (typeClick != 'singkat' || typeClick != 'paraghraf')){
					inputOtherHandling(nameAnswer,id)
				}
				return
			}

			let row = number - 1

			countQuestion.push(id)

			if(typeClick == 'singkat'){
				
				type = `
					<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
						<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
						<input type="text" class="alpaca-control form-control" value="${nameAnswer}" name="answer_${row}" autocomplete="off">
					</div>
				`
			} else if (typeClick == 'paraghraf'){

					type = `
					<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
						<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
						<textarea rows="5" name="answer_${row}" cols="5" class="form-control">${nameAnswer}</textarea>
					</div>
					
					`
			} else if (typeClick == 'pilihan ganda' || typeClick == 'pilihan-ganda'){
				icon = 'icon-circle'

				dataOption.forEach((element, key) => {
					checked = (offerId == element.id) ? 'checked' : ''
					tempDataOption += `
					<div class="form-check">
						<label class="form-check-label">
							<input type="radio" class="form-control form-check-input-styled" ${checked} 
								onchange="otherOptionRadio('option-answer-${number}', '${element.value}', ${id})"
								name="answer_option_${row}" value="${element.value}__${element.id}" name="stacked-radio-left" data-fouc>
								${element.value}
						</label>
					</div>
					`
				});

				type = `
				<div class="form-group mt-2" id="option-answer-${number}">
					${tempDataOption}
				</div>
				`
				
			} else if (typeClick == 'kotak' || typeClick == 'kotak centang'){
				icon = 'icon-checkbox-unchecked'

				dataOption.forEach((element,key) => {
					checked = (offerId == element.id) ? 'checked' : ''
					tempDataOption += `
					<div class="form-check">
						<label class="form-check-label">
							<input type="checkbox" id="checkbox-${number}-${element.id}" 
							name="answer_option_${row}_${key}" ${checked} 
							onchange="otherOptionCheckbox('option-answer-${number}', '${element.value}', 'checkbox-${number}-${element.id}', ${id})" 
							value="${element.value}__${element.id}" 
							class="form-control form-check-input-styled" data-fouc>
							${element.value}
						</label>
					</div>
					`
				});

				type = `
				<div class="form-group mt-2" id="option-answer-${number}">
					${tempDataOption}
				</div>
				`
			} else if (typeClick == 'dropdown') {
				icon = 'icon-circle-down2'
				
				dataOption.forEach(element => {
					checked = (offerId == element.id) ? 'selected' : ''
					tempDataOption += `
						<option ${checked} value="${element.value}__${element.id}">${element.value}</option>
					`
				});

				type = `
				<div class="form-group mt-2" id="option-answer-${number}">
					<select data-placeholder="Select option" 
						name="answer_option_${row}" 
						onchange="otherOptionDropdown('option-answer-${number}', this, ${id})"
						class="form-control form-control-select2">
						<option>Select your option</option>
						${tempDataOption}
					</select>
				</div>
				`

			} else if (typeClick == 'file-upload' || typeClick == 'file upload'){
				type = `
					<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
						<label class="pt-2 control-label d-block alpaca-control-label">Berkas File Upload</label>
						<div tabindex="500" class="btn btn-light text-left text-primary btn-file">
							<i class="icon-upload4 mr-2"></i>
							<span class="hidden-xs">File Upload</span>
							<input type="file" class="file-input" id="files-${uniqId}" name="file_${row}" onchange="upload(${row},${uniqId},$(this).val())" data-show-caption="true" data-show-upload="true" data-fouc>
						</div>
						<span class='ml-1 label label-info' id="upload-file-info-${uniqId}">
							<a target="_blank" href="{{route('respondent.form.question.show',[$instrument->id])}}?file=${fileName}">${fileName}</a>
						</span>
					</div>
				`
			}

			let urlActionUpdate = ''
			$(`#form-question`).append(`
				<div id="form-card">
					<div class="card border-left-teal">
						<div class="card-body">
							<div class="row">
								<div class="col-lg-1">
									<span class="question-number">${number}.</span>
								</div>
								<div class="col-lg-11">
									<div class="d-flex ">
										<label>${questionName}</label>
									</div>
									${type}
								</div>
							</div>
							<div id="field-other" class="row"></div>
							
						</div>
					</div>
				</div>
			`)
			number++
		}

		upload = (row,uniqId,name) => {
			$(`#upload-file-info-${uniqId}`).html(name.split('\\').pop())
			let file = $(`#files-${uniqId}`).prop('files')[0]
			formData.delete(`file_${row}`)
			formData.append(`file_${row}`, file)
		}
		
		checkboxHandling = (id) => {
			$(`#checkbox-${number-1}-${id}`).prop('checked', true);
		}

		inputOtherHandling = (nameAnswer, id) => {
			
			$(`#option-answer-${number-1}`).append(`
				<input id="option-answer-${number-1}-other" type="text" name="option_other_${id}" 
					value="${nameAnswer}" class="alpaca-control form-control mt-2 input" placeholder="Jawaban Lainnya" autocomplete="off">
			`);
			console.log(`#option-answer-${number-1}`)
		}
		
		otherOptionCheckbox = (id,name, optionId, questionId) => {
			if(name.toLowerCase().trim() == 'lainnya'){
				if($(`#${optionId}`).prop('checked')){
					if($(`#${id}-other`).length){
						$(`#${id}-other`).removeClass('d-none')
					} else {
						$(`#${id}`).append(`
							<input id="${id}-other" type="text" name="option_other_${questionId}" class="alpaca-control form-control mt-2 input" placeholder="Jawaban Lainnya" autocomplete="off">
						`)
					}
				} else {
					$(`${id}-other`).val('')
					$(`#${id}-other`).addClass('d-none')
				}
			} else {
				$(`#${id}-other`).val('')
				$(`#${id}-other`).addClass('d-none')
			}
		}

		otherOptionRadio = (id,name, questionId) => {
			if(name.toLowerCase().trim() == 'lainnya'){
				if($(`#${id}`).prop('checked', true)){
					if($(`#${id}-other`).length){
						$(`#${id}-other`).removeClass('d-none')
					} else {
						$(`#${id}`).append(`
							<input id="${id}-other" type="text" name="option_other_${questionId}" class="alpaca-control form-control mt-2 input" placeholder="Jawaban Lainnya" autocomplete="off">
						`)
					}
				} else {
					$(`#${id}-other`).val('')
					$(`#${id}-other`).addClass('d-none')
				}
			} else {
				$(`#${id}-other`).val('')
				$(`#${id}-other`).addClass('d-none')
			}
		}

		otherOptionDropdown = (id,elem, questionId) => {
			if(typeof elem.value.split('__')[0] !== 'undefined'){
				let name = elem.value.split('__')[0]
				if(name.toLowerCase().trim() == 'lainnya'){
					if($(`#${id}-other`).length){
						$(`#${id}-other`).removeClass('d-none')
					} else {
						$(`#${id}`).append(`
							<input id="${id}-other" type="text" name="option_other_${questionId}" class="alpaca-control form-control mt-2 input" placeholder="Jawaban Lainnya" autocomplete="off">
						`)
					}
				} else {
					$(`#${id}-other`).val('')
					$(`#${id}-other`).addClass('d-none')
				}
			} else {
				$(`#${id}-other`).val('')
				$(`#${id}-other`).addClass('d-none')
			}
		}
	</script>
@endpush

@section('page-header')
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title">
                <div class="row">
                    <div class="col-md-2">
                        <h2><i class="icon-arrow-left52 mr-2"></i></h2>
                    </div>
                    <div class="col-md-10">                    
                        <div class="row">   
                            <div class="col-md-12">
                                <h4><span class="font-weight-semibold">{{$form->name}}</span></h4>
                            </div>
                            <div class="col-md-12">
                                <span>{{$instrument->name}}</span>
                            </div>
                        </div>
                    </div>
                </div>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex">
					
					<button id="save" onclick="save()" class="mr-3 px-4 btn bg-purple-400 mx-y"><i class="mi-description"></i> <span>Simpan</span></button>
				</div>
			</div>
		</div>	
	</div>
	{{ Breadcrumbs::render('responden.home.form.question',$instrument) }}	
@endsection

@section('content')
<div class="content">
<div class="container">	
		<div class="row">
			<div class="col-lg-12" >
				<div class="card border-top-success">
					<div class="page-header ">
						<div class="page-header-content">
							<div class="page-title">
								<div class="instrument-header d-flex">
									<h4><span class="font-weight-semibold">{{$instrument->name}}</span></h4>
								</div>
								<div class="instrument-description">
									<p class="text-secondary">{{$instrument->description}}</p>
								</div>
							</div>
						</div>	
					</div>
				</div>
			</div>
		
			<div id="content" class="col-md-12">
				<form id="form-question">
					
				</form>
				@if($user->answers()->byInstrumentId($instrument->id)->get()->count() < 1):
					@foreach($instrument->questions()->get() as $question)
						<script>
							question('{{strtolower($question->id)}}','{{strtolower($question->questionType->name)}}', '{{$question->content}}', '{{json_encode($question->offeredAnswer)}}', '{{json_encode($question->userAnswerRespondent)}}')
						</script>
					@endforeach
				@else
					@foreach($user->answers()->byInstrumentId($instrument->id)->get() as $row)
						<script>
							question('{{strtolower($row->question->id)}}','{{strtolower($row->question->questionType->name)}}', '{{$row->question->content}}', '{{json_encode($row->question->offeredAnswer()->byInstrumentId($instrument->id)->get())}}', '{!!json_encode($row)!!}')
						</script>
					@endforeach
				@endif
			</div>
		</div>
	</div>
</div>


@endsection