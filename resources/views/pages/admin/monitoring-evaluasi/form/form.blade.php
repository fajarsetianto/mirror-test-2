<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title font-weight-semibold">@isset($item) Edit @else Buat @endisset Form Monitoring & Evaluasi</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <form action="" id="modal-form">
                @csrf
                @isset($item)
                    @method('PUT')
                @endisset
                
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Judul Form</label>
                    <div class="col-md-9">
                        <input type="text" required class="form-control" @isset($item) value="{{$item->name}}" @endisset name="name" placeholder="Judul Form Monitoring dan Evaluasi">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">
                        Deskripsi Form <br>
                        <small>(Optional)</small>
                    </label>
                    <div class="col-md-9">
                        <textarea name="description" class="form-control" cols="30" rows="5" placeholder="Deskripsi Form Monitoring dan Evaluasi">@isset($item){{$item->description}}@endisset</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">
                        Waktu Pelaksanaan Supervisi
                    </label>
                    <div class="col-md-9">
                        <label class="col-form-label">Waktu Mulai</label>
                        <div class="input-group">
                            <input type="text" required class="form-control pickadate" value="@isset($item) {{$item->supervision_start_date->format('Y/m/d')}} @else {{Carbon\Carbon::now()->format('Y/m/d')}} @endisset" name="supervision_start_date" placeholder="">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-calendar5"></i></span>
                            </span>
                        </div>
                        <label class="col-form-label">Waktu Selesai</label>
                        <div class="input-group">
                            <input type="text" required class="form-control pickadate" value="@isset($item){{$item->supervision_end_date->format('Y/m/d')}} @else {{Carbon\Carbon::now()->format('Y/m/d')}} @endisset" name="supervision_end_date" placeholder="">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-calendar5"></i></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">
                        Kategori
                    </label>
                    <div class="col-md-9">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-primary @isset($item) {{$item->category == 'satuan pendidikan' ? 'active' : '' }}  @else active  @endisset">
                                <input type="radio" name="category" autocomplete="off"  @isset($item) {{$item->category == 'satuan pendidikan' ? 'checked' : '' }}  @else checked @endisset value="satuan pendidikan">
                                Satuan Pendidikan
                            </label>
                            <label class="btn btn-primary @isset($item) {{$item->category == 'non satuan pendidikan' ? 'active' : '' }} @endisset">
                                <input type="radio" name="category" autocomplete="off" @isset($item) {{$item->category == 'non satuan pendidikan' ? 'checked' : '' }} @endisset value="non satuan pendidikan">
                                Non Satuan Pendidikan
                            </label>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-center">
                    @isset($item)
                        <button class="btn bg-warning" type="submit"><i class="icon-pencil font-size-base mr-1"></i> Update</button>
                    @else
                        <button class="btn bg-success" type="submit"><i class="icon-checkmark3 font-size-base mr-1"></i> Tambahkan</button>
                    @endisset
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("#modal-form").on('submit', function (e) {
       e.preventDefault();
       var el = $(this);
       var context = this;
       el.prop('disabled', true);

       setTimeout(function(){el.prop('disabled', false); }, 3000);

       $('button[type="submit"]', this).html('<i class="icon-spinner2 spinner"></i> Please wait...');

       $.ajax({
           url: "{{$url}}",
           type: "POST",
           data: new FormData(this),
           processData: false,
           contentType: false,
           success: function (data) {
               
               $('.modal').modal('hide');
               
               if(typeof instanceDatatable !== 'undefined'){
				    instanceDatatable.ajax.reload();
                    new PNotify({
                        title: data.title,
                        text: data.msg,
                        addclass: 'bg-success border-success',
                    });
                    var redirectUrl = "{{route('monev.form.instrument.index',['$1'])}}";
                    window.location.replace(redirectUrl.replace("%241",data.item.id));
			   }else{
                location.reload();
               }
               

           },
           error: function (data) {
               if(data.status == 422){
                   $('.text-help').remove();
                   $.each( data.responseJSON.errors, function( key, value) {
                       $('[name="'+key+'"]').parent().append(
                           $('<small class="text-help text-danger d-block w-100">').html(value[0])
                       )
                   });
                   new PNotify({
                       title: data.responseJSON.message,
                       text: 'please check your input',
                       addclass: 'bg-danger border-danger',
                   });
               }else{
                   new PNotify({
                       title: data.statusText,
                       text: data.responseJSON.message,
                       addclass: 'bg-danger border-danger',
                   });
               }
               $('button[type="submit"]', context).html('Save <i class="icon-paperplane ml-2"></i> ');

           }
       })

       return false;

   });
   $('.pickadate').pickadate({
        monthsFull: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        weekdaysShort: ['Min','Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', ],
        today: 'Hari Ini',
        format: 'yyyy/mm/dd',
        formatSubmit: 'yyyy/mm/dd',
        selectYears: true,
        selectMonths: true,
        clear: '',
        close: '',
    });

</script>