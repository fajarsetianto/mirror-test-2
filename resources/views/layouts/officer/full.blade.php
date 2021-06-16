@extends('layouts.clear',['navbarTop' => true])

@section('navbar')
	@include('layouts.officer.navbar')
@endsection

@section('sidebar')
	@include('layouts.officer.sidebar')
@endsection

@section('footer')
	@include('layouts.parts.footer')
@endsection