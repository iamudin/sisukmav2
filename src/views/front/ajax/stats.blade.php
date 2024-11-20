<div class="row gy-4" data-aos="fade-up" data-aos-delay="100">

    <div class="col-lg-12">

      <div class="service-item  position-relative ">

        <h3>Kabupaten</h3>
        <div class="row">
          <div class="col-lg-6">
        <h5>Nilai IKM</h5>

          <p style="font-size:100px" class="mt-5 mb-5">{{round($data->ikm,2)}}</p>
          <p class="text-center"><h5>Mutu Pelayanan</h5>
<h1>{{prediket(round($data->ikm,2),true)}}</h1> ({{prediket(round($data->ikm,2))}})
</p>
        </div>
          <div class="col-lg-6">
          <h5>Responden</h5>
        <table class="table" style="text-align:left;" border="0">
        <tr>
          <td>Jumlah</td>
          <td>:</td>
          <td><b>{{$data->jumlah}}</b> Orang</td>
        </tr>
        <tr>
          <td>Jenis Kelamin</td>
          <td>:</td>
          <td>L = <b>{{$data->l}}</b>  Orang / P = <b>{{$data->p}}</b> Orang </td>
        </tr>
        <tr>
          <td>Pendidikan</td>
          <td>:</td>
          <td>
            @foreach(['Non Pendidikan','SD','SMP','SMA','DIII','S1','S2','S3'] as $r)
            @endforeach
          </td>
        </tr>
        </table>
          </div>
        </div>


      </div>
    </div>

    @foreach(collect($data->data_ikm_skpd)->sortByDesc('data.ikm')->values() as $k=>$r)
    <div class="col-lg-6">

        <div class="service-item  position-relative ">

        <h3>{{$r->nama_skpd}}</h3>
        <div class="row">
        <div class="col-lg-4">
        <h5>Nilai IKM</h5>
        <p style="font-size:60px" class="mt-5 mb-5">{{round($r->data->ikm,2)}}</p>
        <p class="text-center"><h5>Mutu Pelayanan </h5>
        <h1>{{prediket(round($r->data->ikm,2),true)}}</h1> ({{prediket(round($r->data->ikm,2))}})
        </p>
        </div>
        <div class="col-lg-8">
        <h5>Responden</h5>
        <table class="table" style="text-align:left;" border="0">
        <tr>
        <td>Jumlah</td>
        <td>:</td>
        <td><b>{{$r->data->jumlah}}</b> Orang</td>
        </tr>
        <tr>
        <td>Jenis Kelamin</td>
        <td>:</td>
        <td>L = <b>{{$r->data->l}}</b>  Orang / P = <b>{{$r->data->p}}</b> Orang </td>
        </tr>
        <tr>
        <td>Pendidikan</td>
        <td>:</td>
        <td>
        @foreach(['Non Pendidikan','SD','SMP','SMA','DIII','S1','S2','S3'] as $rp)
        @php $k = Str::lower(str_replace(' ','_',$rp)); @endphp
        {{$rp}} <span class="float-end "><b>{{$r->data->pendidikan->$k ?? 0}}</b>  <span class="text-muted">Orang</span></span> <br>
        @endforeach

        </td>
        </tr>
        </table>
        <div>
              <button style="cursor:pointer" onclick="cetak_rekap('{{ $r->skpd_id }}',$('.ajax_data').val())" class="btn btn-info btn-sm  float-end" aria-hidden="true"><i class="fa fa-print" aria-hidden="true"></i> Cetak</button>

        </div>
        </div>
        </div>


        </div>
        </div>

    @endforeach
</div>
