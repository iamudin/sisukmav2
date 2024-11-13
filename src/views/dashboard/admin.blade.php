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

    fetch('{{ route("ajax.rekap_skpd_pdf") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token Laravel untuk keamanan
        },
        body: JSON.stringify({
            year: dec.year,
            unsur_tambahan: dec.unsur_tambahan,
            month: dec.month,
            from: dec.from,
            to: dec.to,
            type: type,
            skpd_id: skpd_id
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        // return response.json(); // Jika respons JSON, gunakan `.json()`, atau `.text()` jika HTML
    })
    .then(data => {
        // Lakukan sesuatu dengan `data` respons jika diperlukan
        // console.log(data);
    })
    .catch(error => {
        // console.error('Error:', error);
    });
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

//    function modal_detail_ikm(dataikm,skpd,alamat,telp,type_unsur,skpd_id){
//     var periode = 'dfdf';
//     $.ajax({
//             url: '{{ route("ajax.detailikm") }}',
//             type: 'POST',
//             data:{
//                 _token:"{{ csrf_token() }}",
//                 data_ikm:dataikm,
//                 skpd:skpd,
//                 alamat:alamat,
//                 telp:telp,
//                 periode:periode,
//                 type_unsur:type_unsur,
//                 skpd_id:skpd_id
//             },
//             success: function(response) {
//                 $('#detailikm .modal-body').html(response);
//                 $('#detailikm').modal('show');
//             },
//             error: function(xhr, status, error) {
//                 console.error(xhr.responseText);
//             }
//         });
//    }

</script>
@endpush
@endsection
