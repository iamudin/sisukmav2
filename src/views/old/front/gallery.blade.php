@extends('front.layoutfront')
@section('content')
<div class="breadcrumbs ">
      <div class="bg-light page-header d-flex align-items-center" style="background-image: url('{{url('trnp.png')}}');background-size:fixed;">
        <div class="container position-relative">
          <div class="row d-flex justify-content-center">
            <div class="col-lg-6 text-center">
              <h2 class="text-muted">Gallery</h2>
            </div>
          </div>
        </div>
      </div>
      <nav>
        <div class="container">
          <ol>
            <li><a href="{{url('/')}}">Beranda</a></li>
            <li>Gallery</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog">
      <div class="container" data-aos="fade-up">

        <div class="row gy-4 posts-list">
          @if(request('id'))
          @php 
    $gmbr = DB::table('img_gallery')->whereIdGallery($data->id)->get();
  @endphp
  <div class="col-lg-12">
<h2>{{$data->nama}}</h2>
<span>Oleh {{nama_skpd($data->id_skpd)}}</span>
  </div>
  @foreach($gmbr as $r)
  
          <div class="col-xl-4 col-md-6">
            <article>

              <div class="post-img">
                <img src="{{asset($r->path)}}" alt="" class="img-fluid">
              </div>


              <h2 class="title">
                {{$r->caption}}
              </h2>

       

            </article>
          </div>
          @endforeach
          @else
@foreach($data as $r)
@php 
$gmbr = DB::table('img_gallery')->whereIdGallery($r->id)->first() ? DB::table('img_gallery')->whereIdGallery($r->id)->first()->path : null;
@endphp
          <div class="col-xl-4 col-md-6">
            <article>

              <div class="post-img">
                <img src="{{asset($gmbr)}}" alt="" class="img-fluid">
              </div>


              <h2 class="title">
                <a href="{{url('gallery/'.$r->permalink)}}">{{$r->nama}}</a>
              </h2>

              <div class="d-flex align-items-center">
                <img src="{{asset('logo.png')}}" alt="" class="img-fluid post-author-img flex-shrink-0">
                <div class="post-meta">
                  <p class="post-author-list">{{nama_skpd($r->id_skpd)}}</p>
                  <p class="post-date">
                    <time datetime="2022-01-01">{{tglindo($r->created)}}</time>
                  </p>
                </div>
              </div>

            </article>
          </div><!-- End post list item -->

@endforeach
@endif
        </div><!-- End blog posts list -->



      </div>
    </section><!-- End Blog Section -->

@endsection