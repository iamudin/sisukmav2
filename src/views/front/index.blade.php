@extends('sisukma::front.layoutfront')
@section('content')
    <!-- ======= Our Services Section ======= -->
    <section id="services" class="services sections-bg text-center">
      <div class="container " data-aos="fade-up">
        <div class="row">
          <div class="col-lg-12 text-center">

          </div>
        </div>
        <div class="section-header">
          <h2>Statistik</h2>
          <p>Berikut adalah indeks kepuasan masyarakat yang didapatkan dari hasil survei <b> Periode</b> <br><br> <br> <button onclick="$('.periode').modal('show');" class="btn btn-primary btn-sm"> <i class="fa fa-edit" aria-hidden="true"></i> Ganti Periode</button> </p>

        </div>
        <input type="hidden" class="ajax_data" value="{!!base64_encode($ajaxdata ?? json_encode(['year'=>date('Y')]))!!}">
        <div class="data-dashboard">
            <center  id="spinner" style="display: none" >
                <div class="spinner-border text-primary" role="status">

               </div><br>
               Sedang memuat data...</center>
        </div>
      </div>
    </section><!-- End Our Services Section -->
@include('sisukma::periodeget')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#spinner').show();
        loadData();
    });

    function loadData()
    {
        $.ajax({
            url: '{{ route('ajax.web.data') }}',
            type: 'POST',
            data:
                {!! $ajaxdata ?? '{"_token":"'.csrf_token().'"}'!!}
            ,
            success: function(response) {
            $('#spinner').hide();

                $('.data-dashboard').html(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    </script>
  @endsection
