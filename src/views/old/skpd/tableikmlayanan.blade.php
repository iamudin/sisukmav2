<center><h4>REKAPITULASI RESPONDEN LAYANAN <br>{{Str::upper(Str::headline(request()->layanan))}}<br> PERIODE {{Str::upper($periode)}}</h4></center>
<?php
  $unsur = ['u1','u2','u3','u4','u5','u6','u7','u8','u9'];
  foreach($unsur as $r){
    $u[$r] = 0;
  }
 

?>
@if($respon->count() > 0)

<table border="1" style="border-collapse:collapse;width:100%;border-color:#000;font-size:10px" class="table table-bordered">
    <tr>
      <th rowspan="2" style="width:150px">No Res</th>
      <th colspan="9 style="text-align:center" >Nilai Unsur Pelayanan</th>
      <th align="center" rowspan="{{$respon->count()+4}}"></th>
    </tr>
    <tr>
      @for($i=1;$i<=9; $i++)
      <th style="text-align:center">U{{$i}}</th>
      @endfor
    </tr>
@foreach($respon as $k=>$r)

<?php
  foreach($unsur as $row){
    $u[$row] += $r->$row;
  }

?>
<tr>
  <td align="center">{{$k+1}}</td>
  @foreach($unsur as $row)
   <td align="center">{{$r->$row}}</td>
  @endforeach
</tr>
@endforeach
<tr>
  <td>Nilai/Unsur</td>

  @foreach($unsur as $r)
  <td align="center">{{ $u[$r]}}</td>
  @endforeach
    </tr>
    <tr>
      <td>NRR/Unsur</td>
      @foreach($unsur as $r)
  <td align="center">{{round( $u[$r] / $respon->count(),2)}}</td>
  @endforeach
    </tr>
    <tr>
      <td>NRR Tertimbang/Unsur</td>
      @foreach($unsur as $r)
  <td align="center">{{round( ($u[$r] / $respon->count())*1/9,2)}}</td>
  @endforeach
   
      <td align="center">*)
      <?php if($respon->count()>0){
  foreach($unsur as $r){
    $a[] = ($u[$r] / $respon->count()) * 1/9;
  }
}
else{
  $a[] = 0;
}?>
{{$a ? round(array_sum($a),2) : 0}}
        </td>
    </tr>

    <tr>
      <td colspan="10">Ikm Pelayanan</td>
    
      <td align="center">**) <b>{{$a ? round(array_sum($a)*25,2) : 0}}</b></td>
    </tr>
  
</table>
<table style="width:100%;border:none;font-size:10px" border="0">
<tr style="border:none"><td style="border:none" rowspan="11">
  Keterangan :<br>
  - U1 s.d U9 = Unsur-unsur pelayanan<br>
  - NRR = Unsur-unsur pelayanan<br>
  - IKM = Indek Kepuasan Masyarakat<br>
  - *) = Jumlah NRR IKM tertimbang<br>
  - **) = Jumlah NRR Tertimbang x 25<br>
  - NRR Per Usur = Jumlah nilai per unsur dibagi jumlah kusioner yang terisi<br>
  - NRR tertimbang = NRR per unsur x 0,111 per unsur<br><br>
  <h5 style="border:2px solid #000;padding:10px;margin-right:50px">IKM PELAYANAN <span style="float:right">{{$a ? round(array_sum($a)*25,2) : 0}}</span></h5>

  Mutu Pelayanan:<br>
  A (Sangat Baik) : 88,31 - 100,00<br>
  B (Baik) : 76,61 - 88,30<br>
  C (Kurang Baik) : 65,00 - 76,60<br>
  D (Tidak Baik): 25,00 - 64,99<br>
<td>
</tr>
    <tr >
    <td  style="border:1px solid #000;padding-left:4px">No</td>
    <td  style="border:1px solid #000;padding-left:4px">Unsur Pelayanan</td>
    <td  style="border:1px solid #000;padding-left:4px">Nilai Rata-Rata</td>
    </tr>
    @for($i=1; $i<=9; $i++)
    <tr >
      <td  style="border:1px solid #000;padding-left:4px">U{{$i}}</td>
      <td  style="border:1px solid #000;padding-left:4px">{{array(1=>'Persyaratan','Prosedur','Waktu Pelayanan','Biaya / Tarif','Produk Pelayanan','Kompetensi Pelaksana','Perilaku Pelaksana','Penanganan Pengaduan Saran dan Masukan','Sarana dan Prasarana')[$i]}}</td>
      <td  style="border:1px solid #000;padding-right:20px;text-align:right">
      {{array(1=>round($u['u1'] / $respon->count(),2),round($u['u2'] / $respon->count(),2),round($u['u3'] / $respon->count(),2),round($u['u4'] / $respon->count(),2),round($u['u5'] / $respon->count(),2),round($u['u6'] / $respon->count(),2),round($u['u7'] / $respon->count(),2),round($u['u8'] / $respon->count(),2),round($u['u9'] / $respon->count(),2))[$i]}}
    </td>

    </tr>
    @endfor

</table>

@else 
<div class="alert alert-warning">Belum ada responden</div>
@endif