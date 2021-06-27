@if($notifications->isNotEmpty())
    <div class="dropdown-content-body dropdown-scrollable">
        <ul class="media-list">
            @foreach($notifications as $notification)
                <li class="media">
                    <div class="media-body">
                        <div class="media-title">
                            <a href="{{route($prefix_routes.'.notification.read',[$notification->id])}}">
                                <span class="font-weight-semibold">{{$notification->data['title']}}</span>
                                <span class="text-muted float-right font-size-sm">{{$notification->created_at->diffForHumans()}}</span>
                            </a>
                        </div>
                        <span class="text-muted">{{$notification->data['content']}}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="dropdown-content-footer justify-content-center p-0">
        <a href="{{route($prefix_routes.'.notification.markallread',[$guard])}}" class="bg-light text-grey w-100 py-2 text-center">Tandai semua sudah dibaca</a>
    </div>
@else
<div class="dropdown-content-body dropdown-scrollable">
    <div class="text-center p-3">
        Tidak ada notifikasi baru
    </div>

@endif