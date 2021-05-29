<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title font-weight-semibold">@isset($item) Edit @else Tambah @endisset Group Pertanyaan</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <form action="" id="modal-form">
                @csrf
                @isset($item)
                    @method('PUT')
                @endisset
                
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Group Pertanyaan</label>
                    <div class="col-md-9">
                        <input type="text" required class="form-control" @isset($item) value="{{$item->name}}" @endisset name="name" placeholder="Nama Group Pertanyaan">
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
               if(typeof instrumentDatatable !== 'undefined'){
                instrumentDatatable.ajax.reload();
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
               new PNotify({
                   title: data.title,
                   text: data.msg,
                   addclass: 'bg-success border-success',
               });

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

</script>