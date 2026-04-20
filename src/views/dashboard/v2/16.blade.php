
    @if($data16 && count($data16) > 0)
        @php $main = $data16->where('skpd_id', null)->first(); @endphp
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Kuisioner</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">16</div>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $main->sample_diambil }} /
                                    {{ $main->total_responden }}
                                </div>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $main->nilai_ikm > 0 ? round($main->nilai_ikm, 2) . ' / ' . round($main->nilai_konversi, 2) : 0}}
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
                                    {{ $main->predikat_mutu_layanan }}
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
                <ul class="nav nav-tabs" id="ikmTabs16" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="statistik-tab" data-bs-toggle="tab" data-bs-target="#statistik"
                            type="button" role="tab">
                            Statistik Responden
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="perangkat-tab" data-bs-toggle="tab" data-bs-target="#perangkat"
                            type="button" role="tab">
                            Perangkat Daerah
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="rekapitulasi-tab" data-bs-toggle="tab" data-bs-target="#rekapitulasi"
                            type="button" role="tab">
                            Rekapitulasi IKM Kabupaten
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="rekaptahun-tab" data-bs-toggle="tab" data-bs-target="#rekaptahun"
                            type="button" role="tab">
                            Rekapitulasi Tahunan
                        </button>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="ikmTabsContent16">
                    <div class="tab-pane fade" id="rekapitulasi" role="tabpanel">
                
                            @php
    $ip = request()->ip();
@endphp

@if(in_array($ip, ['127.0.0.1', '::1']))
    {{-- LOCAL --}}
    <iframe style="width:100%;height:80vh"
        src="{{ route('cetakrekap16v2') . '?' . request()->getQueryString() }}"
        frameborder="0">
    </iframe>
@else
    {{-- NON LOCAL --}}
    <iframe
        src="https://docs.google.com/gview?url={{ route('cetakrekap16v2') . '?' . request()->getQueryString() }}&embedded=true"
        type="application/pdf"
        width="100%"
        height="600px">
    </iframe>
@endif
                    </div>
                    <div class="tab-pane fade" id="rekaptahun" role="tabpanel">
                        {{-- <iframe style="width:100%;height:80vh"
                            src="{{ route('rekaptahunan') . '?' . request()->getQueryString() }}" frameborder="0"></iframe>
                             --}}
                             <div class="alert alert-warning">Belum tersedia</div>
                    </div>
                    <div class="tab-pane fade  show active" id="statistik" role="tabpanel">
                        @php $opd16 = $main; @endphp
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
                                        @foreach($opd16->statistik_responden->jenis_kelamin as $key => $row)
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
                                        @foreach($opd16->statistik_responden->pendidikan as $key => $row)
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
                                        @foreach($opd16->statistik_responden->pekerjaan as $key => $row)
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
                                        @foreach($opd16->statistik_responden->usia as $key => $row)
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
                                            <td>{{ $opd16->statistik_responden->kategori_pengguna->non_disabilitas->jumlah }}
                                            </td>
                                            <td>{{ $opd16->statistik_responden->kategori_pengguna->non_disabilitas->persentase }}%
                                            </td>
                                        </tr>

                                        @foreach($opd16->statistik_responden->kategori_pengguna->disabilitas as $key => $row)
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
                    <div class="tab-pane fade" id="perangkat" role="tabpanel" style="overflow:auto;max-height:70vh">
                        <table class="table table-striped table-bordered" style="width:100%;font-size:small">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama SKPD</th>
                                    <th>Total Responden</th>
                                    <th>Sample</th>
                                    <th>Nilai IKM</th>
                                    <th>Nilai Konversi</th>
                                    <th>Statistik</th>
                                    <th>Cetak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data16->where('skpd_id', '!=', null)->sortByDesc('nilai_konversi')->values() as $k => $opd)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $opd->nama_skpd }}</td>
                                        <td>{{ $opd->total_responden }}</td>
                                        <td>{{ $opd->sample_diambil }}</td>
                                        <td>{{ round($opd->nilai_ikm, 2) }}</td>
                                        <td>{{ round($opd->nilai_konversi, 2) }}</td>
                                        <td style="width:100px">
                                            <button data-respon="{{ base64_encode(json_encode($opd)) }}"
                                                class="btn btn-sm btn-primary btn-detail" data-respon="{{ $opd->skpd_id }}">
                                                <i class="fa fa-bar-chart"></i> Lihat
                                            </button>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-success dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-print"></i> Responden (PDF)
                                                    </button>

                                                    <ul class="dropdown-menu">

                                                        <!-- Semua Layanan -->
                                                        <li>
                                                            <a download class="dropdown-item"
                                                                href="{{ route('cetakolahan16v2', $opd->skpd_id) }}?{{ request()->getQueryString() }}">
                                                                Semua Layanan
                                                            </a>
                                                        </li>

                                                        @foreach($opd->hasil_perlayanan as $row)

                                                            <!-- Layanan 2 -->
                                                            <li>
                                                                <a download class="dropdown-item"
                                                                    href="{{ route('cetakolahan16v2', [$opd->skpd_id, $row->id_layanan]) }}?{{ request()->getQueryString() }}">
                                                                    {{ $row->nama_layanan }}
                                                                </a>
                                                            </li>
                                                        @endforeach

                                                        <!-- Tambah sesuai kebutuhan -->
                                                        <!-- <li><a class="dropdown-item" href="...">Layanan 3</a></li> -->
                                                    </ul>
                                                </div>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-info dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-print"></i> Responden (Excel)
                                                    </button>

                                                    <ul class="dropdown-menu">

                                                        <!-- Semua Layanan -->
                                                        <li>
                                                            <a download class="dropdown-item"
                                                                href="{{ route('cetakolahan16v2', $opd->skpd_id) }}?as_xls=true&{{ request()->getQueryString() }}">
                                                                Semua Layanan
                                                            </a>
                                                        </li>

                                                        @foreach($opd->hasil_perlayanan as $row)

                                                            <!-- Layanan 2 -->
                                                            <li>
                                                                <a download class="dropdown-item"
                                                                    href="{{ route('cetakolahan16v2', [$opd->skpd_id, $row->id_layanan]) }}?as_xls=true&{{ request()->getQueryString() }}">
                                                                    {{ $row->nama_layanan }}
                                                                </a>
                                                            </li>
                                                        @endforeach

                                                        <!-- Tambah sesuai kebutuhan -->
                                                        <!-- <li><a class="dropdown-item" href="...">Layanan 3</a></li> -->
                                                    </ul>
                                                </div>
                                                <a href="{{ route('cetakrekap16v2', $opd->skpd_id) . '?' . request()->getQueryString()  }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-print"></i> Rekapitulasi
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
    @else
                 @if(!str($periode)->contains('2026'))
                 <div class="alert alert-warning">Unsur 16 Tidak tersedia !</div>
                @endif
                @if(str($periode)->contains('2026'))
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                @endif

        @endif
    {{-- <div class="tab-pane fade" id="responden" role="tabpanel" aria-labelledby="responden-tab">
    </div> --}}
