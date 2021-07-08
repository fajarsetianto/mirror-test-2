<div class="row">
    <div class="col-md-6">
        <div class="card border-left-teal">
            <div class="card-header">
                <span class="text-muted">Responden</span> 
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-1">
                        <span class="question-number">{{$number}}</span>
                    </div>
                    <div class="col-lg-11">
                        <div class="d-flex ">
                            <label>{{$item->content}}</label>
                        </div>
                        <div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
                            <label class="pt-2 control-label alpaca-control-label">Jawaban</label>
                            @if($item->relationLoaded('userAnswers'))
                                @if($item->userAnswers->isNotEmpty())
                                    <p>
                                        {{$item->userAnswers->first()->answer}}
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div id="field-other" class="row"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-left-teal">
            <div class="card-header">
                <span class="text-muted">Petugas</span> 
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-1">
                        <span class="question-number">{{$number}}</span>
                    </div>
                    <div class="col-lg-11">
                        <div class="d-flex ">
                            <label>{{$item->content}}</label>
                        </div>
                        <div class="form-group alpaca-field alpaca-field-text alpaca-optional alpaca-autocomplete alpaca-edit alpaca-top alpaca-field-valid" data-alpaca-field-id="alpaca5" data-alpaca-field-path="/" data-alpaca-field-name="">
                            <label class="pt-2 control-label alpaca-control-label">Jawaban</label>
                            @if($item->relationLoaded('officerAnswer'))
                                @if($item->officerAnswer->isNotEmpty())
                                    <p>
                                        {{$item->officerAnswer->first()->answer}}
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div id="field-other" class="row"></div>
            </div>
        </div>
    </div>
    @if($item->relationLoaded('officerAnswer'))
        @if($item->officerAnswer->where('discrepancy','!=',null)->isNotEmpty())
            <div class="col-md-12">
                <div class="card border-left-teal">
                    <div class="card-header">
                        <span class="text-muted">Perbedaan</span> 
                    </div>
                    <div class="card-body">
                        <p>
                            {{$item->officerAnswer->where('discrepancy','!=',null)->first()->discrepancy}}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

