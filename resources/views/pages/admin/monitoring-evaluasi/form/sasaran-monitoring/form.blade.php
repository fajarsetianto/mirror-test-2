<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title font-weight-semibold">@isset($item) Edit @else Tambah @endisset Sasaran Monitoring</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <form action="" id="modal-form">
                @csrf
                @isset($item)
                    @method('PUT')
                @endisset
                
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Pengisi Form</label>
                    <div class="col-md-9">
                        <select class="form-control select2" id="target-type" name="type">
                            <option value="" disabled selected>Pilih Tipe Pengisi Form</option>
                            <option value="responden" @isset($item) {{$item->type == 'responden' ?  'selected' : ''}} @endisset>responden</option>
                            <option value="petugas MONEV" @isset($item) {{$item->type == 'petugas MONEV' ?  'selected' : ''}} @endisset>petugas MONEV</option>
                            <option value="responden & petugas MONEV" @isset($item) {{$item->type == 'responden & petugas MONEV' ?  'selected' : ''}} @endisset>responden & petugas MONEV</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Pilih Lembaga</label>
                    <div class="col-md-9">
                        <select class="form-control select2" name="institution_id">
                            <option value="" disabled selected>Pilih Lembaga</option>
                            @foreach ($institutions as $institution)
                                <option value="{{$institution->id}}" @isset($item) {{$item->institution_id == $institution->id ?  'selected' : ''}} @endisset>{{$institution->name}}</option>
                            @endforeach
                        </select>
                        <div class="form-group text-center text-muted content-divider mb-0">
                            <span class="px-2">atau</span>
                        </div>
                        <button type="button" class="btn btn-light w-100" onclick="component('{{route('institution.non-satuan.create')}}')"><i class="mi-assignment-turned-in"></i> Tambah Lembaga Baru</button>
                    </div>
                </div>
                <div id="dynamic-input-wrapper">
                    @isset($item)
                        @if ($item->type != 'responden')
                            @include('pages.admin.monitoring-evaluasi.form.sasaran-monitoring.parts.petugas',['item' => $item])
                        @endif
                    @endisset
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
    $('.select2').select2();
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

   $('#target-type').change(function(){
       if($(this).val() === 'responden'){
           $('#dynamic-input-wrapper').html('')
       }else{
        //    if($('#dynamic-input-wrapper').html() == ''){
            getInput($(this).val())
        //    }
       }
   })

   function getInput(type){
					$.blockUI({ 
						message: '<i class="icon-spinner4 spinner"></i>',
						overlayCSS: {
							backgroundColor: '#1b2024',
							opacity: 0.8,
							zIndex: 1200,
							cursor: 'wait'
						},
						css: {
							border: 0,
							color: '#fff',
							padding: 0,
							zIndex: 1201,
							backgroundColor: 'transparent'
						},
					});

                    url = "{{route('monev.form.target.input',[$form->id])}}"

					$.get(url, function(data){
						$('#dynamic-input-wrapper').html(data);
					}).done(function() {
						$('.select2').select2();
                        
					})
					.fail(function() {
						alert( "Terjadi Kesalahan" );
					})
					.always(function() {
						$.unblockUI();
					});
				}
   

</script>