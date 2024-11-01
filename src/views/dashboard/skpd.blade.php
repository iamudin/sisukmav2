@extends('sisukma::layout.app')
@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4"><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
   <br>
   <h4 for="">Periode :</h4>

   <p style="font-size:30px;border-left:5px solid orange;padding-left:10px"> <a href="{{$urlcetak ?? null}}" class="btn btn-sm btn-primary"><i class="fa fa-print" aria-hidden="true"> </i> Print</a> <b>{{ $periode }}</b> <button onclick="$('.periode').modal('show')" class="btn btn-sm btn-danger float-end"> <i class="fa fa-edit"></i> Ganti Periode</button></p>
   <center  id="spinner" style="display: none" >
    <div class="spinner-border text-primary" role="status">

   </div><br>
   Sedang memuat data...</center>

    <div class="row data-dashboard" >

    </div>

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
@include('sisukma::periode')
@endsection
