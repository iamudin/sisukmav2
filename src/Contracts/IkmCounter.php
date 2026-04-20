<?php
namespace Sisukma\V2\Contracts;

use Sisukma\V2\Models\Respon;
use Illuminate\Support\Facades\DB;

class   IkmCounter {



    function getDataIKM9($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
    {
        $query = Respon::query()
            ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
            ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
            ->select(
                'respons.*',
                'layanans.nama_layanan as nama_layanan',
                'skpds.nama_skpd as nama_skpd',
                'layanans.id as id_layanan'
            )
            ->whereHas('layanan.skpd.periode_aktif', function ($q)use($tahun) {
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

    function getDataIKM16($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
    {
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
            $nilaiPerunsur = [];
            for ($i = 1; $i <= 16; $i++) {
                $totalPerUnsur["u$i"] = $sampleData->sum("u$i");
                $rataPerUnsur["u$i"] = $sampleData->avg("u$i");
            }
            $nilaiPerunsur['p1'] = round(($rataPerUnsur['u1'] + $rataPerUnsur['u2']) / 2, 3);
            $nilaiPerunsur['p2'] = round(($rataPerUnsur['u3'] + $rataPerUnsur['u4'] + $rataPerUnsur['u5']) / 3, 3);
            $nilaiPerunsur['p3'] = $rataPerUnsur['u6'];
            $nilaiPerunsur['p4'] = round(($rataPerUnsur['u7'] + $rataPerUnsur['u8'] + $rataPerUnsur['u9']) / 3, 3);
            $nilaiPerunsur['p5'] = $rataPerUnsur['u10'];
            $nilaiPerunsur['p6'] = $rataPerUnsur['u11'];
            $nilaiPerunsur['p7'] = round(($rataPerUnsur['u12'] + $rataPerUnsur['u13'] + $rataPerUnsur['u14']) / 3, 3);
            $nilaiPerunsur['p8'] = $rataPerUnsur['u15'];
            $nilaiPerunsur['p9'] = $rataPerUnsur['u16'];

            $nilaiIKM = round(array_sum($nilaiPerunsur) / count($nilaiPerunsur), 3);
            $konversi = round($nilaiIKM * 25, 2);
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

        for ($i = 1; $i <= 9;$i++){
            $NilaiPerUnsurGab["p$i"] = collect($hasilPerLayanan)->avg("nilai_perunsur.p$i");
        }
        for ($i = 1; $i <= 16; $i++) {
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
            'jumlah_nilai_perunsur' => $NilaiPerUnsurGab,
            'jumlah_rata_perunsur' => $RataPerUnsurGab,
            'jumlah_total_perunsur' => $TotalPerUnsurGab,
            'nilai_ikm' => $nilaiIKMGabungan,
            'nilai_konversi' => $konversiGab,
            'predikat_mutu' => $predikatGab,
            'data_responden' => $dataRespondenGabungan,
        ];
    }

    function getStatistik9($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
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
            ->whereHas('layanan.skpd.periode_aktif', function ($q)use($tahun) {
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
 function getStatistik16($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
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
            ->whereHas('layanan.skpd.periode_aktif', function ($q)use($tahun) {
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

    // function getStatistik16($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
    // {
    //     $query = Respon::query()
    //         ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
    //         ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
    //         ->select(
    //             'respons.*',
    //             'layanans.nama_layanan as nama_layanan',
    //             'skpds.nama_skpd as nama_skpd',
    //             'skpds.id as skpd_id',
    //             'layanans.id as id_layanan'
    //         )
    //         ->whereHas('layanan.skpd.periode_aktif', function ($q) use ($tahun) {
    //             $q->where('tahun', $tahun ?? date('Y'));
    //         });


    //     if ($skpd)
    //         $query->where('skpds.id', $skpd);
    //     if ($id_layanan)
    //         $query->where('layanans.id', $id_layanan);

    //     // 🔹 Filter periode
    //     if ($periode === 'bulan' && $bulan && $tahun) {
    //         $query->whereYear('respons.tgl_survei', $tahun)->whereMonth('respons.tgl_survei', $bulan);
    //     } elseif ($periode === 'triwulan' && $tahun && $bulan) {
    //         $triwulan = ceil($bulan / 3);
    //         $start = ($triwulan - 1) * 3 + 1;
    //         $end = $start + 2;
    //         $query->whereYear('respons.tgl_survei', $tahun)
    //             ->whereBetween(DB::raw('MONTH(respons.tgl_survei)'), [$start, $end]);
    //     } elseif ($periode === 'semester' && $tahun && $bulan) {
    //         $semester = $bulan <= 6 ? 1 : 2;
    //         $start = $semester == 1 ? 1 : 7;
    //         $end = $semester == 1 ? 6 : 12;
    //         $query->whereYear('respons.tgl_survei', $tahun)
    //             ->whereBetween(DB::raw('MONTH(respons.tgl_survei)'), [$start, $end]);
    //     } elseif ($periode === 'tahun' && $tahun) {
    //         $query->whereYear('respons.tgl_survei', $tahun);
    //     }

    //     $data = $query->get();
    //     if ($data->isEmpty())
    //         return [];

    //     // 🔹 Kelompokkan per SKPD
    //     $groupedBySkpd = $data->groupBy('skpd_id');
    //     $hasil = [];

    //     foreach ($groupedBySkpd as $skpdId => $records) {
    //         $hasil[] = $this->hitungStatistikPerKumpulanGabungan16($records, $skpdId, $records->first()->nama_skpd);
    //     }

    //     // 🔹 Tambahkan total keseluruhan jika tidak difilter SKPD
    //     if (is_null($skpd)) {
    //         $totalAll = $this->hitungStatistikPerKumpulanGabungan16($data, null, 'Total Keseluruhan');
    //         $hasil[] = array_merge(['skpd_id' => null, 'nama_skpd' => 'Total Semua SKPD'], $totalAll);
    //     } else {
    //         return $hasil[0] ?? ['message' => 'Data SKPD tidak ditemukan.'];
    //     }

    //     return $hasil;
    // }
// function hitungStatistikPerKumpulanGabungan16($records, $skpdId = null, $namaSkpd = null)
// {
//   $groupedByLayanan = $records->groupBy('id_layanan');

//   $totalResponden = 0;
//   $totalSample = 0;
//   $allSampled = collect();
//   $sumNilaiP = array_fill(1, 9, 0); // total konversi antar layanan

//   foreach ($groupedByLayanan as $layananId => $recordsLayanan) {
//     $totalResponden += $recordsLayanan->count();

//     // ambil sample per layanan
//     $sample = round(3.841 * $recordsLayanan->count() * 0.25);
//     $totalSample += $sample;

//     // urutkan agar sampling seragam seperti getDataUnsur()
//     $sampleData = $recordsLayanan
//       ->sortByDesc('u1')
//       ->sortByDesc('u2')
//       ->sortByDesc('u3')
//       ->sortByDesc('u4')
//       ->sortByDesc('u5')
//       ->sortByDesc('u6')
//       ->sortByDesc('u7')
//       ->sortByDesc('u8')
//       ->sortByDesc('u9')
//       ->sortByDesc('u10')
//       ->sortByDesc('u11')
//       ->sortByDesc('u12')
//       ->sortByDesc('u13')
//       ->sortByDesc('u14')
//       ->sortByDesc('u15')
//       ->sortByDesc('u16')
//       ->take($sample);

//     if ($sampleData->isEmpty())
//       continue;
//     $allSampled = $allSampled->merge($sampleData);
//     // Rata-rata tiap unsur U1–U16
//     $rataRataPerUnsur = [];
//     for ($i = 1; $i <= 16; $i++) {
//       $rataRataPerUnsur["u$i"] = round($sampleData->avg("u$i"), 3);
//     }

//     // Hitung P1–P9 seperti getDataUnsur()
//     $p = [];
//     $p['p1'] = round(($rataRataPerUnsur['u1'] + $rataRataPerUnsur['u2']) / 2, 3);
//     $p['p2'] = round(($rataRataPerUnsur['u3'] + $rataRataPerUnsur['u4'] + $rataRataPerUnsur['u5']) / 3, 3);
//     $p['p3'] = $rataRataPerUnsur['u6'];
//     $p['p4'] = round(($rataRataPerUnsur['u7'] + $rataRataPerUnsur['u8'] + $rataRataPerUnsur['u9']) / 3, 3);
//     $p['p5'] = $rataRataPerUnsur['u10'];
//     $p['p6'] = $rataRataPerUnsur['u11'];
//     $p['p7'] = round(($rataRataPerUnsur['u12'] + $rataRataPerUnsur['u13'] + $rataRataPerUnsur['u14']) / 3, 3);
//     $p['p8'] = $rataRataPerUnsur['u15'];
//     $p['p9'] = $rataRataPerUnsur['u16'];

//     // Nilai konversi per unsur
//     $konversi = [];
//     foreach ($p as $key => $nilai) {
//       $konversi[$key] = round($nilai * 25, 2);
//     }

//     // Agregasi total antar layanan
//     foreach ($konversi as $key => $val) {
//       $index = intval(substr($key, 1));
//       $sumNilaiP[$index] += $val;
//     }
//   }

//   // Hitung rata-rata antar layanan
//   $jumlahLayanan = count($groupedByLayanan);
//   if ($jumlahLayanan > 0) {
//     foreach ($sumNilaiP as $i => $total) {
//       $sumNilaiP[$i] = round($total / $jumlahLayanan, 2);
//     }
//   }

//   // Nilai akhir seperti getDataUnsur()
//   $nilaiIkm = round(array_sum($sumNilaiP) / count($sumNilaiP), 2);
//   $predikat = getPredikat($nilaiIkm / 25);

//   // 🔹 Statistik tambahan responden
//   $statistik = [];

//   // Jenis Kelamin
//   $statistik['jenis_kelamin'] = $allSampled->groupBy('jenis_kelamin')
//     ->map(fn($g) => $g->count())
//     ->toArray();

//   // Pendidikan
//   $pendidikanOptions = ['Non Pendidikan', 'SD', 'SMP', 'SMA', 'DIII', 'S1', 'S2', 'S3'];
//   $groupPendidikan = $allSampled->groupBy('pendidikan')->map->count();
//   $statistik['pendidikan'] = collect($pendidikanOptions)->mapWithKeys(function ($opt) use ($groupPendidikan) {
//     return [$opt => $groupPendidikan[$opt] ?? 0];
//   })->toArray();

//   // Pekerjaan
//   $statistik['pekerjaan'] = $allSampled->groupBy('pekerjaan')
//     ->map(fn($g) => $g->count())
//     ->toArray();

//   // Disabilitas — bedakan non dan jenis disabilitas
//   $nonDisabilitasCount = $allSampled->where('disabilitas', 'non_disabilitas')->count();
//   $disabilitasJenis = [
//     'Fisik' => $allSampled->where('jenis_disabilitas', 'Disabilitas Fisik')->count(),
//     'Mental' => $allSampled->where('jenis_disabilitas', 'Disabilitas Mental')->count(),
//     'Intelektual' => $allSampled->where('jenis_disabilitas', 'Disabilitas Intelektual')->count(),
//     'Sensorik' => $allSampled->where('jenis_disabilitas', 'Disabilitas Sensorik')->count(),
//   ];
//   $statistik['disabilitas'] = [
//     'non_disabilitas' => ['label' => 'Non Disabilitas', 'jumlah' => $nonDisabilitasCount, 'satuan' => 'Orang'],
//     'disabilitas' => collect($disabilitasJenis)->map(fn($jumlah, $label) => [
//       'label' => $label,
//       'jumlah' => $jumlah,
//       'satuan' => 'Orang'
//     ])->values()->toArray(),
//   ];

//   // Usia
//   $statistik['usia'] = [
//     '≤20' => $allSampled->where('usia', '<=', 20)->count(),
//     '21–30' => $allSampled->whereBetween('usia', [21, 30])->count(),
//     '31–40' => $allSampled->whereBetween('usia', [31, 40])->count(),
//     '41–50' => $allSampled->whereBetween('usia', [41, 50])->count(),
//     '>50' => $allSampled->where('usia', '>', 50)->count(),
//   ];
//   return [
//     'skpd_id' => $skpdId,
//     'nama_skpd' => $namaSkpd,
//     'total_responden' => $totalResponden,
//     'sample_diambil' => $totalSample,
//     'nilai_ikm' => round($nilaiIkm / 25, 3), // normalisasi agar sama (0–4)
//     'nilai_konversi' => $nilaiIkm,
//     'predikat_mutu_layanan' => $predikat,
//     'statistik_responden' => $statistik,
//   ];
// }


//     function hitungStatistikPerKumpulanGabungan16($records, $skpdId = null, $namaSkpd = null)
//     {
//         $groupedByLayanan = $records->groupBy('id_layanan');

//         $totalResponden = 0;
//         $totalSample = 0;
//         $allSampled = collect();

//         // 🔹 Sample per layanan, lalu gabungkan
//         foreach ($groupedByLayanan as $layananId => $recordsLayanan) {
//             $totalResponden += $recordsLayanan->count();
//             $sample = round(3.841 * $recordsLayanan->count() * 0.25);
//             $totalSample += $sample;

//             $sampleData = $recordsLayanan
//                 ->sortByDesc('u1')
//                 ->sortByDesc('u2')
//                 ->sortByDesc('u3')
//                 ->sortByDesc('u4')
//                 ->sortByDesc('u5')
//                 ->sortByDesc('u6')
//                 ->sortByDesc('u7')
//                 ->sortByDesc('u8')
//                 ->sortByDesc('u9')
//                 ->sortByDesc('u10')
//                 ->sortByDesc('u11')
//                 ->sortByDesc('u12')
//                 ->sortByDesc('u13')
//                 ->sortByDesc('u14')
//                 ->sortByDesc('u15')
//                 ->sortByDesc('u16')
//                 ->take($sample);

//     $allSampled = $allSampled->merge($sampleData);
//     // Rata-rata tiap unsur U1–U16
//     $rataRataPerUnsur = [];
//     for ($i = 1; $i <= 16; $i++) {
//       $rataRataPerUnsur["u$i"] = round($sampleData->avg("u$i"), 3);
//     }

//     // Hitung P1–P9 seperti getDataUnsur()
//     $p = [];
//     $p['p1'] = round(($rataRataPerUnsur['u1'] + $rataRataPerUnsur['u2']) / 2, 3);
//     $p['p2'] = round(($rataRataPerUnsur['u3'] + $rataRataPerUnsur['u4'] + $rataRataPerUnsur['u5']) / 3, 3);
//     $p['p3'] = $rataRataPerUnsur['u6'];
//     $p['p4'] = round(($rataRataPerUnsur['u7'] + $rataRataPerUnsur['u8'] + $rataRataPerUnsur['u9']) / 3, 3);
//     $p['p5'] = $rataRataPerUnsur['u10'];
//     $p['p6'] = $rataRataPerUnsur['u11'];
//     $p['p7'] = round(($rataRataPerUnsur['u12'] + $rataRataPerUnsur['u13'] + $rataRataPerUnsur['u14']) / 3, 3);
//     $p['p8'] = $rataRataPerUnsur['u15'];
//     $p['p9'] = $rataRataPerUnsur['u16'];

//     // Nilai konversi per unsur
//     $konversi = [];
//     foreach ($p as $key => $nilai) {
//       $konversi[$key] = round($nilai * 25, 2);
//     }
//  $sumNilaiP[] = null;
//     // Agregasi total antar layanan
//     foreach ($konversi as $key => $val) {
//                 dd($val);
//       $index = intval(substr($key, 1));
//      $sumNilaiP[$index] += $val;
//     }
//   }

//   // Hitung rata-rata antar layanan
//   $jumlahLayanan = count($groupedByLayanan);
//   if ($jumlahLayanan > 0) {
//     foreach ($sumNilaiP as $i => $total) {
//       $sumNilaiP[$i] = round($total / $jumlahLayanan, 2);
//     }
//   }

//   // Nilai akhir seperti getDataUnsur()
//   $nilaiIkm = round(array_sum($sumNilaiP) / count($sumNilaiP), 2);
//   $predikat = getPredikat($nilaiIkm / 25);

//             $statistik = [];

//             // Jenis Kelamin
//             $jenisKelaminOptions = ['L', 'P'];
//             $groupJenisKelamin = $sampleData->groupBy('jenis_kelamin')->map->count();
//             $jumlahJenisKelamin = collect($jenisKelaminOptions)->mapWithKeys(fn($opt) => [$opt => $groupJenisKelamin[$opt] ?? 0]);
//             $totalJenisKelamin = $jumlahJenisKelamin->sum();
//             $statistik['jenis_kelamin'] = $jumlahJenisKelamin->mapWithKeys(function ($jumlah, $jk) use ($totalJenisKelamin) {
//                 $persentase = $totalJenisKelamin > 0 ? round(($jumlah / $totalJenisKelamin) * 100, 2) : 0;
//                 return [$jk => ['jumlah' => $jumlah, 'persentase' => $persentase]];
//             })->toArray();

//             // Pendidikan
//             $pendidikanOptions = ['Non Pendidikan', 'SD', 'SMP', 'SMA', 'DIII', 'S1', 'S2', 'S3'];
//             $groupPendidikan = $sampleData->groupBy('pendidikan')->map->count();
//             $jumlahPendidikan = collect($pendidikanOptions)->mapWithKeys(fn($opt) => [$opt => $groupPendidikan[$opt] ?? 0]);
//             $totalPendidikan = $jumlahPendidikan->sum();
//             $statistik['pendidikan'] = $jumlahPendidikan->mapWithKeys(function ($jumlah, $pendidikan) use ($totalPendidikan) {
//                 $persentase = $totalPendidikan > 0 ? round(($jumlah / $totalPendidikan) * 100, 2) : 0;
//                 return [$pendidikan => ['jumlah' => $jumlah, 'persentase' => $persentase]];
//             })->toArray();

//             // Pekerjaan
//             $pekerjaanOptions = ['TNI', 'POLRI', 'ASN', 'SWASTA', 'WIRAUSAHA', 'LAINNYA'];
//             $groupPekerjaan = $sampleData->groupBy('pekerjaan')->map->count();
//             $jumlahPekerjaan = collect($pekerjaanOptions)->mapWithKeys(fn($opt) => [$opt => $groupPekerjaan[$opt] ?? 0]);
//             $totalPekerjaan = $jumlahPekerjaan->sum();
//             $statistik['pekerjaan'] = $jumlahPekerjaan->mapWithKeys(function ($jumlah, $pekerjaan) use ($totalPekerjaan) {
//                 $persentase = $totalPekerjaan > 0 ? round(($jumlah / $totalPekerjaan) * 100, 2) : 0;
//                 return [$pekerjaan => ['jumlah' => $jumlah, 'persentase' => $persentase]];
//             })->toArray();

//             // Kategori Pengguna (Disabilitas / Non Disabilitas)
//             $nonDisabilitasCount = $sampleData->where('disabilitas', 'Non Disabilitas')->count();
//             $disabilitasJenis = [
//                 'Fisik' => $sampleData->where('jenis_disabilitas', 'Disabilitas Fisik')->count(),
//                 'Mental' => $sampleData->where('jenis_disabilitas', 'Disabilitas Mental')->count(),
//                 'Intelektual' => $sampleData->where('jenis_disabilitas', 'Disabilitas Intelektual')->count(),
//                 'Sensorik' => $sampleData->where('jenis_disabilitas', 'Disabilitas Sensorik')->count(),
//             ];
//             $totalKategori = $nonDisabilitasCount + array_sum($disabilitasJenis);
//             $statistik['kategori_pengguna'] = [
//                 'non_disabilitas' => [
//                     'label' => 'Non Disabilitas',
//                     'jumlah' => $nonDisabilitasCount,
//                     'persentase' => $totalKategori > 0 ? round(($nonDisabilitasCount / $totalKategori) * 100, 2) : 0,
//                 ],
//                 'disabilitas' => collect($disabilitasJenis)->map(function ($jumlah, $label) use ($totalKategori) {
//                     $persentase = $totalKategori > 0 ? round(($jumlah / $totalKategori) * 100, 2) : 0;
//                     return [
//                         'label' => $label,
//                         'jumlah' => $jumlah,
//                         'persentase' => $persentase,
//                     ];
//                 })->values()->toArray(),
//             ];

//             // Usia
//             $usiaKategori = [
//                 '17–23' => $sampleData->whereBetween('usia', [17, 23])->count(),
//                 '24–29' => $sampleData->whereBetween('usia', [24, 29])->count(),
//                 '30–40' => $sampleData->whereBetween('usia', [30, 40])->count(),
//                 'Diatas 40' => $sampleData->where('usia', '>', 40)->count(),
//             ];
//             $totalUsia = array_sum($usiaKategori);
//             $statistik['usia'] = collect($usiaKategori)->map(function ($jumlah, $label) use ($totalUsia) {
//                 $persentase = $totalUsia > 0 ? round(($jumlah / $totalUsia) * 100, 2) : 0;
//                 return [
//                     'label' => $label,
//                     'jumlah' => $jumlah,
//                     'persentase' => $persentase,
//                 ];
//             })->values()->toArray();

//             $hasilPerLayanan[] = [
//                 'id_layanan' => $layananId,
//                 'total_responden' => $recordsLayanan->count(),
//                 'nama_layanan' => $recordsLayanan->first()->nama_layanan,
//                 'sample_diambil' => $sampleData->count(),
//                 'rata_perunsur' => '',//$rataPerUnsur,
//                 'total_perunsur' => '',//$totalPerUnsur,
//                 'nilai_perunsur' => '',//$nilaiPerunsur,
//                 'nilai_ikm' => '',//$nilaiIKM,
//                 'nilai_konversi' => $konversi,
//                 'predikat_mutu' => $predikat,
//                 'saran' => collect($recordsLayanan)->where('saran', '!=', '')->map(function ($item) {
//                     return [
//                         'nama' => $item->nik ?? '-',
//                         'tgl_survei' => $item->tgl_survei ?? '-',
//                         'pendidikan' => $item->pendidikan ?? '-',
//                         'jenis_kelamin' => $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
//                         'pekerjaan' => $item->pekerjaan ?? '-',
//                         'usia' => $item->usia ?? '-',
//                         'disabilitas' => $item->disabilitas == 'Non Disabilitas'
//                             ? 'Non Disabilitas'
//                             : ($item->jenis_disabilitas ?? '-'),
//                         'saran' => $item->saran ?? '-',
//                     ];

//                 }),
//                 'statistik_responden' => $statistik
//             ];

//         if ($allSampled->isEmpty()) {
//             return [
//                 'skpd_id' => $skpdId,
//                 'nama_skpd' => $namaSkpd,
//                 'message' => 'Tidak ada data sample untuk dihitung.'
//             ];
//         }

//         // 🔹 Hitung rata-rata unsur (U1–U16) dari seluruh sample gabungan
//         $JumlahRataPerunsur = [];
//         $JumlahTotalPerunsur = [];
//         $JumlahNilaiPerunsur = [];
//         for ($i = 1; $i <= 9; $i++) {
//             $JumlahNilaiPerunsur["u$i"] = collect($hasilPerLayanan)->avg("nilai_perunsur.u$i");
//             $JumlahTotalPerunsur["u$i"] = collect($hasilPerLayanan)->sum("total_perunsur.u$i");
//             $JumlahRataPerunsur["u$i"] = collect($hasilPerLayanan)->avg("rata_perunsur.u$i");
//         }

//         // 🔹 Nilai konversi & IKM total gabungan


//         $nilaiIkmGab = array_sum($JumlahRataPerunsur) / count($JumlahRataPerunsur);
//         $nilaiKonversiGab = $nilaiIkmGab * 25;
//         $predikat = getPredikat($nilaiIkmGab);

//         // 🔹 Statistik tambahan responden
//         $statistik = [];

//         // Daftar opsi jenis kelamin
//         $jenisKelaminOptions = ['L', 'P'];

//         // Hitung jumlah per jenis kelamin
//         $groupJenisKelamin = $allSampled->groupBy('jenis_kelamin')->map->count();

//         // Buat array jumlah per kategori
//         $jumlahJenisKelamin = collect($jenisKelaminOptions)->mapWithKeys(function ($opt) use ($groupJenisKelamin) {
//             return [$opt => $groupJenisKelamin[$opt] ?? 0];
//         });

//         // Hitung total
//         $totalJenisKelamin = $jumlahJenisKelamin->sum();

//         // Gabungkan jumlah + persentase
//         $statistik['jenis_kelamin'] = $jumlahJenisKelamin->mapWithKeys(function ($jumlah, $jenisKelamin) use ($totalJenisKelamin) {
//             $persentase = $totalJenisKelamin > 0 ? round(($jumlah / $totalJenisKelamin) * 100, 2) : 0;
//             return [
//                 $jenisKelamin => [
//                     'jumlah' => $jumlah,
//                     'persentase' => $persentase
//                 ]
//             ];
//         })->toArray();


//         $pendidikanOptions = ['Non Pendidikan', 'SD', 'SMP', 'SMA', 'DIII', 'S1', 'S2', 'S3'];

//         // Hitung jumlah per pendidikan
//         $groupPendidikan = $allSampled->groupBy('pendidikan')->map->count();

//         // Buat array jumlah per kategori
//         $jumlahPendidikan = collect($pendidikanOptions)->mapWithKeys(function ($opt) use ($groupPendidikan) {
//             return [$opt => $groupPendidikan[$opt] ?? 0];
//         });

//         // Hitung total
//         $totalPendidikan = $jumlahPendidikan->sum();

//         // Gabungkan jumlah + persentase
//         $statistik['pendidikan'] = $jumlahPendidikan->mapWithKeys(function ($jumlah, $pendidikan) use ($totalPendidikan) {
//             $persentase = $totalPendidikan > 0 ? round(($jumlah / $totalPendidikan) * 100, 2) : 0;
//             return [
//                 $pendidikan => [
//                     'jumlah' => $jumlah,
//                     'persentase' => $persentase
//                 ]
//             ];
//         })->toArray();



//         $pekerjaanOptions = ['TNI', 'POLRI', 'ASN', 'SWASTA', 'WIRAUSAHA', 'Lainnya'];

//         // Hitung jumlah per pekerjaan
//         $groupPekerjaan = $allSampled->groupBy('pekerjaan')->map->count();

//         // Buat array jumlah per kategori
//         $jumlahPekerjaan = collect($pekerjaanOptions)->mapWithKeys(function ($opt) use ($groupPekerjaan) {
//             return [$opt => $groupPekerjaan[$opt] ?? 0];
//         });

//         // Hitung total
//         $totalPekerjaan = $jumlahPekerjaan->sum();

//         // Gabungkan jumlah + persentase
//         $statistik['pekerjaan'] = $jumlahPekerjaan->mapWithKeys(function ($jumlah, $pekerjaan) use ($totalPekerjaan) {
//             $persentase = $totalPekerjaan > 0 ? round(($jumlah / $totalPekerjaan) * 100, 2) : 0;
//             return [
//                 $pekerjaan => [
//                     'jumlah' => $jumlah,
//                     'persentase' => $persentase
//                 ]
//             ];
//         })->toArray();

//         // Hitung jumlah Non Disabilitas
//         $nonDisabilitasCount = $allSampled->where('disabilitas', 'Non Disabilitas')->count();

//         // Hitung per jenis disabilitas
//         $disabilitasJenis = [
//             'Fisik' => $allSampled->where('jenis_disabilitas', 'Disabilitas Fisik')->count(),
//             'Mental' => $allSampled->where('jenis_disabilitas', 'Disabilitas Mental')->count(),
//             'Intelektual' => $allSampled->where('jenis_disabilitas', 'Disabilitas Intelektual')->count(),
//             'Sensorik' => $allSampled->where('jenis_disabilitas', 'Disabilitas Sensorik')->count(),
//         ];

//         // Total semua responden
//         $totalKategori = $nonDisabilitasCount + array_sum($disabilitasJenis);

//         // Susun data
//         $statistik['kategori_pengguna'] = [
//             'non_disabilitas' => [
//                 'label' => 'Non Disabilitas',
//                 'jumlah' => $nonDisabilitasCount,
//                 'persentase' => $totalKategori > 0 ? round(($nonDisabilitasCount / $totalKategori) * 100, 2) : 0,
//             ],
//             'disabilitas' => collect($disabilitasJenis)->map(function ($jumlah, $label) use ($totalKategori) {
//                 $persentase = $totalKategori > 0 ? round(($jumlah / $totalKategori) * 100, 2) : 0;
//                 return [
//                     'label' => $label,
//                     'jumlah' => $jumlah,
//                     'persentase' => $persentase,
//                 ];
//             })->values()->toArray(),
//         ];


//         // Hitung jumlah per kategori usia
//         $usiaKategori = [
//             '17–23' => $allSampled->whereBetween('usia', [17, 23])->count(),
//             '24–29' => $allSampled->whereBetween('usia', [24, 29])->count(),
//             '30–40' => $allSampled->whereBetween('usia', [30, 40])->count(),
//             'Diatas 40' => $allSampled->where('usia', '>', 40)->count(),
//         ];

//         // Hitung total responden
//         $totalUsia = array_sum($usiaKategori);

//         // Susun data dengan label, jumlah, satuan, dan persentase
//         $statistik['usia'] = collect($usiaKategori)->map(function ($jumlah, $label) use ($totalUsia) {
//             $persentase = $totalUsia > 0 ? round(($jumlah / $totalUsia) * 100, 2) : 0;
//             return [
//                 'label' => $label,
//                 'jumlah' => $jumlah,
//                 'persentase' => $persentase,
//             ];
//         })->values()->toArray();


//         return [
//             'skpd_id' => $skpdId,
//             'nama_skpd' => $namaSkpd,
//             'total_responden' => $totalResponden,
//             'sample_diambil' => $totalSample,
//             'nilai_ikm' => $nilaiIkmGab,
//             'nilai_konversi' => $nilaiKonversiGab,
//             'predikat_mutu_layanan' => $predikat,
//             'hasil_perlayanan' => $hasilPerLayanan,
//             'nilai_perunsur' => $JumlahNilaiPerunsur,
//             'rata_perunsur' => $JumlahRataPerunsur,
//             'total_perunsur' => $JumlahTotalPerunsur,

//             'statistik_responden' => $statistik,
//         ];
//     }
    
  function hitungStatistikPerKumpulanGabungan16($records, $skpdId = null, $namaSkpd = null) 
    {
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
    $p['p1'] = round(($rataPerUnsur['u1'] + $rataPerUnsur['u2']) / 2, 3);
    $p['p2'] = round(($rataPerUnsur['u3'] + $rataPerUnsur['u4'] + $rataPerUnsur['u5']) / 3, 3);
    $p['p3'] = $rataPerUnsur['u6'];
    $p['p4'] = round(($rataPerUnsur['u7'] + $rataPerUnsur['u8'] + $rataPerUnsur['u9']) / 3, 3);
    $p['p5'] = $rataPerUnsur['u10'];
    $p['p6'] = $rataPerUnsur['u11'];
    $p['p7'] = round(($rataPerUnsur['u12'] + $rataPerUnsur['u13'] + $rataPerUnsur['u14']) / 3, 3);
    $p['p8'] = $rataPerUnsur['u15'];
    $p['p9'] = $rataPerUnsur['u16'];
    $nilaiPerunsur = $p;

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
                'saran' => collect($recordsLayanan)->where('saran','!=','')->map(function ($item) {
                        return [
                            'nama' => $item->nik ?? '-',
                            'tgl_survei' => $item->tgl_survei ?? '-',
                            'pendidikan' => $item->pendidikan ?? '-',
                            'jenis_kelamin' => $item->jenis_kelamin=='L' ? 'Laki-laki' : 'Perempuan',
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
        // $JumlahRataPerunsur = [];
        // $JumlahTotalPerunsur = [];
        $JumlahNilaiPerunsur = [];
        for ($i = 1; $i <= 9; $i++) {
            $JumlahNilaiPerunsur["p$i"] = collect($hasilPerLayanan)->avg("nilai_perunsur.p$i");
            // $JumlahTotalPerunsur["p$i"] = collect($hasilPerLayanan)->sum("total_perunsur.u$i");
            // $JumlahRataPerunsur["u$i"] = collect($hasilPerLayanan)->avg("rata_perunsur.u$i");
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
function hitungStatistikPerKumpulanGabungan9($records, $skpdId = null, $namaSkpd = null) 
    {
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
                'saran' => collect($recordsLayanan)->where('saran','!=','')->map(function ($item) {
                        return [
                            'nama' => $item->nik ?? '-',
                            'tgl_survei' => $item->tgl_survei ?? '-',
                            'pendidikan' => $item->pendidikan ?? '-',
                            'jenis_kelamin' => $item->jenis_kelamin=='L' ? 'Laki-laki' : 'Perempuan',
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
   /* function getDataUnsur($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
    {
        $query = Respon::query()
            ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
            ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
            ->leftJoin('evaluasis', function ($join) use ($tahun) {
                $join->on('layanans.id', '=', 'evaluasis.layanan_id');
                if ($tahun) {
                    $join->whereYear('evaluasis.tahun', '=', $tahun);
                }
            })
            ->select(
                'respons.*',
                'layanans.nama_layanan as nama_layanan',
                'skpds.nama_skpd as nama_skpd',
                'layanans.id as id_layanan',
                'evaluasis.unsur_perbaikan',
                'evaluasis.rtl',
                'evaluasis.rencana_tindak_lanjut'
            );

        // --- FILTER SKPD & LAYANAN ---
        if ($skpd) {
            $query->where('skpds.id', $skpd);
        }
        if ($id_layanan) {
            $query->where('layanans.id', $id_layanan);
        }

        // --- FILTER PERIODE RESPON ---
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

        // --- AMBIL DATA KELOMPOK PER LAYANAN ---
        $dataLayanan = $query->get()->groupBy('layanan_id');

        $hasilPerLayanan = [];
        $totalLayanan = $dataLayanan->count();

        foreach ($dataLayanan as $layananId => $responden) {
            $sample = round(3.841 * $responden->count() * 0.25);

            $sampleData = $responden
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

            // Hitung rata-rata per unsur
            $rataRataPerUnsur = [];
            for ($i = 1; $i <= 9; $i++) {
                $rataRataPerUnsur["p$i"] = round($sampleData->avg("u$i"), 3);
            }

            // Konversi dan predikat per unsur
            $konversi = [];
            foreach ($rataRataPerUnsur as $key => $nilai) {
                $konversi[$key] = round($nilai * 25, 2);
            }

            // Nilai rata-rata per layanan
            $nilai_ikm = round(array_sum($rataRataPerUnsur) / count($rataRataPerUnsur), 3);
            $nilai_konversi = round($nilai_ikm * 25, 2);
            $predikat_mutu = getPredikat($nilai_ikm);

            $hasilPerLayanan[] = [
                'nama_skpd' => $responden->first()->nama_skpd,
                'nama_layanan' => $responden->first()->nama_layanan,
                'id_layanan' => $responden->first()->id_layanan,
                'jumlah_responden' => $sample,
                'konversi_unsur' => $konversi,
                'nilai_ikm' => $nilai_ikm,
                'nilai_konversi' => $nilai_konversi,
                'predikat_mutu_layanan' => $predikat_mutu,
                'unsur_perbaikan' => $responden->first()->unsur_perbaikan,
                'rtl' => $responden->first()->rtl,
                'rencana_tindak_lanjut' => $responden->first()->rencana_tindak_lanjut,
            ];
        }

        // --- HITUNG NILAI PER UNSUR ---
        $sumPerUnsur = [];
        foreach ($hasilPerLayanan as $layanan) {
            foreach ($layanan['konversi_unsur'] as $key => $val) {
                if (!isset($sumPerUnsur[$key]))
                    $sumPerUnsur[$key] = 0;
                $sumPerUnsur[$key] += $val;
            }
        }

        $nilaiPerUnsur = [];
        $predikatPerUnsur = [];
        foreach ($sumPerUnsur as $key => $total) {
            $rata = round($total / $totalLayanan, 2);
            $nilaiPerUnsur[$key] = $rata;
            $predikatPerUnsur[$key] = getPredikat($rata / 25);
        }

        // --- NILAI IKM TOTAL ---
        $nilai_ikm = round(array_sum($nilaiPerUnsur) / count($nilaiPerUnsur), 2);
        $predikat_ikm = getPredikat($nilai_ikm / 25);

        return [
            'jumlah_layanan' => $totalLayanan,
            'per_layanan' => $hasilPerLayanan,
            'nilai_perunsur' => $nilaiPerUnsur,
            'predikat_perunsur' => $predikatPerUnsur,
            'nilai_ikm' => $nilai_ikm,
            'predikat_ikm' => $predikat_ikm,
        ];
    }
    */
}