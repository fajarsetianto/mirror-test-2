@extends('layouts.responden.full')
@section('site-title','Terimakasih')
@section('content')
<!-- Content area -->
			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">
                <div>
                    <h3 class="text-center font-weight-bold">
                        Sistem Monitoring dan Evaluasi Pembangunan Gedung <br>
					    Direktorat Jenderal Pendidikan Vokasi
					</h3>
					@if($completed)
						<div class="card mb-0">
							<div class="card-header bg-teal-400 text-white text-center">
								Terimakasih
							</div>
							<div class="card-body text-center">
								<div class="m-3"><i class="icon-cloud-check text-green-700" style="font-size: 5rem"></i></div>
								Jawaban anda sudah kami terima
							</div>
						</div>
					@else
						<div class="card mb-0">
							<div class="card-header bg-danger text-white text-center">
								Terimakasih
							</div>
							<div class="card-body text-center">
								<div class="m-3"><i class="icon-notification2 text-danger" style="font-size: 5rem"></i></div>
								Anda sudah tidak bisa mengisi Form ini lagi
							</div>
						</div>
					@endif
                </div>
			</div>
			<!-- /content area -->
@endsection
