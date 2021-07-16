@extends('layouts.clear',['navbarTop' => true])

@section('navbar')
	@include('layouts.parts.navbar')
@endsection

@section('sidebar')
	@include('layouts.super-admin.sidebar')
@endsection

@section('footer')
	@include('layouts.parts.footer')
@endsection