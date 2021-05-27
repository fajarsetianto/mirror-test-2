<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title font-weight-semibold"> Pilih Jenis Pertanyaan</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <form action="#" id="modal-form">
                <label class="text-secondary">TEXT</label>
                <div class="form-group row pl-2">
                    <div class="col-md-6 cursor" id="singkat" onclick="clicked('singkat')">
                        <i class="icon-paragraph-left3 mr-2"></i> Jawaban Singkat
                    </div>
                    <div class="col-md-6 cursor" id="paraghraf" onclick="clicked('paraghraf')">
                        <i class="icon-indent-increase2 mr-2"></i> Paraghraf
                    </div> 
                </div>
                <label class="text-secondary">PILIHAN</label>
                <div class="form-group row pl-2">
                    <div class="col-md-6 cursor" id="ganda" onclick="clicked('ganda')">
                        <i class="icon-radio-checked mr-2"></i> Multiple Choice
                    </div>
                    <div class="col-md-6 cursor" id="multiple" onclick="clicked('multiple')">
                        <i class="icon-checkbox-checked2 mr-2"></i> Checkbox
                    </div>
                </div>
                <div class="form-group row pl-2">
                    <div class="col-md-4 cursor" id="dropdown" onclick="clicked('dropdown')">
                        <i class="icon-circle-down2 mr-2"></i> Dropdown
                    </div>
                </div>
                <label class="text-secondary">FILE UPLOAD</label>
                <div class="form-group row pl-2">
                    <div class="col-md-4 cursor"id="file-upload" onclick="clicked('file-upload')">
                        <i class="icon-cloud-upload2 mr-2"></i> File Upload
                    </div>
                </div>
                <input type="hidden" id="id-checked">
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
    prevIdClicked = ''
    numberOption = 1
    clicked = (idClicked) =>{
        $('#id-checked').val(idClicked)
        if(prevIdClicked != ''){
            $(`#${prevIdClicked}`).removeClass('text-success')
        }
        $(`#${idClicked}`).addClass('text-success')
        prevIdClicked = idClicked
    }


    $("#modal-form").on('submit', function (e) {
       e.preventDefault()
       idClicked = $('#id-checked').val()
       question(idClicked)
       $('.modal').modal('hide');
       return false;

   });

</script>