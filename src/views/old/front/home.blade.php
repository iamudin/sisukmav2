<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>E-Survei Kabupaten Bengkalis</title>
        <!-- Font Awesome icons (free version)-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('landing/css/styles.css') }}" rel="stylesheet">
        <!-- Fonts CSS-->
        <link rel="stylesheet" href="{{ asset('landing/css/heading.css') }}">
        <link rel="stylesheet" href="{{ asset('landing/css/body.css') }}">
    </head>
    <body id="page-top">
        <nav class="navbar navbar-expand-lg bg-secondary fixed-top" id="mainNav">
            <div class="container"><a class="navbar-brand js-scroll-trigger" href="#page-top"></a>
                
            </div>
        </nav>
        <header class="masthead bg-primary text-white text-center">
            <div class="container d-flex align-items-center flex-column">
                <!-- Masthead Avatar Image--><img class="masthead-avatar mb-5" src="{{ asset('landing/Lambang_Kabupaten_Bengkalis.png') }}" alt="">
                <!-- Masthead Heading-->
                <h1 class="masthead-heading mb-0">Selamat Datang Di Aplikasi E-Survei</h1>
                <!-- Icon Divider-->
                <div class="divider-custom divider-light">
                    <div class="divider-custom-line"></div>
                    
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Masthead Subheading-->
                <a data-toggle="modal" data-target="#myModal" class="btn btn-warning">Mulai Survei</a>
            </div>
        </header>

        <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Mohon Pilih Layanan</h4>

        <button type="button" class="btn btn-danger" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div style="overflow:scroll; height:400px; padding:30px;">
            
    @foreach($data as $i =>$v)    
    <div style="    background-color: cornflowerblue;
    height: 50px;
    position: relative;
    border-radius: 10px;
    color: white;
    padding-left:20px;
    padding-top:5px;
    justify-content: center;
    font-size: 20px;
    margin-bottom:20px;
    font-weight: bold;">{{ $v->nama_layanan }} <a  href="{{ url('survei/'.base64_encode($v->id_layanan)) }}" style="float:right;" class="btn btn-warning">Mulai Survei</a></div>
    @endforeach


      </div>
        
 
        </div>
       
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>
   
        <footer class="footer text-center">
            <div class="container">
                <div class="row">
                    <!-- Footer Location-->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h4 class="mb-4">Lokasi</h4>
                        <p class="pre-wrap lead mb-0">Jl. R. A. Kartini, Bengkalis Kota, Kec. Bengkalis, Kabupaten Bengkalis, Riau 28712</p>
                    </div>
                    <!-- Footer Social Icons-->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h4 class="mb-4">Media Sosial</h4><a class="btn btn-outline-light btn-social mx-1" href="https://www.facebook.com/StartBootstrap"><i class="fab fa-fw fa-facebook-f"></i></a><a class="btn btn-outline-light btn-social mx-1" href="https://www.twitter.com/sbootstrap"><i class="fab fa-fw fa-twitter"></i></a><a class="btn btn-outline-light btn-social mx-1" href="https://www.linkedin.com/in/startbootstrap"><i class="fab fa-fw fa-linkedin-in"></i></a><a class="btn btn-outline-light btn-social mx-1" href="https://www.dribble.com/startbootstrap"><i class="fab fa-fw fa-dribbble"></i></a>
                    </div>
                    <!-- Footer About Text-->
                    <div class="col-lg-4">
                        <h4 class="mb-4">Tentang Aplikasi</h4>
                        <p class="pre-wrap lead mb-0">Aplikasi ini dibuat untuk masyarakat melakukan Survei di pelayanan SKPD Kabupaten Bengkalis</p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Copyright Section-->
        <section class="copyright py-4 text-center text-white">
            <div class="container"><small class="pre-wrap">Copyright © Dinas Komunikasi Informatika dan Statistik Kabupaten Bengkalis</small></div>
        </section>
        <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes)-->
        <div class="scroll-to-top d-lg-none position-fixed"><a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top"><i class="fa fa-chevron-up"></i></a></div>
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <!-- Contact form JS-->
        <script src="assets/mail/jqBootstrapValidation.js"></script>
        <script src="assets/mail/contact_me.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>