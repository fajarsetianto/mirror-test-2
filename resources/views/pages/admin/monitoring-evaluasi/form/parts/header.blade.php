<div class="card">
	<div class="card-header bg-teal-400 text-white header-elements-inline">
		<h3 class="card-title font-weight-semibold">{{ strtoupper($form->name)}}</h3>
        @isset($editable)
			@if($form->isEditable())
				<div class="header-elements">
					<button type="button" onclick="component('{{route('admin.monev.form.edit',[$form->id])}}')" class="btn bg-success-400 btn-icon"><i class="icon-pencil"></i></button>
				</div>
			@endif
        @endisset
	</div>
	<div class="card-body">
		{{$form->description}}
	</div>
	<div class="card-footer d-flex justify-content-between">
		<div class="mr-4">
			<span class="font-weight-bold">Jumlah Pertanyaan </span>: <span class="badge badge-info">{{$form->questions->count()}}</span>
		</div>
		<div class="mr-4">
			<span class="font-weight-bold">Maksimal Skor</span>:  <span class="badge badge-info">{{$form->maxScore}}</span> 
		</div>
	</div>
	<div class="card-footer bg-white d-flex align-items-center">
		<div class="mr-4">
			<i class="mi-assignment-ind mi-2x mr-2 text-info"></i>
			<span class="font-weight-bold">Kategori Sasaran Monitoring </span>: {{$form->category}}
		</div>

		<div class="mr-4">
			<i class="mi-access-alarms mr-2 mi-2x text-success"></i>
			<span class="font-weight-bold">Waktu Mulai </span>: {{$form->supervision_start_date->format('d/m/Y')}}
		</div>
		<div class="mr-4">
			<i class="mi-alarm-off mr-2 mi-2x text-danger"></i>
			<span class="font-weight-bold">Waktu Selesai </span>: {{$form->supervision_end_date->format('d/m/Y')}}
		</div>
	</div>
	
</div>