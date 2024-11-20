@extends('sisukma::layout.app')
@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4" style="width:100%"> <i class="fas fa-user"></i> Akun <a href="{{route('dashboard')}}" style="float:right" class="text-white btn btn-danger btn-sm"><i class="fas fa-undo"></i> Kembali</a></h3>
<br>
<form enctype="multipart/form-data" action="{{ route('user.account') }}" method="post">
  @csrf
    <div class="row">
        <div class="col-lg-12">
      <div class="form-group">
        <label for="" class="mb-2 mt-2">Nama</label>
        <input  type="text" class="form-control" name="nama" placeholder="Masukkan nama skpd" required value="{{ $user->nama ?? null }}">
      </div>
      <div class="form-group">
        <label for="" class="mb-2 mt-2">Username</label>
        <input type="text" class="form-control" name="username" placeholder="Masukkan Username" value="{{ $user->username ?? null }}" readonly>
      </div>
      <div class="form-group">
        <label for="" class="mb-2 mt-2">Password Baru</label>
        <input type="text" class="form-control" name="password" placeholder="Masukkan Password (jika diganti)" >
      </div>
      <div class="form-group">
        <label for="" class="mb-2 mt-2">Konfirmasi Password Baru</label>
        <input type="text" class="form-control" name="password_confirmation" placeholder="Ulangi Password">
      </div>
      <div class="form-group pb-4">
        <br>
        <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> <i class="fas fa-save"></i> Simpan</button>
        </div>
    </div>


</div>

</form>
</div>

@endsection
