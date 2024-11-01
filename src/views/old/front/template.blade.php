<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <style>
  </style>
  <body>
      
  <div style="border:15px double #000;padding:30px;background:url('{{url('trnp.png')}}') #fff;background-attachment:fixed;height:1500px;width:1100px;margin:0 auto" >
<div class="row">
    <div class="col-12 text-center">
    <img class="mb-5"  src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/88/Lambang_Kabupaten_Bengkalis.png/300px-Lambang_Kabupaten_Bengkalis.png" height="160" alt=""><br><h1><b>{{Str::upper($skpd->nama_skpd)}} KABUPATEN BENGKALIS</b></h1> 

    </div>
  
</div>
<div class="row">
  <div class="col-lg-12 text-center mt-4">
    <h1 >MOHON BANTUAN SAUDARA UNTUK MENGISI SURVEY INDEKS KEPUASAN MASYARAKAT (IKM) PADA LAYANAN KAMI</h1>
    <br>
    <h4>Scan QR Dibawah ini :</h4>
    <img src="{{toqr('https://e-survei.bengkaliskab.go.id/survei/'.enc64($skpd->id_skpd))}}" style="height:650px;width:660px;padding:25px;border-radius:50px 0 50px 0;border:10px solid #000;" >
    <br><span style="font-size:20px"><b>Diterbitkan dari  https://sisukma.bengkaliskab.go.id</b></span>
    <br>
    <br>
    <h1><b>TERIMA KASIH</b></h1>
<img src="https://bengkaliskab.go.id/bermasa-logo.png" height="100" alt="">
  </div>
  </div>
  </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>