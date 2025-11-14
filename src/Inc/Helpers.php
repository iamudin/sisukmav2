<?php
function unsur($key) {
    $mapping = [
        'u1' => 'Persyaratan',
        'u2' => 'Prosedur',
        'u3' => 'Waktu',
        'u4' => 'Biaya',
        'u5' => 'Produk',
        'u6' => 'Kompetensi',
        'u7' => 'Perilaku',
        'u8' => 'Aduan',
        'u9' => 'Sarpras',
    ];

    return $mapping[$key] ?? $key; // jika tidak ditemukan, kembalikan key aslinya
}
if (!function_exists('nilaiTerendah')) {
function nilaiTerendah($data) {
    // Ambil hanya nilai u1 - u9
    $nilai = [];
    for ($i = 1; $i <= 9; $i++) {
        $key = 'u' . $i;
        if (isset($data[$key])) {
            $nilai[$key] = $data[$key];
        }
    }

    // Cari nilai minimum
    $min = min($nilai);

    // Cari semua kunci (u1-u9) yang nilainya sama dengan minimum
    $terendah = [];
    foreach ($nilai as $key => $val) {
        if ($val == $min) {
            $terendah[$key] = $val;
        }
    }

    return [
        'nilai_terendah' => $min,
        'kolom_terendah' => array_keys($terendah),
        'detail' => $terendah
    ];
}
}


if (!function_exists('sample')) {
    function get_sample(int $populasi){
        $num1 = 3.841 * $populasi * 0.25;
        $num2 = ($populasi - 1) * 0.0025 + 3.841*0.25;
        return round($num1 / $num2);


    }
}
if (!function_exists('checkwaktu')) {
function checkwaktu($start){
    $firstime = array('08','09','10','11','12');
    $second = array('13','14','15','16','17');
  $waktu = date('H',strtotime($start));
  if(in_array($waktu,$firstime)){
    return '08:00 - 12:00';
  }elseif(in_array($waktu,$second)){
    return '13:00 - 17:00';
  }else{
    return false;
  }
  }
}
if (!function_exists('qrcode')) {
    function qrcode($string,$size){
    return \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
    ->generate($string,);
}
}
if (!function_exists('isDate')) {
    function isDate($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
  }
}
if (!function_exists('blnindo')) {

function blnindo($bln){
    $bulan_array = array(
      1 => 'Januari',
      2 => 'Februari',
      3 => 'Maret',
      4 => 'April',
      5 => 'Mei',
      6 => 'Juni',
      7 => 'Juli',
      8 => 'Agustus',
      9 => 'September',
      10 => 'Oktober',
      11 => 'November',
      12 => 'Desember',
  );
  return $bulan_array[ltrim($bln,'0')] ?? null;
  }
}
if (!function_exists('prediket')) {

function prediket(float $nilai,$huruf=false){
    if($nilai >= 25.00 && $nilai <=64.99):
      $a = "Tidak Baik";
      $b= "D";

      elseif($nilai >= 65 && $nilai <=76.60):
        $a = "Kurang Baik";
      $b= "C";

        elseif($nilai >= 76.61 && $nilai <=88.30):
          $a = "Baik";
      $b= "B";

    elseif($nilai >= 88.31 && $nilai <=100.30):
      $a = "Sangat Baik";
      $b= "A";
    else:
      $a = "-";
      $b= "-";
    endif;
    return $huruf ? $b : $a;
  }
}


if (!function_exists('sampel')) {
  function sampel($populasi = 0): float|int
  {
    return is_numeric($populasi) ? round(3.841 * $populasi * 0.25) : 0;
  }
}

function getPredikat($nilai)
{
  if ($nilai >= 3.5324) {
    return 'A';
  } elseif ($nilai >= 3.0644) {
    return 'B';
  } elseif ($nilai >= 2.6) {
    return 'C';
  } elseif ($nilai >= 1) {
    return 'D';
  } else {
    return '-';
  }
}

