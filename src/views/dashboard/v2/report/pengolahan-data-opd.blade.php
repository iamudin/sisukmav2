<center>
    <h2>PENGOLAHAN DATA HASIL SURVEY KEPUASAN MASYARAKAT</h2>

   @if(isset($nama_skpd) ) <h4>Unit Pelayanan : {{ $nama_skpd }}</h4>@endif
    <h4>Periode : {{ str($periode.' '.($bulan ?? null).' '.$tahun)->upper() }}</h4>
    
</center>

<table border="1" cellspacing="0" cellpadding="5" style="font-size:small;border-collapse: collapse; text-align: center; width: 100%;">
    <tbody>
        <tr style="background-color: #f2f2f2;">
            <th rowspan="3">NO. RES</th>
            <th rowspan="3">Jenis Layanan</th>
            <th rowspan="3">Jenis Kelamin</th>
            <th rowspan="3">Pendidikan</th>
            <th rowspan="3">Pekerjaan</th>
            <th rowspan="3">Kategorisasi Pengguna Layanan</th>
            <th rowspan="3">Kategori Disabilitas</th>
            <th colspan="16">Nilai Unsur Pelayanan</th>
        </tr>
        <tr style="background-color: #f9f9f9;">
            <th colspan="2">Persyaratan</th>
            <th colspan="3">Prosedur</th>
            <th>Waktu</th>
            <th colspan="3">Biaya</th>
            <th>Produk</th>
            <th>Kompetensi</th>
            <th colspan="3">Perilaku</th>
            <th>Aduan</th>
            <th>Sapras</th>
        </tr>
        <tr style="background-color: #f9f9f9;">
            <th>P1</th>
            <th>P2</th>
            <th>P3</th>
            <th>P4</th>
            <th>P5</th>
            <th>P6</th>
            <th>P7</th>
            <th>P8</th>
            <th>P9</th>
            <th>P10</th>
            <th>P11</th>
            <th>P12</th>
            <th>P13</th>
            <th>P14</th>
            <th>P15</th>
            <th>P16</th>
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
        <td>{{ $data->total_perunsur->$k }}</td>
    @endfor
        </tr>
    
        <tr style="background-color: #f9f9f9;">
            <th colspan="7" style="text-align: left;">Nilai Rata-rata Pertanyaan</th>
            @for($u = 1; $u <= 16; $u++)
                @php $k = "u" . $u @endphp
                <td>{{ $data->rata_rata_perunsur->$k }}</td>
            @endfor
        </tr>
    
        <tr style="background-color: #f2f2f2;">
            <th colspan="7" style="text-align: left;">Nilai Per Unsur</th>
            <td colspan="2">{{$data->p_unsur->p1}}</td>
            <td colspan="3">{{$data->p_unsur->p2}}</td>
            <td>{{$data->p_unsur->p3}}</td>
            <td colspan="3">{{$data->p_unsur->p4}}</td>
            <td >{{$data->p_unsur->p5}}</td>
            <td>{{$data->p_unsur->p6}}</td>
            <td colspan="3">{{$data->p_unsur->p7}}</td>
            <td >{{$data->p_unsur->p8}}</td>
            <td>{{$data->p_unsur->p9}}</td>
        </tr>
    
        <tr style="background-color: #f9f9f9;">
            <th colspan="7" style="text-align: left;">Kategori Per Unsur</th>
            <td colspan="2">{{$data->prediket_unsur->p1}}</td>
            <td colspan="3">{{$data->prediket_unsur->p2}}</td>
            <td>{{$data->prediket_unsur->p3}}</td>
            <td colspan="3">{{$data->prediket_unsur->p4}}</td>
            <td >{{$data->prediket_unsur->p5}}</td>
            <td>{{$data->prediket_unsur->p6}}</td>
            <td colspan="3">{{$data->prediket_unsur->p7}}</td>
            <td >{{$data->prediket_unsur->p8}}</td>
            <td>{{$data->prediket_unsur->p9}}</td>
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



