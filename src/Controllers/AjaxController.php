<?php

namespace Sisukma\V2\Controllers;

use Sisukma\V2\Models\Skpd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sisukma\V2\Contracts\IkmManager;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AjaxController extends Controller
{

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


   public function cetak_rekap_skpd(Request $request){
    $dskpd = Skpd::find($request->skpd_id) ?? null;
    $skpd = $dskpd->nama_skpd ?? null;
    $skpd_id = $request->skpd_id?? null;
    $periode = (new IkmManager)->as_periode($request->from,$request->to).' '.($request->year ?? date('Y'));
    $alamat = $dskpd->alamat?? null;
    $telp = $dskpd->telp?? null;
    $type_unsur = $request->unsur_tambahan ?? 9;
    $ikm = false;
    if($request->type=='ikm'){
        $data = json_decode(json_encode((new IkmManager)->nilai_ikm_skpd($request->skpd_id,$request->unsur_tambahan ?? 9)));
        $ikm = true;
        $pdf = PDF::loadView('sisukma::report.ikm_skpd',compact('ikm','skpd_id','data','skpd','periode','alamat','telp','type_unsur'));
        return $pdf->download('ikm-'.str($skpd.' '.$periode)->slug().'.pdf');

    }elseif($request->type=='pengolahan'){
    $data = json_decode(json_encode((new IkmManager)->nilai_ikm_skpd($request->skpd_id,$request->unsur_tambahan ?? 9)));
    $pdf = PDF::loadView('sisukma::report.pengolahan-data',compact('ikm','skpd_id','data','skpd','periode','alamat','telp','type_unsur'));
    return $pdf->download('pengolahan-data-'.str($skpd.' '.$periode)->slug().'.pdf');
}
else{
    $data = json_decode(json_encode((new IkmManager)->nilai_ikm_kabupaten($request->unsur_tambahan ?? 9)));
    $pdf = PDF::loadView('sisukma::report.ikm_kabupaten',compact('ikm','skpd_id','data','skpd','periode','alamat','telp','type_unsur'));
    return $pdf->download('ikm-kabupaten-'.str($periode)->slug().'.pdf');
    //dfd
}
    }
    public function dashboard(Request $request){
        if($request->user()->isAdmin()){
            // dd($request->all());
            $data_v3 = (new IkmManager)->getSurveyRekap($request);
            $data = (new IkmManager)->nilai_ikm_kabupaten($request->unsur_tambahan);
            $type_unsur = $request->unsur_tambahan;
            return view('sisukma::dashboard.ajax.admin',compact('data','type_unsur','data_v3'))->render();

        }else{
            $dskpd = $request->user()->skpd;
            $skpd = $dskpd->nama_skpd ?? null;
            $skpd_id = $dskpd->id ?? null;
            $periode = (new IkmManager)->as_periode($request->from,$request->to).' '.($request->year ?? date('Y'));
            $alamat = $dskpd->alamat?? null;
            $telp = $dskpd->telp?? null;
            $type_unsur = $request->unsur_tambahan ?? 9;
            $fs = 'fs';
            $data = json_decode(json_encode((new IkmManager)->nilai_ikm_skpd($dskpd->id,$request->unsur_tambahan)));
            return view('sisukma::dashboard.ajax.skpd',compact('fs','skpd_id','data','skpd','periode','alamat','telp','type_unsur'))->render();

        }
    }
}
