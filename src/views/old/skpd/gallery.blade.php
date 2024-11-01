@extends('layout.app')
@section('content')

@if(request()->act && request()->act=='add' || request()->act=='edit')
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">{{request()->act=='add'? 'Tambah' : 'Edit'}} Gallery   <a href="{{url('adminskpd/gallery')}}" style="float:right" class="text-white btn btn-danger btn-sm">Kembali</a></h2>
<br>
<form class="" action="{{URL::full()}}" method="post">
  @csrf
  <div class="form-group">
    <label for="">Nama Gallery</label>
    <input type="text" class="form-control" name="nama" value="{{$data->nama ?? null}}" required>
  </div>
  @if($data && $foto)
  <div class="form-group">
    <label for="">Daftar Foto</label>
    <br>
    <div class="row">
    @foreach($foto as $r)
    <div class="col-lg-3 col-6">
      <a onclick="return confirm('Hapus?')" href="{{URL::full().'&delete_foto='.$r->path}}"><i class="fa fa-trash text-danger"></i></a>
    <figure>
  <img  class="rounded" src="{{asset($r->path)}}" alt="Trulli" style="width:100%">
  <figcaption><center>{{$r->caption}}</center></figcaption>
</figure>
</div>
    
    @endforeach
    <div class="col-lg-2 col-4"> <img src="https://static.thenounproject.com/png/187803-200.png" style="cursor:pointer" alt="" onclick="$('.modal').modal('show')"> </div>
</div>
  </div>
  @endif
  <div class="form-group">
    <br>
    <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> Simpan Perubahan</button>
  </div>
</form>
<br>


          </div>
          <div class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{URL::full()}}" method="post" enctype="multipart/form-data">
            @csrf
      <div class="modal-header">
        <h5 class="modal-title">Upload Foto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="form-group">
        <label for="">Gambar</label>
        <input  accept="image/png, image/jpeg" type="file" class="form-control" name="path" required>
      </div>
      <div class="form-group">
        <label for="">Keterangan</label>
        <textarea  class="form-control" name="caption" required></textarea>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="upload" value="true" class="btn btn-primary">Submit</button>
      </div>
</form>
    </div>
  </div>
</div>

@else
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">Data Gallery  <a href="{{url('adminskpd/gallery?act=add')}}" style="float:right" class="text-white btn btn-primary btn-sm">Tambah</a></h2>
<br>
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th>Nama Gallery</th>
                        <th>Jumlah Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $i => $v)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $v->nama }}</td>
                        <td style="text-align:center">{{DB::table('img_gallery')->whereIdGallery($v->id)->count()}}</td>
                        <td>
                            <a href="{{url('adminskpd/gallery?act=edit&id='.base64_encode($v->id))}}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                            <a onclick="return confirm('Anda yakin akan menghapus data?, data yang dihapus tidak dapat dikembalikan kembali')" href="{{ url('adminskpd/gallery?act=delete&id='.base64_encode($v->id)) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                  
                 
                </tbody>
            </table>
          </div>

@endif
@endsection
