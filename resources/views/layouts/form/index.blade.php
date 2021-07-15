{{-- @extends('layouts.clear')

@section('site-title','Preview '.$form->name)

@section('content')    
    
    <div class="card">
        <div class="card-header bg-teal-600">
            <h3 class="font-weight-bold mb-0">{{$form->first()->name}}</h3>
        </div>
        <div class="card-body">
            <p>{{$form->description}}</p>
            
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-6">
                    <h6 class="font-weight-bold">Informasi Pengisi Form Monitoring dan Evaluasi</h6>
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Kategori Sasaran Monitoring</div>
                        <div class="col-md-7"><span class="badge badge-warning">{{$form->category}}</span></div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Pengisi Form Monev</div>
                        <div class="col-md-7">{{$target->type}}</div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Sasaran Monitoring</div>
                        <div class="col-md-7">{{$target->institutionable->name}}</div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Reponden</div>
                        <div class="col-md-7">{{$target->respondent->name}} ({{$target->institutionable->email}})</div>
                    </div>
                    @if($target->officers->isNotEmpty())
                        <div class="form-group row mb-0">
                            <div class="col-md-5 font-weight-bold">Petugas Monev</div>
                            <div class="col-md-7">
                                @include('layouts.parts.officers',['officers' => $target->officers])
                            </div>
                        </div>
                    @endif
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Waktu Mulai</div>
                        <div class="col-md-7">{{$target->form->supervision_start_date->format('d/m/Y')}}</div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Waktu Selesai</div>
                        <div class="col-md-7">{{$target->form->supervision_end_date->format('d/m/Y')}} <span class="badge badge-danger">Sisa Waktu : {{$target->form->supervisionDaysRemainingRender()}} Hari</span></div>
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
    @foreach ($form->instruments as $instrument)
        @include('layouts.parts.instrument',[$instrument]);
    @endforeach

@endsection --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{'Monev '.$form->name.' pada '.$target->institutionable->name}}</title>
    <style>
        /*! CSS Used from: http://codenesia-5.test/assets/css/bootstrap.min.css */
        *,
        ::after,
        ::before {
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Roboto, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: .8125rem;
            font-weight: 400;
            line-height: 1.5385;
            color: #333;
            text-align: left;
            background-color: #f5f5f5;
        }

        h3,
        h4,
        h6 {
            margin-top: 0;
            margin-bottom: .625rem;
        }

        p {
            margin-top: 0;
            margin-bottom: .625rem;
        }

        ul {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        label {
            display: inline-block;
            margin-bottom: .5rem;
        }

        h3,
        h4,
        h6 {
            margin-bottom: .625rem;
            font-weight: 400;
            line-height: 1.5385;
        }

        h3 {
            font-size: 1.3125rem;
        }

        h4 {
            font-size: 1.1875rem;
        }

        h6 {
            font-size: .9375rem;
        }

        .row {
            display: flex;
            display: -webkit-box;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -0.625rem;
            margin-left: -0.625rem;
        }

        .col-6,
        .col-lg-1,
        .col-lg-11,
        .col-md-5,
        .col-md-6,
        .col-md-7 {
            position: relative;
            width: 100%;
            padding-right: .625rem;
            padding-left: .625rem;
        }

        .col-6 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }

        @media (min-width:768px) {
            .col-md-5 {
                -ms-flex: 0 0 41.66667%;
                flex: 0 0 41.66667%;
                max-width: 41.66667%;
            }
            .col-md-6 {
                -ms-flex: 0 0 50%;
                flex: 0 0 50%;
                max-width: 50%;
            }
            .col-md-7 {
                -ms-flex: 0 0 58.33333%;
                flex: 0 0 58.33333%;
                max-width: 58.33333%;
            }
        }

        @media (min-width:992px) {
            .col-lg-1 {
                -ms-flex: 0 0 8.33333%;
                flex: 0 0 8.33333%;
                max-width: 8.33333%;
            }
            .col-lg-11 {
                -ms-flex: 0 0 91.66667%;
                flex: 0 0 91.66667%;
                max-width: 91.66667%;
            }
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .card {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: .1875rem;
        }

        .card-body {
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: 1.25rem;
        }

        .card-header {
            padding: .9375rem 1.25rem;
            margin-bottom: 0;
            background-color: rgba(0, 0, 0, .02);
            border-bottom: 1px solid rgba(0, 0, 0, .125);
        }

        .card-header:first-child {
            border-radius: .125rem .125rem 0 0;
        }

        .card-footer {
            padding: .9375rem 1.25rem;
            background-color: rgba(0, 0, 0, .02);
            border-top: 1px solid rgba(0, 0, 0, .125);
        }

        .card-footer:last-child {
            border-radius: 0 0 .125rem .125rem;
        }

        .badge {
            display: inline-block;
            padding: .3125rem .375rem;
            font-size: 75%;
            font-weight: 500;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .125rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        @media (prefers-reduced-motion:reduce) {
            .badge {
                transition: none;
            }
        }

        .badge:empty {
            display: none;
        }

        .badge-secondary {
            color: #fff;
            background-color: #777;
        }

        .badge-success {
            color: #fff;
            background-color: #4caf50;
        }

        .badge-info {
            color: #fff;
            background-color: #00bcd4;
        }

        .badge-warning {
            color: #fff;
            background-color: #ff7043;
        }

        .badge-danger {
            color: #fff;
            background-color: #f44336;
        }

        .d-flex {
            display: -ms-flexbox!important;
            display: flex!important;
        }

        .mb-0 {
            margin-bottom: 0!important;
        }

        .pt-2 {
            padding-top: .625rem!important;
        }

        .text-center {
            text-align: center!important;
        }

        .font-weight-bold {
            font-weight: 700!important;
        }

        .text-secondary {
            color: #777!important;
        }

        .text-success {
            color: #4caf50!important;
        }

        .text-muted {
            color: #999!important;
        }

        @media print {
            *,
            ::after,
            ::before {
                text-shadow: none!important;
                box-shadow: none!important;
            }
            h3,
            p {
                orphans: 3;
                widows: 3;
            }
            h3 {
                page-break-after: avoid;
            }
            body {
                min-width: 992px!important;
            }
            .badge {
                border: 1px solid #000;
            }
        }


        /*! CSS Used from: http://codenesia-5.test/assets/css/bootstrap_limitless.min.css */

        body {
            position: relative;
        }

        h3,
        h4,
        h6 {
            letter-spacing: -.015em;
        }

        .font-weight-semibold {
            font-weight: 500;
        }

        @media (max-width:767.98px) {
            .form-group [class*=col-md-]:not([class*=col-form-label])+[class*=col-md-] {
                margin-top: 1.25rem;
            }
        }

        .card {
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .card-header:not([class*=bg-]):not([class*=alpha-]) {
            background-color: transparent;
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
            border-bottom-width: 0;
        }

        .card-header:not([class*=bg-]):not([class*=alpha-])+.card-body {
            padding-top: 0;
        }

        [class*=bg-]:not(.bg-transparent):not(.bg-light):not(.bg-white):not(.btn-outline):not(body) {
            color: #fff;
        }


        /*! CSS Used from: http://codenesia-5.test/assets/css/layout.min.css */

        body {
            min-height: 100vh;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            -ms-flex: 1;
            flex: 1;
        }

        .page-content {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-positive: 1;
            flex-grow: 1;
        }

        .content-wrapper {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            -ms-flex: 1;
            flex: 1;
            overflow: auto;
        }

        .page-title {
            padding: 2rem 0;
            position: relative;
        }

        .page-title h4 {
            margin: 0;
        }

        .page-header-content {
            position: relative;
            padding: 0 1.25rem;
        }


        /*! CSS Used from: http://codenesia-5.test/assets/css/colors.min.css */

        .text-success {
            color: #4caf50;
        }

        .border-top-success {
            border-top-color: #4caf50;
        }

        .border-left-teal {
            border-left-color: #009688;
        }

        .bg-teal-600 {
            background-color: #00897b;
        }


        /*! CSS Used fontfaces */

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 100;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOkCnqEu92Fr1MmgVxFIzIFKw.woff2) format('woff2');
            unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 100;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOkCnqEu92Fr1MmgVxMIzIFKw.woff2) format('woff2');
            unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 100;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOkCnqEu92Fr1MmgVxEIzIFKw.woff2) format('woff2');
            unicode-range: U+1F00-1FFF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 100;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOkCnqEu92Fr1MmgVxLIzIFKw.woff2) format('woff2');
            unicode-range: U+0370-03FF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 100;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOkCnqEu92Fr1MmgVxHIzIFKw.woff2) format('woff2');
            unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 100;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOkCnqEu92Fr1MmgVxGIzIFKw.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 100;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOkCnqEu92Fr1MmgVxIIzI.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 300;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmSU5fCRc4EsA.woff2) format('woff2');
            unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 300;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmSU5fABc4EsA.woff2) format('woff2');
            unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 300;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmSU5fCBc4EsA.woff2) format('woff2');
            unicode-range: U+1F00-1FFF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 300;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmSU5fBxc4EsA.woff2) format('woff2');
            unicode-range: U+0370-03FF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 300;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmSU5fCxc4EsA.woff2) format('woff2');
            unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 300;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmSU5fChc4EsA.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 300;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmSU5fBBc4.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOmCnqEu92Fr1Mu72xKOzY.woff2) format('woff2');
            unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOmCnqEu92Fr1Mu5mxKOzY.woff2) format('woff2');
            unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOmCnqEu92Fr1Mu7mxKOzY.woff2) format('woff2');
            unicode-range: U+1F00-1FFF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOmCnqEu92Fr1Mu4WxKOzY.woff2) format('woff2');
            unicode-range: U+0370-03FF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOmCnqEu92Fr1Mu7WxKOzY.woff2) format('woff2');
            unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOmCnqEu92Fr1Mu7GxKOzY.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOmCnqEu92Fr1Mu4mxK.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 500;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmEU9fCRc4EsA.woff2) format('woff2');
            unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 500;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmEU9fABc4EsA.woff2) format('woff2');
            unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 500;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmEU9fCBc4EsA.woff2) format('woff2');
            unicode-range: U+1F00-1FFF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 500;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmEU9fBxc4EsA.woff2) format('woff2');
            unicode-range: U+0370-03FF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 500;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmEU9fCxc4EsA.woff2) format('woff2');
            unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 500;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmEU9fChc4EsA.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 500;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmEU9fBBc4.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 700;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmWUlfCRc4EsA.woff2) format('woff2');
            unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 700;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmWUlfABc4EsA.woff2) format('woff2');
            unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 700;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmWUlfCBc4EsA.woff2) format('woff2');
            unicode-range: U+1F00-1FFF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 700;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmWUlfBxc4EsA.woff2) format('woff2');
            unicode-range: U+0370-03FF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 700;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmWUlfCxc4EsA.woff2) format('woff2');
            unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 700;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmWUlfChc4EsA.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 700;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmWUlfBBc4.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 900;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmYUtfCRc4EsA.woff2) format('woff2');
            unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 900;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmYUtfABc4EsA.woff2) format('woff2');
            unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 900;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmYUtfCBc4EsA.woff2) format('woff2');
            unicode-range: U+1F00-1FFF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 900;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmYUtfBxc4EsA.woff2) format('woff2');
            unicode-range: U+0370-03FF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 900;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmYUtfCxc4EsA.woff2) format('woff2');
            unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 900;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmYUtfChc4EsA.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 900;
            src: url(https://fonts.gstatic.com/s/roboto/v27/KFOlCnqEu92Fr1MmYUtfBBc4.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header bg-teal-600">
            <h3 class="font-weight-bold mb-0">{{$form->name}}</h3>
        </div>
        <div class="card-body">
            <p>{{$form->description}}</p>
            
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-6">
                    <h6 class="font-weight-bold">Informasi Pengisi Form Monitoring dan Evaluasi</h6>
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Kategori Sasaran Monitoring</div>
                        <div class="col-md-7"><span class="badge badge-warning">{{$form->category}}</span></div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Pengisi Form Monev</div>
                        <div class="col-md-7">{{$target->type}}</div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Sasaran Monitoring</div>
                        <div class="col-md-7">{{$target->institutionable->name}}</div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Reponden</div>
                        <div class="col-md-7">
                        @if($target->respondent != null)
                            {{$target->respondent->name}} 
                        @endif
                        ({{$target->institutionable->email}})</div>
                    </div>
                    @if($target->officers->isNotEmpty())
                        <div class="form-group row mb-0">
                            <div class="col-md-5 font-weight-bold">Petugas Monev</div>
                            <div class="col-md-7">
                                @include('layouts.parts.officers',['officers' => $target->officers])
                            </div>
                        </div>
                    @endif
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Waktu Mulai</div>
                        <div class="col-md-7">{{$target->form->supervision_start_date->format('d/m/Y')}}</div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Waktu Selesai</div>
                        <div class="col-md-7">{{$target->form->supervision_end_date->format('d/m/Y')}} <span class="badge badge-danger">Sisa Waktu : {{$target->form->supervisionDaysRemainingRender()}} Hari</span></div>
                    </div>        
                    <div class="form-group row mb-0">
                        <div class="col-md-5 font-weight-bold">Jumlah Pertanyaan</div>
                        <div class="col-md-7"><span class="badge badge-info">{{$form->questions->count()}}</span></div>
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
                                        {{$form->maxScore}}
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
                                        {{$target->score()}}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>
    @foreach ($form->instruments as $instrument)
        @include('layouts.parts.instrument',[$instrument])
    @endforeach
</body>
</html>



