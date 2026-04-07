<center>
    <h2>PENGOLAHAN DATA HASIL SURVEY KEPUASAN MASYARAKAT</h2>

    @if(isset($nama_skpd))
    <h4>Unit Pelayanan : {{ $nama_skpd }}<br>Periode : {{ getNamaPeriode($jenis_periode, $periode, $tahun) }}</h4>@endif
    @if($layanan)
        <h4>{{ $layanan }}</h4>
    @endif

</center>

<table border="1" cellspacing="0" cellpadding="5"
    style="font-size:small;border-collapse: collapse; text-align: center; width: 100%;">
    <tbody>
        <tr style="background-color: #f2f2f2;">
            <th rowspan="3">NO. RES</th>
            <th rowspan="3">Jenis Layanan</th>
            <th rowspan="3">Pendidikan</th>
            <th rowspan="3">Jenis Kelamin</th>
            <th rowspan="3">Pekerjaan</th>
            <th rowspan="3">Kategorisasi Pengguna Layanan</th>
            <th rowspan="3">Kategori Disabilitas</th>
            <th colspan="16">Nilai Unsur Pelayanan</th>
        </tr>
        <tr style="background-color: #f9f9f9;">
            <th colspan="2">Persyaratan</th>
            <th colspan="3">Prosedur</th>
            <th colspan="1">Waktu</th>
            <th colspan="3">Biaya</th>
            <th colspan="1">Produk</th>
            <th colspan="1">Kompetensi</th>
            <th colspan="3">Perilaku</th>
            <th>Aduan</th>
            <th>Sapras</th>
        </tr>
        <tr>
 @for($u = 1; $u <= 16; $u++)
                   <td>{{ "P" . $u }}</td>
                @endfor
                </tr>
        @foreach($data->data_responden as $row)

            <tr>
                <td>{{$loop->iteration}}</td>
                <td align="left">{{ $row->nama_layanan }}</td>
                <td>{{ $row->pendidikan }}</td>
                <td>{{ $row->jenis_kelamin }}</td>
                <td>{{ $row->pekerjaan }}</td>
                <td>{{str($row->disabilitas)->headline()}}</td>
                <td>{{ $row->jenis_disabilitas }}</td>
                @for($u = 1; $u <= 16; $u++)
                    @php $k = "u" . $u @endphp
                    <td>{{ $row->$k }}</td>
                @endfor
            </tr>
        @endforeach

        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <th colspan="7" style="text-align: left;">Total Nilai Pertanyaan</th>
            @for($u = 1; $u <= 16; $u++)
                @php $k = "u" . $u @endphp
                <td>{{ $data->jumlah_total_perunsur->$k }}</td>
            @endfor
        </tr>

        <tr style="background-color: #f9f9f9;">
            <th colspan="7" style="text-align: left;">Nilai Rata-rata Pertanyaan</th>
            @for($u = 1; $u <= 16; $u++)
                @php $k = "u" . $u @endphp
                <td>{{ round($data->jumlah_rata_perunsur->$k, 2)}}</td>
            @endfor
        </tr>

        <tr style="background-color: #f2f2f2;">
            <th colspan="7" style="text-align: left;">Nilai Per Unsur</th>
           
            <td colspan="2">{{round($data->jumlah_nilai_perunsur->p1, 2)}}</td>
            <td colspan="3">{{round($data->jumlah_nilai_perunsur->p2, 2)}}</td>
            <td colspan="1">{{round($data->jumlah_nilai_perunsur->p3, 2)}}</td>
            <td colspan="3">{{round($data->jumlah_nilai_perunsur->p4, 2)}}</td>
            <td colspan="1">{{round($data->jumlah_nilai_perunsur->p5, 2)}}</td>
            <td colspan="1">{{round($data->jumlah_nilai_perunsur->p6, 2)}}</td>
            <td colspan="3">{{round($data->jumlah_nilai_perunsur->p7, 2)}}</td>
            <td colspan="1">{{round($data->jumlah_nilai_perunsur->p8, 2)}}</td>
            <td colspan="1">{{round($data->jumlah_nilai_perunsur->p9, 2)}}</td>


        </tr>

        <tr style="background-color: #f9f9f9;">
            <th colspan="7" style="text-align: left;">Kategori Per Unsur</th>
            <td colspan="2">{{prediket(round($data->jumlah_nilai_perunsur->p1, 2)*25,true)}}</td>
            <td colspan="3">{{prediket(round($data->jumlah_nilai_perunsur->p2, 2)*25,true)}}</td>
            <td colspan="1">{{prediket(round($data->jumlah_nilai_perunsur->p3, 2)*25,true)}}</td>
            <td colspan="3">{{prediket(round($data->jumlah_nilai_perunsur->p4, 2)*25,true)}}</td>
            <td colspan="1">{{prediket(round($data->jumlah_nilai_perunsur->p5, 2)*25,true)}}</td>
            <td colspan="1">{{prediket(round($data->jumlah_nilai_perunsur->p6, 2)*25,true)}}</td>
            <td colspan="3">{{prediket(round($data->jumlah_nilai_perunsur->p7, 2)*25,true)}}</td>
            <td colspan="1">{{prediket(round($data->jumlah_nilai_perunsur->p8, 2)*25,true)}}</td>
            <td colspan="1">{{prediket(round($data->jumlah_nilai_perunsur->p9, 2)*25,true)}}</td>

        </tr>

        <tr style="background-color: #f2f2f2;">
            <th colspan="7" style="text-align: left;">IKM OPP</th>
            <td colspan="16">{{round($data->nilai_ikm, 2)}}</td>
        </tr>

        <tr style="background-color: #f9f9f9;">
            <th colspan="7" style="text-align: left;">IKM Konversi OPP</th>
            <td colspan="16">{{round($data->nilai_konversi, 2)}}</td>
        </tr>

        <tr style="background-color: #f2f2f2;">
            <th colspan="7" style="text-align: left;">Mutu Layanan</th>
            <td colspan="16">{{$data->predikat_mutu}}</td>
        </tr>
    </tbody>

</table>