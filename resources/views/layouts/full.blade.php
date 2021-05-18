@include('layouts.parts.head')

<body class="navbar-top">
    @include('layouts.parts.navbar')
    <!-- Page content -->
	<div class="page-content">

        @include('layouts.parts.sidebar')

        <!-- Main content -->
		<div class="content-wrapper">
            
            @include('layouts.parts.page-header',['breadcrumb' => isset($breadcrumb) ? $breadcrumb : null])
            <div class="content">
                @yield('content')
            </div>
            
            @include('layouts.parts.footer')

        </div>
        <!-- /Main content -->

    </div>
    <!-- /page content -->
    @stack('scripts-bottom')
</body>