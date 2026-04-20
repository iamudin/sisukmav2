<?php
namespace Sisukma\V2\Contracts;

use Sisukma\V2\Models\Respon;
use Illuminate\Support\Facades\DB;

class IkmCounter
{



    function getDataIKM9($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null) {
        $query = Respon::query()
            ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
            ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
            ->select(
                'respons.*',
                'layanans.nama_layanan as nama_layanan',
                'skpds.nama_skpd as nama_skpd',
                'layanans.id as id_layanan'
            )
            ->whereHas('layanan.skpd.periode_aktif', function ($q) use ($tahun) {
                $q->where('tahun', $tahun ?? date('Y'));
            });


        if ($skpd) {
            $query->where('skpds.id', $skpd);
        }
        if ($id_layanan) {
            $query->where('layanans.id', $id_layanan);
        }

        // --- FILTER PERIODE ---
        if ($periode === 'bulan' && $bulan && $tahun) {
            $query->whereYear('respons.tgl_survei', $tahun)
                ->whereMonth('respons.tgl_survei', $bulan);
        } elseif ($periode === 'triwulan' && $tahun && $bulan) {
            $triwulan = ceil($bulan / 3);
            $startMonth = ($triwulan - 1) * 3 + 1;
            $endMonth = $startMonth + 2;
            $query->whereYear('respons.tgl_survei', $tahun)
                ->whereBetween(DB::raw('MONTH(respons.tgl_survei)'), [$startMonth, $endMonth]);
        } elseif ($periode === 'semester' && $tahun && $bulan) {
            $semester = $bulan <= 6 ? 1 : 2;
            $startMonth = $semester == 1 ? 1 : 7;
            $endMonth = $semester == 1 ? 6 : 12;
            $query->whereYear('respons.tgl_survei', $tahun)
                ->whereBetween(DB::raw('MONTH(respons.tgl_survei)'), [$startMonth, $endMonth]);
        } elseif ($periode === 'tahun' && $tahun) {
            $query->whereYear('respons.tgl_survei', $tahun);
        }

        $allData = $query->get();
        if ($allData->isEmpty()) {
            return ['message' => 'Tidak ada data responden untuk periode ini.'];
        }

        // --- KELOMPOK PERLAYANAN ---
        $grouped = $allData->groupBy('id_layanan');

        $hasilPerLayanan = [];
        $sampleGabungan = collect(); // kumpulan semua sample dari semua layanan

        foreach ($grouped as $layananId => $dataLayanan) {
            $totalResponden = $dataLayanan->count();
            $sample = round(3.841 * $totalResponden * 0.25);
            // ambil sample berdasarkan urutan nilai tertinggi
            $sampleData = $dataLayanan
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

            // simpan sample ke gabungan global
            $sampleGabungan = $sampleGabungan->merge($sampleData);

            // hitung IKM per layanan (informasi tambahan)
            $totalPerUnsur = [];
            $rataPerUnsur = [];
            $nilaiPerunsur = [];
            for ($i = 1; $i <= 9; $i++) {
                $totalPerUnsur["u$i"] = $sampleData->sum("u$i");
                $rataPerUnsur["u$i"] = $sampleData->avg("u$i");
                $nilaiPerunsur["u$i"] = $sampleData->avg("u$i") * 25;
            }

            $nilaiIKM = array_sum($rataPerUnsur) / count($rataPerUnsur);
            $konversi = $nilaiIKM * 25;
            $predikat = getPredikat($nilaiIKM);

            $hasilPerLayanan[] = [
                'id_layanan' => $layananId,
                'nama_skpd' => $dataLayanan->first()->nama_skpd,
                'nama_layanan' => $dataLayanan->first()->nama_layanan,
                'total_responden' => $totalResponden,
                'sample_diambil' => $sampleData->count(),
                'rata_perunsur' => $rataPerUnsur,
                'total_perunsur' => $totalPerUnsur,
                'nilai_perunsur' => $nilaiPerunsur,
                'nilai_ikm' => $nilaiIKM,
                'nilai_konversi' => $konversi,
                'predikat_mutu' => $predikat,
                'saran' => $dataLayanan->map(function ($item) {
                    return $item->saran;
                }),
            ];
        }

        // --- HITUNG IKM GABUNGAN DARI SEMUA SAMPLE LAYANAN ---
        $NilaiPerUnsurGab = [];
        $RataPerUnsurGab = [];
        $TotalPerUnsurGab = [];

        for ($i = 1; $i <= 9; $i++) {
            $NilaiPerUnsurGab["u$i"] = collect($hasilPerLayanan)->avg("nilai_perunsur.u$i");
            $RataPerUnsurGab["u$i"] = collect($hasilPerLayanan)->avg("rata_perunsur.u$i");
            $TotalPerUnsurGab["u$i"] = collect($hasilPerLayanan)->sum("total_perunsur.u$i");
        }

        $nilaiIKMGabungan = array_sum($RataPerUnsurGab) / count($RataPerUnsurGab);
        $konversiGab = $nilaiIKMGabungan * 25;
        $predikatGab = getPredikat($nilaiIKMGabungan);

        // --- TAMPILKAN DATA SEMUA RESPONDEN (U1–U9) ---
        $dataRespondenGabungan = $sampleGabungan->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_skpd' => $item->nama_skpd,
                'nama_layanan' => $item->nama_layanan,
                'jenis_kelamin' => $item->jenis_kelamin,
                'pendidikan' => $item->pendidikan,
                'pekerjaan' => $item->pekerjaan,
                'disabilitas' => $item->disabilitas,
                'jenis_disabilitas' => $item->jenis_disabilitas,
                'u1' => $item->u1,
                'u2' => $item->u2,
                'u3' => $item->u3,
                'u4' => $item->u4,
                'u5' => $item->u5,
                'u6' => $item->u6,
                'u7' => $item->u7,
                'u8' => $item->u8,
                'u9' => $item->u9,
                'saran' => $item->saran,
            ];
        });

        return [
            'jumlah_total_responden' => $allData->count(),
            'jumlah_total_sample_diambil' => $sampleGabungan->count(),
            'hasil_perlayanan' => $hasilPerLayanan,
            'jumlah_nilai_perunsur' => $NilaiPerUnsurGab,
            'jumlah_rata_perunsur' => $RataPerUnsurGab,
            'jumlah_total_perunsur' => $TotalPerUnsurGab,
            'nilai_ikm' => $nilaiIKMGabungan,
            'nilai_konversi' => $konversiGab,
            'predikat_mutu' => $predikatGab,
            'data_responden' => $dataRespondenGabungan,
        ];
    }

    function getDataIKM16($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null) {
        $query = Respon::query()
            ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
            ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
            ->select(
                'respons.*',
                'layanans.nama_layanan as nama_layanan',
                'skpds.nama_skpd as nama_skpd',
                'layanans.id as id_layanan'
            )
            ->whereHas('layanan.skpd.periode_aktif', function ($q) use ($tahun) {
                $q->where('tahun', $tahun ?? date('Y'));
            });


        if ($skpd) {
            $query->where('skpds.id', $skpd);
        }
        if ($id_layanan) {
            $query->where('layanans.id', $id_layanan);
        }

        // --- FILTER PERIODE ---
        if ($periode === 'bulan' && $bulan && $tahun) {
            $query->whereYear('respons.tgl_survei', $tahun)
                ->whereMonth('respons.tgl_survei', $bulan);
        } elseif ($periode === 'triwulan' && $tahun && $bulan) {
            $triwulan = ceil($bulan / 3);
            $startMonth = ($triwulan - 1) * 3 + 1;
            $endMonth = $startMonth + 2;
            $query->whereYear('respons.tgl_survei', $tahun)
                ->whereBetween(DB::raw('MONTH(respons.tgl_survei)'), [$startMonth, $endMonth]);
        } elseif ($periode === 'semester' && $tahun && $bulan) {
            $semester = $bulan <= 6 ? 1 : 2;
            $startMonth = $semester == 1 ? 1 : 7;
            $endMonth = $semester == 1 ? 6 : 12;
            $query->whereYear('respons.tgl_survei', $tahun)
                ->whereBetween(DB::raw('MONTH(respons.tgl_survei)'), [$startMonth, $endMonth]);
        } elseif ($periode === 'tahun' && $tahun) {
            $query->whereYear('respons.tgl_survei', $tahun);
        }

        $allData = $query->get();
        if ($allData->isEmpty()) {
            return ['message' => 'Tidak ada data responden untuk periode ini.'];
        }

        // --- KELOMPOK PERLAYANAN ---
        $grouped = $allData->groupBy('id_layanan');

        $hasilPerLayanan = [];
        $sampleGabungan = collect(); // kumpulan semua sample dari semua layanan

        foreach ($grouped as $layananId => $dataLayanan) {
            $totalResponden = $dataLayanan->count();
            $sample = round(3.841 * $totalResponden * 0.25);
            // ambil sample berdasarkan urutan nilai tertinggi
            $sampleData = $dataLayanan
                ->sortByDesc('u1')
                ->sortByDesc('u2')
                ->sortByDesc('u3')
                ->sortByDesc('u4')
                ->sortByDesc('u5')
                ->sortByDesc('u6')
                ->sortByDesc('u7')
                ->sortByDesc('u8')
                ->sortByDesc('u9')
                ->sortByDesc('u10')
                ->sortByDesc('u11')
                ->sortByDesc('u12')
                ->sortByDesc('u13')
                ->sortByDesc('u14')
                ->sortByDesc('u15')
                ->sortByDesc('u16')
                ->take($sample);

            // simpan sample ke gabungan global
            $sampleGabungan = $sampleGabungan->merge($sampleData);

            // hitung IKM per layanan (informasi tambahan)
            $totalPerUnsur = [];
            $rataPerUnsur = [];
            $p = [];
            for ($i = 1; $i <= 16; $i++) {
                $totalPerUnsur["u$i"] = $sampleData->sum("u$i");
                $rataPerUnsur["u$i"] = $sampleData->avg("u$i");
            }
            $p['p1'] = ($rataPerUnsur['u1'] + $rataPerUnsur['u2'] )/ 2;
            $p['p2'] = ($rataPerUnsur['u3'] + $rataPerUnsur['u4'] + $rataPerUnsur['u5'])/ 3;
            $p['p3'] = $rataPerUnsur['u6'];
            $p['p4'] = ($rataPerUnsur['u7'] + $rataPerUnsur['u8'] + $rataPerUnsur['u9']) / 3;
            $p['p5'] = $rataPerUnsur['u10'];
            $p['p6'] = $rataPerUnsur['u11'];
            $p['p7'] = ($rataPerUnsur['u12'] + $rataPerUnsur['u13'] + $rataPerUnsur['u14']) / 3;
            $p['p8'] = $rataPerUnsur['u15'];
            $p['p9'] = $rataPerUnsur['u16'];

            $nilaiIKM = array_sum($p) / count($p);
            $konversi = $nilaiIKM * 25;
            $predikat = getPredikat($nilaiIKM);


            $hasilPerLayanan[] = [
                'id_layanan' => $layananId,
                'nama_skpd' => $dataLayanan->first()->nama_skpd,
                'nama_layanan' => $dataLayanan->first()->nama_layanan,
                'total_responden' => $totalResponden,
                'sample_diambil' => $sampleData->count(),
                'rata_perunsur' => $rataPerUnsur,
                'total_perunsur' => $totalPerUnsur,
                'nilai_perunsur' => $p,
                'nilai_ikm' => $nilaiIKM,
                'nilai_konversi' => $konversi,
                'predikat_mutu' => $predikat,
                'saran' => $dataLayanan->map(function ($item) {
                    return $item->saran;
                }),
            ];
        }

        // --- HITUNG IKM GABUNGAN DARI SEMUA SAMPLE LAYANAN ---
        $RataPerUnsurGab = [];
        $TotalPerUnsurGab = [];

        for ($i = 1; $i <= 16; $i++) {
            $TotalPerUnsurGab["u$i"] = collect($hasilPerLayanan)->sum("total_perunsur.u$i");
            $RataPerUnsurGab["u$i"] = collect($hasilPerLayanan)->sum("total_perunsur.u$i") / collect($hasilPerLayanan)->sum("sample_diambil");
        }
           $p = [];
            $p['p1'] = ($RataPerUnsurGab['u1'] + $RataPerUnsurGab['u2']) / 2;
            $p['p2'] = ($RataPerUnsurGab['u3'] + $RataPerUnsurGab['u4'] + $RataPerUnsurGab['u5']) / 3;
            $p['p3'] = $RataPerUnsurGab['u6'];
            $p['p4'] = ($RataPerUnsurGab['u7'] + $RataPerUnsurGab['u8'] + $RataPerUnsurGab['u9']) / 3;
            $p['p5'] = $RataPerUnsurGab['u10'];
            $p['p6'] = $RataPerUnsurGab['u11'];
            $p['p7'] = ($RataPerUnsurGab['u12'] + $RataPerUnsurGab['u13'] + $RataPerUnsurGab['u14']) / 3;
            $p['p8'] = $RataPerUnsurGab['u15'];
            $p['p9'] = $RataPerUnsurGab['u16'];
           

        $nilaiIKMGabungan = array_sum($p) / count($p);
        $konversiGab = $nilaiIKMGabungan * 25;
        $predikatGab = getPredikat($nilaiIKMGabungan);

        // --- TAMPILKAN DATA SEMUA RESPONDEN (U1–U9) ---
        $dataRespondenGabungan = $sampleGabungan->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_skpd' => $item->nama_skpd,
                'nama_layanan' => $item->nama_layanan,
                'jenis_kelamin' => $item->jenis_kelamin,
                'pendidikan' => $item->pendidikan,
                'pekerjaan' => $item->pekerjaan,
                'disabilitas' => $item->disabilitas,
                'jenis_disabilitas' => $item->jenis_disabilitas,
                'u1' => $item->u1,
                'u2' => $item->u2,
                'u3' => $item->u3,
                'u4' => $item->u4,
                'u5' => $item->u5,
                'u6' => $item->u6,
                'u7' => $item->u7,
                'u8' => $item->u8,
                'u9' => $item->u9,
                'u10' => $item->u10,
                'u11' => $item->u11,
                'u12' => $item->u12,
                'u13' => $item->u13,
                'u14' => $item->u14,
                'u15' => $item->u15,
                'u16' => $item->u16,
                'saran' => $item->saran,
            ];
        });

        return [
            'jumlah_total_responden' => $allData->count(),
            'jumlah_total_sample_diambil' => $sampleGabungan->count(),
            'hasil_perlayanan' => $hasilPerLayanan,
            'jumlah_nilai_perunsur' => $p,
            'jumlah_rata_perunsur' => $RataPerUnsurGab,
            'jumlah_total_perunsur' => $TotalPerUnsurGab,
            'nilai_ikm' => $nilaiIKMGabungan,
            'nilai_konversi' => $konversiGab,
            'predikat_mutu' => $predikatGab,
            'data_responden' => $dataRespondenGabungan,
        ];
    }

    function getStatistik9($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null) {
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
            $hasil[] = $this->hitungStatistikPerKumpulanGabungan9($records, $skpdId, $records->first()->nama_skpd);
        }

        // 🔹 Tambahkan total keseluruhan jika tidak difilter SKPD
        if (is_null($skpd)) {
            $totalAll = $this->hitungStatistikPerKumpulanGabungan9($data, null, 'Total Keseluruhan');
            $hasil[] = array_merge(['skpd_id' => null, 'nama_skpd' => 'Total Semua SKPD'], $totalAll);
        } else {
            return $hasil[0] ?? ['message' => 'Data SKPD tidak ditemukan.'];
        }

        return $hasil;
    }
    function getStatistik16($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null) {
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
            $hasil[] = $this->hitungStatistikPerKumpulanGabungan16($records, $skpdId, $records->first()->nama_skpd);
        }

        // 🔹 Tambahkan total keseluruhan jika tidak difilter SKPD
        if (is_null($skpd)) {
            $totalAll = $this->hitungStatistikPerKumpulanGabungan16($data, null, 'Total Keseluruhan');
            $hasil[] = array_merge(['skpd_id' => null, 'nama_skpd' => 'Total Semua SKPD'], $totalAll);
        } else {
            return $hasil[0] ?? ['message' => 'Data SKPD tidak ditemukan.'];
        }

        return $hasil;
    }


    function hitungStatistikPerKumpulanGabungan16($records, $skpdId = null, $namaSkpd = null) {
        $groupedByLayanan = $records->groupBy('id_layanan');

        $totalResponden = 0;
        $totalSample = 0;
        $allSampled = collect();

        // 🔹 Sample per layanan, lalu gabungkan
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
                ->sortByDesc('u10')
                ->sortByDesc('u11')
                ->sortByDesc('u12')
                ->sortByDesc('u13')
                ->sortByDesc('u14')
                ->sortByDesc('u15')
                ->sortByDesc('u16')
                ->take($sample);

            $allSampled = $allSampled->merge($sampleData);

            $totalPerUnsur = [];
            $rataPerUnsur = [];
            $nilaiPerunsur = [];
            for ($i = 1; $i <= 16; $i++) {
                $totalPerUnsur["u$i"] = $sampleData->sum("u$i");
                $rataPerUnsur["u$i"] = $sampleData->avg("u$i");
            }
            $p = [];
            $p['p1'] = ($rataPerUnsur['u1'] + $rataPerUnsur['u2']) / 2;
            $p['p2'] = ($rataPerUnsur['u3'] + $rataPerUnsur['u4'] + $rataPerUnsur['u5']) / 3;
            $p['p3'] = $rataPerUnsur['u6'];
            $p['p4'] = ($rataPerUnsur['u7'] + $rataPerUnsur['u8'] + $rataPerUnsur['u9']) / 3;
            $p['p5'] = $rataPerUnsur['u10'];
            $p['p6'] = $rataPerUnsur['u11'];
            $p['p7'] = ($rataPerUnsur['u12'] + $rataPerUnsur['u13'] + $rataPerUnsur['u14']) / 3;
            $p['p8'] = $rataPerUnsur['u15'];
            $p['p9'] = $rataPerUnsur['u16'];
            $p;

            $nilaiIKM = array_sum($p) / count($p);
            $konversi = $nilaiIKM * 25;
            $predikat = getPredikat($nilaiIKM);
            $statistik = [];

            // Jenis Kelamin
            $jenisKelaminOptions = ['L', 'P'];
            $groupJenisKelamin = $sampleData->groupBy('jenis_kelamin')->map->count();
            $jumlahJenisKelamin = collect($jenisKelaminOptions)->mapWithKeys(fn($opt) => [$opt => $groupJenisKelamin[$opt] ?? 0]);
            $totalJenisKelamin = $jumlahJenisKelamin->sum();
            $statistik['jenis_kelamin'] = $jumlahJenisKelamin->mapWithKeys(function ($jumlah, $jk) use ($totalJenisKelamin) {
                $persentase = $totalJenisKelamin > 0 ? round(($jumlah / $totalJenisKelamin) * 100, 2) : 0;
                return [$jk => ['jumlah' => $jumlah, 'persentase' => $persentase]];
            })->toArray();

            // Pendidikan
            $pendidikanOptions = ['Non Pendidikan', 'SD', 'SMP', 'SMA', 'DIII', 'S1', 'S2', 'S3'];
            $groupPendidikan = $sampleData->groupBy('pendidikan')->map->count();
            $jumlahPendidikan = collect($pendidikanOptions)->mapWithKeys(fn($opt) => [$opt => $groupPendidikan[$opt] ?? 0]);
            $totalPendidikan = $jumlahPendidikan->sum();
            $statistik['pendidikan'] = $jumlahPendidikan->mapWithKeys(function ($jumlah, $pendidikan) use ($totalPendidikan) {
                $persentase = $totalPendidikan > 0 ? round(($jumlah / $totalPendidikan) * 100, 2) : 0;
                return [$pendidikan => ['jumlah' => $jumlah, 'persentase' => $persentase]];
            })->toArray();

            // Pekerjaan
            $pekerjaanOptions = ['TNI', 'POLRI', 'ASN', 'SWASTA', 'WIRAUSAHA', 'LAINNYA'];
            $groupPekerjaan = $sampleData->groupBy('pekerjaan')->map->count();
            $jumlahPekerjaan = collect($pekerjaanOptions)->mapWithKeys(fn($opt) => [$opt => $groupPekerjaan[$opt] ?? 0]);
            $totalPekerjaan = $jumlahPekerjaan->sum();
            $statistik['pekerjaan'] = $jumlahPekerjaan->mapWithKeys(function ($jumlah, $pekerjaan) use ($totalPekerjaan) {
                $persentase = $totalPekerjaan > 0 ? round(($jumlah / $totalPekerjaan) * 100, 2) : 0;
                return [$pekerjaan => ['jumlah' => $jumlah, 'persentase' => $persentase]];
            })->toArray();

            // Kategori Pengguna (Disabilitas / Non Disabilitas)
            $nonDisabilitasCount = $sampleData->where('disabilitas', 'Non Disabilitas')->count();
            $disabilitasJenis = [
                'Fisik' => $sampleData->where('jenis_disabilitas', 'Disabilitas Fisik')->count(),
                'Mental' => $sampleData->where('jenis_disabilitas', 'Disabilitas Mental')->count(),
                'Intelektual' => $sampleData->where('jenis_disabilitas', 'Disabilitas Intelektual')->count(),
                'Sensorik' => $sampleData->where('jenis_disabilitas', 'Disabilitas Sensorik')->count(),
            ];
            $totalKategori = $nonDisabilitasCount + array_sum($disabilitasJenis);
            $statistik['kategori_pengguna'] = [
                'non_disabilitas' => [
                    'label' => 'Non Disabilitas',
                    'jumlah' => $nonDisabilitasCount,
                    'persentase' => $totalKategori > 0 ? round(($nonDisabilitasCount / $totalKategori) * 100, 2) : 0,
                ],
                'disabilitas' => collect($disabilitasJenis)->map(function ($jumlah, $label) use ($totalKategori) {
                    $persentase = $totalKategori > 0 ? round(($jumlah / $totalKategori) * 100, 2) : 0;
                    return [
                        'label' => $label,
                        'jumlah' => $jumlah,
                        'persentase' => $persentase,
                    ];
                })->values()->toArray(),
            ];

            // Usia
            $usiaKategori = [
                '17–23' => $sampleData->whereBetween('usia', [17, 23])->count(),
                '24–29' => $sampleData->whereBetween('usia', [24, 29])->count(),
                '30–40' => $sampleData->whereBetween('usia', [30, 40])->count(),
                'Diatas 40' => $sampleData->where('usia', '>', 40)->count(),
            ];
            $totalUsia = array_sum($usiaKategori);
            $statistik['usia'] = collect($usiaKategori)->map(function ($jumlah, $label) use ($totalUsia) {
                $persentase = $totalUsia > 0 ? round(($jumlah / $totalUsia) * 100, 2) : 0;
                return [
                    'label' => $label,
                    'jumlah' => $jumlah,
                    'persentase' => $persentase,
                ];
            })->values()->toArray();

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
                'saran' => collect($recordsLayanan)->where('saran', '!=', '')->map(function ($item) {
                    return [
                        'nama' => $item->nik ?? '-',
                        'tgl_survei' => $item->tgl_survei ?? '-',
                        'pendidikan' => $item->pendidikan ?? '-',
                        'jenis_kelamin' => $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                        'pekerjaan' => $item->pekerjaan ?? '-',
                        'usia' => $item->usia ?? '-',
                        'disabilitas' => $item->disabilitas == 'Non Disabilitas'
                            ? 'Non Disabilitas'
                            : ($item->jenis_disabilitas ?? '-'),
                        'saran' => $item->saran ?? '-',
                    ];

                }),
                'statistik_responden' => $statistik
            ];
        }

        if ($allSampled->isEmpty()) {
            return [
                'skpd_id' => $skpdId,
                'nama_skpd' => $namaSkpd,
                'message' => 'Tidak ada data sample untuk dihitung.'
            ];
        }

        // 🔹 Hitung rata-rata unsur (U1–U16) dari seluruh sample gabungan
        $JumlahRataPerunsur = [];
        $JumlahTotalPerunsur = [];
        for ($i = 1; $i <= 16; $i++) {
            $JumlahTotalPerunsur["u$i"] = collect($hasilPerLayanan)->sum("total_perunsur.u$i");
            $JumlahRataPerunsur["u$i"] = collect($hasilPerLayanan)->sum("total_perunsur.u$i") / collect($hasilPerLayanan)->sum("sample_diambil");
        }
            $p = [];
            $p['p1'] = ($JumlahRataPerunsur['u1'] + $JumlahRataPerunsur['u2']) / 2;
            $p['p2'] = ($JumlahRataPerunsur['u3'] + $JumlahRataPerunsur['u4'] + $JumlahRataPerunsur['u5']) / 3;
            $p['p3'] = $JumlahRataPerunsur['u6'];
            $p['p4'] = ($JumlahRataPerunsur['u7'] + $JumlahRataPerunsur['u8'] + $JumlahRataPerunsur['u9']) / 3;
            $p['p5'] = $JumlahRataPerunsur['u10'];
            $p['p6'] = $JumlahRataPerunsur['u11'];
            $p['p7'] = ($JumlahRataPerunsur['u12'] + $JumlahRataPerunsur['u13'] + $JumlahRataPerunsur['u14']) / 3;
            $p['p8'] = $JumlahRataPerunsur['u15'];
            $p['p9'] = $JumlahRataPerunsur['u16'];

        // 🔹 Nilai konversi & IKM total gabungan
        $nilaiIkmGab = array_sum($p) / count($p);
        $nilaiKonversiGab = $nilaiIkmGab * 25;
        $predikat = getPredikat($nilaiIkmGab);

        // 🔹 Statistik tambahan responden
        $statistik = [];

        // Daftar opsi jenis kelamin
        $jenisKelaminOptions = ['L', 'P'];

        // Hitung jumlah per jenis kelamin
        $groupJenisKelamin = $allSampled->groupBy('jenis_kelamin')->map->count();

        // Buat array jumlah per kategori
        $jumlahJenisKelamin = collect($jenisKelaminOptions)->mapWithKeys(function ($opt) use ($groupJenisKelamin) {
            return [$opt => $groupJenisKelamin[$opt] ?? 0];
        });

        // Hitung total
        $totalJenisKelamin = $jumlahJenisKelamin->sum();

        // Gabungkan jumlah + persentase
        $statistik['jenis_kelamin'] = $jumlahJenisKelamin->mapWithKeys(function ($jumlah, $jenisKelamin) use ($totalJenisKelamin) {
            $persentase = $totalJenisKelamin > 0 ? round(($jumlah / $totalJenisKelamin) * 100, 2) : 0;
            return [
                $jenisKelamin => [
                    'jumlah' => $jumlah,
                    'persentase' => $persentase
                ]
            ];
        })->toArray();


        $pendidikanOptions = ['Non Pendidikan', 'SD', 'SMP', 'SMA', 'DIII', 'S1', 'S2', 'S3'];

        // Hitung jumlah per pendidikan
        $groupPendidikan = $allSampled->groupBy('pendidikan')->map->count();

        // Buat array jumlah per kategori
        $jumlahPendidikan = collect($pendidikanOptions)->mapWithKeys(function ($opt) use ($groupPendidikan) {
            return [$opt => $groupPendidikan[$opt] ?? 0];
        });

        // Hitung total
        $totalPendidikan = $jumlahPendidikan->sum();

        // Gabungkan jumlah + persentase
        $statistik['pendidikan'] = $jumlahPendidikan->mapWithKeys(function ($jumlah, $pendidikan) use ($totalPendidikan) {
            $persentase = $totalPendidikan > 0 ? round(($jumlah / $totalPendidikan) * 100, 2) : 0;
            return [
                $pendidikan => [
                    'jumlah' => $jumlah,
                    'persentase' => $persentase
                ]
            ];
        })->toArray();



        $pekerjaanOptions = ['TNI', 'POLRI', 'ASN', 'SWASTA', 'WIRAUSAHA', 'Lainnya'];

        // Hitung jumlah per pekerjaan
        $groupPekerjaan = $allSampled->groupBy('pekerjaan')->map->count();

        // Buat array jumlah per kategori
        $jumlahPekerjaan = collect($pekerjaanOptions)->mapWithKeys(function ($opt) use ($groupPekerjaan) {
            return [$opt => $groupPekerjaan[$opt] ?? 0];
        });

        // Hitung total
        $totalPekerjaan = $jumlahPekerjaan->sum();

        // Gabungkan jumlah + persentase
        $statistik['pekerjaan'] = $jumlahPekerjaan->mapWithKeys(function ($jumlah, $pekerjaan) use ($totalPekerjaan) {
            $persentase = $totalPekerjaan > 0 ? round(($jumlah / $totalPekerjaan) * 100, 2) : 0;
            return [
                $pekerjaan => [
                    'jumlah' => $jumlah,
                    'persentase' => $persentase
                ]
            ];
        })->toArray();

        // Hitung jumlah Non Disabilitas
        $nonDisabilitasCount = $allSampled->where('disabilitas', 'Non Disabilitas')->count();

        // Hitung per jenis disabilitas
        $disabilitasJenis = [
            'Fisik' => $allSampled->where('jenis_disabilitas', 'Disabilitas Fisik')->count(),
            'Mental' => $allSampled->where('jenis_disabilitas', 'Disabilitas Mental')->count(),
            'Intelektual' => $allSampled->where('jenis_disabilitas', 'Disabilitas Intelektual')->count(),
            'Sensorik' => $allSampled->where('jenis_disabilitas', 'Disabilitas Sensorik')->count(),
        ];

        // Total semua responden
        $totalKategori = $nonDisabilitasCount + array_sum($disabilitasJenis);

        // Susun data
        $statistik['kategori_pengguna'] = [
            'non_disabilitas' => [
                'label' => 'Non Disabilitas',
                'jumlah' => $nonDisabilitasCount,
                'persentase' => $totalKategori > 0 ? round(($nonDisabilitasCount / $totalKategori) * 100, 2) : 0,
            ],
            'disabilitas' => collect($disabilitasJenis)->map(function ($jumlah, $label) use ($totalKategori) {
                $persentase = $totalKategori > 0 ? round(($jumlah / $totalKategori) * 100, 2) : 0;
                return [
                    'label' => $label,
                    'jumlah' => $jumlah,
                    'persentase' => $persentase,
                ];
            })->values()->toArray(),
        ];


        // Hitung jumlah per kategori usia
        $usiaKategori = [
            '17–23' => $allSampled->whereBetween('usia', [17, 23])->count(),
            '24–29' => $allSampled->whereBetween('usia', [24, 29])->count(),
            '30–40' => $allSampled->whereBetween('usia', [30, 40])->count(),
            'Diatas 40' => $allSampled->where('usia', '>', 40)->count(),
        ];

        // Hitung total responden
        $totalUsia = array_sum($usiaKategori);

        // Susun data dengan label, jumlah, satuan, dan persentase
        $statistik['usia'] = collect($usiaKategori)->map(function ($jumlah, $label) use ($totalUsia) {
            $persentase = $totalUsia > 0 ? round(($jumlah / $totalUsia) * 100, 2) : 0;
            return [
                'label' => $label,
                'jumlah' => $jumlah,
                'persentase' => $persentase,
            ];
        })->values()->toArray();


        return [
            'skpd_id' => $skpdId,
            'nama_skpd' => $namaSkpd,
            'total_responden' => $totalResponden,
            'sample_diambil' => $totalSample,
            'nilai_ikm' => $nilaiIkmGab,
            'total_perunsur' => $JumlahTotalPerunsur,
            'nilai_konversi' => $nilaiKonversiGab,
            'predikat_mutu_layanan' => $predikat,
            'hasil_perlayanan' => $hasilPerLayanan,
            'nilai_perunsur' => $p,
            'statistik_responden' => $statistik,
        ];
    }
    function hitungStatistikPerKumpulanGabungan9($records, $skpdId = null, $namaSkpd = null) {
        $groupedByLayanan = $records->groupBy('id_layanan');

        $totalResponden = 0;
        $totalSample = 0;
        $allSampled = collect();

        // 🔹 Sample per layanan, lalu gabungkan
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

            $totalPerUnsur = [];
            $rataPerUnsur = [];
            $nilaiPerunsur = [];
            for ($i = 1; $i <= 9; $i++) {
                $totalPerUnsur["u$i"] = $sampleData->sum("u$i");
                $rataPerUnsur["u$i"] = $sampleData->avg("u$i");
                $nilaiPerunsur["u$i"] = $sampleData->avg("u$i") * 25;
            }

            $nilaiIKM = array_sum($rataPerUnsur) / count($rataPerUnsur);
            $konversi = $nilaiIKM * 25;
            $predikat = getPredikat($nilaiIKM);
            $statistik = [];

            // Jenis Kelamin
            $jenisKelaminOptions = ['L', 'P'];
            $groupJenisKelamin = $sampleData->groupBy('jenis_kelamin')->map->count();
            $jumlahJenisKelamin = collect($jenisKelaminOptions)->mapWithKeys(fn($opt) => [$opt => $groupJenisKelamin[$opt] ?? 0]);
            $totalJenisKelamin = $jumlahJenisKelamin->sum();
            $statistik['jenis_kelamin'] = $jumlahJenisKelamin->mapWithKeys(function ($jumlah, $jk) use ($totalJenisKelamin) {
                $persentase = $totalJenisKelamin > 0 ? round(($jumlah / $totalJenisKelamin) * 100, 2) : 0;
                return [$jk => ['jumlah' => $jumlah, 'persentase' => $persentase]];
            })->toArray();

            // Pendidikan
            $pendidikanOptions = ['Non Pendidikan', 'SD', 'SMP', 'SMA', 'DIII', 'S1', 'S2', 'S3'];
            $groupPendidikan = $sampleData->groupBy('pendidikan')->map->count();
            $jumlahPendidikan = collect($pendidikanOptions)->mapWithKeys(fn($opt) => [$opt => $groupPendidikan[$opt] ?? 0]);
            $totalPendidikan = $jumlahPendidikan->sum();
            $statistik['pendidikan'] = $jumlahPendidikan->mapWithKeys(function ($jumlah, $pendidikan) use ($totalPendidikan) {
                $persentase = $totalPendidikan > 0 ? round(($jumlah / $totalPendidikan) * 100, 2) : 0;
                return [$pendidikan => ['jumlah' => $jumlah, 'persentase' => $persentase]];
            })->toArray();

            // Pekerjaan
            $pekerjaanOptions = ['TNI', 'POLRI', 'ASN', 'SWASTA', 'WIRAUSAHA', 'LAINNYA'];
            $groupPekerjaan = $sampleData->groupBy('pekerjaan')->map->count();
            $jumlahPekerjaan = collect($pekerjaanOptions)->mapWithKeys(fn($opt) => [$opt => $groupPekerjaan[$opt] ?? 0]);
            $totalPekerjaan = $jumlahPekerjaan->sum();
            $statistik['pekerjaan'] = $jumlahPekerjaan->mapWithKeys(function ($jumlah, $pekerjaan) use ($totalPekerjaan) {
                $persentase = $totalPekerjaan > 0 ? round(($jumlah / $totalPekerjaan) * 100, 2) : 0;
                return [$pekerjaan => ['jumlah' => $jumlah, 'persentase' => $persentase]];
            })->toArray();

            // Kategori Pengguna (Disabilitas / Non Disabilitas)
            $nonDisabilitasCount = $sampleData->where('disabilitas', 'Non Disabilitas')->count();
            $disabilitasJenis = [
                'Fisik' => $sampleData->where('jenis_disabilitas', 'Disabilitas Fisik')->count(),
                'Mental' => $sampleData->where('jenis_disabilitas', 'Disabilitas Mental')->count(),
                'Intelektual' => $sampleData->where('jenis_disabilitas', 'Disabilitas Intelektual')->count(),
                'Sensorik' => $sampleData->where('jenis_disabilitas', 'Disabilitas Sensorik')->count(),
            ];
            $totalKategori = $nonDisabilitasCount + array_sum($disabilitasJenis);
            $statistik['kategori_pengguna'] = [
                'non_disabilitas' => [
                    'label' => 'Non Disabilitas',
                    'jumlah' => $nonDisabilitasCount,
                    'persentase' => $totalKategori > 0 ? round(($nonDisabilitasCount / $totalKategori) * 100, 2) : 0,
                ],
                'disabilitas' => collect($disabilitasJenis)->map(function ($jumlah, $label) use ($totalKategori) {
                    $persentase = $totalKategori > 0 ? round(($jumlah / $totalKategori) * 100, 2) : 0;
                    return [
                        'label' => $label,
                        'jumlah' => $jumlah,
                        'persentase' => $persentase,
                    ];
                })->values()->toArray(),
            ];

            // Usia
            $usiaKategori = [
                '17–23' => $sampleData->whereBetween('usia', [17, 23])->count(),
                '24–29' => $sampleData->whereBetween('usia', [24, 29])->count(),
                '30–40' => $sampleData->whereBetween('usia', [30, 40])->count(),
                'Diatas 40' => $sampleData->where('usia', '>', 40)->count(),
            ];
            $totalUsia = array_sum($usiaKategori);
            $statistik['usia'] = collect($usiaKategori)->map(function ($jumlah, $label) use ($totalUsia) {
                $persentase = $totalUsia > 0 ? round(($jumlah / $totalUsia) * 100, 2) : 0;
                return [
                    'label' => $label,
                    'jumlah' => $jumlah,
                    'persentase' => $persentase,
                ];
            })->values()->toArray();

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
                'saran' => collect($recordsLayanan)->where('saran', '!=', '')->map(function ($item) {
                    return [
                        'nama' => $item->nik ?? '-',
                        'tgl_survei' => $item->tgl_survei ?? '-',
                        'pendidikan' => $item->pendidikan ?? '-',
                        'jenis_kelamin' => $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                        'pekerjaan' => $item->pekerjaan ?? '-',
                        'usia' => $item->usia ?? '-',
                        'disabilitas' => $item->disabilitas == 'Non Disabilitas'
                            ? 'Non Disabilitas'
                            : ($item->jenis_disabilitas ?? '-'),
                        'saran' => $item->saran ?? '-',
                    ];

                }),
                'statistik_responden' => $statistik
            ];
        }

        if ($allSampled->isEmpty()) {
            return [
                'skpd_id' => $skpdId,
                'nama_skpd' => $namaSkpd,
                'message' => 'Tidak ada data sample untuk dihitung.'
            ];
        }

        // 🔹 Hitung rata-rata unsur (U1–U16) dari seluruh sample gabungan
        $JumlahRataPerunsur = [];
        $JumlahTotalPerunsur = [];
        $JumlahNilaiPerunsur = [];
        for ($i = 1; $i <= 9; $i++) {
            $JumlahNilaiPerunsur["u$i"] = collect($hasilPerLayanan)->avg("nilai_perunsur.u$i");
            $JumlahTotalPerunsur["u$i"] = collect($hasilPerLayanan)->sum("total_perunsur.u$i");
            $JumlahRataPerunsur["u$i"] = collect($hasilPerLayanan)->avg("rata_perunsur.u$i");
        }

        // 🔹 Nilai konversi & IKM total gabungan


        $nilaiIkmGab = array_sum($JumlahRataPerunsur) / count($JumlahRataPerunsur);
        $nilaiKonversiGab = $nilaiIkmGab * 25;
        $predikat = getPredikat($nilaiIkmGab);

        // 🔹 Statistik tambahan responden
        $statistik = [];

        // Daftar opsi jenis kelamin
        $jenisKelaminOptions = ['L', 'P'];

        // Hitung jumlah per jenis kelamin
        $groupJenisKelamin = $allSampled->groupBy('jenis_kelamin')->map->count();

        // Buat array jumlah per kategori
        $jumlahJenisKelamin = collect($jenisKelaminOptions)->mapWithKeys(function ($opt) use ($groupJenisKelamin) {
            return [$opt => $groupJenisKelamin[$opt] ?? 0];
        });

        // Hitung total
        $totalJenisKelamin = $jumlahJenisKelamin->sum();

        // Gabungkan jumlah + persentase
        $statistik['jenis_kelamin'] = $jumlahJenisKelamin->mapWithKeys(function ($jumlah, $jenisKelamin) use ($totalJenisKelamin) {
            $persentase = $totalJenisKelamin > 0 ? round(($jumlah / $totalJenisKelamin) * 100, 2) : 0;
            return [
                $jenisKelamin => [
                    'jumlah' => $jumlah,
                    'persentase' => $persentase
                ]
            ];
        })->toArray();


        $pendidikanOptions = ['Non Pendidikan', 'SD', 'SMP', 'SMA', 'DIII', 'S1', 'S2', 'S3'];

        // Hitung jumlah per pendidikan
        $groupPendidikan = $allSampled->groupBy('pendidikan')->map->count();

        // Buat array jumlah per kategori
        $jumlahPendidikan = collect($pendidikanOptions)->mapWithKeys(function ($opt) use ($groupPendidikan) {
            return [$opt => $groupPendidikan[$opt] ?? 0];
        });

        // Hitung total
        $totalPendidikan = $jumlahPendidikan->sum();

        // Gabungkan jumlah + persentase
        $statistik['pendidikan'] = $jumlahPendidikan->mapWithKeys(function ($jumlah, $pendidikan) use ($totalPendidikan) {
            $persentase = $totalPendidikan > 0 ? round(($jumlah / $totalPendidikan) * 100, 2) : 0;
            return [
                $pendidikan => [
                    'jumlah' => $jumlah,
                    'persentase' => $persentase
                ]
            ];
        })->toArray();



        $pekerjaanOptions = ['TNI', 'POLRI', 'ASN', 'SWASTA', 'WIRAUSAHA', 'Lainnya'];

        // Hitung jumlah per pekerjaan
        $groupPekerjaan = $allSampled->groupBy('pekerjaan')->map->count();

        // Buat array jumlah per kategori
        $jumlahPekerjaan = collect($pekerjaanOptions)->mapWithKeys(function ($opt) use ($groupPekerjaan) {
            return [$opt => $groupPekerjaan[$opt] ?? 0];
        });

        // Hitung total
        $totalPekerjaan = $jumlahPekerjaan->sum();

        // Gabungkan jumlah + persentase
        $statistik['pekerjaan'] = $jumlahPekerjaan->mapWithKeys(function ($jumlah, $pekerjaan) use ($totalPekerjaan) {
            $persentase = $totalPekerjaan > 0 ? round(($jumlah / $totalPekerjaan) * 100, 2) : 0;
            return [
                $pekerjaan => [
                    'jumlah' => $jumlah,
                    'persentase' => $persentase
                ]
            ];
        })->toArray();

        // Hitung jumlah Non Disabilitas
        $nonDisabilitasCount = $allSampled->where('disabilitas', 'Non Disabilitas')->count();

        // Hitung per jenis disabilitas
        $disabilitasJenis = [
            'Fisik' => $allSampled->where('jenis_disabilitas', 'Disabilitas Fisik')->count(),
            'Mental' => $allSampled->where('jenis_disabilitas', 'Disabilitas Mental')->count(),
            'Intelektual' => $allSampled->where('jenis_disabilitas', 'Disabilitas Intelektual')->count(),
            'Sensorik' => $allSampled->where('jenis_disabilitas', 'Disabilitas Sensorik')->count(),
        ];

        // Total semua responden
        $totalKategori = $nonDisabilitasCount + array_sum($disabilitasJenis);

        // Susun data
        $statistik['kategori_pengguna'] = [
            'non_disabilitas' => [
                'label' => 'Non Disabilitas',
                'jumlah' => $nonDisabilitasCount,
                'persentase' => $totalKategori > 0 ? round(($nonDisabilitasCount / $totalKategori) * 100, 2) : 0,
            ],
            'disabilitas' => collect($disabilitasJenis)->map(function ($jumlah, $label) use ($totalKategori) {
                $persentase = $totalKategori > 0 ? round(($jumlah / $totalKategori) * 100, 2) : 0;
                return [
                    'label' => $label,
                    'jumlah' => $jumlah,
                    'persentase' => $persentase,
                ];
            })->values()->toArray(),
        ];


        // Hitung jumlah per kategori usia
        $usiaKategori = [
            '17–23' => $allSampled->whereBetween('usia', [17, 23])->count(),
            '24–29' => $allSampled->whereBetween('usia', [24, 29])->count(),
            '30–40' => $allSampled->whereBetween('usia', [30, 40])->count(),
            'Diatas 40' => $allSampled->where('usia', '>', 40)->count(),
        ];

        // Hitung total responden
        $totalUsia = array_sum($usiaKategori);

        // Susun data dengan label, jumlah, satuan, dan persentase
        $statistik['usia'] = collect($usiaKategori)->map(function ($jumlah, $label) use ($totalUsia) {
            $persentase = $totalUsia > 0 ? round(($jumlah / $totalUsia) * 100, 2) : 0;
            return [
                'label' => $label,
                'jumlah' => $jumlah,
                'persentase' => $persentase,
            ];
        })->values()->toArray();


        return [
            'skpd_id' => $skpdId,
            'nama_skpd' => $namaSkpd,
            'total_responden' => $totalResponden,
            'sample_diambil' => $totalSample,
            'nilai_ikm' => $nilaiIkmGab,
            'nilai_konversi' => $nilaiKonversiGab,
            'predikat_mutu_layanan' => $predikat,
            'hasil_perlayanan' => $hasilPerLayanan,
            'nilai_perunsur' => $JumlahNilaiPerunsur,
            'rata_perunsur' => $JumlahRataPerunsur,
            'total_perunsur' => $JumlahTotalPerunsur,

            'statistik_responden' => $statistik,
        ];
    }
}