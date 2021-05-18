            @unless ($breadcrumbs->isEmpty())
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
						 @foreach ($breadcrumbs as $breadcrumb)

							@if (!is_null($breadcrumb->url) && !$loop->last)
								<li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
								<a href="{{ $breadcrumb->url }}" class="breadcrumb-item">
									{{-- <i class="icon-home2 mr-2"></i>  --}}
									{{ $breadcrumb->title }}
								</a>
							@else
								<span class="breadcrumb-item active">{{ $breadcrumb->title }}</span>
							@endif

						@endforeach
							
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
				@endunless