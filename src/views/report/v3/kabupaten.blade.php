<table border="1" cellpadding="5" cellspacing="0"
    style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; text-align: center; font-size: 14px;">
    <tr style="font-weight: bold;">
        <td rowspan="2">NO</td>
        <td rowspan="2">Nama Unit</td>
        <td rowspan="2">Priode Pelaksanaan</td>
        <td rowspan="2">Jenis Layanan</td>
        <td colspan="9">NILAI UNSUR PELAYANAN</td>
        <td rowspan="2">IKM</td>
        <td rowspan="2">Kategori/Mutu</td>
        <td rowspan="2">Jumlah Responden</td>
    </tr>
    <tr style="font-weight: bold; background-color: #d9eaf7;">
        <td>Persyaratan</td>
        <td>Prosedur</td>
        <td>Waktu</td>
        <td>Biaya</td>
        <td>Produk</td>
        <td>Kompetensi</td>
        <td>Perilaku</td>
        <td>Aduan</td>
        <td>Sarpras</td>
    </tr>
    @foreach($data_v3 as $row)
            <tr>
                <td style="vertical-align: top">{{$loop->iteration}}</td>
                <td align="left">{{$row['nama_skpd']}}</td>
                <td>{{$periode}}</td>
                <td align="left">{{$row['nama_layanan']}}</td>
           @for($i=1;$i<=9;$i++)
            @php $k = 'u'.$i; @endphp
            <td>{{$row['total_nilai_unsur'][$k]}}</td>
           @endfor
                <td style="background-color: red; color: white;">105</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
    @endforeach
    <tr style="font-weight: bold;">
        <td colspan="4">Nilai Per Unsur</td>
        <td>83</td>
        <td>84</td>
        <td>76</td>
        <td>54</td>
        <td>67</td>
        <td>88</td>
        <td>99</td>
        <td>98</td>
        <td>105</td>
        <td></td>
        <td></td>
    </tr>
    <tr style="font-weight: bold;">
        <td colspan="4">Kategori Per Unsur</td>
        <td>A</td>
        <td>A</td>
        <td>A</td>
        <td>A</td>
        <td>A</td>
        <td>A</td>
        <td>A</td>
        <td>A</td>
        <td>A</td>
        <td></td>
        <td></td>
    </tr>
    <tr style="font-weight: bold;">
        <td colspan="13" style="text-align: right;">IKM IP</td>
        <td colspan="2">83.77777778</td>
    </tr>
    <tr style="font-weight: bold;">
        <td colspan="13" style="text-align: right;">Mutu Layanan</td>
        <td colspan="2">A</td>
    </tr>
</table>