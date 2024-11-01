@extends('front.layout')
@section('content')
<style>
</style>
<div class="row ">
<div class="col-lg-12">
    <form id="formsurvei" action="{{URL::full()}}" method="post" >
        @csrf
<div class="list-group w-100" style="min-width:80vw text-center">
<div class="row q-all start" >
<center><h5>Silahkan Tekan Tombol Mulai Untuk Melakukan Survei Kepuasan pada Layanan </h5><h1><b>{{$data->nama_layanan}}</b></h1></center>


<div class="col-lg-12 col-12 text-center mt-4">
<button type="button" class="btn btn-xl btn-primary mb-2 me-2" onclick="$('.q-all').hide();$('.nik').show()" style="font-size:40px"><b>MULAI</b></button><br>
<a href="{{url('survei/'.request()->segment(2))}}"> <i class="fa fa-home" aria-hidden="true"></i> Kembali Ke Layanan</a>
</div>
</div>
<div class="row q-all nik" style="display:none" >
<center><h1>Nama</h1></center>
<br>
<br>
<br>

<div class="col-lg-12 col-12 text-center">
<input type="hidden" name="jam_survei" value="{{checkwaktu(now())}}">
<input class="keyboard-input" onkeyup="if(this.value.length > 2) {$('.next-usia').show()} else{$('.next-usia').hide()}" onkeydown="return event.key != 'Enter';" style="text-align:center;font-size:25px;min-width:90%;font-weight:bold;border:5px solid lightblue" type="text"  name="nik" placeholder="Masukkan nama anda">

  <br>
  <br>
<div>
<button type="button" class="btn btn-danger btn-md float-start previous" style="width:150px" onclick="$('.q-all').hide();$('.start').show()"><i class="fa fa-angle-left" aria-hidden="true"></i> Sebelumnya</button>
<button type="button" style="display:none" class="next-usia btn btn-md btn-primary mb-2 me-2 float-end" onclick="if($('input[name=nik]').val().length > 0) {$('.q-all').hide();$('.usia').show();}else{alert('Masukkan Nama');$('input[name=nik]').focus();}"><b>Lanjutkan</b></button>
</div>
</div>

</div>
<div class="row q-all usia" style="display:none" >
<center><h1>Usia Kamu (Th)</h1></center>
<br>
<br>
<br>

<div class="col-lg-12 col-12 text-center">
<select style="height:50px;font-size:30px" onchange="if(this.value){$('.next-jk').show()}else{$('.next-jk').hide()}" name="usia" id="usia"  class="form-control">
    <option value="">--pilih usia--</option>
    @for($i=17; $i<=100; $i++)
<option value="{{$i}}"><b>{{$i}}</b></option>
    @endfor
</select>
<br>

    <div>
    <button type="button" class="btn btn-danger btn-md float-start previous" style="width:150px" onclick="$('.q-all').hide();$('.nik').show()"><i class="fa fa-angle-left" aria-hidden="true"></i> Sebelumnya</button>
    <button type="button" class="btn btn-primary btn-md float-end next-jk" onclick="$('.q-all').hide();$('.jk').show()" style="width:150px;display:none">Lanjutkan <i class="fa fa-angle-right" aria-hidden="true"></i></button>
    </div>
</div>
</div>
<div class="row q-all jk" style="display:none">
<center><h1>Jenis Kelamin</h1></center>
<br>
<br>
<br>

<div class="col-lg-12 col-12">
<input type="hidden" name="jenis_kelamin">

<div class="list-group"  >
@foreach(['P','L'] as $k=>$r)
<button onclick="$('input[name=jenis_kelamin]').val('{{$r}}');$('.next-pd').show();$('.k').removeClass('active');$('.k-{{$k}}').addClass('active')" type="button" class="k-{{$k}} k w-100 list-group-item list-group-item-action"><b>{{$r=='L'? 'Pria' : 'Wanita'}}</b></button>
@endforeach

</div>
<br>
    <div>
    <button type="button" class="btn btn-danger btn-md float-start previous" style="width:150px" onclick="$('.q-all').hide();$('.usia').show()"><i class="fa fa-angle-left" aria-hidden="true"></i> Sebelumnya</button>
    <button type="button" class="btn btn-primary btn-md float-end next-pd" onclick="$('.q-all').hide();$('.pd').show()" style="width:150px;display:none">Lanjutkan <i class="fa fa-angle-right" aria-hidden="true"></i></button>
    </div>
</div>
</div>
<div class="row q-all pd" style="display:none" >
<center><h1>Pendidikan</h1></center>
<br>
<br>
<br>

<div class="col-lg-12 col-12">
<input type="hidden" name="pendidikan">

<div class="list-group" style="min-width:100%">
 
@foreach(['Non Pendidikan','SD','SMP','SMA','DIII','S1','S2','S3'] as $k=>$r)
<button style="min-width:100%" onclick="$('input[name=pendidikan]').val('{{$r}}');$('.edu').removeClass('active');$('.edu-{{$k}}').addClass('active');$('.next-kerja').show()" type="button" class="edu edu-{{$k}} list-group-item list-group-item-action"><b>{{$r}}</b></button>
              @endforeach

