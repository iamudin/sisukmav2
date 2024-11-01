@extends('layout.app')
@section('content')

<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">Pengaturan </h2>
<br>
<form class="" action="{{URL::full()}}" method="post" enctype="multipart/form-data">
  @csrf
  <div class="form-group">
    <label for="">Nama SKPD</label>
    <input type="text" disabled class="form-control" name="nama_layanan" value="{{$data->nama_skpd ?? null}}" >
  </div>
  <div class="form-group">
    <label for="">Alamat</label>
    <input type="text"  class="form-control" name="alamat" value="{{$data->alamat ?? null}}" >
  </div>
  <div class="form-group">
    <label for="">Telp</label>
    <input type="text"  class="form-control" name="telp" value="{{$data->telp ?? null}}" >
  </div>
  <div class="form-group">
    <label for="">Email</label>
    <input type="email"  class="form-control" name="email" value="{{$data->email ?? null}}" >
  </div>
  <div class="form-group">
    <label for="">Isi Survei Hanya Pada Jam kerja ? (08:00 - 12.00 & 13:00 - 17:00)</label><br>
    <input type="radio" name="survei" @if($data->dibatasi=='Y') checked @endif value="Y"> Iya<br>
    <input type="radio" name="survei"  @if($data->dibatasi=='N') checked @endif value="N"> Tidak, Boleh Kapan Saja.
  </div>
  <div class="form-group">
    <label for="">Banner</label>
    @if($data->banner && file_exists(public_path($data->banner)))
    <br>
  <img src="{{asset($data->banner)}}" class="img-fluid" alt="">    
   <br>
   <small class="text-danger">(*) Pilih gambar untuk mengganti</small>
    @endif
    <input type="file"  class="form-control" name="banner" >
  </div>
  <div class="form-group">
    <br>
    <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> Simpan</button>
  </div>
</form>
<br>

          </div>

@endsection