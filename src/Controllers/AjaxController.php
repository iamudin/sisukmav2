<?php

namespace Sisukma\V2\Controllers;

use Sisukma\V2\Models\Skpd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sisukma\V2\Contracts\IkmManager;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AjaxController extends Controller  implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }
    public function detailikm(Request $request){
        $data = json_decode(base64_decode($request->data_ikm));
        $skpd = $request->skpd;
        $skpd_id = $request->skpd_id;
        $periode = $request->periode;
        $alamat = $request->alamat;
        $telp = $request->telp;
        $type_unsur = $request->type_unsur;
        return view('sisukma::dashboard.ajax.detail_ikm',compact('skpd_id','data','skpd','periode','alamat','telp','type_unsur'))->render();
    }

    public function cetak_rekap_kabupaten(Request $request){

    }
   public function cetak_rekap_skpd(Request $request){
    $dskpd = Skpd::find($request->skpd_id);
    $skpd = $dskpd->nama_skpd;
    $skpd_id = $request->skpd_id;
    $periode = (new IkmManager)->as_periode($request->from,$request->to).' '.$request->year;
    $alamat = $dskpd->alamat;
    $telp = $dskpd->telp;
    $type_unsur = $request->unsur_tambahan ?? 9;
    $data = json_decode(json_encode((new IkmManager)->nilai_ikm_skpd($request->skpd_id,$request->unsur_tambahan ?? 9)));

    $pdf = PDF::loadView('sisukma::report.pengolahan-data',compact('skpd_id','data','skpd','periode','alamat','telp','type_unsur'));
    return $pdf->download('pengolahan-data-'.str($skpd.' '.$periode)->slug().'.pdf');
    }
    public function dashboard(Request $request){
        if($request->user()->isAdmin()){
            $data = (new IkmManager)->nilai_ikm_kabupaten($request->unsur_tambahan);
            $type_unsur = $request->unsur_tambahan;
            return view('sisukma::dashboard.ajax.admin',compact('data','type_unsur'))->render();

        }else{
            $data = json_decode(json_encode((new IkmManager)->nilai_ikm_skpd($request->user()->skpd->id,$request->unsur_tambahan)));
            return view('sisukma::dashboard.ajax.skpd',compact('data'))->render();

        }
    }
}
