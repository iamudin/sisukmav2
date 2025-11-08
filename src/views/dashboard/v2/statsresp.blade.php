@php $opd = $data; @endphp
<ul class="nav nav-tabs" id="ikmTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="statistiks-tab" data-bs-toggle="tab" data-bs-target="#statistiks"
            type="button" role="tab">
            Statistik Responden
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="perlayanan-tab" data-bs-toggle="tab" data-bs-target="#perlayanan" type="button"
            role="tab">
            IKM Perlayanan
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="sr-tab" data-bs-toggle="tab" data-bs-target="#sr" type="button"
            role="tab">
            Saran Responden
        </button>
    </li>
</ul>
<div class="tab-content mt-3" id="ikmTabsContent">
<div class="tab-pane fade" id="sr" role="tabpanel">
    <div class="table-responsive" style="max-height: 70vh;overflow:auto">
        <table class="table table-bordered table-striped align-middle" style="font-size:small">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Layanan</th>
                    <th>Nama</th>
                    <th style="width:150px">Tgl Survei</th>
                    <th>J.Kelamin</th>
                    <th>Pendidikan</th>
                    <th>Pekerjaan</th>
                    <th>Usia</th>
                    <th style="width:150px">Disabilitas</th>
                    <th>Saran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($opd->hasil_perlayanan as $index => $layanan)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td colspan="9">{{ $layanan->nama_layanan}}</td>
                    </tr>
                    @foreach(collect($layanan->saran)->values() ?? [] as $k => $saran)
                        <tr>
                            <td></td>
                            <td align="center">{{ $k + 1 }}</td>
                            <td>{{ $saran->nama}}</td>
                            <td>{{date('d F Y', strtotime($saran->tgl_survei))}}</td>
                            <td>{{ $saran->jenis_kelamin}}</td>
                            <td>{{ $saran->pendidikan}}</td>
                            <td>{{ $saran->pekerjaan}}</td>
                            <td>{{ $saran->usia}}</td>
                            <td>{{ $saran->disabilitas}}</td>
                            <td>{{ $saran->saran}}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="tab-pane fade" id="perlayanan" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle" style="font-size:small">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Layanan</th>
                        <th>Jumlah Responden</th>
                        <th>Sample Diambil</th>
                        <th>Nilai IKM</th>
                        <th>Nilai Konversi</th>
                        <th>Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Contoh data statis, nanti bisa diganti loop dari hasilPerLayanan -->
                    @foreach($opd->hasil_perlayanan as $index => $layanan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $layanan->nama_layanan }}</td>
                            <td>{{$layanan->total_responden}}</td>
                            <td>{{$layanan->sample_diambil}}</td>
                            <td>{{round($layanan->nilai_ikm, 2)}}</td>
                            <td>{{round($layanan->nilai_konversi, 2)}}</td>
                            <td class="fw-bold text-success">{{$layanan->predikat_mutu}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</div>
<div class="tab-pane fade  show active" id="statistiks" role="tabpanel">
    <div class="row">
        <div class="col-md-4">
            <h5 class="fw-bold">Jenis Kelamin</h5>
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opd->statistik_responden->jenis_kelamin as $key => $row)
                        <tr>
                            <td>{{$key == 'L' ? 'Laki-laki' : 'Perempuan'}}</td>
                            <td>{{ $row->jumlah }}</td>
                            <td>{{ $row->persentase }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <h5 class="fw-bold">Pendidikan</h5>
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opd->statistik_responden->pendidikan as $key => $row)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{ $row->jumlah }}</td>
                            <td>{{ $row->persentase }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h5 class="fw-bold">Pekerjaan</h5>
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opd->statistik_responden->pekerjaan as $key => $row)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{ $row->jumlah }}</td>
                            <td>{{ $row->persentase }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <h5 class="fw-bold">Usia</h5>
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opd->statistik_responden->usia as $key => $row)
                        <tr>
                            <td>{{$row->label}}</td>
                            <td>{{ $row->jumlah }}</td>
                            <td>{{ $row->persentase }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <h5 class="fw-bold">Kategorisasi Pengguna Layanan</h5>
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>Non Disabilitas</td>
                        <td>{{ $opd->statistik_responden->kategori_pengguna->non_disabilitas->jumlah }}</td>
                        <td>{{ $opd->statistik_responden->kategori_pengguna->non_disabilitas->persentase }}%</td>
                    </tr>

                    @foreach($opd->statistik_responden->kategori_pengguna->disabilitas as $key => $row)
                        <tr>
                            <td>Disabilitas {{$row->label}}</td>
                            <td>{{ $row->jumlah }}</td>
                            <td>{{ $row->persentase }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div></div>