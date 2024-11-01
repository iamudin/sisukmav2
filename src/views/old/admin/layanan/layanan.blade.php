@extends('layout.app')
@section('content')

@if(request()->act && request()->act=='add' || request()->act=='edit')
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">{{request()->act=='add'? 'Tambah' : 'Edit'}} Layanan   <a href="{{url('admin/layanan')}}" style="float:right" class="text-white btn btn-danger btn-sm">Kembali</a></h2>
<br>
<form class="" action="{{url((!empty($edit))? 'admin/layanan/update':'admin/layanan/create')}}" method="post">
  @csrf
  <div class="form-group">
    <label for="">Nama SKPD</label>
    <select class="form-control" name="id_opd" required>
        <option>--Pilih SKPD--</option>
        @foreach($skpd as $i => $v)
        <option value="{{ $v->id_skpd }}" @if(!empty($edit))@if($edit['id_skpd']==$v->id_skpd) selected @endif @endif>{{ $v->nama_skpd }}</option>
        @endforeach

    </select>
  </div>
  <div class="form-group">
    <label for="">Nama Layanan</label>
    <input type="hidden" name="id" value="<?php (!empty($edit)) ? print $edit->id_layanan:''; ?>">
    <textarea class="form-control" name="layanan" required><?php (!empty($edit)) ? print $edit->nama_layanan:''; ?></textarea>
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
    <h2 class="mt-4" style="width:100%">Data Layanan  <div style="float:right"><a href="{{url('admin/layanan?act=add')}}"  class="text-white btn btn-primary btn-sm">Tambah</a></div></h2>
<br>
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th>Nama Layanan </th>
                        <th>SKPD</th>
                        <th>Total Responden</th>
                        <th >Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $i => $v)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $v->nama_layanan }}</td>
                        <td>{{ $v->nama_skpd}}</td>
                        <td>{{ jlh_respon($v->id_layanan) }}</td>
                        
                        <td >
                            <a  href="{{url('admin/layanan?act=edit&id='.base64_encode($v->id_layanan))}}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                            <a onclick="return confirm('Anda yakin akan menghapus data?, data yang dihapus tidak dapat dikembalikan kembali')" href="{{ url('admin/layanan/delete/'.base64_encode($v->id_layanan)) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                  
                 
                </tbody>
            </table>
          </div>

@endif
@endsection
