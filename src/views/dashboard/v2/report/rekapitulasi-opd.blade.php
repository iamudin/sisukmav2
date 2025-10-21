<center>
    <h2>PENGOLAHAN DATA HASIL SURVEY KEPUASAN MASYARAKAT</h2>

    @if(isset($record))
    <h4>Unit Pelayanan : {{ isset($record) ? $record['nama_skpd'] : ''}}</h4>@endif
        <h4>Periode : {{ getNamaPeriode($jenis_periode, $periode, $tahun) }}</h4>


</center>
<table border="1" cellspacing="0" cellpadding="6"
    style="border-collapse: collapse; text-align: center; font-family: Arial; font-size: 13px;width:100%">
    <thead>
        <tr style="background-color: #cfe2f3;">
            <th rowspan="2">NO. RES</th>
            <th rowspan="2">Jenis Layanan</th>
            <th rowspan="2">Periode Pelaksanaan</th>
            <th colspan="9">NILAI UNSUR PELAYANAN</th>
            <th rowspan="2">IKM</th>
            <th rowspan="2">Kategori / Mutu</th>
            <th rowspan="2">Jumlah Responden</th>
        </tr>
        <tr style="background-color: #cfe2f3;">
            <th>Persyaratan</th>
            <th>Prosedur</th>
            <th>Waktu</th>
            <th>Biaya</th>
            <th>Produk</th>
            <th>Kompetensi</th>
            <th>Perilaku</th>
            <th>Aduan</th>
            <th>Sarpas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data->per_layanan as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td align="left">{{ $row->nama_layanan }}</td>
                <td>{{ getNamaPeriode($jenis_periode, $periode, $tahun) }}</td>
                <td>{{ $row->konversi_unsur->p1 }}</td>
                <td>{{ $row->konversi_unsur->p2 }}</td>
                <td>{{ $row->konversi_unsur->p3 }}</td>
                <td>{{ $row->konversi_unsur->p4 }}</td>
                <td>{{ $row->konversi_unsur->p5 }}</td>
                <td>{{ $row->konversi_unsur->p6 }}</td>
                <td>{{ $row->konversi_unsur->p7 }}</td>
                <td>{{ $row->konversi_unsur->p8 }}</td>
                <td>{{ $row->konversi_unsur->p9 }}</td>
                <td>{{ $row->nilai_konversi }}</td>
                <td>{{ $row->predikat_mutu_layanan }}</td>
                <td>{{ $row->jumlah_responden }}</td>
            </tr>
           @endforeach
        <tr style="background-color: #d9ead3; font-weight: bold;">
            <td colspan="3">Nilai Per Unsur</td>
            <td>{{ $data->nilai_perunsur->p1 }}</td>
            <td>{{ $data->nilai_perunsur->p2 }}</td>
            <td>{{ $data->nilai_perunsur->p3 }}</td>
            <td>{{ $data->nilai_perunsur->p4 }}</td>
            <td>{{ $data->nilai_perunsur->p5 }}</td>
            <td>{{ $data->nilai_perunsur->p6 }}</td>
            <td>{{ $data->nilai_perunsur->p7 }}</td>
            <td>{{ $data->nilai_perunsur->p8 }}</td>
            <td>{{ $data->nilai_perunsur->p9 }}</td>
            <td colspan="3"></td>
        </tr>
        <tr style="background-color: #d9ead3; font-weight: bold;">
            <td colspan="3">Kategori Per Unsur</td>
            <td>{{ $data->predikat_perunsur->p1 }}</td>
            <td>{{ $data->predikat_perunsur->p2 }}</td>
            <td>{{ $data->predikat_perunsur->p3 }}</td>
            <td>{{ $data->predikat_perunsur->p4 }}</td>
            <td>{{ $data->predikat_perunsur->p5 }}</td>
            <td>{{ $data->predikat_perunsur->p6 }}</td>
            <td>{{ $data->predikat_perunsur->p7 }}</td>
            <td>{{ $data->predikat_perunsur->p8 }}</td>
            <td>{{ $data->predikat_perunsur->p9 }}</td>
            <td colspan="3"></td>
        </tr>
        <tr style="background-color: #fff200; font-weight: bold;">
            <td colspan="3">IKM IP</td>
            <td colspan="9">{{ $data->nilai_ikm }}</td>
            <td colspan="3"></td>
        </tr>
        <tr style="background-color: #d9ead3; font-weight: bold;">
            <td colspan="3">Mutu Layanan</td>
            <td colspan="9">{{ $data->predikat_ikm }}</td>
            <td colspan="3"></td>
        </tr>
    </tbody>
</table>