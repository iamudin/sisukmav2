<!DOCTYPE html>
<html lang="en">
<head>
  <title>S I S U K M A</title>
  <meta charset="utf-8">
  <link href="" rel="icon">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>

<div class="container-fluid p-5 bg-success text-white text-center">
  <img  style="width:5%"class="masthead-avatar mb-5" src="https://e-survei.bengkaliskab.go.id/landing/Lambang_Kabupaten_Bengkalis.png" alt="">
  <h1>Survei Kepuasan Masyarakat</h1>
  <p style="font-size:30px">{{$layanan->nama_skpd}}</p>
  <h5>{{$layanan->nama_layanan}}</h5>
  <a href="{{ url('/') }}" class="btn btn-warning"> kembali Halaman Awal</a>
</div>

<div class="container mt-5 mb-5">
  <div class="row">
    <div class="col-12">
        @if(Session::has('has_survei') && Session::get('has_survei')== request('id'))
        <div class="alert alert-success">Terima Kasih Anda Sudah Melakukan Survei</div>
        @else
        <div class="alert alert-info">Silahkan isi form data dibawah ini beserta kuisioner yang telah disediakan</div>
        <form class="" action="{{URL::full()}}" method="post">
          @csrf
     
          <div class="form-group">
          <label for="">Jenis Kelamin</label><br>
          <input type="radio" name="jenis_kelamin" value="L" required> Laki-laki<br>
          <input type="radio" name="jenis_kelamin" value="P" required> Perempuan
          </div>
          <div class="form-group">
          <label for="">Jam Survei</label><br>
          <input type="radio" name="jam_survei" value="08:00 - 12:00" required> 08:00 - 12:0<br>
          <input type="radio" name="jam_survei" value="13:00 - 17:00" required> 13:00 - 17:00
          </div>
          <div class="form-group">
            <label for="">Usia</label>
            <input type="number" class="form-control" name="usia" required>
          </div>
          <div class="form-group">
            <label for="">Pendidikan</label>
            <select class="form-control form-select" name="pendidikan" required>
              <option value="">--pilih--</option>
              @foreach(['SD','SMP','SMA','D3','S1','S2','S3'] as $r)
              <option value="{{$r}}">{{$r}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="">Pekerjaan</label>
            <select class="form-control form-select" name="pekerjaan" onchange="if(this.value){if(this.value=='Lainnya'){$('.p2').show();}else{$('.p2').hide();}}" required>
              <option value="">--pilih--</option>
              @foreach(['PNS','TNI','POLRI','SWASTA','WIRAUSAHA','Lainnya'] as $r)
              <option  value="{{$r}}">{{$r}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group p2" style="display:none">
            <label for="">Pekerjaan Lain</label>
            <input type="text" class="form-control" placeholder="Tuliskan nama pekerjaan lain" name="pekerjaan2">
          </div>
          <ol style="margin:0;padding:10px 19px">
          @foreach(App\Models\Unsur::get() as $k=>$r)
          <li>{{$r->nama_unsur}} <br>
            <ul style="list-style:none;padding:10px 7px">
              @foreach(['a','b','c','d'] as $num=>$l)
              <li> <input required style="cursor:pointer" type="radio" name="u{{$k+1}}" value="{{$num+1}}"> {{$r->$l}}</li>
              @endforeach
          </ul>
          </li>
          @endforeach
        </ol>
        <div class="form-group">
          <label for="">Saran</label>
        <textarea placeholder="Tuliskan saran terkait layanan ini..." name="saran" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <br>
        <button class="btn btn-md btn-primary float-end" name="submit" value="true">Submit</button>
        </div>
        </form>
      </div>
      @endif
    </div>
  </div>

</body>
</html>