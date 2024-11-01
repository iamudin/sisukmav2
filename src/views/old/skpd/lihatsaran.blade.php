<div class="modal saran" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
    <form action="{{URL::full()}}" method="post">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-comments" aria-hidden="true"></i> Saran Periode {{$periode}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <input type="hidden" name="saran" value="{{enc64(json_encode($data->saran))}}">
      <input type="hidden" name="periode" value="{{'Periode '.$periode}}">
      <div class="modal-body" style="max-height:70vh;overflow:auto">
    
      <div class="row">
        <div class="col-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Jenis Layanan</th>
                    <th>Saran</th>
                </tr>
            </thead>
            @php $no=0; @endphp
            <tbody>
                @forelse(collect($data->saran)->sortBy('tgl_survei') as $row)
               
                <tr>
                    <td>{{$no +1}}</td>
                    <td>{{$row->tgl_survei}}</td>
                    <td>{{$row->jam_survei ?? '-'}}</td>
                    <td>{{$row->nama_layanan}}</td>
                    <td>{{$row->saran??'-'}}</td>
                </tr>
                @php $no++; @endphp

                @empty 
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        @if(!empty($data->saran))<button type="submit" name="cetak_saran" value="true" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i> Cetak Saran</button>@endif
      </div>
    </form>

    </div>
  </div>
</div>