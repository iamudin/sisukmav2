<html>
    <body>
        <center><h3>REKAPITULASI NILAI INDEKS KEPUASAN MASYARAKAT <br>DI LINGKUNGAN PEMERINTAH KABUPATEN BENGKALIS<br> PERIODE {{Str::upper($periode)}}</h3></center>
        <table border="1" style="border-collapse:collapse;width:100%;border-style:solid;border-color:#000;font-size:10px">
<tr>
    <td rowspan="2" align="center">No</td>
    <td rowspan="2" align="center">Perangkat Daerah</td>
    <td colspan="9" align="center">Nilai Unsur Pelayanan</td>
    <td rowspan="2" align="center">Nilai IKM</td>
</tr>
<tr>
@for($i=1;$i<=9; $i++)
<td align="center">U{{$i}}</td>
@endfor
</tr>
@foreach($data->data as $k=>$r)
<tr>
    <td align="center">{{$k+1}}</td>
    <td >{{$r->nama_skpd}}</td>
    @foreach(['u1','u2','u3','u4','u5','u6','u7','u8','u9'] as $s)
    <td align="right" style="padding-right:10px">{{round($r->$s,2)}}</td>
    @endforeach
    <td align="right" style="padding-right:10px">{{round($r->ikm,2)}}</td>
</tr>
@endforeach
<tr>
    <td colspan="2" align="center">IKM Perunsur</td>
    @foreach(['u1','u2','u3','u4','u5','u6','u7','u8','u9'] as $s)
    <td align="right" style="padding-right:10px">{{round($data->unsur->$s,2)}}</td>
    @endforeach
    <td align="right" style="padding-right:10px">{{round($data->unsur->total_ikm,2)}}</td>
</tr>
        </table>
<br>
<br>
<br>


    </body>
</html>