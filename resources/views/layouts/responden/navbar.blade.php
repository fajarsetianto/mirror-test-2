<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-light fixed-top">
    <div class="navbar-brand">
        <a class="d-inline-block" href="{{route('respondent.dashboard')}}"><h6 class="font-weight-bold mb-0 text-black-50">Sistem Monitoring dan Evaluasi Pembangunan Gedung</h6></a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse " id="navbar-mobile">
        <ul class="navbar-nav mr-md-auto">
            
        </ul>

        {{-- <span class="badge bg-success ml-md-3 mr-md-auto">Online</span> --}}

        <ul class="navbar-nav ">
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                    <img src="{{asset('assets/global/images/placeholders/placeholder.jpg')}}" class="rounded-circle mr-2" height="34" alt="">
                    <span>{{ucfirst(auth()->user()->name)}}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
                    
                    <form id="logout-form" action="{{ route('respondent.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->