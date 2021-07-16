<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-success-600">
            <h6 class="modal-title font-weight-semibold">Tambah Sasaran Monitoring</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            @if($form->targets->isEmpty())
                Anda belum menambahkan Sasaran Monitoring. Tambahkan Sasaran Monitoring sekarang.
            @else
            <table class="table" id="target-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Sekolah</th>
                        <th>Nama Petugas</th>
                        <th>Pengisi Form</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($form->targets as $target)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$target->institutionable->name}}</td>
                            <td>
                                @include('layouts.parts.officers',['officers' => $target->officers])
                            </td>
                            <td>{{$target->type}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        <div class="modal-footer">
            @if($form->isEditable())
                <button type="button" class="btn btn-link" data-dismiss="modal">Tambahkan Nanti</button>
                <a href="{{route('admin.monev.form.target.index',[$form->id])}}" class="btn bg-success">Tambahkan Sasaran Monitoring</a>
            @else
                <a href="{{route('admin.monev.form.target.index',[$form->id])}}" class="btn bg-success">Lihat Sasaran Monitoring</a>
            @endif
        </div>
    </div>
</div>
<script>
    $('#target-table').DataTable({})
</script>