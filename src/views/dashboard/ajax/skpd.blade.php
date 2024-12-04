

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Kuisioner</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $type_unsur }}</div>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data->jumlah }}</div>
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
                                                Total IKM</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data->ikm > 0 ? round($data->ikm,2) : 0}}</div>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data->ikm > 0 ? '('.prediket(round($data->ikm,2),true) .') '.prediket(round($data->ikm,2)) : 'N/A' }}</div>
                                        </div>
                                        <div class="col-auto">

                                            <i class="fas fa-desktop fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


<div class="col-lg-12">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true"> <i class="fas fa-eye"></i> Detail</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="ikm-tab" data-bs-toggle="tab" data-bs-target="#ikm" type="button" role="tab" aria-controls="ikm" aria-selected="false"> <i class="fas fa-building"></i> IKM </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ikm-perlayanan-tab" data-bs-toggle="tab" data-bs-target="#ikm-perlayanan" type="button" role="tab" aria-controls="ikm-perlayanan" aria-selected="false"> <i class="fas fa-building"></i> IKM Perlayanan </button>
          </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pengolahan-tab" data-bs-toggle="tab" data-bs-target="#pengolahan" type="button" role="tab" aria-controls="pengolahan" aria-selected="false"> <i class="fas fa-building"></i> Pengolahan Data </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sampel-tab" data-bs-toggle="tab" data-bs-target="#sampel" type="button" role="tab" aria-controls="sampel" aria-selected="false"> <i class="fas fa-users"></i> Sampel </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="saran-tab" data-bs-toggle="tab" data-bs-target="#saran" type="button" role="tab" aria-controls="saran" aria-selected="false"> <i class="fas fa-comments"></i> Saran </button>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade py-4" id="ikm-perlayanan" role="tabpanel" aria-labelledby="ikm-perlayanan-tab" style="max-height: 80vh;overflow:auto">
            @include('sisukma::report.ikm_layanan')
        </div>
        <div class="tab-pane fade show active py-4" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">

                <!-- Area Chart -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Persentase Responden  Berdasarkan Jenis Kelamin</h6>

                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Jenis Kelamin</th>
                                <th>Responden</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Laki-laki</td>
                                <td><b>{{$data->l}}</b></td>
                                <td><b>{{ $data->jumlah && $data->l ? round($data->l / $data->jumlah*100) : 0}}%</b></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Perempuan</td>
                                <td><b>{{$data->p}}</b></td>
                                <td><b>{{$data->jumlah && $data->p  ? round($data->p / $data->jumlah*100):0}}%</b></td>
                            </tr>
                        </tbody>
                           </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Persentase Responden  Berdasarkan Usia</h6>

                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Usia (tahun)</th>
                                <th>Responden</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->usia ?? [] as $k=>$row)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{$row->range}}</td>
                                <td><b>{{$row->jumlah}}</b></td>
                                <td><b>{{ $row->jumlah > 0 ? round($row->jumlah / array_sum(array_column($data->usia,'jumlah')) * 100,2) : 0}}%</b></td>
                            </tr>
                            @endforeach
                        </tbody>
                           </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Persentase Responden  Pendidikan</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pendidikan</th>
                                <th>Responden</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $jlpendidikan = 0;@endphp
                            @php $no=0; @endphp
                            @foreach(['SMA','Non Pendidikan','SD','SMP','DIII','S1','S2','S3'] as $r)

                               @php
                               $ld = Str::lower(str_replace(' ','_',$r));
                               $jlpendidikan += $data->pendidikan->$ld ?? 0; @endphp
                            @endforeach

                            @foreach(['SMA','Non Pendidikan','SD','SMP','DIII','S1','S2','S3'] as $r)
                            @php
                            $ld = Str::lower(str_replace(' ','_',$r));
                            @endphp
                            <tr>
                                <td>{{$no+1}}</td>
                                <td>{{$r}}</td>
                                <td><b>{{$data->pendidikan?->$ld ?? 0}}</b></td>
                                <td><b>{{ $data->pendidikan ? round($data->pendidikan->$ld / $jlpendidikan*100,2) : 0}}%</b></td>
                            </tr>

                         @php $no++; @endphp
                         @endforeach
                        </tbody>
                           </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Persentase Responden Berdasarkan Pekerjaan</h6>

                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pekerjaan</th>
                                <th>Responden</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $jlpkerjaan = 0; @endphp
                        @foreach($data->pekerjaan as $k=>$row)
                        @php $jlpkerjaan +=$row;@endphp
                        @endforeach
                            @php $no=0; @endphp
                            @foreach($data->pekerjaan as $k=>$row)
                            <tr>
                                <td>{{$no+1}}</td>
                                <td>{{Str::upper(Str::headline($k))}}</td>
                                <td><b>{{$row}}</b></td>
                                <td><b>{{round($row/$jlpkerjaan*100,2)}}%</b></td>
                            </tr>
                            @php $no++; @endphp
                            @endforeach
                        </tbody>
                           </table>
                            </div>
                        </div>
                    </div>

                <!-- Pie Chart -->

            </div>

        </div>
        <div class="tab-pane fade py-4" id="ikm" role="tabpanel" aria-labelledby="ikm-tab" style="max-height: 80vh;overflow:auto">
            <button class="btn btn-warning btn-sm" onclick="cetak_rekap('{{ $skpd_id }}',$('.ajax_data').val(),'ikm')" > <i class="fas fa-print"></i> Cetak</button>
            {{-- <div class="dropdown">
                <button class="btn btn-warning btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                  <li><a class="dropdown-item" href="JavaScript:void(0)" onclick="cetak_rekap('{{ $skpd_id }}',$('.ajax_data').val(),'ikm','tojpg')"> <i class="fas fa-file-image"></i> JPG</a></li>
                  <li><a class="dropdown-item" href="JavaScript:void(0)" onclick="cetak_rekap('{{ $skpd_id }}',$('.ajax_data').val(),'ikm','topdf')"> <i class="fas fa-file-pdf"></i> PDF</a></li>
                </ul>
              </div> --}}
            <hr>
            @include('sisukma::report.ikm_skpd')
        </div>
        <div class="tab-pane fade py-4" id="pengolahan" role="tabpanel" aria-labelledby="pengolahan-tab" style="max-height: 80vh;overflow:auto">
            <button class="btn btn-warning btn-sm" onclick="cetak_rekap('{{ $skpd_id }}',$('.ajax_data').val(),'pengolahan')"> <i class="fas fa-print"></i> Cetak</button>
            <hr>
            @include('sisukma::report.pengolahan-data')

        </div>
        <div class="tab-pane fade py-4" id="sampel" role="tabpanel" aria-labelledby="sampel-tab" style="max-height: 80vh;overflow:auto">
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
        <div class="tab-pane fade py-4" id="saran" role="tabpanel" aria-labelledby="saran-tab" style="max-height: 80vh;overflow:auto">
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
                 @foreach(collect($data->saran)->where('saran','!=','')->sortByDesc('id')->values() as $k=>$row)
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
</div>
</div>
