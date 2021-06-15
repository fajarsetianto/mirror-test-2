@extends('layouts.responden.full')
@section('site-title','Dashboard')
@section('content')
<!-- Content area -->
			<div class="content container">
				<!-- Login card -->
				<div class="card">
                    <div class="card-header bg-teal-600">
                        <h3 class="font-weight-bold mb-0">{{$user->target->form->name}}</h3>
                    </div>
                    <div class="card-body">
                        <p>{{$user->target->form->description}}</p>
                    </div>
                    <div class="card-footer">
                        <h6 class="font-weight-bold">Informasi Pengisi Form Monitoring dan Evaluasi</h6>
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-6 font-weight-bold">Kategori Sasaran Monitoring</label>
                            <div class="col-md-9 col-6"><span class="badge badge-warning">{{$user->target->form->category}}</span></div>
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-6 font-weight-bold">Pengisi Form Monev</label>
                            <div class="col-md-9 col-6">{{$user->target->type}}</div>
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-6 font-weight-bold">Sasaran Monitoring</label>
                            <div class="col-md-9 col-6">{{$user->target->institutionable->name}}</div>
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-6 font-weight-bold">Reponden</label>
                            <div class="col-md-9 col-6">{{$user->name}} ({{$user->target->institutionable->email}})</div>
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-6 font-weight-bold">Petugas Monev</label>
                            <div class="col-md-9 col-6">{{$user->target->officerName()}}</div>
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-6 font-weight-bold">Waktu Mulai</label>
                            <div class="col-md-9 col-6">{{$user->target->form->supervision_start_date->format('d/m/Y')}}</div>
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-6 font-weight-bold">Waktu Selesai</label>
                            <div class="col-md-9 col-6">{{$user->target->form->supervision_end_date->format('d/m/Y')}} <span class="badge badge-danger">Sisa Waktu : {{$user->target->form->supervisionDaysRemaining()}} Hari</span></div>
                        </div>

                        <div class="form-group mb-0 text-center my-3">
                            <a href="{{route('respondent.form.index')}}" class="btn btn-lg bg-purple-600"><i class="mi-assignment"></i> Mulai</a>
                        </div> 
                
                    </div>
                </div>
				<!-- /login card -->

			</div>
			<!-- /content area -->
    
@endsection