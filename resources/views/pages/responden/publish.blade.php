<div class="modal-dialog modal-md"> 
    <div class="modal-content">
        @if($passed == true)
        <div class="modal-header bg-info">
            <h6 class="modal-title text-center">Kirim Jawaban</h6>
            <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
            <div class="alert alert-info alert-styled-left">
                <span class="font-weight-semibold">Apakah anda yakin? </span><br> Anda tidak bisa merubah isi jawaban ini lagi.
            </div>
            <div class="d-flex align-items-center justify-content-end">
                <button class="btn bg-secondary" data-dismiss="modal">Batal</button>
                <button class="btn bg-success ml-2" onclick="event.preventDefault(); document.getElementById('modal-form').submit();"><i class="icon-checkmark3 font-size-base mr-1"></i> Lanjutkan</button>
                <form id="modal-form" action="{{ route('respondent.form.publishing') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
        @else
            <div class="modal-header bg-danger">
                <h6 class="modal-title text-center">Peringatan</h6>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-styled-left">
                    {{$failed_note}}
                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <button class="btn bg-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        @endif
    </div>
</div>
    