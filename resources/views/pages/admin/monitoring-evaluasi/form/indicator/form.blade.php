<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title font-weight-semibold">@isset($item) Edit @else Tambah @endisset Indikator</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <form action="" id="modal-form">
                @csrf
                @isset($item)
                    @method('PUT')
                @endisset
                
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Bobot Nilai Minimum</label>
                    <div class="col-md-9">
                        <input type="number" required class="form-control" @isset($item) value="{{$item->minimum}}" @endisset name="minimum" placeholder="Bobot Nilai Minimum">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Bobot Nilai Maximum</label>
                    <div class="col-md-9">
                        <input type="number" required class="form-control" @isset($item) value="{{$item->maximum}}" @endisset name="maximum" placeholder="Bobot Nilai Maximum">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Warna Indikator</label>
                    <div class="col-md-9">
                        <input type="text" name="color" class="form-control colorpicker-palette" @isset($item) value="{{$item->color}}" @else value="rgb(0,0,0)" @endisset data-fouc>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Deskripsi</label>
                    <div class="col-md-9">
                        <input type="text" required class="form-control" @isset($item) value="{{$item->description}}" @endisset name="description" placeholder="Deskripsi Indikator">
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
               indicatorDatatable.ajax.reload();
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

   // Color palette
   var demoPalette = [
            ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
            ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
            ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
            ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
            ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
            ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
            ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
            ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
        ];
        $('.colorpicker-palette').spectrum({
            showPalette: true,
            showPaletteOnly: true,
            palette: demoPalette
        });

</script>