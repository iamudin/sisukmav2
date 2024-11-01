@extends('front.layoutfront')
@section('content')
    <!-- ======= Our Services Section ======= -->
    <section id="services" class="services sections-bg text-center">
      <div class="container " data-aos="fade-up">
        <div class="row">
          <div class="col-lg-12 text-center">

          </div>
        </div>
        <div class="section-header">
          <h2>Statistik</h2>
          <p>Berikut adalah indeks kepuasan masyarakat yang didapatkan dari hasil survei <b> Periode {{$periode}}</b> <br><br> <br> <button onclick="$('.periode').modal('show');" class="btn btn-primary btn-sm"> <i class="fa fa-edit" aria-hidden="true"></i> Ganti Periode</button> </p>

        </div>

        <div class="row gy-4" data-aos="fade-up" data-aos-delay="100">

          <div class="col-lg-12">

            <div class="service-item  position-relative ">

              <h3>Kabupaten</h3>
              <div class="row">
                <div class="col-lg-6">
              <h5>Nilai IKM</h5>

                <p style="font-size:100px" class="mt-5 mb-5">{{round($ikm['ikm'],2)}}</p>
                <p class="text-center"><h5>Mutu Pelayanan</h5>
  <h1>{{prediket(round($ikm['ikm'],2),true)}}</h1> ({{prediket(round($ikm['ikm'],2))}})
</p>
              </div>
                <div class="col-lg-6">
                <h5>Responden</h5>
              <table class="table" style="text-align:left;" border="0">
              <tr>
                <td>Jumlah</td>
                <td>:</td>
                <td><b>{{$ikm['jumlah']}}</b> Orang</td>
              </tr>
              <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>L = <b>{{$ikm['l']}}</b>  Orang / P = <b>{{$ikm['p']}}</b> Orang </td>
              </tr>
              <tr>
                <td>Pendidikan</td>
                <td>:</td>
                <td>
                  @foreach(['Non Pendidikan','SD','SMP','SMA','DIII','S1','S2','S3'] as $r)
                  {{$r}} <span class="float-end "><b>{{$ikm[Str::lower(str_replace(' ','_',$r))]}}</b>  <span class="text-muted">Orang</span></span> <br>
                  @endforeach
                </td>
              </tr>
              </table>
                </div>
              </div>


            </div>
          </div><!-- End Service Item -->
@foreach(\App\Models\Skpd::withWhereHas('periodeAktif', function ($q) {
        $q->where('tahun', request()->year??date('Y'));
    })->whereStatusSample(1)->get() as $r)

@php
$u = new \App\IkmManager;
$id = $r->id_skpd;
$ikm = $u->nilai_ikm_skpd($r->id_skpd);
@endphp
          <div class="col-lg-6">

<div class="service-item  position-relative ">

  <h3>{{$r->nama_skpd}}</h3>
  <div class="row">
    <div class="col-lg-4">
  <h5>Nilai IKM</h5>
    <p style="font-size:60px" class="mt-5 mb-5">{{round($ikm['ikm'],2)}}</p>
  <p class="text-center"><h5>Mutu Pelayanan </h5>
  <h1>{{prediket(round($ikm['ikm'],2),true)}}</h1> ({{prediket(round($ikm['ikm'],2))}})
</p>
  </div>
    <div class="col-lg-8">
    <h5>Responden</h5>
  <table class="table" style="text-align:left;" border="0">
  <tr>
    <td>Jumlah</td>
    <td>:</td>
    <td><b>{{$ikm['jumlah']}}</b> Orang</td>
  </tr>
  <tr>
    <td>Jenis Kelamin</td>
    <td>:</td>
    <td>L = <b>{{$ikm['l']}}</b>  Orang / P = <b>{{$ikm['p']}}</b> Orang </td>
  </tr>
  <tr>
    <td>Pendidikan</td>
    <td>:</td>
    <td>
      @foreach(['Non Pendidikan','SD','SMP','SMA','DIII','S1','S2','S3'] as $r)
      {{$r}} <span class="float-end "><b>{{$ikm['pendidikan'][Str::lower(str_replace(' ','_',$r))] ?? 0}}</b>  <span class="text-muted">Orang</span></span> <br>
      @endforeach

    </td>
  </tr>
  </table>
  <div>
<form action="{{url()->full()}}" method="post"> @csrf
  <input type="hidden" name="ids" value="{{enc64($id)}}">
  <input type="hidden" name="periode" value="{{$periode}}">
<button style="cursor:pointer"  name="print" value="{{enc64(json_encode($ikm))}}" type="submit" class="btn btn-info btn-sm  float-end" aria-hidden="true"><i class="fa fa-print" aria-hidden="true"></i></button>
</form>
  </div>
    </div>
  </div>


</div>
</div>
@endforeach

        </div>

      </div>
    </section><!-- End Our Services Section -->
@include('periode')
  @endsection
