@extends('layout.app')
@section('content')

@if(request()->act && request()->act=='add' || request()->act=='edit')
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">{{request()->act=='add'? 'Tambah' : 'Edit'}} Unsur   <a href="{{url('admin/unsur')}}" style="float:right" class="text-white btn btn-danger btn-sm">Kembali</a></h2>
<br>
<form class="" action="{{url((!empty($edit))? 'admin/unsur/update':'admin/unsur/create')}}" method="post">
  @csrf
  
  <div class="form-group">
    <label for="">Nama Unsur</label>
    <input type="hidden" name="id" value="<?php (!empty($edit)) ? print $edit->id_unsur:''; ?>">
    <textarea class="form-control" name="nama" required><?php (!empty($edit)) ? print $edit->nama_unsur:''; ?></textarea>
  </div>
  <div class="form-group">
    <label for="">A</label>
    <input class="form-control"type="text" name="a" value="<?php (!empty($edit)) ? print $edit->a:''; ?>">
  </div>
  <div class="form-group">
    <label for="">B</label>
    <input class="form-control"type="text" name="b" value="<?php (!empty($edit)) ? print $edit->b:''; ?>">
  </div>
  <div class="form-group">
    <label for="">C</label>
    <input class="form-control"type="text" name="c" value="<?php (!empty($edit)) ? print $edit->c:''; ?>">
  </div>
  <div class="form-group">
    <label for="">D</label>
    <input class="form-control"type="text" name="d" value="<?php (!empty($edit)) ? print $edit->d:''; ?>">
  </div>
  <div class="form-group">
    <label for="">Urutan</label>
    <input class="form-control"type="number" name="urutan" value="<?php (!empty($edit)) ? print $edit->urutan:''; ?>">
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
    <h2 class="mt-4" style="width:100%">Data Unsur  <a href="{{url('admin/unsur?act=add')}}" style="float:right" class="text-white btn btn-primary btn-sm">Tambah</a></h2>
<br>
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th>Nama Unsur</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $i => $v)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $v->nama_unsur }}</td>
                        <td>{{ $v->a }}</td>
                        <td>{{ $v->b }}</td>
                        <td>{{ $v->c }}</td>
                        <td>{{ $v->d }}</td>
                        <td>{{ $v->urutan }}</td>
                        <td>
                            <a href="{{url('admin/unsur?act=edit&id='.base64_encode($v->id_unsur))}}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                            <a onclick="return confirm('Anda yakin akan menghapus data?, data yang dihapus tidak dapat dikembalikan kembali')" href="{{ url('admin/unsur/delete/'.base64_encode($v->id_unsur)) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                  
                 
                </tbody>
            </table>
          </div>

@endif
@endsection
