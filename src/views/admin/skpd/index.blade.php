@extends('sisukma::layout.app')
@section('content')

<div class="container-fluid px-4">
    <h3 class="mt-4" style="width:100%"> <i class="fas fa-desktop"></i> Data Perangkat Daerah  <a href="{{route('skpd.create')}}"  class="text-white btn btn-primary btn-sm float-end"> <i class="fa fa-plus"></i> Tambah</a></h3>
<br>
<div class="table-responsive">
    <table id="datatablesSimple" class="table table-striped table-bordered" style="font-size:small">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="110px">QR Code</th>
                <th>Nama SKPD </th>
                <th>Jenis</th>
                <th>Periode</th>
                <th>Jumlah Unit</th>
                <th>Jumlah Layanan</th>
                <th>User</th>
                <th >Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $i => $v)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ qrcode(url('survei/'.base64_encode($v->id)),100) }}</td>
                <td><b >{{ $v->nama_skpd }}</b><br><small>Link Survei : <a href="{{ url('survei/'.base64_encode($v->id)) }}">{{ url('survei/'.base64_encode($v->id)) }}</a></small></td>
                <td>{{ $v->total_unsur }} Kuisioner</td>
                <td>
                    <ul class="m-0 p-0 ps-2">
                    @foreach($v->periode_aktif as $row)
                    <li>{{ $row->tahun }}</li>
                    @endforeach
                </ul>
                </td>
                <td>{{ $v->units_count }}</td>
                <td>{{ $v->layanans_count }}</td>
                <td width="200px">
                    @if($v->user)
                   <b> {{ $v->user?->username}}</b><br><small>Terkahir login : <br><span class="badge bg-dark">{{ $v->user?->last_login_at }}</span></small>
                    @endif
                </td>

                <td width="20px" >
                    <div class="btn-group">

                    <a  href="{{route('skpd.edit',$v->id)}}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                    <form class="btn btn-sm btn-danger" style="margin:0;padding:0;padding-top:4px" action="{{ route('skpd.destroy', $v->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Anda yakin akan menghapus data? Data yang dihapus tidak dapat dikembalikan kembali')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"  style="background:transparent;border:none">
                            <i class="fa fa-trash text-white"></i>
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
