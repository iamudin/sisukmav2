<style>
            tr {
        page-break-inside: avoid;
    }
</style>
<center>
    <h4>PENGOLAHAN DATA SURVEI KEPUASAN MASYARAKAT PER RESPONDEN<br>DAN PERUNSUR LAYANAN</h4>
</center>
<table border="0" style="width:700px">
    <tr>
        <td>Unit Pelayanan </td>
        <td>: {{ $skpd}}</td>
    </tr>
    <tr>
        <td>Periode </td>
        <td>: {{ $periode }}</td>
    </tr>
    <tr>
        <td>Alamat </td>
        <td>: {{ $alamat }}</td>
    </tr>
    <tr>
        <td>Telp. Fax </td>
        <td>: {{ $telp }}</td>
    </tr>
</table>


<br>
<?php
$respon = $data->jumlah;
$resp = $data->responden;
for($unsur=1; $unsur<=$type_unsur; $unsur++):
$ukey = 'u'.$unsur;
$u[$ukey] = 0;
endfor;

?>
<table border="1" style="border-collapse:collapse;width:100%;border-style:solid;border-color:#000;font-size:10px"
    class="table table-bordered">
    <tr>
        <th rowspan="2" style="width:140px">No Res</th>
        <th colspan="{{ $type_unsur }}" style="text-align:center">Nilai Unsur Pelayanan</th>
        <th align="center" rowspan="{{ $respon + 4 }}"></th>
    </tr>
    <tr>
        @for ($i = 1; $i <= $type_unsur; $i++)
            <th style="text-align:center">U{{ $i }}</th>
        @endfor
    </tr>
    @if (count($resp) > 0)

@foreach($resp as $k=>$r)
        <tr>
            <td align="center">{{ $k + 1 }}</td>
            @foreach($u as $k=>$unsur)
            @php
            $u[$k] += $r->$k;
            @endphp
            @endforeach

            @foreach($u as $k=>$unsur)
            <td align="center">{{ $r->$k }}</td>
            @endforeach
        </tr>
@endforeach
        <tr>
            <td>Nilai/Unsur</td>
            @foreach($u as $k=>$unsur)
            <td align="center">{{ $u[$k] }}</td>
            @endforeach

        </tr>
        <tr>
            <td>NRR/Unsur</td>
            @foreach($u as $k=>$unsur)
            <td align="center">{{ round($u[$k] / $respon, 2) }}</td>

            @endforeach

        </tr>
        <tr>
            <td>NRR Tertimbang/Unsur</td>
            @foreach($u as $k=>$unsur)
            <td align="center">{{ round(($u[$k] / $respon) * (1 / $type_unsur), 2) }}</td>


            @endforeach


            <td align="center">*)
            @foreach($u as $k=>$unsur)
            @php
            $total_unsur[] = $u[$k] / $respon * (1 / $type_unsur);
            @endphp
            @endforeach
               {{ round(array_sum($total_unsur),2) }}
            </td>
        </tr>

        <tr>
            <td colspan="{{ $type_unsur+1 }}">Ikm Pelayanan</td>
            @php $ikm =array_sum($total_unsur) * 25;
            @endphp
            <td align="center">**) <b>{{$ikm > 0? round($ikm,2) : 0}}</b></td>
        </tr>
        @else
        <tr>
            <td colspan='10' align="center">
                <h1>Belum ada data</h1>
            </td>
        </tr>
        @endif
</table>

    @if (count($resp) > 0)
<table style="border-collapse:collapse;width:100%;border:none;margin-top:10px;font-size:10px" border="0">
    <tr style="border:none">
        <td style="border:none" rowspan="{{ $type_unsur+3 }}">
            Keterangan :<br>
            - U1 s.d U9 = Unsur-unsur pelayanan<br>
            - NRR = Nilai Rata-rata Unsur<br>
            - IKM = Indek Kepuasan Masyarakat<br>
            - *) = Jumlah NRR IKM tertimbang<br>
            - **) = Jumlah NRR Tertimbang x 25<br>
            - NRR Per Usur = Jumlah nilai per unsur dibagi jumlah kusioner yang terisi<br>
            - NRR tertimbang = NRR per unsur x 0,111 per unsur<br><br>

            <h5 style="border:2px solid #000;padding:10px;margin-right:50px">IKM UNIT PELAYANAN <span
                    style="float:right;font-weight: bold">{{$ikm > 0 ? round($ikm,2) : 0}}</span></h5>


        <td>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding-left:4px">No</td>
        <td style="border:1px solid #000;padding-left:4px">Unsur Pelayanan</td>
        <td style="border:1px solid #000;padding-left:4px">Nilai Rata-Rata</td>
    </tr>
    @for ($i = 1; $i <= $type_unsur; $i++)
        <tr>
            <td style="border:1px solid #000;padding-left:4px">U{{ $i }}</td>
            <td style="border:1px solid #000;padding-left:4px">
                {{ [1 => 'Persyaratan', 'Prosedur', 'Waktu Pelayanan', 'Biaya / Tarif', 'Produk Pelayanan', 'Kompetensi Pelaksana', 'Perilaku Pelaksana', 'Penanganan Pengaduan Saran dan Masukan', 'Sarana dan Prasarana','Transparansi','Integritas'][$i] }}
            </td>
            <td style="border:1px solid #000;padding-right:20px;text-align:right">
                @php
                $ukey = 'u'.$i;
                @endphp
                {{ round($u[$ukey] / $respon,2) }}
            </td>

        </tr>
    @endfor
</table>
@endif
@if($respon > 0)
<div style="font-size:10px">
Mutu Pelayanan:<br>
A (Sangat Baik) : 88,31 - 100,00<br>
B (Baik) : 76,61 - 88,30<br>
C (Kurang Baik) : 65,00 - 76,60<br>
D (Tidak Baik): 25,00 - 64,99<br>
@endif
</div>
