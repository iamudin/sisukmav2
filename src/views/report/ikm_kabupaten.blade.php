
        <center><h3>REKAPITULASI NILAI INDEKS KEPUASAN MASYARAKAT <br>DI LINGKUNGAN PEMERINTAH KABUPATEN BENGKALIS<br> PERIODE {{Str::upper($periode)}}</h3></center>
        <table class="table table-bordered table-striped" border="1" style="border-collapse:collapse;width:100%;border-style:solid;border-color:#000;font-size:small">
            <thead>
<tr>
    <th rowspan="2" align="center" style="vertical-align: middle;text-align:center">No</th>
    <th rowspan="2" align="center" style="vertical-align: middle;text-align:center">Perangkat Daerah</th>
    <th colspan="9" align="center"  style="vertical-align: middle;text-align:center">Nilai Unsur Pelayanan</th>
    <th rowspan="2" align="center"  style="vertical-align: middle;text-align:center">Nilai IKM</th>
</tr>
<tr>
@for($i=1;$i<=9; $i++)
<th align="center" style="text-align:center">U{{$i}}</th>
@endfor
</tr>
</thead>
@foreach(collect($data->data_ikm_skpd)->sortByDesc('data.ikm')->values() as $k=>$r)
<tr>
    <td align="center">{{$k+1}}</td>
    <td style="width:300px" >{{$r->nama_skpd}}</td>
    @foreach(['u1','u2','u3','u4','u5','u6','u7','u8','u9'] as $s)
    @php
    $unsur[$s][] = $r->data->$s;
    @endphp
    <td align="right" style="padding-right:10px">{{round($r->data->$s,2)}}</td>
    @endforeach
    @php
    $ikm[] = $r->data->ikm;
    @endphp
    <td align="right" style="padding-right:10px">{{round($r->data->ikm,2)}}</td>
</tr>
@endforeach
<tr>
    <td colspan="2" align="center">IKM Perunsur</td>
    @foreach(['u1','u2','u3','u4','u5','u6','u7','u8','u9'] as $s)
    <td align="right" style="padding-right:10px">{{round(array_sum($unsur[$s]) / $data->total_skpd,2)}}</td>
    @endforeach
    <td align="right" style="padding-right:10px"><b>{{round(array_sum($ikm) / $data->total_skpd,2)}}</b></td>
</tr>
        </table>
