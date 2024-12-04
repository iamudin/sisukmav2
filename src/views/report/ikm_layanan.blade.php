<style>
    .list-group {
        counter-reset: list;
        padding-left:20px
    }
    .list-group-item {
        counter-increment: list;
        position: relative;
    }
    .list-group-item:hover {
        background-color: #f8f9fa; /* Ganti dengan warna hover yang diinginkan */
        cursor: pointer; /* Menambahkan kursor pointer saat hover */
    }
    .list-group-item::before {
        content: counter(list) ". ";
        position: absolute;
        margin-right:10px;
        left: -20px; /* Sesuaikan posisi sesuai kebutuhan */
    }
</style>
<table class="table table-striped table-bordered" style="font-size:small">
    <thead>
        <tr>
            <th style="width:20px">No</th>
            <th style="width:30%">Layanan</th>
            <th>Responden</th>
            <th style="width:80px">Nilai IKM</th>
            <th style="width:120px">Prediket</th>
            <th width="10px">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach(collect($data->sample_ikm_layanan)->sortByDesc('ikm')->values() as $k=>$row)
        <tr>
            <td>{{ $k+1 }}</td>
            <td>{{ $row->nama_layanan }}</td>
            <td>Jumlah : <b>{{ $row->responden->jumlah }}</b><br>
                Jenis Kelamin : <br>L = <b>{{ $row->responden->l }}</b>, P = <b>{{ $row->responden->p }}</b>

            </td>
            <td align="right"><b>{{ round($row->ikm,2) }}</b></td>
            <td>{{ prediket($row->ikm,true) .' ('.prediket($row->ikm).')' }}</td>
            <td> <button class="btn btn-sm btn-success" onclick="$('#detail-saran-{{ $k }}').modal('show')"> <i class="fas fa-eye"></i> </button> </td>
        </tr>
        <div class="modal fade" id="detail-saran-{{ $k }}" tabindex="-1" style="font-size:small">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Saran</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="$('#detailikm').modal('hide')" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <ol class="list-group" style="list-style:numeric">
                    @foreach(collect($row->responden->saran)->where('saran','!=','') as $ks=>$ro)
                    <li class="list-group-item">{!! $ro->saran !!}</li>
                    @endforeach
                </ol>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="$('#detailikm').modal('hide')">Close</button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
    </tbody>
</table>
