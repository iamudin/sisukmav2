<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>S I S U K M A - Admin</title>
    <link rel="icon" href="https://bengkaliskab.go.id/favicon.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</head>

<body class="sb-nav-fixed">
    <nav class="bg-primary sb-topnav navbar navbar-expand navbar-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="{{ url('/') }}"><b>S I S U K M A</b></a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline me-auto me-0 me-md-3 my-2 my-md-0 w-30" style="color:#f5f5f5">
          <marquee>Selamat datang {{ request()->user()->isAdmin() ? 'Admin Kabupaten' : 'Admin '.request()->user()->skpd->nama_skpd }}</marquee>

        </form>
        <!-- Navbar-->

        <ul class="navbar-nav ms-auto ms-auto me-3 me-lg-4">
            <li class="nav-item">
                <a class="nav-link text-warning" target="_blank" href="{{ url('/') }}"><i class="fas fa-globe fa-fw"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-warning" href="{{ route('user.account') }}"><i class="fas fa-user fa-fw"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" id="navbarDropdown" href="{{ route('logout') }}"
                    onclick="return confirm('Yakin untuk keluar ?')"><i class="fas fa-sign-out-alt fa-fw"></i></a>
            </li>
        </ul>
    </nav>
    <style media="screen">
        .sb-menu {
            border-left: 4px solid #07c;
            background: lightblue;
            margin-bottom: 8px;
            border-radius: 5px;
            color: #111
        }

        .nav {
            padding: 10px
        }
    </style>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion bg-light" style="border-right:4px dashed #ccc" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading pt-0 mt-0"> <i class="fas fa-arrow-down"></i> MENU <i class="fas fa-arrow-down"></i> </div>

                        <a class="nav-link sb-menu" href="{{ route('dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        @if(Auth::user()->isAdmin())

                        <a class="nav-link sb-menu" href="{{ route('unsur.index') }}"> <div class="sb-nav-link-icon"><i class="fa fa-list"></i> Unsur</div></a>
                        @endif
                        <a class="nav-link sb-menu" href="{{ route('layanan.index') }}"> <div class="sb-nav-link-icon"><i class="fas fa-desktop"></i> Layanan</div></a>
                        <a class="nav-link sb-menu" href="{{ route('unit.index') }}"> <div class="sb-nav-link-icon"><i class="fas fa-rocket"></i> Unit Pelayanan</div></a>

                        @if(Auth::user()->isAdmin())
                        <a class="nav-link sb-menu" href="{{ route('skpd.index') }}"> <div class="sb-nav-link-icon"><i class="fas fa-building"></i> Perangkat Daerah</div></a>
                        @else
                        <a class="nav-link sb-menu" href="{{ route('skpd.profile') }}"> <div class="sb-nav-link-icon"><i class="fas fa-building"></i> Perangkat Daerah</div></a>
                        <a class="nav-link sb-menu" href="{{ url('survei/'.base64_encode(Auth::user()->skpd->id))}}"> <div class="sb-nav-link-icon"><i class="fas fa-hand-pointer"></i> Form Survei</div></a>
                        @endif
                        <a class="nav-link sb-menu" href="{{ route('gallery.index') }}"> <div class="sb-nav-link-icon"><i class="fas fa-image"></i> Gallery</div></a>

                        @if(Auth::user()->isSkpd())
                        <br>
                        <br>
                        <div class="sb-sidenav-menu-heading pt-0 mt-0">QR Code <i class="fas fa-qrcode"></i> </div>
                        {{ QrCode::size(200)->generate('sdfsdfsdf') }}
                        <a href="{{ route('skpd.cetakqr',Auth::user()->skpd->id) }}?cetak=ori" class="btn btn-warning btn-sm mt-2">Download QR</a>
                        <a href="{{ route('skpd.cetakqr',Auth::user()->skpd->id) }}?cetaktamplte=true&cetak=true" class="btn btn-success btn-sm mt-2">Versi Template</a>
                        @endif


                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Anda Login Sebagai :</div>
                    <span class="badge bg-danger"> <i class="fas fa-user"></i> {{ request()->user()->isAdmin() ? 'Admin Kabupaten' : 'Admin Perangkat Daerah'}}</span>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                @if (Session::has('success'))
                    <div class="alert alert-success align-right" style="margin:10px">
                        {{ Session::get('success') }}
                        <span class="text-right" style="cursor:pointer;float:right"
                            onclick="$('.alert').hide()">x</span>
                    </div>
                @endif
                @if (Session::has('danger'))
                    <div class="alert alert-danger align-right" style="margin:10px">
                        {{ Session::get('danger') }}
                        <span class="text-right" style="cursor:pointer;float:right"
                            onclick="$('.alert').hide()">x</span>
                    </div>
                @endif
                @if ($errors->any())
                <div class="alert alert-danger align-right" style="margin:10px">
                    <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach</ul>
            </div>

            @endif
                @yield('content')
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; SISUKMA V.2.0</div>
                        <div>

                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
</body>

</html>
