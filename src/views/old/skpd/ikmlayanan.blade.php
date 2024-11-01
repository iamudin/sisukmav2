<div class="modal ikmlayanan" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
    <form action="{{URL::full()}}" method="post">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-bar-chart" aria-hidden="true"></i> Rekapitulasi Responden </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
    
      <div class="row">
        <div class="col-12" id="dataikm">

</div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
       <a download href="javascript:void(0)" class="btn btn-primary btn-cetak"><i class="fa fa-print" aria-hidden="true"></i> Cetak IKM Layanan</a>
      </div>
    </form>

    </div>
  </div>
</div>
