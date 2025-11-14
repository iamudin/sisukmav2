<center>
    <h2>PENGOLAHAN DATA HASIL SURVEY KEPUASAN MASYARAKAT</h2>

    @if(isset($nama_skpd))
    <h4>Unit Pelayanan : {{ $nama_skpd }}<br>Periode : {{ getNamaPeriode($jenis_periode, $periode, $tahun) }}</h4>@endif
  


</center>
<table border="1" cellspacing="0" cellpadding="6"
    style="border-collapse: collapse; text-align: center; font-family: Arial; font-size: 8px;width:100%">
    <tbody>
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
  
        @foreach($data->hasil_perlayanan as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td align="left" style="width:500px">{{ $row->nama_layanan }}</td>
                <td>{{ getNamaPeriode($jenis_periode, $periode, $tahun) }}</td>
                @for($u = 1; $u <= 9; $u++)
                @php $k = "u$u";@endphp
                <td>{{ round($row->nilai_perunsur->$k)}}</td>
                @endfor
                <td>{{ round($row->nilai_konversi, 2) }}</td>
                <td>{{ $row->predikat_mutu }}</td>
                <td>{{ $row->sample_diambil }}</td>
       
            </tr>
           @endforeach
        <tr style="background-color: #d9ead3; font-weight: bold;">
            <td colspan="3">Nilai Per Unsur</td>
        @for($u = 1; $u <= 9; $u++)
            @php $k = "u$u";@endphp
            <td>{{ round($data->jumlah_nilai_perunsur->$k, 2) }}</td>
        @endfor
            <td colspan="3"></td>
                </tr>
        <tr style="background-color: #d9ead3; font-weight: bold;">
            <td colspan="3">Kategori Per Unsur</td>
            @for($u = 1; $u <= 9; $u++)
                @php $k = "u$u";@endphp
                <td>{{ getPredikat($data->jumlah_rata_perunsur->$k) }}</td>
            @endfor
            <td colspan="7"></td>
                </tr>
        <tr style="background-color: #fff200; font-weight: bold;">
            <td colspan="3">IKM IP</td>
            <td colspan="9">{{ round($data->nilai_konversi, 2) }}</td>
            <td colspan="3"></td>
                </tr>
        <tr style="background-color: #d9ead3; font-weight: bold;">
            <td colspan="3">Mutu Layanan</td>
            <td colspan="9">{{ $data->predikat_mutu }}</td>
            <td colspan="3"></td>
                </tr>
    </tbody>
</table>