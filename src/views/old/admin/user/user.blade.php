@extends('layout.app')
@section('content')

@if(request()->act && request()->act=='add' || request()->act=='edit')
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">{{request()->act=='add'? 'Tambah' : 'Edit'}} User Perangkat Daerah   <a href="{{url('admin/userskpd')}}" style="float:right" class="text-white btn btn-danger btn-sm">Kembali</a></h2>
<br>
<form class="" action="{{url((!empty($edit))? 'admin/userskpd/update':'admin/userskpd/create')}}" method="post">
  @csrf
  <div class="form-group">
    <label for="">SKPD</label>
    <select class="form-control" name="id_opd" required>
        <option>--Pilih SKPD--</option>
        @foreach($skpd as $i => $v)
        <option value="{{ $v->id_skpd }}" @if(!empty($edit))@if($edit['id_skpd']==$v->id_skpd) selected @endif @endif>{{ $v->nama_skpd }}</option>
        @endforeach

    </select>
  </div>
  <div class="form-group">
    <label for="">Nama user</label>
    <input type="hidden" name="id" value="<?php (!empty($edit)) ? print $edit->id:''; ?>">
    <input type="text" class="form-control" name="nama" value="<?php (!empty($edit)) ? print $edit->nama:''; ?>" required>
  </div>
  <div class="form-group">
    <label for="">Username</label>
    <input type="text" name="username" class="form-control" value="<?php (!empty($edit)) ? print $edit->username:''; ?>" required>
  </div>
   <div class="form-group">
    <label for="">Password</label>
    <input type="password" name="password" class="form-control" required>
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
    <h2 class="mt-4" style="width:100%">Data USER SKPD  <a href="{{url('admin/userskpd?act=add')}}" style="float:right" class="text-white btn btn-primary btn-sm">Tambah</a></h2>
<br>
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th>Nama User</th>
                        <th>SKPD</th>
                        <th>username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $i => $v)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $v->nama }}</td>
                        <td>{{ $v->nama_skpd }}</td>
                        <td>{{ $v->username }}</td>
                        <td>
                            <a href="{{url('admin/userskpd?act=edit&id='.base64_encode($v->id))}}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                            <a onclick="return confirm('Anda yakin akan menghapus data?, data yang dihapus tidak dapat dikembalikan kembali')" href="{{ url('admin/userskpd/delete/'.base64_encode($v->id)) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                        
                        </td>
                    
                        
                    </tr>
                    @endforeach
                  
                  
                 
                </tbody>
            </table>
          </div>

@endif
@endsection
