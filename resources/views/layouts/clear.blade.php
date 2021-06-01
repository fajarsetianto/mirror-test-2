@include('layouts.parts.head')
<body @isset($navbarTop)
class="navbar-top"
@endisset>
	@yield('navbar')
	<!-- Page content -->
	<div class="page-content">
		@yield('sidebar')
		<!-- Main content -->
		<div class="content-wrapper">
			@yield('page-header')
            @yield('content')
            @yield('footer')
		</div>
		<!-- /main content -->
		
	</div>
	<!-- /page content -->
	@stack('scripts-bottom')
</body>
</html>