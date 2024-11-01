<table class="table table-striped table-bordered" >
<thead>
    <tr>
        <th width="20px">No</th>
        <th>Nama Perangkat Daearh</th>
        <th>Sampel</th>
        <th>Nilai IKM</th>
        <th>Mutu</th>
        <th>Prediket</th>
        <th width="90px">Aksi</th>
    </tr>
</thead>
<tbody>

    @foreach(collect($data->data_ikm_skpd)->sortByDesc('data.ikm')->values() as $k=>$row)
    <tr>
        <td>{{ $k+1 }}</td>
        <td>{{ $row->nama_skpd }}</td>
        <td width="60px" align="right">{{ $row->data->jumlah }}</td>
        <td width="100px" align="right">{{ $row->data->ikm > 0 ? round($row->data->ikm,2) : 0 }}%</td>
        <td  width="70px" align="center">{{ prediket($row->data->ikm,true) }}</td>
        <td width="100px" >{{ prediket($row->data->ikm) }}</td>
        <td>
            @if($row->data->jumlah > 0)
            <div class="btn-group">
                <button class="btn btn-sm btn-warning" onclick="modal_detail_ikm('{{ base64_encode(json_encode($row->data)) }}','{{ str($row->nama_skpd)->upper() }}','{{ $row->alamat_skpd }}','{{ $row->telp }}','{{ $type_unsur }}','{{ $row->skpd_id }}')"   title="Lihat Detail" >
                    <i class="fa fa-eye"></i> Detail
                </button>
            </div>
        @endif
        </td>

    </tr>
    @endforeach
</tbody>
</table>
