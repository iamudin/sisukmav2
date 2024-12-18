@extends('layout.app')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
   <br>
   <p style="font-size:30px;border-left:5px solid orange;padding-left:10px"> <a href="{{$urlcetak??null}}&cetak=true" class="btn btn-sm btn-primary"><i class="fa fa-print" aria-hidden="true"> </i> </a> Periode <b>{{$periode}}</b> <button onclick="$('.periode').modal('show')" class="btn btn-sm btn-danger float-end"> <i class="fa fa-edit"></i> Ganti Periode</button></p>

    <div class="row">
   
              

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Kuisioner</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">9</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file fa-2x text-gray-300"></i>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$data->jumlah}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

        

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total IKM</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{round($data->ikm,2) ?? 0}}</div>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{prediket(round($data->ikm,2),true)}} ({{prediket(round($data->ikm,2))}})</div>
                                        </div>
                                        <div class="col-auto">
                                            
                                            <i class="fas fa-desktop fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-1 col-md-6 mb-4" style="cursor:pointer"  onclick="$('.saran').modal('show')">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                    
                                        <div class="col-auto">
                                            
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                            Saran
                                    </div>
                                </div>
                            </div>
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
                                    @foreach($data->usia as $k=>$row)
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td>{{$row->range}}</td>
                                        <td><b>{{$row->jumlah}}</b></td>
                                        <td><b>{{$row->jumlah && $data->usia? round($row->jumlah / array_sum(array_column($data->usia,'jumlah')) * 100,2):0}}%</b></td>
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
                                @foreach($data->pendidikan as $k=>$row)
                                @php $jlpendidikan +=$row;@endphp
                                @endforeach
                                    @php $no=0; @endphp
                                    @foreach($data->pendidikan as $k=>$row)
                                    <tr>
                                        <td>{{$no+1}}</td>
                                        <td>{{Str::upper(Str::headline($k))}}</td>
                                        <td><b>{{$row}}</b></td>
                                        <td><b>{{$row && $jlpendidikan ? round($row/$jlpendidikan *100,2):0}}%</b></td>
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
                                        <td><b>{{$row && $jlpkerjaan ? round($row/$jlpkerjaan*100,2) : 0}}%</b></td>
                                    </tr>
                                    @php $no++; @endphp
                                    @endforeach
                                </tbody>
                                   </table>
                                    </div>
                                </div>
                            </div>
                        <!-- Pie Chart -->
                        <div class="col-xl-5 col-lg-5">
                            <center>
                                <h4>QR Code Survei</h4>
                            <br>    
                            <a target="_blank" href="{{url('survei/'.enc64(session('id_skpd')))}}"><img style="width:400px;height:400px" src="{{toqr(url('survei/'.enc64(session('id_skpd'))))}}" height="100" ></a>
                        <br>
                        <br>
                        <a href="{{url('printqr/'.session('id_skpd'))}}?print=true" class="btn btn-primary btn-md"> <i class="fa fa-download" aria-hidden="true"></i> Download QR</a>
                    </center>
                            <br>
                        </div>
                    </div>


</div>
@include('skpd.lihatsaran')
@include('periode')
@endsection
