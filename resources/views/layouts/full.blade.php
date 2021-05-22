@include('layouts.parts.head')

<body class="navbar-top">
    @include('layouts.parts.navbar')
    <!-- Page content -->
	<div class="page-content">

        @include('layouts.parts.sidebar')

        <!-- Main content -->
		<div class="content-wrapper">
            
            @yield('page-header')
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