@extends('sisukma::front.layoutfront')
@section('content')
                            <!-- Hero Slider -->
                        <div id="hero-slider" class="flex transition-transform duration-700 ease-in-out mt-12">
                          @forelse(['https://sisukma.bengkaliskab.go.id/e-surve.webp'] as $row)
                            <div class="relative w-full aspect-[16/5] h-full flex items-center justify-center bg-cover bg-center"
                                 style="background-image: url('{{ $row}}');">

                              <!-- overlay hitam -->

                            </div>
                          @empty

                          @endforelse
                        </div>





                            <!-- Statistik -->
                        <section id="statistik" class="max-w-7xl mx-auto px-4 py-16 min-h-[100vh]">

                        @php
    $tahunSekarang = date('Y');
    $jenisPeriode = request('jenis_periode', 'tahun');
    $tahun = request('tahun', $tahunSekarang);
    $periode = request('periode', null);

    // Mapping label periode
    $labelPeriode = match ($jenisPeriode) {
        'semester' => $periode == 1 ? 'Semester I' : ($periode == 7 ? 'Semester II' : ''),
        'triwulan' => match ($periode) {
                1 => 'Triwulan I',
                4 => 'Triwulan II',
                7 => 'Triwulan III',
                10 => 'Triwulan IV',
                default => '',
            },
        'bulanan' => "Bulan " . ($periode ? date('F', mktime(0, 0, 0, $periode, 10)) : ''),
        default => "tahunan",
    };
                        @endphp
                            <div class="bg-white/80 backdrop-blur-md border border-blue-100 shadow-md rounded-xl p-4 mb-8">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <h4 class="text-lg font-semibold text-blue-800">Filter Periode Survei</h4>
                                        <p class="text-sm text-blue-600">
                                            Pilih jenis periode, tahun, dan rentang survei untuk menampilkan hasil IKM
                                        </p>
                                    </div>

                                    <form action="" method="GET" class="flex flex-wrap items-center gap-3">
                                        <!-- Jenis Periode -->
                                        <select id="jenis_periode" name="jenis_periode"
                                            class="border border-blue-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            <option value="tahun" {{ $jenisPeriode === 'tahun' ? 'selected' : '' }}>Tahunan</option>
                                            <option value="semester" {{ $jenisPeriode === 'semester' ? 'selected' : '' }}>Semester</option>
                                            <option value="triwulan" {{ $jenisPeriode === 'triwulan' ? 'selected' : '' }}>Triwulan</option>
                                            <option value="bulan" {{ $jenisPeriode === 'bulan' ? 'selected' : '' }}>Bulanan</option>
                                        </select>

                                        <!-- Tahun -->
                                        <select id="tahun" name="tahun"
                                            class="border border-blue-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            <option value="">Pilih Tahun</option>
                                            @for($i = 2023; $i <= date('Y'); $i++)
                                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>

                                        <!-- Periode Dinamis -->
                                        <select id="periode" name="periode"
                                            class="hidden border border-blue-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        </select>

                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition-all">
                                            Tampilkan
                                        </button>
                                    </form>
                                </div>

                                <!-- Judul Periode Aktif -->
                                <div class="mt-4 p-3 bg-blue-50 border border-blue-100 rounded-lg text-blue-800 text-sm">
                                    <strong>Periode Saat Ini:</strong>
                                    {{ $namaperiode}}
                                </div>
                            </div>
                                <script>
                                    const jenisSelect = document.getElementById('jenis_periode');
                                    const periodeSelect = document.getElementById('periode');

                                    jenisSelect.addEventListener('change', function () {
                                        const jenis = this.value;
                                        periodeSelect.innerHTML = ''; // kosongkan dulu

                                        if (!jenis || jenis === 'tahun') {
                                            periodeSelect.classList.add('hidden');
                                            return;
                                        }

                                        periodeSelect.classList.remove('hidden');

                                        let options = [];

                                        if (jenis === 'triwulan') {
                                            options = [
                                                { text: 'Triwulan I', value: 1 },
                                                { text: 'Triwulan II', value: 4 },
                                                { text: 'Triwulan III', value: 7 },
                                                { text: 'Triwulan IV', value: 10 },
                                            ];
                                        } else if (jenis === 'semester') {
                                            options = [
                                                { text: 'Semester I', value: 1 },
                                                { text: 'Semester II', value: 7 },
                                            ];
                                        } else if (jenis === 'bulan') {
                                            options = [
                                                { text: 'Januari', value: 1 },
                                                { text: 'Februari', value: 2 },
                                                { text: 'Maret', value: 3 },
                                                { text: 'April', value: 4 },
                                                { text: 'Mei', value: 5 },
                                                { text: 'Juni', value: 6 },
                                                { text: 'Juli', value: 7 },
                                                { text: 'Agustus', value: 8 },
                                                { text: 'September', value: 9 },
                                                { text: 'Oktober', value: 10 },
                                                { text: 'November', value: 11 },
                                                { text: 'Desember', value: 12 },
                                            ];
                                        }

                                        options.forEach(opt => {
                                            const option = document.createElement('option');
                                            option.value = opt.value;
                                            option.textContent = opt.text;
                                            periodeSelect.appendChild(option);
                                        });
                                    });
                                </script>

                                </div>

                                <!-- Nilai Kabupaten (paling besar) -->
                                <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-1 gap-6 mb-5">
                                    @if(!$data)
                                       <div id="loading-spinner" class="flex flex-col items-center justify-center py-12 text-blue-600">
                                    <svg class="animate-spin h-10 w-10 text-blue-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-600">Sedang memuat data...</p>
                                </div>
                                @else
                                @if($row = $data->where('skpd_id', null)->first())
                                  <div class="bg-white/90 rounded-2xl shadow-md p-6 border border-blue-100">
                                                <h3 class="text-xl font-semibold text-center text-blue-800 mb-4">KABUPATEN BENGKALIS</h3>
                                                <hr class="border-gray-200 mb-4">

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <p class="text-gray-600 font-medium">Nilai IKM</p>
                                                        <p class="text-5xl font-bold text-blue-600 mt-2">{{ round($row->nilai_konversi, 2) }}</p>
                                                        <p class="text-gray-700 font-medium mt-2">Mutu Pelayanan</p>
                                                        <p class="text-blue-700 font-semibold text-lg">{{ $row->predikat_mutu_layanan }}</p>
                                                        <p class="text-gray-500 text-sm">({{ prediket($row->nilai_konversi) }})</p>
                                                    </div>

                                                    <div>
                                                        <table class="w-full text-sm text-gray-700">
                                                            <tbody>
                                                                <tr class="border-b border-gray-200">
                                                                    <td class="py-1 font-medium w-1/1">Jumlah</td>
                                                                    <td class="py-1">: <span class="font-semibold">{{ $row->sample_diambil }} Orang</span></td>
                                                                </tr>
                                                                <tr class="border-b border-gray-200">
                                                                    <td class="py-1 font-medium">Jenis Kelamin</td>
                                                                    <td class="py-1">: L = {{ $row->statistik_responden->jenis_kelamin->L->jumlah ?? 0 }} / P = {{ $row->statistik_responden->jenis_kelamin->P->jumlah ?? 0 }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" class="pt-2 font-medium">Pendidikan</td>
                                                                </tr>
                                                                @foreach($row->statistik_responden->pendidikan as $key => $pd)
                                                                    <tr>
                                                                        <td>{{ $key }}</td>
                                                                        <td>: {{ $pd->jumlah}} Orang</td>
                                                                    </tr>
                                                                @endforeach


                                                                <tr>
                                                                    <td class="pt-3 font-medium">Non Disabilitas </td><td>: <span
                                                                            class="font-semibold">{{{$row->statistik_responden->kategori_pengguna->non_disabilitas->jumlah ?? 0}}} Orang</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" class="pt-2 font-medium">Disabilitas</td>
                                                                </tr>
                                                                @foreach($row->statistik_responden->kategori_pengguna->disabilitas as $k => $dis)
                                                                <tr>
                                                                    <td>{{$dis->label}}</td>
                                                                    <td>: {{ $dis->jumlah }} Orang</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                    @endif
                                </div>

                                <!-- Daftar Perangkat Daerah -->
                                @if($data)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        @foreach($data->where('skpd_id', '!=', null)->sortByDesc('nilai_konversi') as $row)
                                            <div class="bg-white/90 rounded-2xl shadow-md p-6 border border-blue-100">
                                                <h3 class="text-xl font-semibold text-center text-blue-800 mb-4">{{$row->nama_skpd}}</h3>
                                                <hr class="border-gray-200 mb-4">

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <p class="text-gray-600 font-medium">Nilai IKM</p>
                                                        <p class="text-5xl font-bold text-blue-600 mt-2">{{ round($row->nilai_konversi, 2) }}</p>
                                                        <p class="text-gray-700 font-medium mt-2">Mutu Pelayanan</p>
                                                        <p class="text-blue-700 font-semibold text-lg">{{ $row->predikat_mutu_layanan }}</p>
                                                        <p class="text-gray-500 text-sm">({{ prediket($row->nilai_konversi) }})</p>
                                                            <a href="{{ route('ikm.skpd', $row->skpd_id) . '?' . request()->getQueryString() }}"  class="px-5 bg-blue-600 hover:bg-blue-700 text-white shadow transition" style="font-size:small">Cetak</a>
                                                    </div>

                                                    <div>
                                                        <table class="w-full text-sm text-gray-700">
                                                            <tbody>
                                                                <tr class="border-b border-gray-200">
                                                                    <td class="py-1 font-medium w-1/1">Jumlah</td>
                                                                    <td class="py-1">: <span class="font-semibold">{{ $row->sample_diambil }} Orang</span></td>
                                                                </tr>
                                                                <tr class="border-b border-gray-200">
                                                                    <td class="py-1 font-medium">Jenis Kelamin</td>
                                                                    <td class="py-1">: L = {{ $row->statistik_responden->jenis_kelamin->L->jumlah ?? 0 }} / P = {{ $row->statistik_responden->jenis_kelamin->P->jumlah ?? 0 }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" class="pt-2 font-medium">Pendidikan</td>
                                                                </tr>
                                                                @foreach($row->statistik_responden->pendidikan as $key => $pd)
                                                                    <tr>
                                                                        <td>{{ $key }}</td>
                                                                        <td>: {{ $pd->jumlah}} Orang</td>
                                                                    </tr>
                                                                @endforeach


                                                                <tr >
                                                                    <td class="font-medium pt-4" >Non Disabilitas</td>
                                                                    <td class="pt-4">: <span
                                                                            class="font-semibold">{{{$row->statistik_responden->kategori_pengguna->non_disabilitas->jumlah ?? 0}}} Orang</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="pt-2 font-medium">Disabilitas</td>
                                                                </tr>
                                                                @foreach($row->statistik_responden->kategori_pengguna->disabilitas as $k => $dis)
                                                                <tr>
                                                                    <td>{{$dis->label}}</td>
                                                                    <td>: {{ $dis->jumlah }} Orang</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </section>


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
                                            if(data.msg=='new'){
                                                window.location.reload();
                                            }
                                    })
                                    .catch(error => console.error('Error:', error));
                            });
                        </script>

@endsection