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
	<script src="{{asset('assets/global/js/demo_pages/form_layouts.js')}}"></script>
	<script>
		question = (typeClick, questionName, option, key, number) => {
			let tempDataOption = ''
			if(option != null){
				dataOption = JSON.parse(option.replace(/&quot;/g, '\"'))
			}

			if(typeClick == 'singkat'){
				questionType = 'Singkat'
				
				type = `
					<div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
						<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
						<input type="text" disabled class="alpaca-control form-control" placeholder="Jawaban ${questionType}"  autocomplete="off">
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

				dataOption.forEach(element => {
					tempDataOption += `
					<div class="form-check">
						<input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
						<label class="form-check-label" for="flexRadioDefault2">
							${element.value}
						</label>
					</div>
					`
				});

				type = `
				<div class="form-group">
					<label class="d-block">Opsi Jawaban</label>
					${tempDataOption}
				</div>
				`

				addOption = `
						<div class="row">
							<div class="col-lg-11 ml-auto mt-2">
								<span class="text-secondary cursor"><i class="icon-plus-circle2 text-primary"></i> Tambah Opsi </span>
								atau
								<span class="text-primary cursor">tambah "Lainnya" <span>
							</div>
						</div>
				`
				
			} else if (typeClick == 'multiple' || typeClick == 'multiple choice'){
				questionType = 'Multiple Choice'
				icon = 'icon-checkbox-unchecked'
				countOption = 1
				dataOption.forEach(element => {
					tempDataOption += `
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
						<label class="form-check-label" for="flexCheckChecked">
							${element.value}
						</label>
					</div>
					`
				});

				type = `
				<div class="form-group">
					<label class="d-block">Opsi Jawaban</label>
					${tempDataOption}
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
				<div id="form-group" class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
						<label class="pt-2 control-label alpaca-control-label">Jawaban</label>
						<div class="row mt-2 option option-question" id="row">
							<div class="col-md-2 pr-0 mr-0">
								<i class="${icon}"></i> 
								<span class="option-number">Opsi 1</span>
							</div>
							<div class="col-md-10 ml-0 pl-0">
								<div class="row" id="row-option">
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

			let urlActionUpdate = ''
			$(`#content-${key}`).append(`
				<div id="form-card">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-1">
										<span class="question-number">${number}.</span>
									</div>
									<div class="col-lg-11">
										<div class="d-flex ">
											<label>Pertanyaan - ${questionType}</label>
										</div>
										<input class="alpaca-control form-control flex-1 mr-3" readonly value="${questionName}" placeholder="Pertanyaan - ${questionType}">
										
										${type}
									</div>
								</div>
								<div id="field-other" class="row"></div>
								
							</div>
						</div>
				</div>
			`)
		}

	</script>
@endpush

@section('page-header')
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Preview</span> - {{$form->name}}</h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>
		</div>	
	</div>
@endsection

@section('content')

	<div class="row">
		@foreach($form->instruments()->get() as $key => $row)
		<div class="col-lg-12" >
			<div class="card">
				<div class="page-header">
					<div class="page-header-content">
						<div class="page-title">
							<div class="instrument-header d-flex">
								<h4><span class="font-weight-semibold">{{$row->name}}</span></h4>
							</div>
							<div class="instrument-description">
								<p class="text-secondary">{{$row->description}}</p>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	
		<div id="content-{{$key+1}}" class="col-md-12">
			@foreach($row->questions()->get() as $number => $question)
				<script>
					question('{{strtolower($question->questionType->name)}}', '{{$question->content}}', '{{json_encode($question->offeredAnswer)}}', {{$key+1}}, {{$number+1}})
				</script>
			@endforeach
		</div>
		@endforeach
	</div>

@endsection
@push('scripts-bottom')
	<x-modal></x-modal>
@endpush