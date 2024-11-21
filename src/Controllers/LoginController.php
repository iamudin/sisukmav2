<?php
namespace Sisukma\V2\Controllers;
use Illuminate\Support\Str;
use Sisukma\V2\Models\User;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{


    public function codeCaptcha()
    {
        Session::put('captcha',Str::random(4));
    }
    public function generateCaptcha(Request $request)
    {


        $image = imagecreatetruecolor(120, 40);
        $bgColor = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);

        imagefilledrectangle($image, 0, 0, 120, 40, $bgColor);
        imagettftext($image, 20, 0, 10, 30, $textColor, public_path('back/captcha.ttf'), Session::get('captcha'));

        ob_start();
        imagepng($image);
        $captchaImage = ob_get_clean();
        imagedestroy($image);
        if(!$request->headers->get('referer') ){
        $request->session()->regenerateToken();
        return redirect('/');
        }
        return response($captchaImage)->header('Content-type', 'image/png');
    }
    public function loginForm(Request $request)
    {
        if(Auth::check())
        return to_route('dashboard');
        $this->codeCaptcha();
        return view('sisukma::login',['captcha'=>route('captcha')]);

    }
    public function loginSubmit(Request $request,RateLimiter $limiter,User $user)
    {
        if($request->username && $request->password)
        {
            $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);

          if($request->captcha != Session::get('captcha')){
            $request->session()->regenerateToken();
            return back()->with('error','Kode Captcha Tidak Valid! ');

          }
        if(Auth::attempt(array('username'=>$request->username,'password'=>$request->password)))
        {
            $request->session()->regenerate();
            if(Auth::user()->status == 'Aktif'){
             Auth::user()->update(['last_login_at'=>now(),'last_login_ip'=>$request->ip()]);
            return  redirect()->intended(route('dashboard'));
            }
            else{
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error','Akun telah diblokir!');
            }
        }
        $request->session()->regenerateToken();
        return back()->with('error','Akun tidak ditemukan!');
    }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