function getDataIKM9($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
{
  $query = Sisukma\V2\Models\Respon::query()
    ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
    ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
    ->select(
      'respons.*',
      'layanans.nama_layanan as nama_layanan',
      'skpds.nama_skpd as nama_skpd',
      'layanans.id as id_layanan',
    );
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

  // --- HITUNG TOTAL & SAMPEL ---
  $totalResponden = $query->count();

  if ($totalResponden == 0) {
    return ['message' => 'Tidak ada data responden untuk periode ini.'];
  }

  $sample = round(3.841 * $totalResponden * 0.25);

  // --- AMBIL DATA SAMPEL ACAK ---
  $sampleData = $query
    ->orderBy('u1', 'desc')
    ->orderBy('u2', 'desc')
    ->orderBy('u3', 'desc')
    ->orderBy('u4', 'desc')
    ->orderBy('u5', 'desc')
    ->orderBy('u6', 'desc')
    ->orderBy('u7', 'desc')
    ->orderBy('u8', 'desc')
    ->orderBy('u9', 'desc')
    ->take($sample)
    ->get();

  // --- HITUNG TOTAL & RATA-RATA PER UNSUR ---
  $totalPerUnsur = [];
  $rataRataPerUnsur = [];

  for ($i = 1; $i <= 9; $i++) {
    $totalPerUnsur["u$i"] = $sampleData->sum("u$i");
    $rataRataPerUnsur["u$i"] = round($totalPerUnsur["u$i"] / $sample, 3);
  }

  // --- HITUNG NILAI PER UNSUR (P1 - P9) ---


  // --- PREDIKAT & KONVERSI PER UNSUR ---
  $konversi = [];
  $predikat = [];

  foreach ($rataRataPerUnsur as $key => $nilai) {
    $konversi[$key] = round($nilai * 25, 2);
    $predikat[$key] = getPredikat($nilai);
  }

  // --- NILAI IKM ---
  $nilai_ikm = round(array_sum($rataRataPerUnsur) / count($rataRataPerUnsur), 3);
  $nilai_konversi = round($nilai_ikm * 25, 2);
  $predikat_mutu = getPredikat($nilai_ikm);

  // --- HASIL AKHIR ---
  return [
    'total_responden' => $totalResponden,
    'sample_diambil' => $sample,
    'total_perunsur' => $totalPerUnsur,
    'rata_rata_perunsur' => $rataRataPerUnsur,
    'p_unsur' => $rataRataPerUnsur,
    'konversi_unsur' => $konversi,
    'prediket_unsur' => $predikat,
    'nilai_ikm' => $nilai_ikm,
    'nilai_konversi' => $nilai_konversi,
    'prediket_mutu_layanan' => $predikat_mutu,
    'data_responden' => $sampleData->map(function ($item) {
      return [
        'id' => $item->id,
        'jenis_kelamin' => $item->jenis_kelamin,
        'pendidikan' => $item->pendidikan,
        'pekerjaan' => $item->pekerjaan,
        'jenis_disabilitas' => $item->jenis_disabilitas,
        'disabilitas' => $item->disabilitas,
        'nama_layanan' => $item->nama_layanan,
        'id_layanan' => $item->id_layanan,
        'nama_skpd' => $item->nama_skpd,
        'u1' => $item->u1,
        'u2' => $item->u2,
        'u3' => $item->u3,
        'u4' => $item->u4,
        'u5' => $item->u5,
        'u6' => $item->u6,
        'u7' => $item->u7,
        'u8' => $item->u8,
        'u9' => $item->u9,
       
      ];
    }),
  ];
}
function getDataIKM($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
{
  $query = Sisukma\V2\Models\Respon::query()
    ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
    ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
    ->select(
      'respons.*',
      'layanans.nama_layanan as nama_layanan',
      'skpds.nama_skpd as nama_skpd',
      'layanans.id as id_layanan',
    );
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

  // --- HITUNG TOTAL & SAMPEL ---
  $totalResponden = $query->count();

  if ($totalResponden == 0) {
    return ['message' => 'Tidak ada data responden untuk periode ini.'];
  }

  $sample = round(3.841 * $totalResponden * 0.25);

  // --- AMBIL DATA SAMPEL ACAK ---
  $sampleData = $query
    ->orderBy('u1', 'desc')
    ->orderBy('u2', 'desc')
    ->orderBy('u3', 'desc')
    ->orderBy('u4', 'desc')
    ->orderBy('u5', 'desc')
    ->orderBy('u6', 'desc')
    ->orderBy('u7', 'desc')
    ->orderBy('u8', 'desc')
    ->orderBy('u9', 'desc')
    ->orderBy('u10', 'desc')
    ->orderBy('u11', 'desc')
    ->orderBy('u12', 'desc')
    ->orderBy('u13', 'desc')
    ->orderBy('u14', 'desc')
    ->orderBy('u15', 'desc')
    ->orderBy('u16', 'desc')
    ->take($sample)
    ->get();

  // --- HITUNG TOTAL & RATA-RATA PER UNSUR ---
  $totalPerUnsur = [];
  $rataRataPerUnsur = [];

  for ($i = 1; $i <= 16; $i++) {
    $totalPerUnsur["u$i"] = $sampleData->sum("u$i");
    $rataRataPerUnsur["u$i"] = round($totalPerUnsur["u$i"] / $sample, 3);
  }

  // --- HITUNG NILAI PER UNSUR (P1 - P9) ---
  $p = [];
  $p['p1'] = round(($rataRataPerUnsur['u1'] + $rataRataPerUnsur['u2']) / 2, 3);
  $p['p2'] = round(($rataRataPerUnsur['u3'] + $rataRataPerUnsur['u4'] + $rataRataPerUnsur['u5']) / 3, 3);
  $p['p3'] = $rataRataPerUnsur['u6'];
  $p['p4'] = round(($rataRataPerUnsur['u7'] + $rataRataPerUnsur['u8'] + $rataRataPerUnsur['u9']) / 3, 3);
  $p['p5'] = $rataRataPerUnsur['u10'];
  $p['p6'] = $rataRataPerUnsur['u11'];
  $p['p7'] = round(($rataRataPerUnsur['u12'] + $rataRataPerUnsur['u13'] + $rataRataPerUnsur['u14']) / 3, 3);
  $p['p8'] = $rataRataPerUnsur['u15'];
  $p['p9'] = $rataRataPerUnsur['u16'];

  // --- PREDIKAT & KONVERSI PER UNSUR ---
  $konversi = [];
  $predikat = [];

  foreach ($p as $key => $nilai) {
    $konversi[$key] = round($nilai * 25, 2);
    $predikat[$key] = getPredikat($nilai);
  }

  // --- NILAI IKM ---
  $nilai_ikm = round(array_sum($p) / count($p), 3);
  $nilai_konversi = round($nilai_ikm * 25, 2);
  $predikat_mutu = getPredikat($nilai_ikm);

  // --- HASIL AKHIR ---
  return [
    'total_responden' => $totalResponden,
    'sample_diambil' => $sample,
    'total_perunsur' => $totalPerUnsur,
    'rata_rata_perunsur' => $rataRataPerUnsur,
    'p_unsur' => $p,
    'konversi_unsur' => $konversi,
    'prediket_unsur' => $predikat,
    'nilai_ikm' => $nilai_ikm,
    'nilai_konversi' => $nilai_konversi,
    'prediket_mutu_layanan' => $predikat_mutu,
    'data_responden' => $sampleData->map(function ($item) {
      return [
        'id' => $item->id,
        'jenis_kelamin' => $item->jenis_kelamin,
        'pendidikan' => $item->pendidikan,
        'pekerjaan' => $item->pekerjaan,
        'jenis_disabilitas' => $item->jenis_disabilitas,
        'disabilitas' => $item->disabilitas,
        'nama_layanan' => $item->nama_layanan,
        'id_layanan' => $item->id_layanan,
        'nama_skpd' => $item->nama_skpd,
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
      ];
    }),
  ];
}
function getDataUnsur($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
{
  $query = Sisukma\V2\Models\Respon::query()
    ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
    ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
    ->select(
      'respons.*',
      'layanans.nama_layanan as nama_layanan',
      'skpds.nama_skpd as nama_skpd',
      'layanans.id as id_layanan'
    );
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
      ->sortByDesc('u10')
      ->sortByDesc('u11')
      ->sortByDesc('u12')
      ->sortByDesc('u14')
      ->sortByDesc('u15')
      ->sortByDesc('u16')
      ->take($sample);

    // Hitung rata-rata per unsur
    $rataRataPerUnsur = [];
    for ($i = 1; $i <= 16; $i++) {
      $rataRataPerUnsur["u$i"] = round($sampleData->avg("u$i"), 3);
    }

    // Hitung P1 - P9
    $p = [];
    $p['p1'] = round(($rataRataPerUnsur['u1'] + $rataRataPerUnsur['u2']) / 2, 3);
    $p['p2'] = round(($rataRataPerUnsur['u3'] + $rataRataPerUnsur['u4'] + $rataRataPerUnsur['u5']) / 3, 3);
    $p['p3'] = $rataRataPerUnsur['u6'];
    $p['p4'] = round(($rataRataPerUnsur['u7'] + $rataRataPerUnsur['u8'] + $rataRataPerUnsur['u9']) / 3, 3);
    $p['p5'] = $rataRataPerUnsur['u10'];
    $p['p6'] = $rataRataPerUnsur['u11'];
    $p['p7'] = round(($rataRataPerUnsur['u12'] + $rataRataPerUnsur['u13'] + $rataRataPerUnsur['u14']) / 3, 3);
    $p['p8'] = $rataRataPerUnsur['u15'];
    $p['p9'] = $rataRataPerUnsur['u16'];

    // Konversi dan predikat per unsur
    $konversi = [];
    foreach ($p as $key => $nilai) {
      $konversi[$key] = round($nilai * 25, 2);
    }

    // Nilai rata-rata per layanan
    $nilai_ikm = round(array_sum($p) / count($p), 3);
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
    ];
  }

  // --- HITUNG NILAI PER UNSUR (AKUMULASI DARI SEMUA LAYANAN) ---
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

  // --- HASIL AKHIR ---
  return [
    'jumlah_layanan' => $totalLayanan,
    'per_layanan' => $hasilPerLayanan,
    'nilai_perunsur' => $nilaiPerUnsur,
    'predikat_perunsur' => $predikatPerUnsur,
    'nilai_ikm' => $nilai_ikm,
    'predikat_ikm' => $predikat_ikm,
  ];
}


function nama_skpd($id=null){
if($id){
  return Sisukma\V2\Models\Skpd::find($id)?->nama_skpd ?? null;
}
}
function getRingkasanIkm($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
{
  $query = Sisukma\V2\Models\Respon::query()
    ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
    ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
    ->select(
      'respons.*',
      'layanans.nama_layanan as nama_layanan',
      'skpds.nama_skpd as nama_skpd',
      'layanans.id as id_layanan',
    );

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

  // --- HITUNG TOTAL & SAMPEL ---
  $totalResponden = $query->count();

  if ($totalResponden == 0) {
    return ['message' => 'Tidak ada data responden untuk periode ini.'];
  }

  $sample = round(3.841 * $totalResponden * 0.25);

  // --- AMBIL DATA SAMPEL ACAK ---
  $sampleData = $query
    ->orderBy('u1', 'desc')
    ->orderBy('u2', 'desc')
    ->orderBy('u3', 'desc')
    ->orderBy('u4', 'desc')
    ->orderBy('u5', 'desc')
    ->orderBy('u6', 'desc')
    ->orderBy('u7', 'desc')
    ->orderBy('u8', 'desc')
    ->orderBy('u9', 'desc')
    ->orderBy('u10', 'desc')
    ->orderBy('u11', 'desc')
    ->orderBy('u12', 'desc')
    ->orderBy('u13', 'desc')
    ->orderBy('u14', 'desc')
    ->orderBy('u15', 'desc')
    ->orderBy('u16', 'desc')
    ->take($sample)->get();

  // --- HITUNG TOTAL & RATA-RATA PER UNSUR ---
  $totalPerUnsur = [];
  $rataRataPerUnsur = [];

  for ($i = 1; $i <= 16; $i++) {
    $totalPerUnsur["u$i"] = $sampleData->sum("u$i");
    $rataRataPerUnsur["u$i"] = round($totalPerUnsur["u$i"] / $sample, 3);
  }

  // --- HITUNG NILAI PER UNSUR (P1 - P9) ---
  $p = [];
  $p['p1'] = round(($rataRataPerUnsur['u1'] + $rataRataPerUnsur['u2']) / 2, 3);
  $p['p2'] = round(($rataRataPerUnsur['u3'] + $rataRataPerUnsur['u4'] + $rataRataPerUnsur['u5']) / 3, 3);
  $p['p3'] = $rataRataPerUnsur['u6'];
  $p['p4'] = round(($rataRataPerUnsur['u7'] + $rataRataPerUnsur['u8'] + $rataRataPerUnsur['u9']) / 3, 3);
  $p['p5'] = $rataRataPerUnsur['u10'];
  $p['p6'] = $rataRataPerUnsur['u11'];
  $p['p7'] = round(($rataRataPerUnsur['u12'] + $rataRataPerUnsur['u13'] + $rataRataPerUnsur['u14']) / 3, 3);
  $p['p8'] = $rataRataPerUnsur['u15'];
  $p['p9'] = $rataRataPerUnsur['u16'];

  // --- NILAI IKM ---
  $nilai_ikm = round(array_sum($p) / count($p), 3);
  $nilai_konversi = round($nilai_ikm * 25, 2);
  $predikat_mutu = getPredikat($nilai_ikm);

  // ===============================
  // 🔹 STATISTIK RESPONDEN
  // ===============================
  $getPersentase = function ($collection, $field) use ($sample) {
    return $collection->groupBy($field)->map(function ($group) use ($sample) {
      $count = $group->count();
      return [
        'jumlah' => $count,
        'persentase' => round(($count / $sample) * 100, 2),
      ];
    });
  };

  $statistik = [
    'jenis_kelamin' => $getPersentase($sampleData, 'jenis_kelamin'),
    'pendidikan' => $getPersentase($sampleData, 'pendidikan'),
    'pekerjaan' => $getPersentase($sampleData, 'pekerjaan'),
    'disabilitas' => $getPersentase($sampleData, 'disabilitas'),
    'jenis_disabilitas' => $getPersentase($sampleData, 'jenis_disabilitas'),
  ];

  // Jika ada kolom tanggal_lahir di tabel respons, hitung kategori umur
  if ($sampleData->first() && isset($sampleData->first()->usia)) {
    $umurKategori = $sampleData->map(function ($item) {
      $umur = $item->usia;
      if ($umur < 20)
        return 'Dibawah 20';
      elseif ($umur <= 30)
        return '21-30';
      elseif ($umur <= 40)
        return '31-40';
      elseif ($umur <= 50)
        return '41-50';
      else
        return '51+';
    });

    $statistik['umur'] = collect($umurKategori)->groupBy(fn($u) => $u)->map(fn($g) => [
      'jumlah' => count($g),
      'persentase' => round((count($g) / $sample) * 100, 2),
    ]);
  }

  // --- HASIL AKHIR ---
  return [
    'total_responden' => $totalResponden,
    'sample_diambil' => $sample,
    'nilai_ikm' => $nilai_ikm,
    'nilai_konversi' => $nilai_konversi,
    'predikat_mutu_layanan' => $predikat_mutu,
    'statistik_responden' => $statistik,
  ];
}


function getStatistikIkm($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
{
  // 🔹 Ambil semua data dalam satu query dasar
  $query = Sisukma\V2\Models\Respon::query()
    ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
    ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
    ->select(
      'respons.*',
      'layanans.nama_layanan as nama_layanan',
      'layanans.id as id_layanan',
      'skpds.id as skpd_id',
      'skpds.nama_skpd as nama_skpd'
    );

  if ($skpd) {
    $query->where('skpds.id', $skpd);
  }

  if ($id_layanan) {
    $query->where('layanans.id', $id_layanan);
  }

  // 🔹 Filter periode
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

  // 🔹 Jalankan query sekali saja
  $data = $query->get();

  if ($data->isEmpty()) {
    return ['message' => 'Tidak ada data responden untuk periode ini.'];
  }

  // 🔹 Kelompokkan berdasarkan SKPD
  $groupedBySkpd = $data->groupBy('skpd_id');

  $hasil = [];

  foreach ($groupedBySkpd as $skpdId => $records) {
    $namaSkpd = $records->first()->nama_skpd;
    $totalResponden = $records->count();
    $sample = round(3.841 * $totalResponden * 0.25);
    $sampleData = $records->sortByDesc('u1')
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

    // --- Hitung total dan rata-rata unsur
    $rataRata = [];
    for ($i = 1; $i <= 16; $i++) {
      $total = $sampleData->sum("u$i");
      $rataRata["u$i"] = $sample > 0 ? round($total / $sample, 3) : 0;
    }

    // --- Hitung nilai per unsur (P1–P9)
    $p = [];
    $p['p1'] = round(($rataRata['u1'] + $rataRata['u2']) / 2, 3);
    $p['p2'] = round(($rataRata['u3'] + $rataRata['u4'] + $rataRata['u5']) / 3, 3);
    $p['p3'] = $rataRata['u6'];
    $p['p4'] = round(($rataRata['u7'] + $rataRata['u8'] + $rataRata['u9']) / 3, 3);
    $p['p5'] = $rataRata['u10'];
    $p['p6'] = $rataRata['u11'];
    $p['p7'] = round(($rataRata['u12'] + $rataRata['u13'] + $rataRata['u14']) / 3, 3);
    $p['p8'] = $rataRata['u15'];
    $p['p9'] = $rataRata['u16'];

    $nilaiIkm = round(array_sum($p) / count($p), 3);
    $nilaiKonversi = round($nilaiIkm * 25, 2);
    $predikat = getPredikat($nilaiIkm);

    // --- Statistik responden
    $getPersentase = function ($collection, $field) use ($sample) {
      return $collection->groupBy($field)->map(function ($group) use ($sample) {
        $count = $group->count();
        return [
          'jumlah' => $count,
          'persentase' => $sample > 0 ? round(($count / $sample) * 100, 2) : 0,
        ];
      });
    };

    $statistik = [
      'jenis_kelamin' => $getPersentase($sampleData, 'jenis_kelamin'),
      'pendidikan' => $getPersentase($sampleData, 'pendidikan'),
      'pekerjaan' => $getPersentase($sampleData, 'pekerjaan'),
      'disabilitas' => $getPersentase($sampleData, 'disabilitas'),
      // 'jenis_disabilitas' => $getPersentase($sampleData, 'jenis_disabilitas'),
    ];

    if ($sampleData->first() && isset($sampleData->first()->usia)) {
      $umurKategori = $sampleData->map(function ($item) {
        $umur = $item->usia;
        if ($umur < 20)
          return 'Dibawah 20';
        elseif ($umur <= 30)
          return '21-30';
        elseif ($umur <= 40)
          return '31-40';
        elseif ($umur <= 50)
          return '41-50';
        else
          return '51+';
      });

      $statistik['umur'] = collect($umurKategori)->groupBy(fn($u) => $u)->map(fn($g) => [
        'jumlah' => count($g),
        'persentase' => $sample > 0 ? round((count($g) / $sample) * 100, 2) : 0,
      ]);
    }

    $hasil[] = [
      'skpd_id' => $skpdId,
      'nama_skpd' => $namaSkpd,
      'total_responden' => $totalResponden,
      'sample_diambil' => $sample,
      'nilai_ikm' => $nilaiIkm,
      'nilai_konversi' => $nilaiKonversi,
      'predikat_mutu_layanan' => $predikat,
      'statistik_responden' => $statistik,
    ];
  }

  // 🔹 Jika SKPD tertentu, ambil hanya 1
  if ($skpd) {
    return $hasil[0] ?? ['message' => 'Data SKPD tidak ditemukan.'];
  }

  // 🔹 Jika tidak, kembalikan semua SKPD
  return $hasil;
}







function getBulanPeriode(string $periode, $nomor, string $posisi = 'first'): ?int
{
  $range = match (strtolower($periode)) {
    'triwulan' => match ($nomor) {
        1 => range(1, 3),
        2 => range(4, 6),
        3 => range(7, 9),
        4 => range(10, 12),
        default => [],
      },
    'semester' => match ($nomor) {
        1 => range(1, 6),
        2 => range(7, 12),
        default => [],
      },
    'bulan' => [$nomor],
    default => [],
  };

  if (empty($range))
    return null;

  return match ($posisi) {
    'first' => $range[0],
    'middle' => $range[intdiv(count($range), 2)],
    'last' => end($range),
    default => $range[0],
  };
}

function getNamaPeriode($jenis_periode, $periode, $tahun)
{
  $bulanNama = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
  ];

  $periode = (int) $periode;

  switch ($jenis_periode) {
    case 'bulan':
      $nama = ($bulanNama[$periode] ?? 'Bulan Tidak Diketahui') . " {$tahun}";
      break;

    case 'triwulan':
      if ($periode >= 1 && $periode <= 3) {
        $nama = "Triwulan I {$tahun}";
      } elseif ($periode >= 4 && $periode <= 6) {
        $nama = "Triwulan II {$tahun}";
      } elseif ($periode >= 7 && $periode <= 9) {
        $nama = "Triwulan III {$tahun}";
      } elseif ($periode >= 10 && $periode <= 12) {
        $nama = "Triwulan IV {$tahun}";
      } else {
        $nama = "Triwulan Tidak Diketahui";
      }
      break;

    case 'semester':
      if ($periode >= 1 && $periode <= 6) {
        $nama = "Semester I {$tahun}";
      } elseif ($periode >= 7 && $periode <= 12) {
        $nama = "Semester II {$tahun}";
      } else {
        $nama = "Semester Tidak Diketahui";
      }
      break;

    default:
      $nama = "Tahun {$tahun}";
      break;
  }

  return $nama;
}


function getDataUnsur9($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
{
  $query = Sisukma\V2\Models\Respon::query()
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



function getStatistik($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
{
  $query = Sisukma\V2\Models\Respon::query()
    ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
    ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
    ->select(
      'respons.*',
      'layanans.nama_layanan as nama_layanan',
      'layanans.id as id_layanan',
      'skpds.id as skpd_id',
      'skpds.nama_skpd as nama_skpd'
    );

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
    $hasil[] = hitungStatistikPerKumpulan($records, $skpdId, $records->first()->nama_skpd);
  }

  // 🔹 Tambahkan total keseluruhan jika tidak difilter SKPD
  if (is_null($skpd)) {
    $totalAll = hitungStatistikPerKumpulan($data, null, 'Total Keseluruhan');
    $hasil[] = array_merge(['skpd_id' => null, 'nama_skpd' => 'Total Semua SKPD'], $totalAll);
  } else {
    return $hasil[0] ?? ['message' => 'Data SKPD tidak ditemukan.'];
  }

  return $hasil;
}

function getStatistik9($skpd = null, $id_layanan = null, $periode = 'tahun', $tahun = null, $bulan = null)
{
  $query = Sisukma\V2\Models\Respon::query()
    ->join('layanans', 'respons.layanan_id', '=', 'layanans.id')
    ->join('skpds', 'layanans.skpd_id', '=', 'skpds.id')
    ->select(
      'respons.*',
      'layanans.nama_layanan as nama_layanan',
      'layanans.id as id_layanan',
      'skpds.id as skpd_id',
      'skpds.nama_skpd as nama_skpd'
    );

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
    $hasil[] = hitungStatistikPerKumpulan9($records, $skpdId, $records->first()->nama_skpd);
  }

  // 🔹 Tambahkan total keseluruhan jika tidak difilter SKPD
  if (is_null($skpd)) {
    $totalAll = hitungStatistikPerKumpulan9($data, null, 'Total Keseluruhan');
    $hasil[] = array_merge(['skpd_id' => null, 'nama_skpd' => 'Total Semua SKPD'], $totalAll);
  } else {
    return $hasil[0] ?? ['message' => 'Data SKPD tidak ditemukan.'];
  }

  return $hasil;
}

function hitungStatistikPerKumpulan($records, $skpdId = null, $namaSkpd = null)
{
  $groupedByLayanan = $records->groupBy('id_layanan');

  $totalResponden = 0;
  $totalSample = 0;
  $allSampled = collect();
  $sumNilaiP = array_fill(1, 9, 0); // total konversi antar layanan

  foreach ($groupedByLayanan as $layananId => $recordsLayanan) {
    $totalResponden += $recordsLayanan->count();

    // ambil sample per layanan
    $sample = round(3.841 * $recordsLayanan->count() * 0.25);
    $totalSample += $sample;

    // urutkan agar sampling seragam seperti getDataUnsur()
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

    if ($sampleData->isEmpty())
      continue;
    $allSampled = $allSampled->merge($sampleData);
    // Rata-rata tiap unsur U1–U16
    $rataRataPerUnsur = [];
    for ($i = 1; $i <= 16; $i++) {
      $rataRataPerUnsur["u$i"] = round($sampleData->avg("u$i"), 3);
    }

    // Hitung P1–P9 seperti getDataUnsur()
    $p = [];
    $p['p1'] = round(($rataRataPerUnsur['u1'] + $rataRataPerUnsur['u2']) / 2, 3);
    $p['p2'] = round(($rataRataPerUnsur['u3'] + $rataRataPerUnsur['u4'] + $rataRataPerUnsur['u5']) / 3, 3);
    $p['p3'] = $rataRataPerUnsur['u6'];
    $p['p4'] = round(($rataRataPerUnsur['u7'] + $rataRataPerUnsur['u8'] + $rataRataPerUnsur['u9']) / 3, 3);
    $p['p5'] = $rataRataPerUnsur['u10'];
    $p['p6'] = $rataRataPerUnsur['u11'];
    $p['p7'] = round(($rataRataPerUnsur['u12'] + $rataRataPerUnsur['u13'] + $rataRataPerUnsur['u14']) / 3, 3);
    $p['p8'] = $rataRataPerUnsur['u15'];
    $p['p9'] = $rataRataPerUnsur['u16'];

    // Nilai konversi per unsur
    $konversi = [];
    foreach ($p as $key => $nilai) {
      $konversi[$key] = round($nilai * 25, 2);
    }

    // Agregasi total antar layanan
    foreach ($konversi as $key => $val) {
      $index = intval(substr($key, 1));
      $sumNilaiP[$index] += $val;
    }
  }

  // Hitung rata-rata antar layanan
  $jumlahLayanan = count($groupedByLayanan);
  if ($jumlahLayanan > 0) {
    foreach ($sumNilaiP as $i => $total) {
      $sumNilaiP[$i] = round($total / $jumlahLayanan, 2);
    }
  }

  // Nilai akhir seperti getDataUnsur()
  $nilaiIkm = round(array_sum($sumNilaiP) / count($sumNilaiP), 2);
  $predikat = getPredikat($nilaiIkm / 25);

  // 🔹 Statistik tambahan responden
  $statistik = [];

  // Jenis Kelamin
  $statistik['jenis_kelamin'] = $allSampled->groupBy('jenis_kelamin')
    ->map(fn($g) => $g->count())
    ->toArray();

  // Pendidikan
  $pendidikanOptions = ['Non Pendidikan', 'SD', 'SMP', 'SMA', 'DIII', 'S1', 'S2', 'S3'];
  $groupPendidikan = $allSampled->groupBy('pendidikan')->map->count();
  $statistik['pendidikan'] = collect($pendidikanOptions)->mapWithKeys(function ($opt) use ($groupPendidikan) {
    return [$opt => $groupPendidikan[$opt] ?? 0];
  })->toArray();

  // Pekerjaan
  $statistik['pekerjaan'] = $allSampled->groupBy('pekerjaan')
    ->map(fn($g) => $g->count())
    ->toArray();

  // Disabilitas — bedakan non dan jenis disabilitas
  $nonDisabilitasCount = $allSampled->where('disabilitas', 'non_disabilitas')->count();
  $disabilitasJenis = [
    'Fisik' => $allSampled->where('jenis_disabilitas', 'Disabilitas Fisik')->count(),
    'Mental' => $allSampled->where('jenis_disabilitas', 'Disabilitas Mental')->count(),
    'Intelektual' => $allSampled->where('jenis_disabilitas', 'Disabilitas Intelektual')->count(),
    'Sensorik' => $allSampled->where('jenis_disabilitas', 'Disabilitas Sensorik')->count(),
  ];
  $statistik['disabilitas'] = [
    'non_disabilitas' => ['label' => 'Non Disabilitas', 'jumlah' => $nonDisabilitasCount, 'satuan' => 'Orang'],
    'disabilitas' => collect($disabilitasJenis)->map(fn($jumlah, $label) => [
      'label' => $label,
      'jumlah' => $jumlah,
      'satuan' => 'Orang'
    ])->values()->toArray(),
  ];

  // Usia
  $statistik['usia'] = [
    '≤20' => $allSampled->where('usia', '<=', 20)->count(),
    '21–30' => $allSampled->whereBetween('usia', [21, 30])->count(),
    '31–40' => $allSampled->whereBetween('usia', [31, 40])->count(),
    '41–50' => $allSampled->whereBetween('usia', [41, 50])->count(),
    '>50' => $allSampled->where('usia', '>', 50)->count(),
  ];
  return [
    'skpd_id' => $skpdId,
    'nama_skpd' => $namaSkpd,
    'total_responden' => $totalResponden,
    'sample_diambil' => $totalSample,
    'nilai_ikm' => round($nilaiIkm / 25, 3), // normalisasi agar sama (0–4)
    'nilai_konversi' => $nilaiIkm,
    'predikat_mutu_layanan' => $predikat,
    'statistik_responden' => $statistik,
  ];
}

function hitungStatistikPerKumpulan9($records, $skpdId = null, $namaSkpd = null)
{
  $groupedByLayanan = $records->groupBy('id_layanan');

  $totalResponden = 0;
  $totalSample = 0;
  $allSampled = collect();
  $sumNilaiP = array_fill(1, 9, 0); // total konversi antar layanan

  foreach ($groupedByLayanan as $layananId => $recordsLayanan) {
    $totalResponden += $recordsLayanan->count();

    // ambil sample per layanan
    $sample = round(3.841 * $recordsLayanan->count() * 0.25);
    $totalSample += $sample;

    // urutkan agar sampling seragam seperti getDataUnsur()
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

    if ($sampleData->isEmpty())
      continue;
    $allSampled = $allSampled->merge($sampleData);
    // Rata-rata tiap unsur U1–U16
    $rataRataPerUnsur = [];
    for ($i = 1; $i <= 9; $i++) {
      $rataRataPerUnsur["u$i"] = round($sampleData->avg("u$i"), 3);
    }

    // Hitung P1–P9 seperti getDataUnsur()
   

    // Nilai konversi per unsur
    $konversi = [];
    foreach ($rataRataPerUnsur as $key => $nilai) {
      $konversi[$key] = round($nilai * 25, 2);
    }

    // Agregasi total antar layanan
    foreach ($konversi as $key => $val) {
      $index = intval(substr($key, 1));
      $sumNilaiP[$index] += $val;
    }
  }

  // Hitung rata-rata antar layanan
  $jumlahLayanan = count($groupedByLayanan);
  if ($jumlahLayanan > 0) {
    foreach ($sumNilaiP as $i => $total) {
      $sumNilaiP[$i] = round($total / $jumlahLayanan, 2);
    }
  }

  // Nilai akhir seperti getDataUnsur()
  $nilaiIkm = round(array_sum($sumNilaiP) / count($sumNilaiP), 2);
  $predikat = getPredikat($nilaiIkm / 25);

  // 🔹 Statistik tambahan responden
  $statistik = [];

  // Jenis Kelamin
  $statistik['jenis_kelamin'] = $allSampled->groupBy('jenis_kelamin')
    ->map(fn($g) => $g->count())
    ->toArray();

  // Pendidikan
  $pendidikanOptions = ['Non Pendidikan', 'SD', 'SMP', 'SMA', 'DIII', 'S1', 'S2', 'S3'];
  $groupPendidikan = $allSampled->groupBy('pendidikan')->map->count();
  $statistik['pendidikan'] = collect($pendidikanOptions)->mapWithKeys(function ($opt) use ($groupPendidikan) {
    return [$opt => $groupPendidikan[$opt] ?? 0];
  })->toArray();

  // Pekerjaan
  $statistik['pekerjaan'] = $allSampled->groupBy('pekerjaan')
    ->map(fn($g) => $g->count())
    ->toArray();

  // Disabilitas — bedakan non dan jenis disabilitas
  $nonDisabilitasCount = $allSampled->where('disabilitas', 'non_disabilitas')->count();
  $disabilitasJenis = [
    'Fisik' => $allSampled->where('jenis_disabilitas', 'Disabilitas Fisik')->count(),
    'Mental' => $allSampled->where('jenis_disabilitas', 'Disabilitas Mental')->count(),
    'Intelektual' => $allSampled->where('jenis_disabilitas', 'Disabilitas Intelektual')->count(),
    'Sensorik' => $allSampled->where('jenis_disabilitas', 'Disabilitas Sensorik')->count(),
  ];
  $statistik['disabilitas'] = [
    'non_disabilitas' => ['label' => 'Non Disabilitas', 'jumlah' => $nonDisabilitasCount, 'satuan' => 'Orang'],
    'disabilitas' => collect($disabilitasJenis)->map(fn($jumlah, $label) => [
      'label' => $label,
      'jumlah' => $jumlah,
      'satuan' => 'Orang'
    ])->values()->toArray(),
  ];

  // Usia
  $statistik['usia'] = [
    '≤20' => $allSampled->where('usia', '<=', 20)->count(),
    '21–30' => $allSampled->whereBetween('usia', [21, 30])->count(),
    '31–40' => $allSampled->whereBetween('usia', [31, 40])->count(),
    '41–50' => $allSampled->whereBetween('usia', [41, 50])->count(),
    '>50' => $allSampled->where('usia', '>', 50)->count(),
  ];
  return [
    'skpd_id' => $skpdId,
    'nama_skpd' => $namaSkpd,
    'total_responden' => $totalResponden,
    'sample_diambil' => $totalSample,
    'nilai_ikm' => round($nilaiIkm / 25, 3), // normalisasi agar sama (0–4)
    'nilai_konversi' => $nilaiIkm,
    'predikat_mutu_layanan' => $predikat,
    'statistik_responden' => $statistik,
  ];
}