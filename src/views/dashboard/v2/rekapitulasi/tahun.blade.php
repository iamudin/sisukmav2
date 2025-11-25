<style>
    .table-container {
        margin: 20px auto;
        width: 100%;
        overflow-x: auto;
        font-family: "Segoe UI", Tahoma, sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }

    thead tr {
        color: #fff;
        text-align: center;
        font-weight: bold;
    }

    th,
    td {
        padding: 5px;
        border: 1px solid #9d9d9d;
        text-align: center;
        vertical-align: middle;
        font-size: 14px;
    }

    tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }

    tbody tr:hover {
        background-color: #eef2ff;
        transition: 0.2s;
    }

    td:first-child,
    th:first-child {
        font-weight: 600;
        background: #f1f5f9;
    }

    tfoot td {
        font-weight: bold;
        background: #e2e8f0;
    }

    @media screen and (max-width: 768px) {
        table {
            font-size: 12px;
        }

        th,
        td {
            padding: 8px;
        }
    }
    tr {
        page-break-inside: avoid;
    }
</style>

@php
// Kumpulkan semua data SKPD yang valid
$rows = collect($data)->where('skpd_id', '!=', null);

// Hitung total unsur dan total sample
$totalSample = $rows->sum('sample_diambil');

// Hitung rata-rata nilai perunsur
$avgUnsur = [];
for ($i = 1; $i <= 9; $i++) {
    $k = "u$i";
    $avgUnsur[$k] = round($rows->avg(fn($r) => $r->nilai_akhir_perunsur->$k));
}

// Hitung rata-rata nilai IKM
$avgIKM = round($rows->avg('nilai_akhir_ikm'));
@endphp

    <table>
            <tr>
                <th>No</th>
                <th>Nama OPP</th>
                <th>Periode Survei</th>
                <th>Jenis Layanan</th>
                @foreach(['Persyaratan', 'Prosedur', 'Waktu', 'Biaya', 'Produk', 'Kompetensi', 'Perilaku', 'Aduan', 'Sarpras'] as $r)
                    <th>{{ $r }}</th>
                @endforeach
                <th>IKM</th>
                <th>Jumlah Responden</th>
                <th>Metode SKM</th>
                <th>Unsur Perbaikan</th>
                <th>RTL</th>
                <th>% TL Periode Sebelumnya</th>
            </tr>
            @foreach($rows as $row)
                <tr style="background:#e0f2fe;font-weight:600">
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align:left !important">{{ $row->nama_skpd }}</td>
                    <td>Tahunan</td>
                    <td></td>
                    @for($i = 1; $i <= 9; $i++)
                        @php $k = "u$i"; @endphp
                        <td>{{ round($row->nilai_akhir_perunsur->$k, 2) }}</td>
                    @endfor
                    <td>{{ round($row->nilai_akhir_ikm, 2) }}</td>
                    <td>{{ $row->sample_diambil }}</td>
                    <td>Online</td>
                    <td>{{ $row->perbaikan?->unsur_perbaikan ?? null}}</td>
                    <td>{{ $row->perbaikan?->rencana_tindak_lanjut ?? null}}</td>
                    <td>{{ $row->perbaikan?->persentase_tindak_lanjut_sebelumnya ?? null}}</td>
                </tr>

                @foreach($row->hasil_perlayanan as $r)
                    <tr>
                        <td></td>
                        <td style="text-align:left !important">{{ $row->nama_skpd }}</td>
                        <td>Tahunan</td>
                        <td style="text-align:left !important">{{ $r->nama_layanan }}</td>
                        @for($i = 1; $i <= 9; $i++)
                            @php $k = "u$i"; @endphp
                            <td>{{ round($r->nilai_perunsur->$k) }}</td>
                        @endfor
                        <td>{{ round($r->nilai_konversi) }}</td>
                        <td>{{ $r->sample_diambil }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            @endforeach
   
            <tr>
                <td></td>
                <td><strong>IKM Instansi</strong></td>
                <td>Tahunan</td>
                <td></td>
                @for($i = 1; $i <= 9; $i++)
                    @php $k = "u$i"; @endphp
                    <td><strong>{{ $avgUnsur[$k] }}</strong></td>
                @endfor
                <td><strong>{{ $avgIKM }}</strong></td>
                <td><strong>{{ $totalSample }}</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
    </table>