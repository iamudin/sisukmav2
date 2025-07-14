

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary bg-info shadow h-100 py-2 text-white">
                                <div class="card-body" style="cursor: pointer" onclick="location.href='{{ route('skpd.index') }}'">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                Perangkat Daerah</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data->total_skpd ?? 'N/A'}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-building fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary  shadow h-100 py-2">
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data->jumlah ?? 'N/A' }}</div>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data ? round($data->ikm,2) :  'N/A' }}</div>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$data ?  '('.prediket(round($data?->ikm,2),true) .') '.prediket(round($data?->ikm,2)) : 'N/A'}}</div>
                                        </div>
                                        <div class="col-auto">

                                            <i class="fas fa-desktop fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($data)
<div class="col-lg-12">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true"> <i class="fas fa-eye"></i> Detail</button>
        </li>
        @if($type_unsur == 9)

        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ikm-kab-tab" data-bs-toggle="tab" data-bs-target="#ikm-kab" type="button" role="tab" aria-controls="ikm-kab" aria-selected="false"> <i class="fas fa-building"></i> IKM Kabupaten</button>
          </li>
          @endif
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false"> <i class="fas fa-building"></i> IKM Perangkat Daerah</button>
        </li>

      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active py-4" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('cetakikmkab') }}" class="action" method="post">
                        @csrf
                        <input type="hidden" value="" name="periode" class="periode-sub">
                        <button onclick="$('.periode-sub').val($('.periode-title').text())" name="cetakikmkab" value="{{ base64_encode(json_encode(json_decode(collect($data)->except('data_ikm_skpd'),true))) }}" class="btn btn-warning btn-sm mb-3">CETAK</button>
                    </form>
                </div>
            </div>
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
                        <table class="table table-striped table-bordered">
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
                        <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Usia (tahun)</th>
                                <th>Responden</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->usia as $k=>$row)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{$row->range}}</td>
                                <td><b>{{$row->jumlah}}</b></td>
                                <td><b>{{round($row->jumlah / array_sum(array_column($data->usia,'jumlah')) * 100,2)}}%</b></td>
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
                        <table class="table table-striped table-bordered">
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
                               $jlpendidikan += $data->$ld ?? 0; @endphp
                            @endforeach

                            @foreach(['SMA','Non Pendidikan','SD','SMP','DIII','S1','S2','S3'] as $r)
                            @php
                            $ld = Str::lower(str_replace(' ','_',$r));
                            @endphp
                            <tr>
                                <td>{{$no+1}}</td>
                                <td>{{$r}}</td>
                                <td><b>{{$data->$ld??0}}</b></td>
                                <td><b>{{round($data->$ld /$jlpendidikan*100,2)}}%</b></td>
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
                        <table class="table table-striped table-bordered">
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
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab" style="max-height: 75vh;overflow:auto">
            @include('sisukma::dashboard.ajax.ikm_skpd')
        </div>
        @if($type_unsur == 9)
        <div class="tab-pane fade pt-4" id="ikm-kab" role="tabpanel" aria-labelledby="ikm-kab-tab" style="max-height: 75vh;overflow:auto">
            <button class="btn btn-warning btn-sm" onclick="cetak_rekap(null,$('.ajax_data').val(),'ikmkab')"> <i class="fas fa-print"></i> Cetak</button><br><br>
            @include('sisukma::report.ikm_kabupaten')
        </div>
        @endif
      </div>

</div>
@endif
