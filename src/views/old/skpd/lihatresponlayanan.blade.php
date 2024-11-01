@extends('layout.app')
@section('content')
<div class="container-fluid px-4">
  <div class="row">

  <div class="col-lg-12 mt-3 mb-3 ">
  <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('adminskpd/layanan')}}">Layanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$layanan->nama_layanan}}</li>
  </ol>
</nav>
@php 
$rsp = getResponLayanan(enc64(json_encode($layanan->respon)))->count();
$bg = array(1=>'danger','warning','primary','success');
  @endphp
<h3>Responden : <b>{{$rsp}} Orang</b> <div  style="float:right"><button onclick="$('.import').modal('show')" class="text-white btn btn-success btn-sm"> <i class="fa fa-upload" aria-hidden="true"></i> Import Responden</button> <a href="{{str_replace(request()->path(),'adminskpd/layanan',url()->full())}}" class="btn btn-sm btn-danger">Kembali</a></div> </h3>
<br>

<ol>

@foreach(\App\Models\Unsur::get() as $row)
@php
$u = 'u'.$row->urutan;
@endphp
<li>{{$row->nama_unsur}}
<ol style="list-style-type: lower-alpha;">
@foreach(['1'=>$row->a,'2'=>$row->b,'3'=>$row->c,'4'=>$row->d] as $k=>$r)
@php $jlh = getResponLayanan(enc64(json_encode($layanan->respon)))->where($u,$k)->count(); 
$persen = $rsp > 0 ? round($jlh/$rsp*100,2) : 0;
@endphp
<li>{{$r}} <div class="progress">
  <div class="progress-bar bg-{{$bg[$k]}}" role="progressbar" aria-label="Basic example" style="width: {{$persen}}%" aria-valuenow="{{$persen}}" aria-valuemin="0" aria-valuemax="100">{{$persen}}%</div>
</div></li>
@endforeach
</ol>

</li>
@endforeach
</ol>
</div>

</div>
</div>
<div class="modal import" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
    <form action="{{URL::full()}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Import Responden</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning"><b>Perhatian!</b> File import harus sesuai dengan template. Silahkan <a href="{{url('template-import.xlsx')}}"> <i class="fa fa-download" aria-hidden="true"></i> Download Template</a></div>
    
        <div class="form-group">
          <label for="">Pilih Berkas Responden (format .xlsx)</label>
          <input accept=".xlsx" type="file" name="importfile" class="form-control">
        </div>
        <div class="form-group">
          <br>
          <button type="submit" name="importbtn" value="true" class="btn btn-sm btn-primary w-100">Proses Import</button>
        </div>
        <br>
        <h4>Riwayat Import</h4>
        <table class="table" >
          <thead>
            <tr>
              <th>Waktu Import</th>
              <th>Jumlah Data</th>
              <th>#</th>
            </tr>
          </thead>
        @forelse(\App\Models\Respon::whereIdLayanan($layanan->id_layanan)->whereReference('xlsx')->groupBy('created_at')->get() as $r)
        <tr>
          <td>{{$r->created_at}}</td>
          <td>{{$layanan->respon->where('created_at',$r->created_at)->count()}}</td>
          <td><button onclick="return confirm('Hapus riwayat data ini ?')" class="btn btn-sm btn-danger" type="submit" name="delimport" value="{{enc64($r->created_at)}}"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
        </tr>
        @empty
        <tr>
          <td colspan="3">Belum ada data</td>
        </tr>
        @endforelse
        </table>
</div>
</form>
</div>
</div>
</div>
@endsection