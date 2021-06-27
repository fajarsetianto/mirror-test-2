@extends('layouts.officer.full')

@section('site-title','Form Instrument Monitoring dan Evaluasi')
@push('css-top')
<style>
.border-top-success {
    border-top: 15px solid #26a69a;
}
.button {
    min-width: 200px;
    max-width: 200px;
}
</style>
@endpush
@push('scripts-top')
<script src="{{asset('assets/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('assets/global/js/plugins/tables/datatables/extensions/responsive.min.js')}}"></script>
<script src="{{asset('assets/global/js/plugins/notifications/pnotify.min.js')}}"></script>
<script src="{{asset('assets/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('assets/global/js/demo_pages/form_checkboxes_radios.js')}}"></script>
<script src="{{asset('assets/global/js/demo_pages/form_inputs.js')}}"></script>
<script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
<script>
	let formData = new FormData()

	save = () => {
		let csrf_token = "{{csrf_token()}}"
		formData.delete('_token')
		formData.append('_token', csrf_token)
		$(`.input`).serializeArray().forEach(function(elem){
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

	question = (typeClick, questionName, option, answer, number) => {
		let tempDataOption = ''
		let tempDataOptionResponden = ''
		let typeRespoden = ''
		let typeOfficer = ''
		let tempDataOptionOfficer = ''
		let uniqId = (new Date()).getTime()
		let checked = ''
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
		let row = number - 1
		if(typeClick == 'singkat'){
			questionType = 'Singkat'
			
			typeRespoden = `
				<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
					<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
					<input type="text" disabled class="alpaca-control form-control" value="${nameAnswer}" placeholder="Jawaban ${questionType}"  autocomplete="off">
				</div>
			`
			typeOfficer = `
				<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
					<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
					<input type="text" class="alpaca-control form-control input" name="answer_${row}"  placeholder="Jawaban ${questionType}"  autocomplete="off">
				</div>
			`
		} else if (typeClick == 'paraghraf'){
			questionType = 'Paraghraf'

			typeRespoden = `
				<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
					<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
					<textarea rows="5" disabled cols="5" class="form-control" placeholder="Jawaban ${questionType}">${nameAnswer}</textarea>
				</div>
			`
			typeOfficer = `
				<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
					<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
					<textarea rows="5" cols="5" name="answer_${row}" class="form-control input" placeholder="Jawaban ${questionType}"></textarea>
				</div>
			`
		} else if (typeClick == 'pilihan ganda'){
			questionType = 'Ganda'
			icon = 'icon-circle'

			dataOption.forEach((element, key) => {
				checked = (offerId == element.id) ? 'checked' : ''
				tempDataOptionResponden += `
				<div class="form-check">
					<label class="form-check-label">
						<input type="radio" class="form-control form-check-input-styled" ${checked} disabled data-fouc>
						${element.value}
					</label>
				</div>
				`
				tempDataOptionOfficer += `
				<div class="form-check">
					<label class="form-check-label">
						<input type="radio" class="form-control form-check-input-styled input" value="${element.value}__${element.id}" name="answer_option_${row}" data-fouc>
						${element.value}
					</label>
				</div>
				`
			});

			typeRespoden = `
				<div class="form-group">
					<label class="d-block">Opsi Jawaban</label>
					${tempDataOptionResponden}
				</div>
			`
			typeOfficer = `
				<div class="form-group">
					<label class="d-block">Opsi Jawaban</label>
					${tempDataOptionOfficer}
				</div>
			`
			
		} else if (typeClick == 'kotak' || typeClick == 'kotak centang'){
			questionType = 'Multiple Choice'
			icon = 'icon-checkbox-unchecked'
			
			dataOption.forEach(element => {
				checked = (offerId == element.id) ? 'checked' : ''
				tempDataOptionResponden += `
				<div class="form-check">
					<label class="form-check-label">
						<input type="checkbox" ${checked} class="form-control form-check-input-styled" disabled data-fouc>
						${element.value}
					</label>
				</div>
				`
				tempDataOptionOfficer += `
				<div class="form-check">
					<label class="form-check-label">
						<input type="checkbox" name="answer_option_${row}" value="${element.value}__${element.id}" class="form-control form-check-input-styled input" data-fouc>
						${element.value}
					</label>
				</div>
				`
			});

			typeRespoden = `
				<div class="form-group">
					<label class="d-block">Opsi Jawaban</label>
					${tempDataOptionResponden}
				</div>
			`
			typeOfficer = `
				<div class="form-group">
					<label class="d-block">Opsi Jawaban</label>
					${tempDataOptionOfficer}
				</div>
			`
		} else if (typeClick == 'dropdown') {
			questionType = 'Dropdown'
			icon = 'icon-circle-down2'

			dataOption.forEach(element => {
				checked = (offerId == element.id) ? 'selected' : ''
				tempDataOptionResponden += `
					<option ${checked} value="${element.value}">${element.value}</option>
				`
				tempDataOptionOfficer += `
					<option value="${element.value}__${element.id}">${element.value}</option>
				`
			});

			typeRespoden = `
				<div class="form-group">
					<label class="d-block">Opsi Jawaban</label>
					<select data-placeholder="Select option" disabled class="form-control form-control-select2">
						<option>Select your option</option>
						${tempDataOptionResponden}
					</select>
				</div>
			`
			typeOfficer = `
				<div class="form-group">
					<label class="d-block">Opsi Jawaban</label>
					<select data-placeholder="Select option" name="answer_option_${row}"  class="form-control form-control-select2 input">
						<option>Select your option</option>
						${tempDataOptionOfficer}
					</select>
				</div>
			`

		} else if (typeClick == 'file-upload' || typeClick == 'file upload'){
			questionType = 'File Upload'
			typeRespoden = `
				<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
					<label class="pt-2 control-label d-block alpaca-control-label">Berkas File Upload</label>
					<button disabled class="btn btn-light text-left mb-3 text-primary"><i class="icon-upload4 mr-2"></i>File Upload</button>
				</div>
			`
			typeOfficer = `
				<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
					<label class="pt-2 control-label d-block alpaca-control-label">Berkas File Upload</label>
					<div tabindex="500" class="btn btn-light text-left text-primary btn-file">
						<i class="icon-upload4 mr-2"></i>
						<span class="hidden-xs">File Upload</span>
						<input type="file" class="file-input" id="files-${number}" name="file_${row}" data-show-caption="true" data-show-upload="true" data-fouc>
					</div>
					<span class='ml-1 label label-info' id="upload-file-info-${number}">
						<a target="_blank" href="#"></a>
					</span>
				</div>
			`
		}

		$(`#question-officer`).append(`
			<div class="card card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="card border-left-teal">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12">
										<p class="text-secondary">Responden</p>
									</div>
									<div class="col-lg-1">
										<span class="question-number">${number}</span>
									</div>
									<div class="col-lg-11">
										<label>Pertanyaan - ${questionType}</label>
										<input class="alpaca-control form-control flex-1 mr-3" readonly value="${questionName}" placeholder="Pertanyaan - ${questionType}">
										${typeRespoden}
									</div>
								</div>
								<div id="field-other" class="row"></div>

							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card border-left-teal">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12">
										<p class="text-secondary">Petugas Monitoring dan Evaluasi</p>
									</div>
									<div class="col-lg-1">
										<span class="question-number">${number}</span>
									</div>
									<div class="col-lg-11">
										<label>Pertanyaan - ${questionType}</label>
										<input class="alpaca-control form-control flex-1 mr-3" readonly value="${questionName}" placeholder="Pertanyaan - ${questionType}">
										${typeOfficer}
									</div>
								</div>
								<div id="field-other" class="row"></div>

							</div>
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="">Perbedaan</label>
					<textarea rows="3" name="different_${row}" cols="3" class="form-control input" id="different" placeholder="Perbedaan"></textarea>
				</div>

			</div>
		`)
	}
	
	upload = (row,uniqId,name) => {
		$(`#upload-file-info-${uniqId}`).html(name.split('\\').pop())
		let file = $(`#files-${uniqId}`).prop('files')[0]
		formData.delete(`file_${row}`)
		formData.append(`file_${row}`, file)
	}
</script>
@endpush

@section('page-header')
<div class="page-header page-header-light">
    <div class="page-header-content">
        <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">
				{{$officerTarget->target->form->name}} - {{$item->name}}
            </h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <div class="header-elements border-top-0 bg-white pl-4 pt-0">
            <div class="d-flex">

                <button href="#" onclick="save()" class="mr-3 btn bg-indigo-400 mx-y button"><i class="mi-description"></i>
                    <span>Simpan Sebaga Draft</span>
				</button>
                <button href="#" class="btn btn-primary button"><i class="mi-assignment"></i> <span>Kirim</span></button>
            </div>
        </div>
    </div>
</div>
{{-- {{ Breadcrumbs::render('responden.home.form',$form) }} --}}
@endsection
@section('content')
<div class="content">

    <div class="card border-top-success">
        <div class="page-header">
            <div class="page-header-content">
                <div class="page-title">
                    <div class="instrument-header d-flex">
                        <h4><span class="font-weight-semibold">{{$item->name}}</span></h4>
                    </div>
                    <div class="instrument-description">
                        <p class="text-secondary">{{$item->description}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div id="question-officer"></div>
	@foreach($item->questions()->get() as $key => $row)
		<script>question('{{strtolower($row->questionType->name)}}', '{{$row->content}}', '{{json_encode($row->offeredAnswer)}}', '{{json_encode($row->userAnswer)}}', {{$key+1}})</script>
	@endforeach
</div>


@endsection