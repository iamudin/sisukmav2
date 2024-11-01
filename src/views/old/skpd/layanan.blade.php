@extends('layout.app')
@section('content')

@if(request()->act && request()->act=='add' || request()->act=='edit')
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">{{request()->act=='add'? 'Tambah' : 'Edit'}} Layanan   <a href="{{url('adminskpd/layanan')}}" style="float:right" class="text-white btn btn-danger btn-sm">Kembali</a></h2>
<br>
<form class="" action="{{URL::full()}}" method="post">
  @csrf
  <div class="form-group">
    <label for="">Nama Layanan</label>
    <input type="text" class="form-control" name="nama_layanan" value="{{$edit->nama_layanan ?? null}}" required>
  </div>
  <div class="form-group">
    <br>
    <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> Simpan</button>
  </div>
</form>
<br>

          </div>

@else
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">Layanan<a href="{{url('adminskpd/layanan?act=add')}}" style="float:right" class="text-white btn btn-primary btn-sm">Tambah</a></h2>
    <div class="alert alert-info">  Periode  {{$periode}} <span class="pull-right badge bg-danger" onclick="$('.periode').modal('show')" style="cursor:pointer" >Ganti Periode <i  class="fa fa-pencil" aria-hidden="true"></i></span></div>


            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th>Nama Layanan</th>
                        <th>Jumlah Responden</th>
                        <th>IKM</th>
                        <th>Mutu Pelayanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $i => $v)
                    
                    @php 
                    $respon = getResponLayanan(enc64(json_encode($v->respon))); 
                    $ikm = getIkmLayanan($respon);
                   
                    @endphp
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $v->nama_layanan }}</td>
                        <td ><a title="Klik untuk melihat rincian responden dan Impor Manual data responden" href="{{str_replace(request()->path(),'adminskpd/layanan/lihatrespon/'.enc64($v->id_layanan),url()->full())}}" class="btn btn-success btn-sm"> <b>{{$respon->count()}}</b></a></td>
                        <td><button  title="Klik untuk melihat dan mencetak rekapitulasi responden layanan ini" class="btn btn-info btn-sm" onclick="$('#dataikm').load('{{request()->year ? url()->full().'&layanan='.str_replace(' ','_',$v->nama_layanan).'&lihat='.$v->id_layanan : url()->full().'?year='.date('Y').'&layanan='.str_replace(' ','_',$v->nama_layanan).'&lihat='.$v->id_layanan}}');$('.btn-cetak').attr('href','{{request()->year ? url()->full().'&layanan='.str_replace(' ','_',$v->nama_layanan).'&lihat='.$v->id_layanan.'&cetak=true' : url()->full().'?year='.date('Y').'&layanan='.str_replace(' ','_',$v->nama_layanan).'&lihat='.$v->id_layanan.'&cetak=true'}}');$('.ikmlayanan').modal('show')">{{$ikm}}</td>
                        <td>{{prediket($ikm,true)}} ({{prediket($ikm)}})</td>
                        <td>
                            <a href="{{url('adminskpd/layanan?act=edit&id='.base64_encode($v->id_layanan))}}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                            <a onclick="return confirm('Anda yakin akan menghapus data?, data yang dihapus tidak dapat dikembalikan kembali')" href="{{ url('adminskpd/layanan?act=delete&id='.base64_encode($v->id_layanan)) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                   
                    @endforeach
                  
                 
                </tbody>
            </table>
          </div>
          @include('skpd.ikmlayanan')
          @include('periodeget')

@endif
@endsection
