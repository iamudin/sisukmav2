@extends('sisukma::layout.app')
@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4" style="width:100%"> {!!  request()->segment('3')=='create'? '<i class="fas fa-plus"></i>  Tambah' : '<i class="fas fa-edit"></i>  Edit'!!} Layanan   <a href="{{route('layanan.index')}}" style="float:right" class="text-white btn btn-danger btn-sm"><i class="fas fa-undo"></i> Kembali</a></h3>
<br>
<form class="" action="{{$edit->exists ? route('layanan.update',$edit->id) : route('layanan.store') }}" method="post">
  @csrf
    @if($edit->exists)
    @method('PUT')
    @endif
    @if($skpd)
    <div class="form-group">
        <label for="">Nama SKPD</label>
        <select class="form-control" name="skpd_id" required onchange="if(this.value)location.href='{{ route('layanan.create') }}?skpd='+this.value">
            <option value="">--Pilih SKPD--</option>
            @foreach($skpd as $i => $v)
            <option value="{{ $v->id }}" @if($edit->exists && $edit->skpd_id == $v->id || (request('skpd') && request('skpd')==$v->id)) selected @endif >{{ $v->nama_skpd }}</option>
            @endforeach
        </select>
    </div>

    @endif
    <div class="form-group">
        <label for="">Unit Pelayanan</label>
        <select class="form-control" name="unit_id">
            <option value="">--Pilih unit--</option>
            @if(!empty($edit->unit->id) || $unit)
            @foreach($unit as $i => $v)
            <option value="{{ $v->id }}" @if($edit->exists && $edit->unit?->id == $v->id) selected @endif >{{ $v->nama }}</option>
            @endforeach
            @endif
        </select>
    </div>
      <div class="form-group">
        <label for="">Nama Layanan</label>

        <input class="form-control" name="nama_layanan" placeholder="Masukkan Nama Layanan" value="{{ $edit->nama_layanan ??null }}" required>
      </div>
  <div class="form-group">
    <br>
    <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> <i class="fas fa-save"></i> Simpan</button>
  </div>
</form>

          </div>

@endsection
