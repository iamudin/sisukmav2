
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail" type="button"
            role="tab" aria-controls="detail" aria-selected="true"> <i class="fas fa-eye"></i> IKM</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pengolahan-tab" data-bs-toggle="tab" data-bs-target="#pengolahan" type="button"
            role="tab" aria-controls="pengolahan" aria-selected="true"> <i class="fas fa-eye"></i> Pengolahan Data</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="real-sample-tab" data-bs-toggle="tab" data-bs-target="#real-sample" type="button"
            role="tab" aria-controls="real-sample" aria-selected="true"> <i class="fas fa-eye"></i> Rincian Sample</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="saran-tab" data-bs-toggle="tab" data-bs-target="#saran" type="button"
            role="tab" aria-controls="saran" aria-selected="false"> <i class="fas fa-comments"></i> Feedback Responden</button>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active pt-3" id="detail" role="tabpanel" aria-labelledby="detail-tab" style="font-size:small">
   <button class="btn btn-warning btn-sm" > <i class="fas fa-print"></i> Cetak</button>
   <hr>
    @include('sisukma::report.ikm_skpd')
    </div>
    <div class="tab-pane fade show" id="saran" role="tabpanel" aria-labelledby="saran-tab" style="max-height: 75vh;overflow:auto">
        <div class="alert alert-info"> <i class="fas fa-warning"></i> Hanya menampilkan Responden yang memiliki saran. </div>
       <table class="table table-striped table-bordered" style="font-size:small">
        <thead>
            <tr>
                <th>No</th>
                <th width="90x">Tgl. Survei</th>
                <th width="95px">Jam Survei</th>
                <th>Layanan</th>
                <th>Pekerjaan</th>
                <th>Pendidikan</th>
                <th>Saran</th>
            </tr>
        </thead>
        <tbody>
            @foreach(collect($data->saran)->where('saran','!=','')->values() as $k=>$row)
            <tr>
                <td>{{ $k+1 }}</td>
                <td>{{ date('d M Y',strtotime($row->tgl_survei)) }}</td>
                <td>{{ $row->jam_survei }}</td>
                <td>{{ $row->layanan->nama_layanan }}</td>
                <td>{{ $row->pekerjaan ?? $row->pekerjaan2 }}</td>
                <td>{{ $row->pendidikan }}</td>
                <td>{!! $row->saran !!}</td>
            </tr>
            @endforeach
        </tbody>
       </table>
    </div>
    <div class="tab-pane fade " id="real-sample" role="tabpanel" aria-labelledby="real-sample-tab" style="max-height: 75vh;overflow:auto">
        <div class="alert alert-info"> <i class="fas fa-warning"></i> Sample yang diambil berdasarkan populasi yang memiliki nilai Unsur N Tertinggi. </div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td><b>Bulan</b></td>
                    <td><b>Populasi</b></td>
                    <td><b>Sample</b></td>
                </tr>
            </thead>
            <tbody>
                @foreach($data->detail->respon as $row)
                <tr>
                    <td>{{ blnindo($row->month) }}</td>
                    <td>{{ $row->real }}</td>
                    <td>{{ $row->sample }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2"><b>Total Sample</b></td>
                    <td>{{ $data->detail->sample_total }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="tab-pane fade pt-3" id="pengolahan" role="tabpanel" aria-labelledby="pengolahan-tab" style="max-height: 75vh;overflow:auto;">
   <button class="btn btn-warning btn-sm" onclick="cetak_rekap('{{ $skpd_id }}',$('.ajax_data').val())"> <i class="fas fa-print"></i> Cetak</button>
   <hr>
        @include('sisukma::report.pengolahan-data')


    </div>

</div>