</div>
<br>
    <div>
    <button type="button" class="btn btn-danger btn-md float-start previous" style="width:150px" onclick="$('.q-all').hide();$('.jk').show()"><i class="fa fa-angle-left" aria-hidden="true"></i> Sebelumnya</button>
    <button type="button" class="btn btn-primary btn-md float-end next-kerja" onclick="$('.q-all').hide();$('.kerja').show()" style="width:150px;display:none">Lanjutkan <i class="fa fa-angle-right" aria-hidden="true"></i></button>
    </div>
</div>

</div>

<div class="row q-all kerja" style="display:none" >
<center><h1>Pekerjaan</h1></center>
<br>
<br>
<br>

<div class="col-lg-12 col-12">
<input type="hidden" name="pekerjaan">

<div class="list-group"  >
 
@foreach(['PNS','TNI','POLRI','SWASTA','WIRAUSAHA','Lainnya'] as $k=>$r)
<button type="button" onclick="$('input[name=pekerjaan]').val('{{$r}}');$('.krj').removeClass('active');$('.krj-{{$k}}').addClass('active');$('.next-emot').show()" class="krj krj-{{$k}} w-100 list-group-item list-group-item-action"><b>{{$r}}</b></button>
              @endforeach

</div>
<br>
<div>
    <button type="button" class="btn btn-danger btn-md float-start previous" style="width:150px" onclick="$('.q-all').hide();$('.pd').show()"><i class="fa fa-angle-left" aria-hidden="true"></i> Sebelumnya</button>
    <button type="button" class="btn btn-primary btn-md float-end next-emot" onclick="$('.q-all').hide();$('.q-1').show()" style="width:150px;display:none">Lanjutkan <i class="fa fa-angle-right" aria-hidden="true"></i></button>
    </div>
</div>
</div>


@foreach(DB::table('unsur')->get() as $r)
    <div class="w-100 text-center q-all q-{{$r->urutan}}" style="display:none">
      <h3 style="font-weight:bold" >{{$r->urutan}}. {{$r->nama_unsur}}</h3>
      <input type="hidden" class="u{{$r->urutan}}" name="u{{$r->urutan}}">
   <div class="row text-center mt-5">
    @foreach(['a','b','c','d'] as $k=>$alf)
    <div class="col-lg-3 col-3" style="cursor:pointer" onclick="$('.imgemot-{{$r->urutan}}').attr('style','width:100%;filter: grayscale(1)');$('.img-{{$alf}}-{{$r->urutan}}').attr('style','filter:none;width:100%');$('.u{{$r->urutan}}').val('{{$k+1}}');$('.next-{{$r->urutan}}').show();">
        <img src="{{asset($alf.'.png')}}" class="imgemot-{{$r->urutan}} img-{{$alf}}-{{$r->urutan}}" style="width:100%" alt=""><br>
        <span><b>{{$r->$alf}}</b></span>
    </div>
    @endforeach
  
   </div>
   <br>
    <br>
    <br>
    <div>
    <button type="button" class="btn btn-danger btn-md float-start previous-{{$r->urutan-1}}" style="width:150px" @if($loop->first) onclick="$('.kerja').show();$('.q-{{$r->urutan}}').hide(); " @else onclick="$('.q-{{$r->urutan-1}}').show();$('.q-{{$r->urutan}}').hide();" @endif ><i class="fa fa-angle-left" aria-hidden="true"></i> Sebelumnya</button>
    <button type="button" class="btn btn-primary btn-md float-end next-{{$r->urutan}}" onclick="$('.q-{{$r->urutan}}').hide();$('.q-{{$r->urutan+1}}').show()" style="width:150px;display:none">Lanjutkan <i class="fa fa-angle-right" aria-hidden="true"></i></button>
    </div>
   </div>
  @endforeach
</div>
<div class="w-100 q-10" style="display:none;min-width:80vw;text-align:center">
    <h3>Saran</h3>
    <textarea style="height:200px;text-align:center;font-size:25px;min-width:90%;font-weight:bold;border:5px solid lightblue" name="saran" type="text" class="form-control keyboard-input" placeholder="Tuliskan saran anda..."></textarea>
    <br>
    <div>
    <button class="btn btn-danger btn-md float-start" type="button"  value="true" style="font-weight:bold" onclick="$('.q-10').hide();$('.q-9').show()">Sebelumnya</button>
    <button class="btn btn-primary btn-md float-end btn-submit" type="button" onclick="return alert_confirm()" name="kirim_survei" value="true" style="font-weight:bold">Kirim Survei</button>
    </div>
</div>
@php 
$agent = new \Jenssegers\Agent\Agent;
@endphp
@if($agent->isDesktop())
@include('front.keyboard')
@endif
</form>
</div>
</div>

<style>
  .swal2-cancel {
    margin-right:10px;
  }
</style>
<script>

  function alert_confirm(){
    const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
})

swalWithBootstrapButtons.fire({
  title: 'Kirim Survei Ini ?',
  text: "Apakah anda sudah yakin telah mengisi data survei dengan benar ?",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Ya, Kirim Survei!',
  cancelButtonText: 'Tidak, Lihat Kembali ',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {
  
      $('.btn-submit').attr('type','submit');
      $('.btn-submit').removeAttr('onclick');
      $('.btn-submit').click();
      // $('#formsurvei').submit();
  } else if (
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.cancel
  ) {
    // swalWithBootstrapButtons.fire(
    //   'Cancelled',
    //   'Your imaginary file is safe :)',
    //   'error'
    // )
  }
})
  }
</script>

@endsection