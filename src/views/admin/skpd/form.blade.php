@extends('sisukma::layout.app')
@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4" style="width:100%">{{request()->segment('3')=='create'? 'Tambah' : 'Edit'}} Perangkat Daerah   <a href="{{route('skpd.index')}}" style="float:right" class="text-white btn btn-danger btn-sm"><i class="fas fa-undo"></i> Kembali</a></h3>
<br>
<form class="" action="{{$edit->exists ? route('skpd.update',$edit->id) : route('skpd.store') }}" method="post">
  @csrf
    @if($edit->exists)
    @method('PUT')
    @endif

      <div class="form-group">
        <label for="">Nama SKPD</label>

        <input class="form-control" name="nama_skpd" placeholder="Masukkan nama skpd" required value="{{ $edit->nama_skpd ??null }}">
      </div>
  <div class="form-group">
    <br>
    <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> <i class="fas fa-save"></i> Simpan</button>
  </div>
</form>

          </div>

@endsection
