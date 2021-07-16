<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark fixed-top">
    <div class="navbar-brand">
        <a href="index.html" class="d-inline-block">
            <img src="{{asset('assets/global/images/logo_light.png')}}" alt="">
        </a>
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
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>

            
        </ul>

        {{-- <span class="badge bg-success ml-md-3 mr-md-auto">Online</span> --}}

        <ul class="navbar-nav ">

            <li class="nav-item dropdown">
                <a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
                    <i class="mi-notifications-none" style="font-size: 1.25rem"></i>
                    <span class="d-md-none ml-2">Notifikasi</span>
                    <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0">{{auth('officer')->user()->unReadNotifications()->count()}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
                    <div class="dropdown-content-header">
                        <span class="font-weight-semibold">Notifikasi</span>
                    </div>
                    @include('layouts.parts.notification',[
                        'notifications' => auth('officer')->user()->unReadNotifications,
                        'prefix_routes' => 'officer',
                        'guard' => 'officer'
                    ])
                </div>
               
            </li>

            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                    <img src="{{asset('assets/global/images/placeholders/placeholder.jpg')}}" class="rounded-circle mr-2" height="34" alt="">
                    <span>{{ucfirst(auth('officer')->user()->name)}}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
                    <a href="#" class="dropdown-item"><i class="icon-coins"></i> My balance</a>
                    <a href="#" class="dropdown-item"><i class="icon-comment-discussion"></i> Messages <span class="badge badge-pill bg-blue ml-auto">58</span></a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
                    
                    <form id="logout-form" action="{{ route('officer.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->