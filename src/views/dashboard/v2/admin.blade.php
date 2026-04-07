@extends('sisukma::layout.app')
@section('content')
    <div class="container-fluid px-4">
        @php 
        $periode = $periode ?? date('Y')
        @endphp
        <h3 class="mt-4"><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
        <p style="font-size:30px;">Periode <b class="periode-title">{{ $periode }}</b> <button
                onclick="$('.periode').modal('show')" class="btn btn-sm btn-danger float-end mt-2"> <i
                    class="fa fa-edit"></i> Ganti Periode</button></p>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

        <ul class="nav nav-tabs" id="ikmTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link @if(!str($periode)->contains('2026')) active @endif" id="ikm-tab" data-toggle="tab" href="#ikm" role="tab" aria-controls="ikm"
                    aria-selected="@if(!str($periode)->contains('2026')) true @else false @endif">
                    9 Unsur
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if(str($periode)->contains('2026')) active @endif" id="ikm16-tab" data-toggle="tab" href="#ikm16" role="tab"
                    aria-controls="ikm16" aria-selected="@if(str($periode)->contains('2026')) true @else false @endif">
                    16 Unsur
                </a>
            </li>
        </ul>

        <div class="tab-content mt-3" id="ikmTabContent">
            <div class="tab-pane fade @if(!str($periode)->contains('2026')) show active @endif" id="ikm" role="tabpanel">
        @include('sisukma::dashboard.v2.9')
            </div>
            <div class="tab-pane fade  @if(str($periode)->contains('2026')) show active @endif" id="ikm16" role="tabpanel">
                
        @include('sisukma::dashboard.v2.16')
            </div>

        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let query = window.location.search; // contoh: ?jenis_periode=bulan&tahun=2025
                let queryParams = {};

                if (!query || query === '?') {
                    // jika query kosong, set default
                    const tahunSekarang = new Date().getFullYear();
                    queryParams = {
                        jenis_periode: 'tahun',
                        tahun: tahunSekarang
                    };
                } else {
                    // konversi query string menjadi object
                    query.substring(1).split('&').forEach(pair => {
                        const [key, value] = pair.split('=');
                        queryParams[key] = decodeURIComponent(value || '');
                    });
                }

                // kirim request POST AJAX
                fetch(window.location.pathname, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(queryParams)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.msg == 'new') {
                            window.location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        </script>
        <script>
            $(function () {
                $('.btn-detail').on('click', function () {
                    const responId = $(this).data('respon');

                    // Tampilkan modal
                    $('#modalRespon').modal('show');

                    // Reset isi modal ke loading
                    $('#respon-content').hide().html('');
                    $('#loading').show();

                    // Request AJAX pakai Fetch
                    fetch("{{ route('statsresp') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json', // penting!
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ data: responId })
                    })
                        .then(response => {
                            if (!response.ok) throw new Error('Network response error');
                            return response.text(); // <-- penting, baca HTML sebagai text
                        })
                        .then(html => {
                            $('#loading').hide();
                            $('#respon-content').html(html);
                            $('#respon-content').show();
                        })
                        .catch(error => {
                            $('#loading').hide();
                            $('#respon-content').html('<p class="text-danger">Gagal memuat data.</p>');
                            console.error('Error:', error);
                        });

                });
            });
        </script>

        <div class="modal fade" id="modalRespon" tabindex="-1" role="dialog" aria-labelledby="modalResponLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalResponLabel">Statistik</h5>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Tutup"
                            onclick="$('#modalRespon').modal('hide')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="loading" class="py-3">
                            <div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem;">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div id="respon-content" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
        @include('sisukma::dashboard.v2.periode')
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
@endsection