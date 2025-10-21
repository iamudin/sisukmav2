@extends('sisukma::layout.app')
@section('content')

<div class="container-fluid px-4">
    <h3 class="mt-4" style="width:100%"> <i class="fas fa-list"></i> Data Kategori Unsur  <a href="{{route('kategori.create')}}" style="float:right" class="text-white btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Tambah</a></h3>
<br>

<div class="table-responsive">
            <table id="datatablesSimple" class="table table-striped table-bordered" style="font-size:small">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th>Nama Kategori</th>
                
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $i => $v)
                    <tr>
                        <td align="center">{{ $i+1 }}</td>
                        <td>{{ $v->nama }}</td>
                     
                        <td>{{ $v->urutan }}</td>
                        <td>
                            <div class="btn-group">
                            <a href="{{route('kategori.edit',$v->id)}}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('kategori.destroy', $v->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Anda yakin akan menghapus data? Data yang dihapus tidak dapat dikembalikan kembali')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        </td>
                    </tr>
                    @endforeach


                </tbody>
            </table>
          </div></div>
@endsection
