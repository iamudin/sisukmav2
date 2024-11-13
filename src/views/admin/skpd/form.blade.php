@extends('sisukma::layout.app')
@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4" style="width:100%">{{request()->segment('3')=='create'? 'Tambah' : 'Edit'}} Perangkat Daerah   <a href="{{route('skpd.index')}}" style="float:right" class="text-white btn btn-danger btn-sm"><i class="fas fa-undo"></i> Kembali</a></h3>
<br>
<form class="" action="{{$edit->exists ? route('skpd.update',$edit->id) : route('skpd.store') }}" enctype="multipart/form-data" method="post">
  @csrf
    @if($edit->exists)
    @method('PUT')
    @endif
    <div class="row">
        <div class="col-lg-6">
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
        <label for="" class="mb-2 mt-2">Banner Pelayanan</label>

        @if($edit->exists && $edit->banner && Storage::disk('public')->exists($edit->banner))
        <img src="{{ asset('storage/'.$edit->banner) }}" class="rounded w-100 h-50 mb-4" alt="">
        @endif
        <input type="file" class="form-control" name="banner" placeholder="Masukkan telp 0712345678"  value="{{ $edit->telp ??null }}">
    </div>
    </div>
    <div class="col-lg-6 ps-lg-4">
        <div class="form-group">
            <label for="" class="mb-2 mt-2">Jumlah Unsur yang dinilai :</label><br>
            @foreach(['9','11'] as $row)
            <input type="radio" name="total_unsur" @if($edit->exists && $edit->total_unsur==$row ) checked @endif value="{{ $row }}"> {{ $row }} Unsur<br>
            @endforeach
        </div>
        <div class="form-group">
            <label for="" class="mb-2 mt-2">Periode Penilaian Aktif : </label><br>
            @for($tahun=2022; $tahun<=date('Y')+1; $tahun++)
            <input type="checkbox" @if($edit->exists && $edit->periode_aktif()->where('tahun',$tahun)->exists()) checked="checked" @endif  name="periode_aktif[]" value="{{$tahun}}"> {{$tahun}} <br>
            @endfor
        </div>
        <br>
        <h6 class="fw-bold"> <i class="fas fa-lock"></i> Akses</h6>
        <hr>
        <div class="form-group">
            <label for="" class="mb-2">Nama Admin</label>
            <input required type="text" class="form-control" name="nama_admin" placeholder="Masukkan  nama"  value="{{ $edit->user?->nama ??null }}">
          </div>
          <div class="form-group">
            <label for="" class="mb-2">Username</label>
            <input required type="text" class="form-control" name="username" placeholder="Masukkan  Username"  value="{{ $edit->user?->username ?? null }}">
          </div>
          <div class="form-group">
            <label for="" class="mb-2">Password</label>
            <input type="text" class="form-control" name="password" placeholder="Masukkan  Password min 8 karakter" >
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
