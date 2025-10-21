@extends('sisukma::layout.app')
@section('content')
  <div class="container-fluid px-4">
      <h3 class="mt-4" style="width:100%">{{request()->segment('3')=='create'? 'Tambah' : 'Edit'}} Unsur   <a href="{{route('kategori.index')}}" style="float:right" class="text-white btn btn-danger btn-sm"><i class="fas fa-undo"></i> Kembali</a></h3>
  <br>
  <form class="" action="{{$edit->exists ? route('kategori.update',$edit->id) : route('kategori.store') }}" method="post">
    @csrf
      @if($edit->exists)
      @method('PUT')
      @endif
    <div class="form-group mb-4">
      <label for="">Nama Kategori</label>
      <textarea placeholder="Masukkan nama unsur" class="form-control" name="nama" required>{{ $edit->nama ?? null }}</textarea>
    </div>
    <div class="input-group mb-2">
      <label class="btn btn-dark" for="">Urutan</label>
      <input placeholder="Masukkan Keterangan"  class="form-control"type="number" name="urutan" value="{{ $edit->urutan ?? null }}">
    </div>
    <div class="form-group">
      <br>
      <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> <i class="fas fa-save"></i> Simpan</button>
    </div>
  </form>

            </div>

@endsection
