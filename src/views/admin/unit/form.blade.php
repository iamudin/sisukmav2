@extends('sisukma::layout.app')
@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4" style="width:100%"> {!!  request()->segment('3')=='create'? '<i class="fas fa-plus"></i>  Tambah' : '<i class="fas fa-edit"></i>  Edit'!!} Unit Pelayanan   <a href="{{route('unit.index')}}" style="float:right" class="text-white btn btn-danger btn-sm"><i class="fas fa-undo"></i> Kembali</a></h3>
<br>
<form class="" action="{{$edit->exists ? route('unit.update',$edit->id) : route('unit.store') }}" method="post">
  @csrf
    @if($edit->exists)
    @method('PUT')
    @endif
    @if($skpd)
    <div class="form-group">
        <label for="">Nama SKPD</label>
        <select class="form-control" name="skpd_id" required>
            <option value="">--Pilih SKPD--</option>
            @foreach($skpd as $i => $v)
            <option value="{{ $v->id }}" @if($edit->exists && $edit->skpd_id == $v->id) selected @endif >{{ $v->nama_skpd }}</option>
            @endforeach
        </select>
    </div>
    @endif
      <div class="form-group">
        <label for="">Nama Unit</label>

        <input class="form-control" name="nama" placeholder="Masukkan Nama Unit Pelayanan" value="{{ $edit->nama ??null }}" required>
      </div>
  <div class="form-group">
    <br>
    <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> <i class="fas fa-save"></i> Simpan</button>
  </div>
</form>

          </div>

@endsection
