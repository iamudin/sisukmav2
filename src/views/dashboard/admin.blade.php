@extends('sisukma::layout.app')
@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4"><i class="fas fa-tachometer-alt"></i> Dashboard <a href="{{$urlcetak ?? null}}" class="btn btn-sm btn-warning float-end"><i class="fa fa-print" aria-hidden="true"> </i> Cetak Rekapitulasi</a>  </h3>
<br>
   <p style="font-size:30px;"> <i class="fas fa-calendar"></i> Periode <b class="periode-title">{{ $periode }}</b> <button onclick="$('.periode').modal('show')" class="btn btn-sm btn-danger float-end mt-2"> <i class="fa fa-edit"></i> Ganti Periode</button></p>
   <center  id="spinner" style="display: none" >
    <div class="spinner-border text-primary" role="status">

   </div><br>
   Sedang memuat data...</center>

    <div class="row data-dashboard" >

    </div>
    <input type="hidden" class="ajax_data" value="{!!base64_encode($ajaxdata ?? json_encode(['year'=>date('Y')]))!!}">
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#spinner').show();
        loadData();
    });

    function loadData() {
        $.ajax({
            url: '{{ route('ajax.dashboard') }}',
            type: 'POST',
            data:
                {!! $ajaxdata ?? '{"_token":"'.csrf_token().'","unsur_tambahan":"9"}'!!}
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
    function cetak_rekap(skpd_id,periode){
            let dec = JSON.parse(atob(periode));
    $.ajax({
            url: '{{ route('ajax.rekap_skpd_pdf') }}',
            type: 'POST',
            data:{
            _token:"{{ csrf_token() }}",
            year:dec.year,
            unsur_tambahan:dec.unsur_tambahan,
            month:dec.month,
            from:dec.from,
            to:dec.to,
            skpd_id:skpd_id},
            success: function(response) {
                // location.href=response;
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
   function modal_detail_ikm(dataikm,skpd,alamat,telp,type_unsur,skpd_id){
    var periode = $('.periode-title').text();
    $.ajax({
            url: '{{ route('ajax.detailikm') }}',
            type: 'POST',
            data:{_token:"{{ csrf_token() }}",data_ikm:dataikm,skpd:skpd,alamat:alamat,telp:telp,periode:periode,type_unsur:type_unsur,skpd_id:skpd_id},
            success: function(response) {
                $('#detailikm .modal-body').html(response);
                $('#detailikm').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
   }
</script>
@include('sisukma::periode')
@include('sisukma::dashboard.ajax.modal')
@endsection
