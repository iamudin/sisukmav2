<!DOCTYPE html>
<html lang="en">
<head>
  <title>S I S U K M A</title>
  <meta charset="utf-8">
  <link rel="icon" href="https://bengkaliskab.go.id/favicon.png" type="image/x-icon" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
--><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    body{
    height: 100vh;
} 
.container{
    height: 100%;
}
  </style>
</head>
@php $agent = new \Jenssegers\Agent\Agent;@endphp

<body id="apping" @if($agent->isDesktop()) onload="openFullscreen()" @endif class="bg-light">
<header class="">
<nav class=" navbar fixed-top" style="background:rgb(13, 110, 253)">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="https://bengkaliskab.go.id/favicon.png" alt=""  height="50" class="me-3 d-inline float-start align-text-top">
      <h3 class="text-white" style="line-height:5px;font-weight:bold;margin-top:10px;">S I S U K M A</h3>
      <small class="text-white">Sistem Survei Kepuasan Masyarakat</small>
    </a>
  </div>
</nav>
</header>
<!-- <div class="container-fluid"> -->
<!-- <img src="{{asset($skpd->banner)}}" style="width:100%" alt=""> -->

<div class="container @if(request()->segment(3)) d-flex align-items-center justify-content-center @endif">
@yield('content')
</div>

<script type="text/javascript">
   
var elem = document.documentElement;
function openFullscreen() {
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.webkitRequestFullscreen) { /* Safari */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE11 */
    elem.msRequestFullscreen();
  }
}

</script>
</body>
</html>