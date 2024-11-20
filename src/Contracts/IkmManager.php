<?php
namespace Sisukma\V2\Contracts;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Sisukma\V2\Models\Skpd;
use Sisukma\V2\Models\Respon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class IkmManager
{

    function diffmonth(\DateTime $date1, \DateTime $date2)
    {
        $diff = $date1->diff($date2);
        $months = $diff->y * 12 + $diff->m + $diff->d / 30;
        return (int) round($months);
    }
    function numtomonth($a){
        return strlen($a) > 1 ? $a: '0'.$a;
      }
      function as_periode($from,$to){
        if($from == '01' && $to == '03'):
           $periode = 'Triwulan I';
        elseif($from == '04' && $to == '06'):
           $periode = 'Triwulan II';
           elseif($from == '07' && $to == '09'):
           $periode = 'Triwulan III';
               elseif($from == '10' && $to == '12'):
           $periode = 'Triwulan IV';
               elseif($from == '01' && $to == '06'):
           $periode = 'Semester I';
           elseif($from == '07' && $to == '12'):
           $periode = 'Semester II';
            else:
            $periode = blnindo(request()->month);
        endif;
               return $periode;
       }
    function get_response_of_range($skpd,$year,$from_month,$to_month,$unsur_type=9){
        $response['detail']['respon'] = array();
        $response['detail']['sample_total'] = 0;
        $response['sample'] = collect();
        $data  = Respon::withWhereHas('layanan', function($q) use ($skpd) {
            $q->where('skpd_id', $skpd);
        })
        ->whereRaw('YEAR(tgl_survei) = ?', [$year])
        ->whereBetween(DB::raw('MONTH(tgl_survei)'), [$from_month, $to_month])
        ->get();

        for ($a = $from_month; $a <= $to_month; $a++) {
            $filteredData = $data->filter(function($item) use ($a) {
            $month = $item->tgl_survei->format('n');
            return $month == $this->numtomonth($a);
});
$sortedData = $filteredData->sortByDesc(function($item)use($unsur_type) {
    if($unsur_type==11){
        return [
            $item->u1,
            $item->u2,
            $item->u3,
            $item->u4,
            $item->u5,
            $item->u6,
            $item->u7,
            $item->u8,
            $item->u9,
            $item->u10,
            $item->u11,
        ];
    }else{
        return [
            $item->u1,
            $item->u2,
            $item->u3,
            $item->u4,
            $item->u5,
            $item->u6,
            $item->u7,
            $item->u8,
            $item->u9
        ];
    }

});

            $real_populasi = $sortedData;
            $sample_populasi = $real_populasi->take(get_sample(count($real_populasi)));
           $response['detail']['respon'][] = ['month'=>$this->numtomonth($a),'real'=>count($real_populasi),'sample'=>count($sample_populasi)];
           $response['detail']['sample_total'] +=count($sample_populasi);
           $response['sample'] = $response['sample']->merge($sample_populasi);
        }
return $response;
 }
 function rekapitulasi_unsur_ikm($id){
    return $this->get_response_periode($id);
 }
 function usiarange($data){
    $range = array(
        ['type'=>'1','range'=>'17 - 23','jumlah'=>count($data->where('usia','>=',17)->where('usia','<=',23))],
        ['type'=>'2','range'=>'24 - 29','jumlah'=>count($data->where('usia','>=',24)->where('usia','<=',29))],
        ['type'=>'3','range'=>'30 - 40','jumlah'=>count($data->where('usia','>=',30)->where('usia','<=',40))],
        ['type'=>'4','range'=>'Diatas 40','jumlah'=>count($data->where('usia','>',40))],
    );
    return $range;
}
function usiarangenull(){
$range = array(
    ['type'=>'1','range'=>'17 - 23','jumlah'=>0],
    ['type'=>'2','range'=>'24 - 29','jumlah'=>0],
    ['type'=>'3','range'=>'30 - 40','jumlah'=>0],
    ['type'=>'4','range'=>'Diatas 40','jumlah'=>0],
);
return $range;
}
function pekerjaan($data){
foreach(['TNI','POLRI','PNS','SWASTA','WIRAUSAHA','Lainnya'] as $r):
    $arr[Str::lower(str_replace(' ','_',$r))] = count($data->where('pekerjaan',$r));
endforeach;
return $arr;
}
function pendidikan($data){
foreach(['SMA','Non Pendidikan','SD','SMP','DIII','S1','S2','S3'] as $r):
    $arr[Str::lower(str_replace(' ','_',$r))] = count($data->where('pendidikan',$r));
    endforeach;
    return $arr;
}

 function responden($resp){
    if(count($resp)>0){
        $sample = count($resp);
        $data = $resp;

        $arr['jumlah'] = $sample;
        $arr['l'] = count($data->where('jenis_kelamin','L'));
        $arr['p'] = count($data->where('jenis_kelamin','P'));
        $arr['usia'] = $this->usiarange($data);
        $arr['pekerjaan'] = $this->pekerjaan($data);
        $arr['pendidikan'] = $this->pendidikan($data);
        $arr['saran'] = $data->map->only(['id','tgl_survei','layanan','saran','pekerjaan','pekerjaan2','jenis_kelamin','pendidikan','jam_survei']);
    }else{
        $arr['jumlah'] =0;
        $arr['l'] = 0;
        $arr['p'] = 0;
        $arr['usia'] = $this->usiarangenull();
        $arr['pekerjaan'] = array();
        $arr['pendidikan'] = array();
        $arr['saran'] = array();
    }
    return $arr;
}

public function get_periode_name()
{

    $year = request()->year ?? date('Y');
    //ambil response tahunan
    if ($year && !request()->month && !request()->from && !request()->to && !request()->date) {
        View::share('periode', $year );
    }

    //ambil response bulanan
    if ($year && request()->month && !request()->from && !request()->to) {
        $month = intval(substr(request('month'), 0, 1) == '0' ? substr(request('month'), 1, 2) : request('month'));
            View::share('periode', blnindo(request()->month) . ' ' . request()->year);
        }

    //ambil response triwulan dan semester
    if ($year && request()->from && request()->to) {
        $from = intval(substr(request('from'), 0, 1) == '0' ? substr(request('from'), 1, 2) : request('from'));
        $to = intval(substr(request('to'), 0, 1) == '0' ? substr(request('to'), 1, 2) : request('to'));
        View::share('periode', $this->as_periode(request()->from,request()->to).' '.request()->year);
    }

}
    public function get_response_periode($id_skpd,$unsur=false)
    {

        $year = request()->year ?? date('Y');
        //ambil response tahunan
        if ($year && !request()->month && !request()->from && !request()->to && !request()->date) {
            $data = $this->get_response_of_range($id_skpd,$year,1,12,$unsur);
            View::share('periode', $year );
        }

        //ambil response bulanan
        if ($year && request()->month && !request()->from && !request()->to) {
            $month = intval(substr(request('month'), 0, 1) == '0' ? substr(request('month'), 1, 2) : request('month'));
            $data = $this->get_response_of_range($id_skpd,$year,$month,$month,$unsur);
                View::share('periode', blnindo(request()->month) . ' ' . request()->year);
            }

        //ambil response triwulan dan semester
        if ($year && request()->from && request()->to) {
            $from = intval(substr(request('from'), 0, 1) == '0' ? substr(request('from'), 1, 2) : request('from'));
            $to = intval(substr(request('to'), 0, 1) == '0' ? substr(request('to'), 1, 2) : request('to'));
            $data = $this->get_response_of_range($id_skpd,$year,$from,$to,$unsur);
            View::share('periode', $this->as_periode(request()->from,request()->to).' '.request()->year);
        }

        return $data;
    }
    public function nilai_ikm_skpd($id, $unsur_tambahan)
    {
        $unsur = $unsur_tambahan==11 ? array('u1', 'u2', 'u3', 'u4', 'u5', 'u6', 'u7', 'u8', 'u9', 'u10', 'u11') : array('u1', 'u2', 'u3', 'u4', 'u5', 'u6', 'u7', 'u8', 'u9');
        foreach ($unsur as $r) {
            $u[$r] = 0;
        }
        $respon = $this->get_response_periode($id,$unsur_tambahan);
        $sample = count($respon['sample']);
        $responden = $respon['sample'];

        if ($sample){
            foreach ($responden as $row) {
                foreach ($unsur as $r) {
                    $u[$r] += $row->$r;
                }
            }
            $data = $this->responden($responden);
            foreach ($unsur as $r) {
                $totalunsur[] = ($u[$r] / $sample) * (1 / count($unsur));
                $data[$r] = $u[$r] / $sample;
            }
            $data['ikm'] = array_sum($totalunsur) * 25;
            $data['detail'] = $respon['detail'];
            $data['responden'] = $responden;

         }
         else{
            $data = $this->responden($responden);
            foreach ($unsur as $r) {
                $data[$r] = 0;
            }
            $data['ikm'] = 0;
            $data['responden'] = [];
            $data['detail'] = ['respon'=>[],'sample_total'=>0];
        }
        return $data;
    }
//     function nilai_unsur_rekap($unsur_tambahan){
//         $skpd = Skpd::whereHas('periode_aktif', function ($q) use ($unsur_tambahan) {
//             $q->where('tahun', request()->year ?? date('Y'));
//         });
//         if ($unsur_tambahan==11) {
//             $skpd = $skpd->where('total_unsur',$unsur_tambahan); // Pastikan metode ini ada di dalam model
//         }
//         $skpd = $skpd->get();
//         $unsur = $unsur_tambahan==11 ? array('u1', 'u2', 'u3', 'u4', 'u5', 'u6', 'u7', 'u8', 'u9', 'u10', 'u11') : array('u1', 'u2', 'u3', 'u4', 'u5', 'u6', 'u7', 'u8', 'u9');
//         $data['unsur'] = array();
//         $data['data'] = array();
//         if(count($skpd)){

//         foreach($skpd as $r){
//             $nilai = $this->nilai_ikm_skpd($r->id,$unsur_tambahan);
//             $a['nama_skpd']= $r->nama_skpd;
//             $a['ikm'] = $nilai['ikm'];

//             foreach($unsur as $u){
//                 $a[$u] = $nilai[$u];
//             }
//             array_push($data['data'],$a);
//         }
//         foreach($unsur as $u){
//             $data['unsur'][$u] = array_sum(array_column($data['data'],$u)) / count($skpd);
//         }
//         // $data['ikm_unsur'] = $a;
//         $data['unsur']['total_ikm'] = array_sum(array_column($data['data'],'ikm')) / count($skpd) ;
//         return $data;
//     }
// return [];
//     }
    function pekerjaan_arr(){
        foreach(['TNI','POLRI','PNS','SWASTA','WIRAUSAHA','Lainnya'] as $r):
            $arr[Str::lower(str_replace(' ','_',$r))] = 0;
        endforeach;
        return $arr;
    }
    function nilai_ikm_kabupaten($unsur_tambahan){
        $skpd = Skpd::whereHas('periode_aktif', function ($q) {
            $q->where('tahun', request()->year ?? date('Y'));
        });
        if ($unsur_tambahan==11) {
            $skpd = $skpd->where('total_unsur',$unsur_tambahan); // Pastikan metode ini ada di dalam model
        }
        $skpd = $skpd->get();
        $l['jumlah'] = 0;
        $l['l'] = 0;
        $l['p'] = 0;
        $l['sma'] = 0;
        $l['non_pendidikan'] = 0;
        $l['sd'] = 0;
        $l['smp'] = 0;
        $l['diii'] = 0;
        $l['s1'] = 0;
        $l['s2'] = 0;
        $l['s3'] = 0;
        $l['ikm'] = 0;
        $l['data_ikm_skpd'] = array();
        $d['type1'] =0;
        $d['type2'] = 0;
        $d['type3'] = 0;
        $d['type4'] = 0;
      //  $detailsample = [];
        $l['pekerjaan'] = $this->pekerjaan_arr();
        if(count($skpd)){
            $l['total_skpd'] = count($skpd);
        foreach($skpd as $r){
                $cek = $this->nilai_ikm_skpd($r->id,$unsur_tambahan);
                $l['data_ikm_skpd'][] = ['data'=>$cek,'nama_skpd'=>$r->nama_skpd,'skpd_id'=>$r->id,'alamat_skpd'=>$r->alamat,'telp'=>$r->telp];
                $l['jumlah'] += $cek['jumlah'];
                $l['l'] += $cek['l'];
                $l['p']  += $cek['p'];
                foreach(['SMA','Non Pendidikan','SD','SMP','DIII','S1','S2','S3'] as $r):
                    $l[Str::lower(str_replace(' ','_',$r))] += $cek['pendidikan'][Str::lower(str_replace(' ','_',$r))] ?? 0;
                endforeach;
                foreach(['TNI','POLRI','PNS','SWASTA','WIRAUSAHA','Lainnya'] as $r):
                    $l['pekerjaan'][Str::lower(str_replace(' ','_',$r))] += $cek['pekerjaan'][Str::lower(str_replace(' ','_',$r))] ?? 0;
                endforeach;

                foreach($cek['usia'] as $k=>$u){
                    $d['type'.($k+1)]  += $u['jumlah'];
                  }

                $l['ikm'] += $cek['ikm'];

               // $detailsample[] = ['respon'=>$cek['detail']['respon'],'sample_total'=>$cek['detail']['sample_total']];
        }
        $l['usia'] = $this->usia_arr([1=>$d['type1'],$d['type2'],$d['type3'],$d['type4']]);
      /*  $result = [
            'respon' => [],
            'sample_total' => 0
        ];

        // Iterasi melalui setiap elemen dari data
        foreach ($detailsample as $item) {
            // Tambahkan sample_total
            $result['sample_total'] += $item['sample_total'];

            // Iterasi melalui respon
            foreach ($item['respon'] as $respon) {
                $month = $respon['month'];
                // Jika bulan sudah ada di hasil, tambahkan nilainya
                if (!isset($result['respon'][$month])) {
                    $result['respon'][$month] = [
                        'month' => $month,
                        'real' => $respon['real'],
                        'sample' => $respon['sample'],
                    ];
                } else {
                    $result['respon'][$month]['real'] += $respon['real'];
                    $result['respon'][$month]['sample'] += $respon['sample'];
                }
            }
        }

        // Mengubah hasil menjadi array numerik
        $result['respon'] = array_values($result['respon']);

        $l['detail'] = $result['respon'];
        */
        $l['ikm'] = $l['ikm'] / count($skpd);
        return json_decode(json_encode($l));
    }
    return [];
       }
       function usia_arr($data){
        $range = array(
            ['type'=>'1','range'=>'17 - 23','jumlah'=>$data[1]],
            ['type'=>'2','range'=>'24 - 29','jumlah'=>$data[2]],
            ['type'=>'3','range'=>'30 - 40','jumlah'=>$data[3]],
            ['type'=>'4','range'=>'Diatas 40','jumlah'=>$data[4]],
        );
        return $range;
    }
}
