@extends('sisukma::layout.app')
@section('content')

<div class="container-fluid px-4">
    <h3 class="mt-4" style="width:100%"> <i class="fas fa-table"></i> Import Data Responden <div class="btn-group float-end">
    <a href="{{ Storage::url('template.xlsx') }}" class="btn btn-sm btn-info"> <i class="fas fa-download"></i> Format</a>
    <a href="javascript:void(0)" onclick="$('#fileimport').click()" class="btn btn-sm btn-success"> <i class="fas fa-upload"></i> Import</a>
    </div> </h3>
    <div class="alert alert-info mt-4">
        <strong>{{ $layanan->nama_layanan }}</strong>
    </div>
    <form action="{{ URL::current() }}" method="post" enctype="multipart/form-data">
        @csrf
    <input type="file" name="fileimport" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" id="fileimport" style="display:none" onchange="if(confirm('Import ? ')) {this.form.submit();}">
    </form>
<br>
<div class="table-responsive">
    <table id="datatablesSimple" class="table table-striped table-bordered" style="font-size:small">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th>Tanggal Import</th>
                <th>Jumlah Responden</th>
                <th >Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $i => $v)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $v->date }}</td>
                <td>{{ $v->count}}</td>

                <td style="width:10px">
                    <div class="btn-group">
                        <form action="{{ route('responden.destroy.date',$layanan->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Anda yakin akan menghapus data? Data yang dihapus tidak dapat dikembalikan kembali')">
                            @csrf

                            <button type="submit" name="date" value="{{ $v->date }}" class="btn btn-sm btn-danger">
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
