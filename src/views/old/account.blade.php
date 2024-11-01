@extends('layout.app')
@section('content')

<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">Akun</h2>
<br>
<div class="row">


<div class="col-lg-12">
<form class="" action="{{URL::full()}}" method="post">
  @csrf
  <div class="form-group">
    <label for="">Nama</label>
    <input type="text" class="form-control" name="nama" value="{{$edit->nama ?? null}}">
  </div>
  <div class="form-group">
    <label for="">Email</label>
    <input type="text" class="form-control" name="email" value="{{$edit->email ?? null}}">
  </div>
  <div class="form-group">
    <label for="">Username</label>
    <input type="text" class="form-control" name="username" value="{{$edit->username ?? null}}">
  </div>
  <div class="form-group">
    <label for="">Password</label>
    <input type="password" class="form-control" name="pass">
    <small class="text-danger">* Kosongkan jika tidak diganti</small>
  </div>
  <div class="form-group">
    <label for="">Konfirmasi Password</label>
    <input type="password" class="form-control" name="pass2">
  </div>
  <div class="form-group">
    <br>
    <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true" onclick="return confirm('Simpan Perubahan ?')"> Simpan</button>
  </div>
</form>
</div>
</div>
<br>

          </div>


@endsection
