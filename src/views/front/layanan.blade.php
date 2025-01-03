@extends('sisukma::front.layout')
@section('content')
@if(session()->has('success'))
<script>
  document.addEventListener("DOMContentLoaded", (event) => {
        Swal.fire(
  'Survei Sukses Dilakukan!',
  'Terima Kasih, Anda Puas Kami Senang!',
  'success'
);
setTimeout(function(){
location.reload();
}, 2000);

});

</script>
@endif

<div style="margin-top:100px;padding-bottom:30px" class="row layanan" @if(session()->has('success')) style="display:none" @endif>
<div class="col-lg-12 ">
    @if($skpd->tampilkan_banner=='Y' && $skpd->banner && Storage::disk('public')->exists($skpd->banner))
    <img src="{{Storage::url($skpd->banner)}}" style="width:100%;border-radius:10px" alt="" >
    <br>
<br>
    @endif

<center><h2>Selamat Datang Di Formulir Survei Kepuasan Masyarakat <br><b>{{Str::upper($skpd->nama_skpd)}}</b></h2><span class="badge bg-info">Jam Operasional : 08:00 - 12:00 dan 13:00 - 17:00</span></center>
<br>
@if(Session::has('warning'))
<div class="alert alert-warning"> <i class="fas fa-info"></i> {{ Session::get('warning') }}</div>
@endif
  @if($skpd->layanans->count() > 0)
    <h5>Silahkan Pilih Layanan Dibawah ini :</h5>
<div class="list-group w-100" style="min-width:80vw;max-height:60vh;overflow:auto" >
@foreach($skpd->layanans as $k=>$r)
  <a href="{{url('survei/'.base64_encode($skpd->id).'/'.base64_encode($r->id))}}" class="py-3 list-group-item list-group-item-action" >
    <div class="w-100 ">
      <h6  style="float:left;width:90%;" >{{$r->nama_layanan}}</h6>
      <i class="fas fa-arrow-right" style="float:right;margin-top:1%"></i>
    </div>

  </a>
  @endforeach
</div>
@else
<div class="list-group w-100" style="min-width:80vw" >
<div class="alert alert-warning"> <i class="fas fa-info"></i> Belum Ada Layanan</div>
</div>
@endif
</div>
</div>
@endsection
