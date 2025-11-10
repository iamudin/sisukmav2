@extends('sisukma::layout.app')
@section('content')
    <div class="container-fluid px-4">
        <h3 class="mt-4"><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
        <p style="font-size:30px;">Periode <b class="periode-title">{{ $periode ?? date('Y')}}</b> <button
                onclick="$('.periode').modal('show')" class="btn btn-sm btn-danger float-end mt-2"> <i
                    class="fa fa-edit"></i> Ganti Periode</button></p>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

        <ul class="nav nav-tabs" id="ikmTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="ikm-tab" data-toggle="tab" href="#ikm" role="tab" aria-controls="ikm"
                    aria-selected="true">
                    9 Unsur
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="responden-tab" data-toggle="tab" href="#responden" role="tab"
                    aria-controls="responden" aria-selected="false">
                    16 Unsur
                </a>
            </li>
        </ul>

        <div class="tab-content mt-3" id="ikmTabContent">
            <!-- Tab 1: Nilai IKM -->
            <div class="tab-pane fade show active" id="ikm" role="tabpanel" aria-labelledby="ikm-tab">
                @if($data)
                    @php $main = $data->where('skpd_id', null)->first(); @endphp
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
                            <ul class="nav nav-tabs" id="ikmTabs" role="tablist">

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="statistik-tab" data-bs-toggle="tab"
                                        data-bs-target="#statistik" type="button" role="tab">
                                        Statistik Responden
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link " id="perangkat-tab" data-bs-toggle="tab"
                                        data-bs-target="#perangkat" type="button" role="tab">
                                        Perangkat Daerah
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link " id="rekapitulasi-tab" data-bs-toggle="tab"
                                        data-bs-target="#rekapitulasi" type="button" role="tab">
                                        Rekapitulasi IKM Kabupaten
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="ikmTabsContent">
                                <div class="tab-pane fade" id="rekapitulasi" role="tabpanel">
                                    <iframe style="width:100%;height:80vh"
                                        src="{{ route('cetakrekap9v2') . '?' . request()->getQueryString() }}"
                                        frameborder="0"></iframe>
                                </div>
                                <div class="tab-pane fade  show active" id="statistik" role="tabpanel">
                                    @php $opd = $main; @endphp
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
                                                        <td>{{ $opd->statistik_responden->kategori_pengguna->non_disabilitas->jumlah }}
                                                        </td>
                                                        <td>{{ $opd->statistik_responden->kategori_pengguna->non_disabilitas->persentase }}%
                                                        </td>
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
                                            @foreach($data->where('skpd_id', '!=', null)->sortByDesc('nilai_konversi')->values() as $k => $opd)
                                                <tr>
                                                    <td>{{ $k + 1 }}</td>
                                                    <td>{{ $opd->nama_skpd }}</td>
                                                    <td>{{ $opd->total_responden }}</td>
                                                    <td>{{ $opd->sample_diambil }}</td>
                                                    <td>{{ round($opd->nilai_ikm, 2) }}</td>
                                                    <td>{{ round($opd->nilai_konversi, 2) }}</td>
                                                    <td style="width:100px">
                                                        <button data-respon="{{ base64_encode(json_encode($opd)) }}"
                                                            class="btn btn-sm btn-primary btn-detail"
                                                            data-respon="{{ $opd->skpd_id }}">
                                                            <i class="fa fa-bar-chart"></i> Lihat
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a download
                                                                href="{{ route('cetakolahan9v2', $opd->skpd_id) . '?' . request()->getQueryString()  }}"
                                                                class="btn btn-sm btn-success">
                                                                <i class="fa fa-print"></i> Responden
                                                            </a>
                                                            <a href="{{ route('cetakrekap9v2', $opd->skpd_id) . '?' . request()->getQueryString()  }}"
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
                @else
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="responden" role="tabpanel" aria-labelledby="responden-tab">
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let query = window.location.search; // contoh: ?jenis_periode=bulan&tahun=2025
                let queryParams = {};

                if (!query || query === '?') {
                    // jika query kosong, set default
                    const tahunSekarang = new Date().getFullYear();
                    queryParams = {
                        jenis_periode: 'tahun',
                        tahun: tahunSekarang
                    };
                } else {
                    // konversi query string menjadi object
                    query.substring(1).split('&').forEach(pair => {
                        const [key, value] = pair.split('=');
                        queryParams[key] = decodeURIComponent(value || '');
                    });
                }

                // kirim request POST AJAX
                fetch(window.location.pathname, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(queryParams)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.msg == 'new') {
                            window.location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        </script>
        <script>
            $(function () {
                $('.btn-detail').on('click', function () {
                    const responId = $(this).data('respon');

                    // Tampilkan modal
                    $('#modalRespon').modal('show');

                    // Reset isi modal ke loading
                    $('#respon-content').hide().html('');
                    $('#loading').show();

                    // Request AJAX pakai Fetch
                    fetch("{{ route('statsresp') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json', // penting!
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ data: responId })
                    })
                        .then(response => {
                            if (!response.ok) throw new Error('Network response error');
                            return response.text(); // <-- penting, baca HTML sebagai text
                        })
                        .then(html => {
                            $('#loading').hide();
                            $('#respon-content').html(html);
                            $('#respon-content').show();
                        })
                        .catch(error => {
                            $('#loading').hide();
                            $('#respon-content').html('<p class="text-danger">Gagal memuat data.</p>');
                            console.error('Error:', error);
                        });

                });
            });
        </script>

        <div class="modal fade" id="modalRespon" tabindex="-1" role="dialog" aria-labelledby="modalResponLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalResponLabel">Statistik</h5>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Tutup"
                            onclick="$('#modalRespon').modal('hide')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="loading" class="py-3">
                            <div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem;">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div id="respon-content" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
        @include('sisukma::dashboard.v2.periode')
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
@endsection