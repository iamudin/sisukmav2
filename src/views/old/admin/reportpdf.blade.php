<style media="screen">
  td{
    padding:0 5px;
    border-color:#000
  }
  tr {
    border-color:#000
  }
</style>
<center> <h4 style="text-transform:uppercase">REKAPITULASI BOOKING SERVICE MOBIL<br> BENGKEL {{get_info('nama_bengkel')}}<br>{{$periode}}</h4> </center>
<table style="width:100%;border-collapse:collapse;border-color:#000" border="1">
  <thead>

  <tr>
    <th>NO</th>
    <th>TGL</th>
    <th>PELANGGAN</th>
    <th>MOBIL</th>
    <th>PERBAIKAN</th>
    <th>STATUS</th>
  </tr>
</thead>
<tbody>
  @foreach($data as $k=>$r)
  <tr>
    <td style="text-align:center">{{$k+1}}</td>
    <td>{{tglindo($r->jadwal_booking)}}</td>
    <td>{{$r->nama}}</td>
    <td>{{$r->merk}}</td>
    <td>{{$r->nama_jenis}}</td>
    <td>{{$r->nama_status}}</td>
  </tr>
  @endforeach
</tbody>
</table>
