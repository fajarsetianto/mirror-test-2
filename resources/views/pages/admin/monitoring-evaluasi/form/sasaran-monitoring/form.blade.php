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
                        <select class="form-control select2target" id="target-type" name="type" required>
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
                        
                            <select class="form-control select2ajax" name="institutionable_id" required>
                                @isset($item)
                                    <option value="{{$item->institutionable->id}}" data-email="{{$item->institutionable->email}}" selected="selected">{{$item->institutionable->name}}</option>
                                @endisset
                            </select>
                        
                        @if($form->category == 'non satuan pendidikan')
                            <div class="form-group text-center text-muted content-divider mb-0">
                                <span class="px-2">atau</span>
                            </div>
                            <button type="button" class="btn btn-light w-100" onclick="component('{{route('admin.institution.non-satuan.create')}}')"><i class="mi-assignment-turned-in"></i> Tambah Lembaga Baru</button>
                        @endif
                    </div>
                </div>
                {{-- @if($form->category == 'satuan pendidikan') --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Email Lembaga</label>
                        <div class="col-md-9">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email Satuan Pendidikan" required @isset($item) @if($item->type != 'petugas MONEV' ) value="{{$item->respondent->email}}" @endif @endisset>
                        </div>
                    </div>
                {{-- @endif --}}
                <div id="dynamic-input-wrapper" @isset($item) @if($item->type == 'responden') style="display:none" @endif @else  style="display:none"  @endisset>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Petugas Monev</label>
                        <div class="col-md-9">
                            <div id="dynamic-input-group">
                                @isset($item)
                                    @if ($item->type != 'responden')
                                        @foreach ($item->officers as $officer)
                                            @include('pages.admin.monitoring-evaluasi.form.sasaran-monitoring.parts.petugas',['item' => $officer])    
                                        @endforeach
                                    @endif
                                @endisset
                            </div>
                            <button class="btn btn-success" type="button" onclick="getInput()">Tambah</button>
                        </div>
                    </div>
                    
                   
                </div>
                <div class="d-flex align-items-center justify-content-end">
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
    @isset($item)
        @if($item->type != 'responden')
            $('.select2-officer').select2({
                placeholder: 'Pilih Petugas',
                ajax: {
                    url: '{{route('admin.management-user.select2')}}',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        return query;
                    },
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                    return {
                        results:  $.map(data.data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        }),
                        pagination: {
                            more : data.current_page != data.last_page
                        }
                    };
                },
                cache: true
            }
        });
        reSyncRemoveButton();
        @endif
    @endisset
    $('.select2target').select2();
    $('.select2ajax').select2({
        minimumInputLength: 3,
        placeholder: 'Pilih Lembaga',
        ajax: {
            url: '{{$select2url}}',
            data: function (params) {
                var query = {
                    search: params.term,
                    page: params.page || 1
                }
                return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data.data, function (item) {
                        return {
                            text: item.name,
                            id: item.id,
                            email: item.email
                        }
                    }),
                    pagination: {
                        more : data.current_page != data.last_page
                    }
                };
            },
            cache: true,
            
        },
    });
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
            $('#dynamic-input-wrapper').hide()
            $('#dynamic-input-wrapper #dynamic-input-group select,#dynamic-input-wrapper #dynamic-input-group input').prop('required',false)
            $('#dynamic-input-wrapper #dynamic-input-group select,#dynamic-input-wrapper #dynamic-input-group input').removeAttr('name');
       }else{
            $('#dynamic-input-wrapper').show()
            $('#dynamic-input-wrapper #dynamic-input-group select,#dynamic-input-wrapper #dynamic-input-group input').prop('required',true)
            $('#dynamic-input-wrapper #dynamic-input-group select').attr('name','officers[]');
            $('#dynamic-input-wrapper #dynamic-input-group input').attr('name','officer_leader');
            if(!$('#dynamic-input-wrapper #dynamic-input-group .input-group').length){
                getInput()
            }
       }
   })
   
    $('body').on('click','.remove-input-group',function(){
        $(this).parents('.input-group').remove()
        reSyncRemoveButton();
    });

    function reSyncRemoveButton(){
        if($('#dynamic-input-wrapper #dynamic-input-group .input-group').length > 1){
            if($('#dynamic-input-wrapper #dynamic-input-group .input-group:first-child .input-group-append').length == 0){
                $('#dynamic-input-wrapper #dynamic-input-group .input-group:first-child').append(
                    $('<span>').addClass('input-group-append').append(
                        $('<span>').addClass('input-group-text bg-pink border-pink text-white remove-input-group').append(
                            $('<i>').addClass('icon-trash')
                        )
                    )
                )
            }
        }else{
            if($('#dynamic-input-wrapper #dynamic-input-group .input-group .input-group-append').length == 1){
                $('#dynamic-input-wrapper #dynamic-input-group .input-group .input-group-append' ).remove()
            }
        }
    }

    $('body').on('change','.select2-officer',function(){
        value = $(this).val();
        parent = $(this).parents('.input-group');
        $('input[type="radio"]',parent).val(value);

    })

    $('select[name="institutionable_id"]').on('change', function (e) {
        $('#email').val($(this).select2('data')[0].email)
    });

   function getInput(){
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

                    url = "{{route('admin.monev.form.target.input',[$form->id])}}"

					$.get(url, function(data){
                        $('#dynamic-input-group').append(data);
                        reSyncRemoveButton()
					}).done(function() {
						$('.select2-officer').select2({
                            placeholder: 'Pilih Petugas',
                            ajax: {
                                url: '{{route('admin.management-user.select2')}}',
                                data: function (params) {
                                    var query = {
                                        search: params.term,
                                        page: params.page || 1
                                    }
                                    return query;
                                },
                                dataType: 'json',
                                delay: 250,
                                processResults: function (data) {
                                    return {
                                        results:  $.map(data.data, function (item) {
                                            return {
                                            text: item.name,
                                            id: item.id
                                            }
                                        }),
                                        pagination: {
                                            more : data.current_page != data.last_page
                                        }
                                    };
                                },
                                cache: true
                            }
                        });
					})
					.fail(function() {
						alert( "Terjadi Kesalahan" );
					})
					.always(function() {
						$.unblockUI();
					});
				}
   


</script>