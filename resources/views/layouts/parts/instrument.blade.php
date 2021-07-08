<div class="card border-top-success">
    <div class="page-header ">
        <div class="page-header-content">
            <div class="page-title">
                <div class="instrument-header d-flex">
                    <h4><span class="font-weight-semibold">{{$instrument->name}}</span></h4>
                </div>
                <div class="instrument-description">
                    <p class="text-secondary">{{$instrument->description}}</p>
                </div>
            </div>
        </div>	
    </div>
</div>

@foreach ($instrument->questions as $question)
    @include('layouts.parts.question', ['question' => $question, 'type' => str_replace(' ', '',$target->type)])
@endforeach