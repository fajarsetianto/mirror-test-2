@extends('layouts.full')

@section('site-title','Dashboard')

@push('scripts-top')
    
@endpush

@section('content')

<div class="card-body">
    <div class="row">
        <div class="col-sm-6 col-xl-4">
            <div class="card card-body has-bg-image bg-teal-600">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-enter6 icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h6 class="mb-0">{{$officerCount}}</h6>
                        <span class="text-uppercase font-size-xs">Jumlah Petugas Monev</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card card-body has-bg-image bg-teal-600">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-enter6 icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h6 class="mb-0">{{$formCount}}</h6>
                        <span class="text-uppercase font-size-xs">Jumlah Form</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card card-body has-bg-image bg-teal-600">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-enter6 icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h6 class="mb-0">{{$educationalCount}}</h6>
                        <span class="text-uppercase font-size-xs">Jumlah Lembaga Pendidikan</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card card-body has-bg-image bg-teal-600">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-enter6 icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h6 class="mb-0">{{$nonEducationalCount}}</h6>
                        <span class="text-uppercase font-size-xs">Jumlah Lembaga Non Satuan Pendidikan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection