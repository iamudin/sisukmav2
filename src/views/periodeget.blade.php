<div class="modal periode" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
    <form action="{{URL::full()}}" method="POST">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Periode</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <small>Tahun</small>
            <select name="year" class="form-control" id="" onchange="if(this.value){$('#peri').show()}else{$('#peri').hide()}">
              <option value="">-pilih-</option>
              @for($i=2022; $i<=2024; $i++)
              <option value="{{$i}}">{{$i}}</option>
              @endfor
            </select>
          </div>
          <div id="peri" style="display:none">
          <input type="radio" name="type" onchange="if(this.value){$('.month').show();$('.month select').removeAttr('disabled');$('#from').val('');$('#to').val('');}"> Bulan<br>
          @foreach(array(
            ['name'=>'I','range'=>['01','03']],
            ['name'=>'II','range'=>['04','06']],
            ['name'=>'III','range'=>['07','09']],
            ['name'=>'IV','range'=>['10','12']]
            ) as $row)
          <input type="radio" onchange="$('.month').hide();$('#from').val('{{$row['range'][0]}}');$('#to').val('{{$row['range'][1]}}');" name="type"> Triwulan {{$row['name']}}<br>
         @endforeach
         </div>
          <input type="hidden" name="from" id="from">
          <input type="hidden" name="to" id="to">
          <div class="form-group month" style="display:none">
            <small>Bulan</small>
            <select disabled name="month" class="form-control">
              <option value="">-pilih-</option>
              @foreach(['01','02','03','04','05','06','07','08','09','10','11','12'] as $r)
              <option value="{{$r}}">{{blnindo($r)}}</option>
              @endforeach
            </select>
          </div>

        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Lihat Hasil</button>
      </div>
    </form>

    </div>
  </div>
</div>
