<center>
    <h2>PENGOLAHAN DATA HASIL SURVEY KEPUASAN MASYARAKAT</h2>

@if(isset($nama_skpd))
<h4>Unit Pelayanan : {{ $nama_skpd }}<br>Periode : {{ getNamaPeriode($jenis_periode, $periode, $tahun) }}</h4>@endif
    
</center>

<table border="1" cellspacing="0" cellpadding="5" style="font-size:small;border-collapse: collapse; text-align: center; width: 100%;">
    <tbody>
        <tr style="background-color: #f2f2f2;">
            <th rowspan="2">NO. RES</th>
            <th rowspan="2">Jenis Layanan</th>
            <th rowspan="2">Jenis Kelamin</th>
            <th rowspan="2">Pendidikan</th>
            <th rowspan="2">Pekerjaan</th>
            <th rowspan="2">Kategorisasi Pengguna Layanan</th>
            <th rowspan="2">Kategori Disabilitas</th>
            <th colspan="9">Nilai Unsur Pelayanan</th>
        </tr>
        <tr style="background-color: #f9f9f9;">
            <th>Persyaratan</th>
            <th>Prosedur</th>
            <th>Waktu</th>
            <th>Biaya</th>
            <th>Produk</th>
            <th>Kompetensi</th>
            <th>Perilaku</th>
            <th>Aduan</th>
            <th>Sapras</th>
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
                @for($u = 1; $u <= 9; $u++)
                @php $k = "u" . $u @endphp
                <td>{{ $row->$k }}</td>
                @endfor
            </tr>
        @endforeach
   
        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <th colspan="7" style="text-align: left;">Total Nilai Pertanyaan</th>
    @for($u = 1; $u <= 9; $u++)
        @php $k = "u" . $u @endphp
        <td>{{ $data->total_perunsur->$k }}</td>
    @endfor
        </tr>
    
        <tr style="background-color: #f9f9f9;">
            <th colspan="7" style="text-align: left;">Nilai Rata-rata Pertanyaan</th>
            @for($u = 1; $u <= 9; $u++)
                @php $k = "u" . $u @endphp
                <td>{{ $data->rata_rata_perunsur->$k }}</td>
            @endfor
        </tr>
    
        <tr style="background-color: #f2f2f2;">
            <th colspan="7" style="text-align: left;">Nilai Per Unsur</th>
                @for($u = 1; $u <= 9; $u++)
                    @php $k = "u" . $u @endphp
                            <td>{{$data->p_unsur->$k}}</td>

                @endfor
           
        </tr>
    
        <tr style="background-color: #f9f9f9;">
            <th colspan="7" style="text-align: left;">Kategori Per Unsur</th>
                @for($u = 1; $u <= 9; $u++)
                    @php $k = "u" . $u @endphp
                                <td>{{$data->prediket_unsur->$k}}</td>


                @endfor
           
        </tr>
    
        <tr style="background-color: #f2f2f2;">
            <th colspan="7" style="text-align: left;">IKM OPP</th>
            <td colspan="16">{{$data->nilai_ikm}}</td>
        </tr>
    
        <tr style="background-color: #f9f9f9;">
            <th colspan="7" style="text-align: left;">IKM Konversi OPP</th>
            <td colspan="16">{{$data->nilai_konversi}}</td>
        </tr>
    
        <tr style="background-color: #f2f2f2;">
            <th colspan="7" style="text-align: left;">Mutu Layanan</th>
            <td colspan="16">{{$data->prediket_mutu_layanan}}</td>
        </tr>
    </tbody>

</table>



