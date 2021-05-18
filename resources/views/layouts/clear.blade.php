@include('layouts.parts.head')
<body>

	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

            @yield('content')
            
		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->
	@stack('scripts-bottom')
</body>
</html>