<table style="width:100%; border:4px solid #000; border-collapse:collapse; background:#fff;">

    <!-- Baris Judul SKPD -->
    <tr>
        <td colspan="2"
            style="text-align:center; padding:15px; border:1px solid #000;border-bottom:4px solid #000; background-color:#f3f4f6;">
            <h3 style="color:#161719; font-size:20px; margin:0; font-weight:bold;">
                INDEKS KEPUASAN MASYARAKAT (IKM)
                <br>
                KABUPATEN BENGKALIS
                <br>
                PERIODE {{ $periode }}
            </h3>
        </td>
    </tr>
   

    <tr>
        <!-- Kolom Kiri: Nilai IKM -->
        <td style="width:50%; vertical-align:top; border:1px solid #000; text-align:center; padding:20px;border-right:4px solid #000;vertical-align:middle;">
            <p style="color:#555; font-weight:600; margin:0;">Nilai IKM</p>
            <p style="font-size:100px; font-weight:bold; color:#000000; margin:10px 0;">
                {{ round($row->nilai_konversi, 2) }}
            </p>

            <p style="color:#333; font-weight:600; margin-top:10px;">Mutu Pelayanan</p>
            <p style="color:#000000; font-weight:bold; font-size:22px; margin:5px 0;">
                {{ $row->predikat_mutu_layanan }}
            </p>

            <p style="color:#666; font-size:14px; margin-top:5px;">
                ({{ prediket($row->nilai_konversi) }})
            </p>
        </td>

        <!-- Kolom Kanan: Statistik -->
        <td style="width:50%; vertical-align:top; border:1px solid #000; padding:15px;">
<center><span style="font-size: 24px">Responden</span></center>
<br>
            <table style="width:100%; font-size:14px; border-collapse:collapse;">

                <tbody>

                    <tr>
                        <td style="padding:4px; font-weight:600;">Jumlah </td>
                        <td style="padding:4px;">:
                            <strong>{{ $row->sample_diambil }} Orang</strong>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:4px; font-weight:600;">Jenis Kelamin</td>
                        <td style="padding:4px;">:
                            L = {{ $row->statistik_responden->jenis_kelamin->L->jumlah ?? 0 }}
                            /
                            P = {{ $row->statistik_responden->jenis_kelamin->P->jumlah ?? 0 }}
                        </td>
                    </tr>

                    <!-- Pendidikan -->
                    <tr>
                        <td colspan="2" style="padding-top:10px; font-weight:600;">Pendidikan</td>
                    </tr>

                    @foreach($row->statistik_responden->pendidikan as $key => $pd)
                        <tr>
                            <td style="padding:4px;">{{ $key }}</td>
                            <td style="padding:4px;">: {{ $pd->jumlah }} Orang</td>
                        </tr>
                    @endforeach

                    <!-- Non Disabilitas -->
                    <tr>
                        <td style="padding-top:10px; font-weight:600;">Non Disabilitas</td>
                        <td style="padding-top:10px;">:
                            <strong>{{ $row->statistik_responden->kategori_pengguna->non_disabilitas->jumlah ?? 0 }}
                                Orang</strong>
                        </td>
                    </tr>

                    <!-- Disabilitas -->
                    <tr>
                        <td colspan="2" style="padding-top:10px; font-weight:600;">Disabilitas</td>
                    </tr>

                    @foreach($row->statistik_responden->kategori_pengguna->disabilitas as $d)
                        <tr>
                            <td style="padding:4px;">{{ $d->label }}</td>
                            <td style="padding:4px;">: {{ $d->jumlah }} Orang</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

        </td>

    </tr>
<tr>
    <td colspan="2" align="center" style="border-top:4px solid #000;padding:20px;vertical-align:middle">
        <h3>TERIMA KASIH ATAS PENILAIAN YANG TELAH ANDA BERIKAN
        MASUKAN ANDA SANGAT BERMANFAAT UNTUK KEMAJUAN UNIT KAMI AGAR TERUS<br>
        MEMPERBAIKI DAN MENINGKATKAN KUALITAS PELAYANAN BAGI MASYARAKAT</h3>
    </td>
</tr>
</table>