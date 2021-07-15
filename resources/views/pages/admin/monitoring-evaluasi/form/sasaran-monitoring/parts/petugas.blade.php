
            <div class="input-group mb-3">
                <span class="input-group-prepend">
                    <span class="input-group-text">
                        <input type="radio" name="officer_leader" required @isset($item)
                            value="{{$item->id}}"
                            @if($item->pivot->is_leader)
                                checked
                            @endif
                        @endisset> 
                        <label class="form-check-label ml-2" for="radio">Penanggung Jawab</label>
                    </span>
                </span>
                <div class="flex-fill">
                    <select class="form-control select2-officer" name="officers[]" data-fouc required>
                        @isset($item)
                            <option value="{{$item->id}}" selected="selected">{{$item->name}}</option>
                        @endisset
                    </select>
                </div>
                <span class="input-group-append">
                    <span class="input-group-text bg-pink border-pink text-white remove-input-group">
                        <i class="icon-trash"></i>
                    </span>
                </span>
            </div>

    
    

    


