@extends('sisukma::layout.app')
@section('content')
      <div class="container-fluid px-4">
          <h3 class="mt-4" style="width:100%"> {!!  request()->segment('3') == 'create' ? '<i class="fas fa-plus"></i>  Tambah' : '<i class="fas fa-edit"></i>  Edit'!!} Layanan   <a href="{{route('layanan.index')}}" style="float:right" class="text-white btn btn-danger btn-sm"><i class="fas fa-undo"></i> Kembali</a></h3>
      <br>
      <form class="" action="{{$edit->exists ? route('layanan.update', $edit->id) : route('layanan.store') }}" method="post">
        @csrf
          @if($edit->exists)
          @method('PUT')
          @endif
          @if($skpd)
          <div class="form-group">
              <label for="">Nama SKPD</label>
              <select class="form-control" name="skpd_id" required onchange="if(this.value)location.href='{{ route('layanan.create') }}?skpd='+this.value">
                  <option value="">--Pilih SKPD--</option>
                  @foreach($skpd as $i => $v)
                  <option value="{{ $v->id }}" @if($edit->exists && $edit->skpd_id == $v->id || (request('skpd') && request('skpd') == $v->id)) selected @endif >{{ $v->nama_skpd }}</option>
                  @endforeach
              </select>
          </div>

          @endif
          <div class="form-group">
              <label for="">Unit Pelayanan</label>
              <select class="form-control" name="unit_id">
                  <option value="">--Pilih unit--</option>
                  @if(!empty($edit->unit->id) || $unit)
                  @foreach($unit as $i => $v)
                  <option value="{{ $v->id }}" @if($edit->exists && $edit->unit?->id == $v->id) selected @endif >{{ $v->nama }}</option>
                  @endforeach
                  @endif
              </select>
          </div>
            <div class="form-group">
              <label for="">Nama Layanan</label>
              <input class="form-control" name="nama_layanan" placeholder="Masukkan Nama Layanan" value="{{ $edit->nama_layanan ?? null }}" required>
            </div>
            @if($edit->exists)
              <div class="form-group mt-4">
               <label for=""><b>Evaluasi</b></label>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Tahun</th>
                      <th>Unsur Perioritas Prbaikan</th>
                      <th>Rencana Tidak Lanjut</th>
                      <th>Realisasi RTL Periode Sebelumnya</th>
                    </tr>
                  </thead>
                  @foreach($edit->evaluasi as $row)
                    <tr>
                      <td>{{ $row->tahun  }}</td>
                      <td>{{ $row->unsur_perbaikan  }}</td>
                      <td>{{ $row->rencana_tindak_lanjut  }}</td>
                      <td>{{ $row->rtl  }}</td>
                    </tr>
                  @endforeach
                </table>
                  <button type="button" class="btn btn-primary" onclick="$('#evaluasiModal').modal('show')">
                    Tambah Evaluasi Layanan
                  </button>
                </div>
            @endif
        <div class="form-group">
          <br>
          <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> <i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>

                </div>
    <!-- Tombol untuk membuka modal -->

  @if($edit->exists)
    <!-- Modal -->
    <div class="modal fade" id="evaluasiModal" tabindex="-1" role="dialog" aria-labelledby="evaluasiModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

          <!-- Header -->
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="evaluasiModalLabel">Form Evaluasi Layanan</h5>

          </div>

          <!-- Body -->
          <div class="modal-body">
            <form id="formEvaluasiLayanan" method="POST" action="{{$edit->exists ? route('layanan.evaluasi.store', $edit->id) : ''}}">
              <!-- CSRF (kalau di Laravel) -->
              @csrf
              <div class="form-group">
                <label for="tahun">Tahun</label>
                <select class="form-control" id="tahun" name="tahun" required>
                  <option value="">-- Pilih Tahun --</option>
                  @for ($i = date('Y'); $i >= 2020; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
                </select>
              </div>

              <div class="form-group">
                <label for="unsur">Unsur Prioritas Perbaikan</label>
                <input type="text" class="form-control" id="unsur" name="unsur_perbaikan"
                  placeholder="Masukkan unsur prioritas perbaikan" required>
              </div>

              <div class="form-group">
                <label for="rtl">Rencana Tindak Lanjut</label>
                <input type="text" class="form-control" id="rtl" name="rencana_tindak_lanjut"
                  placeholder="Masukkan rencana tindak lanjut" required>
              </div>

              <div class="form-group">
                <label for="realisasi">Realisasi RTL Periode Sebelumnya</label>
                <input type="text" class="form-control" id="realisasi" name="rtl"
                  placeholder="Masukkan realisasi RTL periode sebelumnya">
              </div>
            </form>
          </div>

          <!-- Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="$('#evaluasiModal').modal('hide')">Tutup</button>
            <button type="submit" form="formEvaluasiLayanan" class="btn btn-primary">Simpan</button>
          </div>
        </div>
      </div>
    </div>
  @endif
@endsection
