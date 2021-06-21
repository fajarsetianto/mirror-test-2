<!-- Main sidebar -->
		<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				Navigation
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->


			<!-- Sidebar content -->
			<div class="sidebar-content">

				<!-- User menu -->
				<div class="sidebar-user">
					<div class="card-body">
						<div class="media d-flex align-items-center">
							<div class="mr-3">
								<a href="#"><img src="{{asset('assets/global/images/placeholders/placeholder.jpg')}}" width="38" height="38" class="rounded-circle" alt=""></a>
							</div>

							<div class="media-body ">
								<div class="media-title font-weight-semibold">{{ucfirst(auth()->user()->name)}}</div>
								{{-- <div class="font-size-xs opacity-50">
									<i class="icon-pin font-size-sm"></i> &nbsp;Santa Ana, CA
								</div> --}}
							</div>

							{{-- <div class="ml-3 align-self-center">
								<a href="#" class="text-white"><i class="icon-cog3"></i></a>
							</div> --}}
						</div>
					</div>
				</div>
				<!-- /user menu -->


				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- Main -->
						<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i></li>
						<li class="nav-item" >
							<a href="{{route('superadmin.dashboard')}}" class="nav-link {{ (request()->is('/superadmin')) ? 'active' : '' }}">
								<i class="mi-home"></i>
								<span>Dashboard</span>
							</a>
						</li>
						{{-- <li class="nav-item nav-item-submenu {{ (request()->is('monitoring-evaluasi/*')) ? 'nav-item-expanded nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="mi-find-in-page"></i> <span>Monitoring & Evaluasi</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts">
								<li class="nav-item"><a href="{{route('admin.monev.form.index')}}" class="nav-link {{ (request()->is('monitoring-evaluasi/form*')) ? 'active' : '' }}">Form MONEV</a></li>
								<li class="nav-item"><a href="{{route('admin.monev.inspection.index')}}" class="nav-link {{ (request()->is('monitoring-evaluasi/pemeriksaan*')) ? 'active' : '' }}">Pemeriksaan</a></li>
								<li class="nav-item"><a href="{{route('admin.monev.inspection-history.index')}}" class="nav-link {{ (request()->is('monitoring-evaluasi/riwayat-pemeriksaan*')) ? 'active' : '' }}">Riwayat Pemeriksaan</a></li>
								<li class="nav-item"><a href="{{route('admin.monev.indicator-report.index')}}" class="nav-link {{ (request()->is('monitoring-evaluasi/laporan-indikator*')) ? 'active' : '' }}">Laporan Indikator</a></li>
							</ul>
						</li> --}}
						<li class="nav-item nav-item-submenu {{ (request()->is('superadmin/management-user*')) ? 'nav-item-expanded nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="mi-people"></i> <span>Manajemen User</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts">
								<li class="nav-item"><a href="{{route('superadmin.management-user.admin.index')}}" class="nav-link {{ (request()->is('superadmin/management-user/admin*')) ? 'active' : '' }}">List Admin</a></li>
								<li class="nav-item"><a href="{{route('superadmin.management-user.officer.index')}}" class="nav-link {{ (request()->is('superadmin/management-user/petugas-monev*')) ? 'active' : '' }}">List Petugas Monev</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu {{ (request()->is('management-lembaga/*')) ? 'nav-item-expanded nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="mi-domain"></i> <span>Manajemen Lembaga</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts">
								<li class="nav-item"><a href="{{route('admin.institution.satuan.index')}}" class="nav-link {{ (request()->is('management-lembaga/satuan-pendidikan*')) ? 'active' : '' }}">Satuan Pendidikan</a></li>
								<li class="nav-item"><a href="{{route('admin.institution.non-satuan.index')}}" class="nav-link {{ (request()->is('management-lembaga/non-satuan-pendidikan*')) ? 'active' : '' }}">Non Satuan Pendidikan</a></li>
							</ul>
						</li>
						<li class="nav-item" >
							<a href="#" class="nav-link">
								<i class="icon-cog3"></i>
								<span>Setting</span>
							</a>
						</li>
						<!-- /page kits -->

					</ul>
				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->
			
		</div>
		<!-- /main sidebar -->