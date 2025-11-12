<?php
namespace Sisukma\V2\Contracts\Rekap;
use Sisukma\V2\Models\Respon;
use Illuminate\Support\Facades\DB;

class Tahun 
{

    function getRekap9($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
    {
        $query = Respon::query()
            ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
            ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
            ->select(
                'respons.*',
                'layanans.nama_layanan as nama_layanan',
                'skpds.nama_skpd as nama_skpd',
                'skpds.id as skpd_id',
                'layanans.id as id_layanan'
            )
            ->whereHas('layanan.skpd.periode_aktif', function ($q) use ($tahun) {
                $q->where('tahun', $tahun ?? date('Y'));
            });


        if ($skpd)
            $query->where('skpds.id', $skpd);
        if ($id_layanan)
            $query->where('layanans.id', $id_layanan);

        // 🔹 Filter periode
        if ($periode === 'bulan' && $bulan && $tahun) {
            $query->whereYear('respons.tgl_survei', $tahun)->whereMonth('respons.tgl_survei', $bulan);
        } elseif ($periode === 'triwulan' && $tahun && $bulan) {
            $triwulan = ceil($bulan / 3);
            $start = ($triwulan - 1) * 3 + 1;
            $end = $start + 2;
            $query->whereYear('respons.tgl_survei', $tahun)
                ->whereBetween(DB::raw('MONTH(respons.tgl_survei)'), [$start, $end]);
        } elseif ($periode === 'semester' && $tahun && $bulan) {
            $semester = $bulan <= 6 ? 1 : 2;
            $start = $semester == 1 ? 1 : 7;
            $end = $semester == 1 ? 6 : 12;
            $query->whereYear('respons.tgl_survei', $tahun)
                ->whereBetween(DB::raw('MONTH(respons.tgl_survei)'), [$start, $end]);
        } elseif ($periode === 'tahun' && $tahun) {
            $query->whereYear('respons.tgl_survei', $tahun);
        }

        $data = $query->get();
        if ($data->isEmpty())
            return [];

        // 🔹 Kelompokkan per SKPD
        $groupedBySkpd = $data->groupBy('skpd_id');
        $hasil = [];

        foreach ($groupedBySkpd as $skpdId => $records) {
            $hasil[] = $this->hitungRekap9($records, $skpdId, $records->first()->nama_skpd);
        }

        // 🔹 Tambahkan total keseluruhan jika tidak difilter SKPD
        if (is_null($skpd)) {
            $totalAll = $this->hitungRekap9($data, null, 'Total Keseluruhan');
            $hasil[] = array_merge(['skpd_id' => null, 'nama_skpd' => 'Total Semua SKPD'], $totalAll);
        } else {
            return $hasil[0] ?? ['message' => 'Data SKPD tidak ditemukan.'];
        }

        return $hasil;
    }
    function hitungRekap9($records, $skpdId = null, $namaSkpd = null)
    {
        $groupedByLayanan = $records->groupBy('id_layanan');

        $totalResponden = 0;
        $totalSample = 0;
        $allSampled = collect();
        $hasilPerLayanan = [];

        foreach ($groupedByLayanan as $layananId => $recordsLayanan) {
            $totalResponden += $recordsLayanan->count();
            $sample = round(3.841 * $recordsLayanan->count() * 0.25);
            $totalSample += $sample;

            $sampleData = $recordsLayanan
                ->sortByDesc('u1')
                ->sortByDesc('u2')
                ->sortByDesc('u3')
                ->sortByDesc('u4')
                ->sortByDesc('u5')
                ->sortByDesc('u6')
                ->sortByDesc('u7')
                ->sortByDesc('u8')
                ->sortByDesc('u9')
                ->take($sample);

            $allSampled = $allSampled->merge($sampleData);

            $rataPerUnsur = [];
            $nilaiPerunsur = [];
            $totalPerUnsur = [];
            for ($i = 1; $i <= 9; $i++) {
                $totalPerUnsur["u$i"] = $sampleData->sum("u$i");
                $rataPerUnsur["u$i"] = $sampleData->avg("u$i");
                $nilaiPerunsur["u$i"] = $rataPerUnsur["u$i"] * 25;
            }

            $nilaiIKM = array_sum($rataPerUnsur) / count($rataPerUnsur);
            $konversi = $nilaiIKM * 25;
            $predikat = getPredikat($nilaiIKM);

            $hasilPerLayanan[] = [
                'id_layanan' => $layananId,
                'total_responden' => $recordsLayanan->count(),
                'nama_layanan' => $recordsLayanan->first()->nama_layanan,
                'sample_diambil' => $sampleData->count(),
                'rata_perunsur' => $rataPerUnsur,
                'total_perunsur' => $totalPerUnsur,
                'nilai_perunsur' => $nilaiPerunsur,
                'nilai_ikm' => $nilaiIKM,
                'nilai_konversi' => $konversi,
                'predikat_mutu' => $predikat,
            ];
        }

        if ($allSampled->isEmpty()) {
            return [
                'skpd_id' => $skpdId,
                'nama_skpd' => $namaSkpd,
                'message' => 'Tidak ada data sample untuk dihitung.'
            ];
        }

        // 🔹 Jumlah rata-rata, total, dan nilai per unsur dari seluruh layanan
        $JumlahRataPerunsur = [];
        $JumlahTotalPerunsur = [];
        $JumlahNilaiPerunsur = [];

        for ($i = 1; $i <= 9; $i++) {
            $JumlahNilaiPerunsur["u$i"] = collect($hasilPerLayanan)->avg("nilai_perunsur.u$i");
            $JumlahTotalPerunsur["u$i"] = collect($hasilPerLayanan)->sum("total_perunsur.u$i");
            $JumlahRataPerunsur["u$i"] = collect($hasilPerLayanan)->avg("rata_perunsur.u$i");
        }

        // 🔹 Nilai akhir setiap unsur (berdasarkan bobot jumlah sample per layanan)
        $NilaiAkhirPerUnsur = [];
        for ($i = 1; $i <= 9; $i++) {
            $totalTerbobot = 0;

            foreach ($hasilPerLayanan as $layanan) {
                $totalTerbobot += ($layanan['nilai_perunsur']["u$i"] * $layanan['sample_diambil']);
            }

            $NilaiAkhirPerUnsur["u$i"] = $totalSample > 0 ? $totalTerbobot / $totalSample : 0;
        }

        foreach ($hasilPerLayanan as $layanan) {
            $nilai_akhir_ikm[] = ($layanan['nilai_konversi'] * $layanan['sample_diambil']) / $totalSample;
        }
        // 🔹 Hitung IKM gabungan dari nilai akhir unsur
        $nilaiIkmGab = array_sum($JumlahRataPerunsur) / count($JumlahRataPerunsur);
        $nilaiKonversiGab = $nilaiIkmGab;
        $predikat = getPredikat($nilaiIkmGab / 25); // dikonversi balik ke skala 1–4 jika getPredikat pakai skala itu

        return [
            'skpd_id' => $skpdId,
            'nama_skpd' => $namaSkpd,
            'total_responden' => $totalResponden,
            'sample_diambil' => $totalSample,
            'nilai_ikm' => $nilaiIkmGab / 25,
            'nilai_konversi' => $nilaiKonversiGab,
            'predikat_mutu_layanan' => $predikat,
            'hasil_perlayanan' => $hasilPerLayanan,
            'nilai_perunsur' => $JumlahNilaiPerunsur,
            'rata_perunsur' => $JumlahRataPerunsur,
            'total_perunsur' => $JumlahTotalPerunsur,
            'nilai_akhir_perunsur' => $NilaiAkhirPerUnsur,
            'nilai_akhir_ikm' => array_sum($nilai_akhir_ikm),
        ];
    }
}