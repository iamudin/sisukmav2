@extends('sisukma::layout.app')
@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4"><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
   <p style="font-size:30px;">Periode <b class="periode-title">{{ $periode }}</b> <button onclick="$('.periode').modal('show')" class="btn btn-sm btn-danger float-end mt-2"> <i class="fa fa-edit"></i> Ganti Periode</button></p>
   <center  id="spinner" style="display: none" >
    <div class="spinner-border text-primary" role="status">

   </div><br>
   Sedang memuat data...</center>

    <div class="row data-dashboard" >

    </div>
    <input type="hidden" class="ajax_data" value="{!!base64_encode($ajaxdata ?? json_encode(['year'=>date('Y')]))!!}">
</div>
<form id="cetakRekapForm" method="POST" action="{{ route('ajax.rekap_skpd_pdf') }}">
    @csrf <!-- Token CSRF Laravel untuk keamanan -->
    <input type="hidden" name="year" id="year">
    <input type="hidden" name="unsur_tambahan" id="unsur_tambahan">
    <input type="hidden" name="month" id="month">
    <input type="hidden" name="from" id="fromF">
    <input type="hidden" name="to" id="toF">
    <input type="hidden" name="type" id="type">
    <input type="hidden" name="skpd_id" id="skpd_id">
</form>
@include('sisukma::periode')
@include('sisukma::dashboard.ajax.modal')
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#spinner').show();
        loadData();
    });

    function loadData()
    {
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
    function cetak_rekap(skpd_id, periode, type) {
    let dec = JSON.parse(atob(periode));

    document.getElementById('year').value = dec.year ?? null;
document.getElementById('unsur_tambahan').value = dec.unsur_tambahan ?? null;
document.getElementById('month').value = dec.month ?? null;
document.getElementById('fromF').value = dec.from ?? null;
document.getElementById('toF').value = dec.to ?? null;
document.getElementById('type').value = type ?? null;
document.getElementById('skpd_id').value = skpd_id ?? null;

// Submit form
document.getElementById('cetakRekapForm').submit();
}

    function modal_detail_ikm(dataikm, skpd, alamat, telp, type_unsur, skpd_id) {
    const periode = $('.periode-title').text();

    fetch('{{ route("ajax.detailikm") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token untuk keamanan Laravel
        },
        body: JSON.stringify({
            data_ikm: dataikm,
            skpd: skpd,
            alamat: alamat,
            telp: telp,
            periode: periode,
            type_unsur: type_unsur,
            skpd_id: skpd_id
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text(); // Laravel mungkin mengirim HTML, jadi kita gunakan `.text()`
    })
    .then(data => {
        document.querySelector('#detailikm .modal-body').innerHTML = data;
        $('#detailikm').modal('show'); // Pastikan Anda menggunakan jQuery untuk memunculkan modal
    })
    .catch(error => {
        // console.error('Error:', error);
    });
}


</script>
@endpush
@endsection
