@extends('sisukma::layout.app')
@section('content')

<div class="container-fluid px-4">
    <h3 class="mt-4" style="width:100%"> <i class="fas fa-camera"></i> Gallery
        @if(Auth::user()->isSkpd())
        <a href="{{route('gallery.create')}}" style="float:right" class="text-white btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Tambah</a>
        @endif
    </h3>
<br>
<div class="table-responsive">
    <table id="datatablesSimple" class="table table-striped table-bordered" style="font-size:small">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th>Nama </th>
                <th>Jumlah Foto</th>
                <th>Perangkat Daerah</th>
                <th>Dipublikasikan</th>
                <th >Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $i => $v)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $v->nama }}</td>
                <td>{{ $v->images->count()}}</td>
                <td>{{ $v->skpd?->nama_skpd}}</td>
                <td>{{ $v->status == 'Y' ? 'Publik' : 'Draft'}}</td>
                <td>
                    <div class="btn-group">
                    <a  href="{{route('gallery.edit',$v->id)}}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                    <form action="{{ route('gallery.destroy', $v->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Anda yakin akan menghapus data? Data yang dihapus tidak dapat dikembalikan kembali')">
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
