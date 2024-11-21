@extends('sisukma::layout.app')
@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4" style="width:100%"> {!!  request()->segment('3')=='create'? '<i class="fas fa-plus"></i>  Tambah' : '<i class="fas fa-edit"></i>  Edit'!!} Gallery<a href="{{route('gallery.index')}}" style="float:right" class="text-white btn btn-danger btn-sm"><i class="fas fa-undo"></i> Kembali</a></h3>
<br>
<form class="" action="{{$edit->exists ? route('gallery.update',$edit->id) : route('gallery.store') }}" method="post" enctype="multipart/form-data">
  @csrf

      <div class="form-group">
        <label for="">Nama</label>
        <input class="form-control" name="nama" placeholder="Masukkan Nama Gallery" value="{{ $edit->nama ??null }}" required>
      </div>


      <div class="form-group">

        <label for="">Status</label><br>
        <input type="radio"  name="aktif" value="Y" required @if($edit->exists && $edit->aktif=='Y') checked @else checked @endif > Publikasikan
        <input type="radio" name="aktif" value="N" required  @if($edit->exists && $edit->aktif=='N') checked @endif> Draft
      </div>
  <div class="form-group">
    <br>
    @if($edit->exists)
    @method('put')
    @endif
    <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> <i class="fas fa-save"></i> Simpan</button>
    <br>
    <br>
  </div>
</form>


@if($edit->exists)
<label for="">Daftar Gambar</label>
<div class="form-group"  style="max-height: 40vh;overflow:auto">
  <table class="table table-striped table-bordered" style="font-size:small">
      <thead>
      <tr>
          <th>Gambar</th>
          <th>Caption</th>
          <th>Aksi</th>
      </tr>
  </thead>
  <tbody>
      @foreach($edit->images as $row)
      <tr>
          <td style="width:90px">
              <img src="{{ Storage::url($row->path) }}" height="70" alt="" class="">
          </td>
          <td>{{ $row->caption }}</td>

          <td style="width:50px">
              <form action="{{ route('imgGallery.destroy',$row->id) }}" onsubmit="return confirm('Yakin untuk menghapus?')" method="post">
                @csrf
                @method('delete')
              <button type="submit" class="btn btn-sm btn-danger">
                  <i class="fas fa-trash"></i>
              </button>
              </form>
          </td>
      </tr>
      @endforeach

  </tbody>
  </table>
</div>
<div class="form-group">
    <form class="" action="{{$edit->exists ? route('gallery.update',$edit->id) : route('gallery.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
  <label for=""> <i class="fas fa-plus"></i> Tambah Gambar</label>
  <hr>
  <table class="table table-striped table-bordered">
      <tr>
          <td style="width:90px"><input type="file" accept="image/png,image/jpg" name="gambar" id="" class="form-control"></td>
          <td><textarea name="caption" id="" class="form-control" placeholder="masukkan caption"></textarea></td>
      </tr>
  </table>
  <button type="submit" class="btn float-end btn-primary btn-sm" name="upload" value="true"> <i class="fas fa-upload"></i> Upload</button>

    </form>
</div>

@endif

          </div>

@endsection
