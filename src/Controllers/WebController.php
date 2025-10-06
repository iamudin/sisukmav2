<?php
namespace Sisukma\V2\Controllers;
use Sisukma\V2\Models\Skpd;
use Illuminate\Http\Request;
use Sisukma\V2\Models\Respon;
use Sisukma\V2\Models\Layanan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Sisukma\V2\Contracts\IkmManager;
use Illuminate\Support\Facades\Cache;
use Sisukma\V2\Models\Gallery;

class WebController extends Controller
{
public function index(Request $request){

    (new IkmManager)->get_periode_name();
    if($request->isMethod('post')){


        View::share('ajaxdata',json_encode($request->all()));
    }
    return view('sisukma::front.index');
}

function dataikm(Request $request){
    $data = (new IkmManager)->getSurveyRekap($request);
        return $data;
}

function detail_ikm_kabupaten(){

}
public function survei(Request $request,$skpd,$layanan=null){

    $skpd = Skpd::with('layanans')->findOrFail(base64_decode($skpd));

    if(!empty($layanan) &&  $data  = $skpd->layanans->where('id',base64_decode($layanan))->first()){
        if($skpd->dibatasi=='Y' && !checkwaktu(now())){
              return redirect('survei/'.base64_encode($skpd->id))->with('warning','Survei Layanan tidak bisa dilakukan karena diluar jam kerja');
          }
      if($request->kirim_survei){
        $data = $request->all();
        unset($data['kirim_survei']);
        unset($data['_token']);
        unset($data['saran']);
        unset($data['tgl']);
        $data['layanan_id'] = base64_decode($layanan);
        $data['tgl_survei'] = now();
        $data['created'] = now();
        $data['reference'] = 'qr';
        $data['saran'] = nl2br($request->saran);
        Respon::create($data);
        $request->session()->regenerateToken();
        return redirect('survei/'.base64_encode($skpd->id))->with('success','Berhasil');
      }
    return view('sisukma::front.survei',compact('data','skpd'));

    }

    return view('sisukma::front.layanan',compact('skpd'));
}
public function get_data_stats(Request $request){
    $reqdata = $request->all();
    unset($reqdata['_token']);
    $reqdata = md5(json_encode($reqdata));
    if(Cache::has($reqdata)){
        $data = Cache::get($reqdata);
    }else{
        $data = (new IkmManager)->nilai_ikm_kabupaten(9);
        Cache::put($reqdata,$data,1800);
    }
    $type_unsur = 9;
    return view('sisukma::front.ajax.stats',compact('data','type_unsur'))->render();
}

public function gallery($slug = null){
    if($slug && $data = Gallery::whereSlug($slug)->with('images','skpd')->first()){
    return view('sisukma::front.detail_gallery',compact('data'));
    }
    $data = Gallery::with('skpd','images')->latest('id')->get();
    return view('sisukma::front.gallery',compact('data'));
}
}
