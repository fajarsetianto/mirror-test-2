<div class="card">
    <div class="card-header bg-teal-600">
        <h3 class="font-weight-bold mb-0">{{$target->form->name}}</h3>
    </div>
    <div class="card-body">
        <p>{{$target->form->description}}</p>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-6">
                <h6 class="font-weight-bold">Informasi Pengisi Form Monitoring dan Evaluasi</h6>
                <div class="form-group row mb-0">
                    <label class="col-md-3 col-6 font-weight-bold">Kategori Sasaran Monitoring</label>
                    <div class="col-md-9 col-6"><span class="badge badge-warning">{{$target->form->category}}</span></div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-md-3 col-6 font-weight-bold">Pengisi Form Monev</label>
                    <div class="col-md-9 col-6">{{$target->type}}</div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-md-3 col-6 font-weight-bold">Sasaran Monitoring</label>
                    <div class="col-md-9 col-6">{{$target->institutionable->name}}</div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-md-3 col-6 font-weight-bold">Reponden</label>
                    <div class="col-md-9 col-6">{{$target->respondent->name}} ({{$target->institutionable->email}})</div>
                </div>
                @if($target->officers->isNotEmpty())
                    <div class="form-group row mb-0">
                        <label class="col-md-3 col-6 font-weight-bold">Petugas Monev</label>
                        <div class="col-md-9 col-6">
                            @include('layouts.parts.officers',['officers' => $user->target->officers])
                        </div>
                    </div>
                @endif
                <div class="form-group row mb-0">
                    <label class="col-md-3 col-6 font-weight-bold">Waktu Mulai</label>
                    <div class="col-md-9 col-6">{{$target->form->supervision_start_date->format('d/m/Y')}}</div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-md-3 col-6 font-weight-bold">Waktu Selesai</label>
                    <div class="col-md-9 col-6">{{$target->form->supervision_end_date->format('d/m/Y')}} <span class="badge badge-danger">Sisa Waktu : {{$target->form->supervisionDaysRemainingRender()}} Hari</span></div>
                </div>        
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-6 text-center">
                        <div class="card">
                            <div class="card-header">
                                Maksimal Skor
                            </div>
                            <div class="card-body text-center">
                                <h4 style="font-size: 5rem">
                                    450
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 text-center">
                        <div class="card">
                            <div class="card-header">
                                Total Skor
                            </div>
                            <div class="card-body text-center">
                                <h4 style="font-size: 5rem">
                                    450
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($target->form->instruments as $instrument)
    @include('layouts.parts.instrument', [$instrument])
@endforeach