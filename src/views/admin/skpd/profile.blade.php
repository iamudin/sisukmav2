@extends('sisukma::layout.app')
@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4" style="width:100%"> <i class="fas fa-gears"></i> {{request()->segment('3')=='create'? 'Tambah' : 'Edit'}} Pengaturan   <a href="{{route('dashboard')}}" style="float:right" class="text-white btn btn-danger btn-sm"><i class="fas fa-undo"></i> Kembali</a></h3>
<br>
<form class="" action="{{ route('skpd.profile') }}" enctype="multipart/form-data" method="post">
  @csrf

    <div class="row">
        <div class="col-lg-12">
      <div class="form-group">
        <label for="" class="mb-2 mt-2">Nama</label>
        <input  type="text" class="form-control" name="nama_skpd" placeholder="Masukkan nama skpd" required value="{{ $edit->nama_skpd ??null }}">
      </div>
      <div class="form-group">
        <label for="" class="mb-2 mt-2">Alamat</label>
        <input  type="text" class="form-control" name="alamat" placeholder="Masukkan alamat"  value="{{ $edit->alamat ??null }}">
      </div>
      <div class="form-group">
        <label for="" class="mb-2 mt-2">Email</label>
        <input  type="text" class="form-control" name="email" placeholder="Masukkan email email@email.com"  value="{{ $edit->email ??null }}">
      </div>
      <div class="form-group">
        <label for="" class="mb-2 mt-2">Website</label>
        <input  type="text" class="form-control" name="website" placeholder="Masukkan website http://namaweb.go.id"  value="{{ $edit->website ??null }}">
      </div>
      <div class="form-group">
        <label for="" class="mb-2 mt-2">Telp</label>
        <input type="text" class="form-control" name="telp" placeholder="Masukkan telp 0712345678"  value="{{ $edit->telp ??null }}">
      </div>
      <div class="form-group">
        <label for="" class="mb-2 mt-2">Isi Survei Hanya Pada Jam kerja ? (08:00 - 12.00 & 13:00 - 17:00)</label><br>
        <input type="radio"  name="dibatasi" value="Y" @if($edit->exists && $edit->dibatasi=='Y') checked @else checked @endif> Iya <br>
        <input type="radio"  name="dibatasi" value="N" @if($edit->exists && $edit->dibatasi=='N') checked @endif> Tidak, Boleh kapan saja

      </div>
      <div class="form-group">
        <label for="" class="mb-2 mt-2">Banner Pelayanan</label>

        @if($edit->exists && $edit->banner && Storage::disk('public')->exists($edit->banner))

        <img src="{{ asset('storage/'.$edit->banner) }}" class="rounded w-100 h-50 mb-4" alt="">
        <input type="checkbox" name="tampilkan_banner" value="Y" @if($edit->tampilkan_banner=='Y') checked @endif> <span class="text-danger">Tampilkan Banner di Halaman Survei</span>
        <br><br>
        @endif
        <input type="file" accept="image/png,image/jpeg" class="form-control" name="banner" >
    </div>
    </div>

<div class="form-group pb-4">
<br>
<button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> <i class="fas fa-save"></i> Simpan</button>
</div>
</div>

</form>
</div>

@endsection
