@extends('front.layout')
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
<center><h2>Selamat Datang Di Formulir Survei Kepuasan Masyarakat <br><b>{{Str::upper($skpd->nama_skpd)}}</b></h2><span class="badge bg-info">Jam Operasional : 08:00 - 12:00 dan 13:00 - 17:00</span></center>
<br>
  @if(count($layanan)>0)
    <h5>Silahkan Pilih Layanan Dibawah ini :</h5>
<div class="list-group w-100" style="min-width:80vw;max-height:60vh;overflow:auto" >
@foreach($layanan as $r)
  <a href="{{url('survei/'.request('id').'/'.enc64($r->id_layanan))}}" class="py-3 list-group-item list-group-item-action" >
    <div class="w-100 ">
      <h5  style="float:left;width:90%" >{{$r->nama_layanan}}</h5>
      <i class="fas fa-arrow-right" style="float:right;margin-top:1%"></i>
    </div>
   
  </a>
  @endforeach
</div>
@else 
<div class="list-group w-100" style="min-width:80vw" >
<center><h3>Belum ada layanan</h3></center>
</div>
@endif
</div>
</div>
@endsection