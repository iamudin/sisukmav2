@extends('sisukma::front.layoutfront')
@section('content')
<div class="breadcrumbs ">

      <nav>
        <div class="container">
          <ol>
            <li><a href="{{url('/')}}"><i class="fas fa-home"></i> Beranda</a></li>
            <li>Gallery</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog">
      <div class="container" data-aos="fade-up">

        <div class="row gy-4 posts-list">

@foreach($data as $r)

          <div class="col-xl-4 col-md-6">
            <article>

              <div class="post-img">
                <img src="{{ Storage::url($r->images?->first()?->path) }}" alt="" class="img-fluid">
              </div>


              <h2 class="title">
                <a href="{{url('gallery/'.$r->slug)}}">{{$r->nama}}</a>
              </h2>

              <div class="d-flex align-items-center">
                <img src="{{asset('logo.png')}}" alt="" class="img-fluid post-author-img flex-shrink-0">
                <div class="post-meta">
                  <small class="post-author-list">{{$r->skpd?->nama_skpd ?? null}}</small>
                  <p class="post-date">
                    <time datetime="2022-01-01">{{$r->created_at->format('Y-m-d')}}</time>
                  </p>
                </div>
              </div>

            </article>
          </div><!-- End post list item -->

@endforeach
        </div><!-- End blog posts list -->



      </div>
    </section><!-- End Blog Section -->

@endsection
