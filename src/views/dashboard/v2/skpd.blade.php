@extends('sisukma::layout.app')
@section('content')
                    <div class="container-fluid px-4">
                        <h3 class="mt-4"><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
                       <p style="font-size:30px;">Periode <b class="periode-title">{{ $nama_periode ?? date('Y')}}</b> <button onclick="$('.periode').modal('show')" class="btn btn-sm btn-danger float-end mt-2"> <i class="fa fa-edit"></i> Ganti Periode</button></p>
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
                                                <a href="{{ route('cetakrekap9v2', $opd->skpd_id) . '?' . request()->getQueryString() }}"
                                                    class="btn btn-print btn-sm btn-info">
                                                    <i class="fa fa-print"></i> Rekap Perlayanan
                                                </a>

                                                <a href="{{ route('cetakolahan9v2', $opd->skpd_id) . '?' . request()->getQueryString() }}"
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
