<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title font-weight-semibold">@isset($item) Edit @else Tambah @endisset Admin</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <form action="" id="modal-form">
                @csrf
                @isset($item)
                    @method('PUT')
                @endisset
                
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Nama</label>
                    <div class="col-md-9">
                        <input type="text" required class="form-control" @isset($item) value="{{$item->name}}" @endisset name="name" placeholder="Nama Petugas">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Email</label>
                    <div class="col-md-9">
                        <input type="email" required class="form-control" @isset($item) value="{{$item->email}}" @endisset name="email" placeholder="Email Petugas">
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
               instanceDatatable.ajax.reload();
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