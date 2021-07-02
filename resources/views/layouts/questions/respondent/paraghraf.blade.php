<div class="card border-left-teal">
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
                    @if($item->userAnswers->isNotEmpty())
                    <p>
                        {{$item->userAnswers->first()->answer}}
                    </p>
                @endif
                </div>
            </div>
        </div>
        <div id="field-other" class="row"></div>
    </div>
</div>