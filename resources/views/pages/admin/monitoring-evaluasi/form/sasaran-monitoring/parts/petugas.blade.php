@if($form->category == 'satuan pendidikan')
    satuan pendidikan avaliable soon
@else
    
    <div class="form-group row">
        <label class="col-md-3 col-form-label">Petugas Monev</label>
        <div class="col-md-9">
            <select class="form-control select2" name="officer_id" data-fouc>
                <option value="" disabled selected>Pilih Petugas Monev</option>
                @foreach (App\Models\User::all() as $user)
                    <option value="{{$user->id}}" @isset($item) {{$item->officer_id == $user->id ?  'selected' : ''}} @endisset>{{$user->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    
@endif

