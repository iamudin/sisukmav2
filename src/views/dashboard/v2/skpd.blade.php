@extends('sisukma::layout.app')
@section('content')
                    <div class="container-fluid px-4">
                        <h3 class="mt-4"><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
                       <p style="font-size:30px;">Periode <b class="periode-title">{{ $periode ?? date('Y')}}</b> <button onclick="$('.periode').modal('show')" class="btn btn-sm btn-danger float-end mt-2"> <i class="fa fa-edit"></i> Ganti Periode</button></p>
    <!-- Nav Tabs -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

            <ul class="nav nav-tabs" id="ikmTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="ikm-tab" data-toggle="tab" href="#ikm" role="tab" aria-controls="ikm"
                        aria-selected="true">
                        9 Unsur
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="responden-tab" data-toggle="tab" href="#responden" role="tab" aria-controls="responden"
                        aria-selected="false">
                       16 Unsur
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content mt-3" id="ikmTabContent">
                <!-- Tab 1: Nilai IKM -->
                <div class="tab-pane fade show active" id="ikm" role="tabpanel" aria-labelledby="ikm-tab">

                    @if($opd = $data9)
                                            <div class="row">
                                                    <div class="col-xl-3 col-md-6 mb-4">
                                                        <div class="card border-left-primary shadow h-100 py-2">
                                                            <div class="card-body">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col mr-2">
                                                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                                            Kuisioner</div>
                                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">9</div>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <i class="fas fa-question fa-2x text-gray-300"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Earnings (Monthly) Card Example -->
                                                    <div class="col-xl-3 col-md-6 mb-4">
                                                        <div class="card border-left-success shadow h-100 py-2">
                                                            <div class="card-body">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col mr-2">
                                                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                                            Total Responden</div>
                                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $opd->sample_diambil }} / {{ $opd->total_responden }}</div>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>



                                                    <!-- Pending Requests Card Example -->
                                                    <div class="col-xl-3 col-md-6 mb-4">
                                                        <div class="card border-left-warning shadow h-100 py-2">
                                                            <div class="card-body">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col mr-2">
                                                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                                            Nilai IKM / Konversi</div>
                                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $opd->nilai_ikm > 0 ? round($opd->nilai_ikm, 2) . ' / ' . round($opd->nilai_konversi, 2) : 0}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 mb-4">
                                                        <div class="card border-left-warning shadow h-100 py-2">
                                                            <div class="card-body">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col mr-2">
                                                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                                            Mutu Pelayanan</div>
                                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                           {{ $opd->predikat_mutu_layanan }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto">

                                                                        <i class="fas fa-desktop fa-2x text-gray-300"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                            <div class="col-lg-12">
                                                <!-- Nav Tabs -->
                                                <ul class="nav nav-tabs" id="ikmTabs" role="tablist">

                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" id="statistik-tab" data-bs-toggle="tab" data-bs-target="#statistik" type="button"
                                                            role="tab">
                                                            Statistik Responden
                                                        </button>
                                                    </li>
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link " id="layanan-tab" data-bs-toggle="tab" data-bs-target="#layanan" type="button" role="tab">
                                                                IKM Perlayanan
                                                            </button>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link " id="saran-tab" data-bs-toggle="tab" data-bs-target="#saran" type="button" role="tab">
                                                                Saran Responden
                                                            </button>
                                                        </li>
                                                            <li class="nav-item" role="presentation">
                                                            <button class="nav-link " id="pengolahan-tab" data-bs-toggle="tab" data-bs-target="#pengolahan" type="button" role="tab">
                                                                Pengolahan Data
                                                            </button>
                                                        </li>
                                                                 </li>
                                                            <li class="nav-item" role="presentation">
                                                            <button class="nav-link " id="rekapitulasi-tab" data-bs-toggle="tab" data-bs-target="#rekapitulasi" type="button" role="tab">
                                                                Rekapitulasi
                                                            </button>
                                                        </li>
                                                </ul>

                                                <!-- Tab Content -->
                                                <div class="tab-content mt-3" id="ikmTabsContent">
                                                    <!-- Tab 1: Hasil Per Layanan -->
                                                    <div class="tab-pane fade" id="pengolahan" role="tabpanel">
                                                        <iframe style="width:100%;height:80vh" src="{{ route('cetakolahan9v2', auth()->user()->skpd->id) . '?' . request()->getQueryString() }}" frameborder="0"></iframe>
                                                    </div>
                                                          <div class="tab-pane fade" id="rekapitulasi" role="tabpanel">
                                                        <iframe style="width:100%;height:80vh" src="{{ route('cetakrekap9v2', auth()->user()->skpd->id) . '?' . request()->getQueryString() }}" frameborder="0"></iframe>
                                                    </div>
                                                    <div class="tab-pane fade" id="saran" role="tabpanel">
                                                        <div class="table-responsive">
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
                                                    <div class="tab-pane fade" id="layanan" role="tabpanel">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped align-middle">
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

                                                    <!-- Tab 2: Statistik Responden -->
                                                    <div class="tab-pane fade  show active" id="statistik" role="tabpanel">
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
                                                    </div>
                                                </div>

                                            </div>
                                            </div>
                    @else
                        <center><div class="alert alert-danger">Belum ada data</div></center>
                    @endif
                </div>

                <!-- Tab 2: Statistik Responden -->
                <div class="tab-pane fade" id="responden" role="tabpanel" aria-labelledby="responden-tab">
                    @if($opd = $data)
                        <div class="row">

                            <div class="col-md-12 mb-4">
                                <div class="card card-stat p-4 h-100">
                                    <h5 class="card-title text-center">{{ $data->nama_skpd }}</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-5 text-center border-right">
                                            <p class="mb-1 font-weight-bold">Nilai IKM</p>
                                            <div class="ikm-value text-primary">
                                                <h1>{{ number_format($opd->nilai_konversi, 2) }}</h1>
                                            </div>
                                            <p class="mt-2 mb-0 text-muted-small font-weight-bold">Mutu Pelayanan</p>
                                            <div class="mutu">{{$opd->predikat_mutu_layanan}}</div>
                                            <p class="text-muted-small mb-0">({{ prediket($opd->nilai_konversi) }})</p>
                                        </div>

                                        <div class="col-7">
                                            <center>
                                                <p class="font-weight-bold mb-1">Responden</p>
                                            </center>
                                            <table class="table table-sm mb-2">
                                                <tr>
                                                    <td>Jumlah</td>
                                                    <td>: <strong>{{ $opd->sample_diambil}}</strong> Orang</td>
                                                </tr>
                                                <tr>
                                                    <td>Jenis Kelamin</td>
                                                    <td>:

                                                        L = {{ $opd->statistik_responden?->jenis_kelamin?->L?->jumlah ?? null }} /
                                                        P = {{ $opd->statistik_responden?->jenis_kelamin?->P?->jumlah ?? null }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top">Pendidikan</td>
                                                    <td>:
                                                        <table class="table table-borderless table-sm mb-0">
                                                            @foreach($opd->statistik_responden->pendidikan as $key => $row)
                                                                <tr>
                                                                    <td style="padding:2px 0;">{{ $key }}</td>
                                                                    <td style="padding:2px 0;">{{ $row }} Orang</td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td valign="top">Non Disabilitas </td>
                                                    <td>:
                                                        {{ $opd->statistik_responden->disabilitas->non_disabilitas->jumlah }} Orang
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td valign="top">Disabilitas : </td>
                                                    <td>
                                                        <table class="table table-borderless table-sm mb-0">
                                                            @foreach($opd->statistik_responden->disabilitas->disabilitas as $key => $row)
                                                                <tr>
                                                                    <td style="padding:2px 0;">{{ $row->label }}</td>
                                                                    <td style="padding:2px 0;">{{ $row->jumlah }} Orang</td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                    <div class="text-right mt-auto">
                                            <a href="{{ route('cetakrekapv2', $opd->skpd_id) . '?' . request()->getQueryString() }}"
                                                class="btn btn-print btn-sm btn-info">
                                                <i class="fa fa-print"></i> Rekap Perlayanan
                                            </a>

                                            <a href="{{ route('cetakolahanv2', $opd->skpd_id) . '?' . request()->getQueryString() }}"
                                                class="btn btn-print btn-sm btn-warning">
                                                <i class="fa fa-print"></i> Responden
                                            </a>
                                    </div>
                                </div>
                            </div></div>
                     @else
                        <center><div class="alert alert-danger">Belum ada data</div></center>
                        @endif

                </div>
            </div>



                    </div>
                    @include('sisukma::dashboard.v2.periode')
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
@endsection
