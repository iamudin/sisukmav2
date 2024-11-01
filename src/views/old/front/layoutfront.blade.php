<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>S I S U K M A - Sistem Survei Kepuasan Masyarakat</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="https://bengkaliskab.go.id/favicon.png" rel="icon">
  <link href="https://bengkaliskab.go.id/favicon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- Vendor CSS Files -->
  <link href="{{asset('back/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('back/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('back/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{asset('back/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
  <link href="{{asset('back/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('back/css/main.css')}}" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <!-- =======================================================
  * Template Name: Impact - v1.2.0
  * Template URL: https://bootstrapmade.com/impact-bootstrap-business-website-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <section id="topbar" class="topbar d-flex align-items-center" style="background:darkblue">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:organisasibengkalis@gmail.com">organisasibengkalis@gmail.com</a></i>
      </div>
      <!-- <div class="social-links d-none d-md-flex align-items-center">
        <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
        <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></i></a>
      </div> -->
    </div>
  </section>

  <header id="header" class="header d-flex align-items-center" style="background:rgb(13, 110, 253)">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="{{url('/')}}" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 style="text-shadow:0 1px 6px #000"> <img src="https://bengkaliskab.go.id/favicon.png" alt=""> S I S U K M A</h1>
      </a>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="{{url('/')}}"> <i class="fa fa-house"></i> &nbsp; Beranda</a></li>
          <li><a href="#services"> <i class="fa fa-chart-bar"></i> &nbsp; Statistik</a></li>
          <li><a href="{{url('/gallery')}}"> <i class="fa fa-camera"></i> &nbsp;  Gallery</a></li>
          <li><a href="{{url('/login')}}"> <i class="fa fa-sign-in"></i> &nbsp;  Login</a></li>
       
   
        </ul>
      </nav><!-- .navbar -->

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
  </header><!-- End Header -->
  <!-- End Header -->

  <!-- ======= Hero Section ======= -->
  @if(request()->is('/'))
  <section id="hero" class="hero" style="margin:0;padding:0">
   <img style="width:100%" src="{{url('e-surve.webp')}}" alt="">
  </section>

 
  @endif
<main id="main">
  <!-- End Hero Section -->
    <!-- ======= Testimonials Section ======= -->
@yield('content')

</main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer" style="background:rgb(13, 110, 253)">

  

    <div class="container mt-4">
      <div class="copyright">
        &copy; Copyright 2023 <strong><span>E-Survei</span></strong> -  Bagian Organisasi Sekretariat Daerah
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/impact-bootstrap-business-website-template/ -->
        Designed by <a href="https://diskominfotik.bengkaliskab.go.id">Tim IT Diskominfotik Kab. Bengkalis</a>
      </div>
    </div>

  </footer><!-- End Footer -->
  <!-- End Footer -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{asset('back/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('back/vendor/aos/aos.js')}}"></script>
  <script src="{{asset('back/vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{asset('back/vendor/purecounter/purecounter_vanilla.js')}}"></script>
  <script src="{{asset('back/vendor/swiper/swiper-bundle.min.js')}}"></script>
  <script src="{{asset('back/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
  <script src="{{asset('back/vendor/php-email-form/validate.js')}}"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('back/js/main.js')}}"></script>

</body>

</html>
