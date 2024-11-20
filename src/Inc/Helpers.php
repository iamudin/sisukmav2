<?php
if (!function_exists('sample')) {
    function get_sample(int $populasi){
        $num1 = 3.841 * $populasi * 0.25;
        $num2 = ($populasi - 1) * 0.0025 + 3.841*0.25;
        return round($num1 / $num2);


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
