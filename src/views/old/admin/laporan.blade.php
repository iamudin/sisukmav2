@extends('layout.app')
@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">Laporan</h2>
<br>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        Data Booking dan Service <i class="fas fa-print float-end" aria-hidden></i>
      </div>
      <div class="card-body">
        <form class="" action="{{URL::full()}}" method="get">
          @csrf
          <div class="row">
            <div class="col-lg-3">
              <label>Dari Tanggal</label>
                <input value="{{request()->dari_tanggal??null}}" type="date" name="dari_tanggal" class="form-control" value="" >
            </div>
            <div class="col-lg-3">
              <label>Sampai Tanggal</label>
              <input value="{{request()->sampai_tanggal ?? null}}" type="date" name="sampai_tanggal" class="form-control" value="" >
            </div>
            <div class="col-lg-3">

              <label>Status</label>
              <select class="form-conrol form-select" name="status_booking" required>
                <option value="">--pilih--</option>
                @foreach(DB::table('tbl_status_booking')->get() as $r)
                <option {{request()->status_booking && request()->status_booking==$r->id_status ? 'selected':''}} value="{{$r->id_status}}">{{$r->nama_status}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-lg-3">
              <label>Jenis Perbaikan</label>
              <select class="form-conrol form-select" name="jenis_perbaikan" required>
                <option value="">--pilih--</option>
                @foreach(DB::table('tbl_jenis_perbaikan')->get() as $r)
                <option {{request()->jenis_perbaikan && request()->jenis_perbaikan==$r->id_jenis ? 'selected':''}} value="{{$r->id_jenis}}">{{$r->nama_jenis}}</option>
                @endforeach
              </select>
            </div>

          </div>

            <div class="float-end">

            <button class="btn btn-sm btn-primary mt-2" name="lihat" value="true"> <i class="fas fa-eye" aria-hidden></i> Lihat</button> <button class="btn btn-sm btn-success mt-2" name="cetak" value="true"><i class="fas fa-print" aria-hidden></i> Cetak</button>
          </div>

        </form>

        @if($data)
        <br>
        <br>
        <center> <h4 style="text-transform:uppercase">REKAPITULASI BOOKING SERVICE MOBIL<br> BENGKEL {{get_info('nama_bengkel')}}<br>{{$periode}}</h4> </center>
        <table class="table">
          <thead>

          <tr>
            <th>NO</th>
            <th>TGL</th>
            <th>PELANGGAN</th>
            <th>MOBIL</th>
            <th>PERBAIKAN</th>
            <th>STATUS</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $k=>$r)
          <tr>
            <td style="text-align:center">{{$k+1}}</td>
            <td>{{tglindo($r->jadwal_booking)}}</td>
            <td>{{$r->nama}}</td>
            <td>{{$r->merk}}</td>
            <td>{{$r->nama_jenis}}</td>
            <td>{{$r->nama_status}}</td>
          </tr>
          @empty
          <tr>
            <td colspan="6">Tidak ada data</td>
          </tr>
          @endforelse
        </tbody>
        </table>
@endif
      </div>
    </div>
  </div>


</div>

</div>
@endsection
