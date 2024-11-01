@extends('layout.app')
@section('content')

@if(request()->act && request()->act=='add' || request()->act=='edit')
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">{{request()->act=='add'? 'Tambah' : 'Edit'}} Perangkat Daerah   <a href="{{url('admin/data-skpd')}}" style="float:right" class="text-white btn btn-danger btn-sm">Kembali</a></h2>
<br>
<div class="row">

<div class="col-lg-4">
@if($edit)
<img class="w-100" src="{{toqr(url('survei/'.enc64($edit->id_skpd)))}}" >
@endif
</div>
<div class="col-lg-8">
<form class="" action="{{URL::full()}}" method="post">
  @csrf
  <div class="form-group">
    <label for="">Nama SKPD</label>
    <input type="text" class="form-control" name="nama_skpd" value="{{$edit->nama_skpd ?? null}}">
  </div>
  <div class="form-group">
    <label for="">Alamat</label>
    <textarea class="form-control" name="alamat">{{$edit->alamat ?? null}}</textarea>
  </div>
  <div class="form-group">
    <label for="">Gunakan Sebagai Sample</label><br>
    @foreach(['0'=>'Tidak','1'=>'Iya'] as $k=>$r)
    <input type="radio" name="status_sample" {{$edit && $edit->status_sample ? ($edit->status_sample==$k ? 'checked' : '') :''}} value="{{$k}}" id=""> {{$r}} <br>
    @endforeach
  </div>
  <div class="form-group">
    <label for="">Tahun Sample</label><br>
    @for($i=2022; $i<=date('Y'); $i++)
    <input type="checkbox" name="tahun_sample[]" @if($edit && $edit->periodeAktif()->where('tahun',$i)->exists()) checked="checked" @endif value="{{$i}}" id=""> {{$i}} <br>
    @endfor
  </div>
  <div class="form-group">
    <br>
    <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> Simpan</button>
  </div>
</form>
</div>
</div>
<br>

          </div>

@else
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">Data Perangkat Daerah
    <div style="float:right"> <a href="{{url('admin/data-skpd?act=add')}}"  class="text-white btn btn-primary btn-sm">Tambah</a></div></h2>
<br>
<p style="font-size:30px;border-left:5px solid orange;padding-left:10px">Periode <b>{{$periode}}</b> <button onclick="$('.periode').modal('show')" class="btn btn-sm btn-danger float-end"> <i class="fa fa-edit"></i> Ganti Periode</button></p>
<br>
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th>QR</th>
                        <th>Nama SKPD</th>
                        <th>Jumlah Layanan</th>
                        <th>Nilai IKM</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                  @php
                 $ik = new \App\IkmManager;
                @endphp
                  @foreach(DB::table('skpd')->get() as $k=>$row)
                  @php
                  $ikm = $ik->nilai_ikm_skpd($row->id_skpd)['ikm'];
                  @endphp
                  <tr>
                    <td>{{$k+1}}</td>
                    <td> <a target="_blank" href="{{'https://sisukma.bengkaliskab.go.id/survei/'.enc64($row->id_skpd)}}"><img src="{{toqr('https://sisukma.bengkaliskab.go.id/survei/'.enc64($row->id_skpd))}}" height="100" ></a></td>
                    <td>{{$row->nama_skpd}}</td>
                    <td>{{jlh_layanan($row->id_skpd)}}</td>
                    <td>{{$ikm ? round($ikm,2) : 0}}</td>
                    <td style="width:120px">

                      <a href="{{url('adminskpd/respon-layanan/'.$row->id_skpd.'?year='.($per ?($per['year'] ? $per['year'] :null) : date('Y')).'&bulan='.($per && isset($per['month']) ? ($per['month']? $per['month'] :null) :null).'&cetak=true')}}" class="btn btn-primary btn-sm"> <i class="fa fa-print" aria-hidden="true"></i> </a>
                      <a href="{{url('admin/data-skpd?act=edit&id='.enc64($row->id_skpd))}}" class="btn btn-warning btn-sm"> <i class="fas fa-edit    "></i></a>
                      <a href="{{url('admin/data-skpd?act=delete&id='.enc64($row->id_skpd))}}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ?')"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
            </table>
          </div>
          @include('periode')

@endif

@endsection
