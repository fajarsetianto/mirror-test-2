@if($officers->isNotEmpty())
    @foreach($officers as $officer)
        <span>{{$loop->iteration}}. {{$officer->name}}</span> @if($officer->pivot->is_leader) <span class="badge badge-info">Leader</span> @endif <br>
    @endforeach
@else
    -
@endif