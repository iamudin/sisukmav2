<?php
namespace Sisukma\V2\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebController extends Controller
{
public function index(Request $request){
    return view('sisukma::web.home');
}
}
