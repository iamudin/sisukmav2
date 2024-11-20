@extends('sisukma::front.layoutfront')
@section('content')
<div class="breadcrumbs ">

      <nav>
        <div class="container">
          <ol>
            <li><a href="{{url('/')}}"><i class="fas fa-home"></i>  Beranda</a></li>
            <li>{{ $data->nama }}</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog">
      <div class="container" data-aos="fade-up">

        <div class="row gy-4 posts-list">

<div class="col-lg-12">
<h2>{{$data->nama}}</h2>
<small>Oleh {{ $data->skpd->nama_skpd}}</small>
  </div>
  @foreach($data->images as $r)

          <div class="col-xl-4 col-md-6">
            <article>

              <div class="post-img">
                <img src="{{Storage::url($r->path)}}" alt="" class="img-fluid">
              </div>


              <h2 class="title">
                {{$r->caption}}
              </h2>



            </article>
          </div>
          @endforeach

        </div><!-- End blog posts list -->



      </div>
    </section><!-- End Blog Section -->

@endsection
