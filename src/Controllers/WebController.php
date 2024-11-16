<?php
namespace Sisukma\V2\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Sisukma\V2\Contracts\IkmManager;

class WebController extends Controller
{
public function index(Request $request){
    (new IkmManager)->get_periode_name();
    if($request->isMethod('post')){
        View::share('ajaxdata',json_encode($request->all()));
    }
    return view('sisukma::front.index');
}

public function get_data_stats(Request $request){
    $data = (new IkmManager)->nilai_ikm_kabupaten(9);
    $type_unsur = 9;
    return view('sisukma::front.ajax.stats',compact('data','type_unsur'))->render();
}
}
