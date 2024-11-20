@extends('sisukma::layout.app')
@section('content')

<div class="container-fluid px-4">
    <h3 class="mt-4" style="width:100%"> <i class="fas fa-table"></i> Data Responden</h3>
<br>
<div class="table-responsive">
    <table id="datatablesSimple" class="table table-striped table-bordered" style="font-size:small">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th>Nama Layanan</th>
                <th>Total Responden</th>
                <th >Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $i => $v)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $v->nama_layanan }}</td>
                <td>{{ $v->respons_count}}</td>

                <td style="width:100px">
                    <div class="btn-group">
                    <a  href="{{route('responden.import',$v->id)}}" class="btn btn-sm btn-warning"><i class="fa fa-upload"></i> Import</a>

                </div>
                </td>
            </tr>
            @endforeach


        </tbody>
    </table>
          </div></div>
@endsection
