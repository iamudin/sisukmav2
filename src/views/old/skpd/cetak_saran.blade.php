<center><h1>Saran {{$periode}}</h1></center>
<style>
    tr th,tr td {padding:6px}
</style>
<table class="table table-striped" style="font-size:small;width:100%;border-collapse:collapse" border="1" >
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Jenis Layanan</th>
                    <th>Saran</th>
                </tr>
            </thead>
            @php $no=0; @endphp
            <tbody>
                @forelse($data as $row)
               
                <tr>
                    <td>{{$no +1}}</td>
                    <td>{{$row->tgl_survei}}</td>
                    <td>{{$row->jam_survei ?? '-'}}</td>
                    <td>{{$row->nama_layanan}}</td>
                    <td>{{$row->saran??'-'}}</td>
                </tr>
                @php $no++; @endphp

                @empty 
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
</table>