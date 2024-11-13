<?php

namespace Sisukma\V2\Controllers;

use Carbon\Carbon;
use Sisukma\V2\Models\Skpd;
use Sisukma\V2\Models\Unit;
use Illuminate\Http\Request;
use Sisukma\V2\Models\Unsur;
use Sisukma\V2\Models\Respon;
use Sisukma\V2\Models\Layanan;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Sisukma\V2\Contracts\IkmManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AdminController extends Controller  implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }
    public function index(Request $request)
    {
//         $surveys  = Respon::withwhereHas('layanan')->get();
//         $startOfMonth = Carbon::createFromDate(2023, 01);
// $endOfMonth = Carbon::createFromDate(2023, 06);

// // Filter collection berdasarkan rentang bulan
// $filteredSurveys = $surveys->filter(function ($survey) use ($startOfMonth, $endOfMonth) {
//     $tglSurvei = Carbon::parse($survey->tgl_survei);
//     return $tglSurvei->between($startOfMonth, $endOfMonth);
// });

// // Jika perlu, Anda bisa meng-reset keys setelah filter
// $filteredSurveys = $filteredSurveys->values();
// return $filteredSurveys;
        (new IkmManager)->get_periode_name();
        if($request->isMethod('post')){
            View::share('ajaxdata',json_encode($request->all()));
        }
        if($request->user()->isAdmin()){

            return view('sisukma::dashboard.admin');
        }else{
            return view('sisukma::dashboard.skpd');
        }


    }

    //SKPD
    public function indexSKPD(Request $request)
    {
        abort_if(!$request->user()->isAdmin(),'403','Akses tidak dizinkan');
        $data = Skpd::with('user','periode_aktif')->withCount('layanans','units')->get();
        return view('sisukma::admin.skpd.index', compact('data'));
    }
    public function formSKPD(Request $request,Skpd $skpd)
    {
        abort_if(!$request->user()->isAdmin(),'403','Akses tidak dizinkan');
        $edit = $skpd->load('periode_aktif','user');
        return view('sisukma::admin.skpd.form', compact('edit'));
    }
    public function storeSKPD(Request $request)
    {
        $data = $request->validate([
           'nama_skpd'=>'required|string|unique',
           'alamat'=>'required|string',
           'email'=>'nullable|email|unique',
           'website'=>'nullable|url|unique',
            'total_unsur'=>'required|in:11,9',
           'telp'=>'nullable|numeric|unique',
           'banner'=>'nullable|image|max:1024',
           'periode_aktif'=>'nullable|array',
        ]);
        if ($request->user()->isAdmin()) {
            $id = Skpd::create($data);
            if($id ){
                if($per = $request->periode_aktif){
                    foreach($per as $row){
                        $id->periode_aktif()->updateOrCreate(['tahun'=>$row],['tahun'=>$row]);
                    }
                }
                if($request->hasFile('banner')){
                    $id->update([
                        'banner'=> $request->file('banner')->store('banner','public')
                    ]);
                }
                $request->validate([
                    'nama_admin'=> 'required|string',
                    'username'=> ['required','alpha_dash:ascii','unique'],
                    'password'=> ['required',Password::min(8)->symbols()->numbers()->mixedCase()],
                ]);
                $id->user()->create([
                    'nama'=>$request->nama_admin,
                    'username'=>$request->username,
                    'password'=> bcrypt($request->password)
                ]);
            }
            return to_route('skpd.edit',$id->id)->with('success', 'Berhasil');
        }
        abort('403','Akses tidak dizinkan');
    }
    public function updateSKPD(Request $request,Skpd $skpd)
    {
        $data = $request->validate([
            'nama_skpd'=>'required|string|'.Rule::unique('skpds')->ignore($skpd->id),
            'alamat'=>'required|string',
            'email'=>'nullable|email|'.Rule::unique('skpds')->ignore($skpd->id),
            'website'=>'nullable|url|'.Rule::unique('skpds')->ignore($skpd->id),
            'telp'=>'nullable|numeric|'.Rule::unique('skpds')->ignore($skpd->id),
            'total_unsur'=>'required|in:11,9',
            'banner'=>'nullable|image|max:1024',
            'periode_aktif'=>'nullable|array',
         ]);

        if ($request->user()->isAdmin()) {
            if($request->hasFile('banner') ){
                if($skpd->banner && Storage::disk('public')->exists($skpd->banner)){
                    Storage::disk('public')->delete($skpd->banner);

                }
            $data['banner'] = $request->file('banner')->store('banner','public');
            }
            $skpd->update($data);
            $skpd->periode_aktif()->delete();
            foreach($request->periode_aktif as $r){
                $skpd->periode_aktif()->updateOrCreate(['tahun'=>$r], ['tahun'=>$r]);
            }
            $request->validate([
                'nama_admin'=> 'required|string',
                'username'=> ['required','alpha_dash:ascii',Rule::unique('users')->ignore($skpd->user->id)],
                'password'=> ['nullable',Password::min(8)->symbols()->numbers()->mixedCase()],
            ]);
            $skpd->user()->update([
                'nama'=>$request->nama_admin,
                'username'=>$request->username,
                'password'=>$request->password ? bcrypt($request->password) : $skpd->user->password
            ]);
            return back()->with('success', 'Berhasil');
        }
        abort('403','Akses tidak dizinkan');
    }
    public function destroySKPD(Request $request,Skpd $skpd)
    {

       if ($request->user()->isAdmin()) {
        if($skpd->layanans()->exists()){
            return to_route('skpd.index')->with('danger', 'Data memiliki relasi Layanan');
            }
            $skpd->delete();
            return to_route('skpd.index')->with('success', 'Berhasil');
         }
         abort('403','Akses tidak dizinkan');
    }

//Unit Pelayanan
public function indexUnit(Request $request)
    {
        $data = Unit::with('skpd')
            ->when($request->user()->isSkpd(), function ($query) use ($request) {
                return $query->whereSkpdId($request->user()->skpd->id); // Ganti dengan kondisi yang sesuai
            })
            ->withCount('layanans')->orderBy('skpd_id')
            ->get();
        // $data = Unit::with('skpd')->orderBy('skpd_id')->get();
        return view('sisukma::admin.unit.index', compact('data'));
    }
    public function formUnit(Request $request, Unit $unit)
    {
       abort_if($request->user()->isSkpd() && $unit->exists && $unit->skpd_id != $request->user()->skpd->id,'403','Aksi tidak di izinkan');
        $edit = $unit;
        if ($request->user()->isAdmin()) {
            $skpd = Skpd::get();
        } else {
            $skpd = null;

        }
        return view(
            'sisukma::admin.unit.form',
            [
                'edit' => $edit,
                'skpd' => $skpd
            ]
        );

        return view('sisukma::admin.dashboard');
    }
    public function storeUnit(Request $request)
    {
        if ($request->user()->isAdmin()) {
            Unit::create([
                'nama' => $request->nama,
                'skpd_id' => $request->skpd_id
            ]);
        } else {
            Unit::create([
                'nama' => $request->nama,
                'skpd_id' => $request->user()->skpd->id
            ]);
        }
        return to_route('unit.index')->with('success', 'Berhasil');
    }

    public function updateUnit(Request $request, Unit $unit)
    {
        if ($request->user()->isAdmin()) {
            $unit->update([
                'nama' => $request->nama,
                'skpd_id' => $request->skpd_id
            ]);
        } else {
            $unit->update([
                'nama' => $request->nama,
                'skpd_id' => $request->user()->skpd->id
            ]);
        }
        return to_route('unit.index')->with('success', 'Berhasil');
    }
    public function destroyUnit(Request $request, Unit $unit)
    {
        abort_if($request->user()->isSkpd() && $unit->skpd_id != $request->user()->skpd->id, 403, 'Aksi tidak di izinkan');
        if ($unit->layanans()->exists()) {
            return to_route('unit.index')->with('danger', 'Tidak berhasil');
        }
        try {
            $unit->delete();
            return to_route('unit.index')->with('success', 'Berhasil');
        } catch (\Exception $e) {
            return to_route('unit.index')->with('danger', 'Terjadi Kesalahan ' . $e->getMessage());
        }
    }
    //Layanan
    public function indexLayanan(Request $request)
    {
        $data = Layanan::with('skpd','unit')
            ->when($request->user()->isSkpd(), function ($query) use ($request) {
                return $query->whereSkpdId($request->user()->skpd->id); // Ganti dengan kondisi yang sesuai
            })->orderBy('skpd_id')
            ->get();
        return view('sisukma::admin.layanan.index', compact('data'));
    }
    public function formLayanan(Request $request, Layanan $layanan)
    {
       abort_if($request->user()->isSkpd() && $layanan->exists && $layanan->skpd_id != $request->user()->skpd->id,'403','Aksi tidak di izinkan');
        $edit = $layanan;
        if ($request->user()->isAdmin()) {
            $skpd = Skpd::get();
            $unit = $request->skpd || $edit->exists ? Unit::whereSkpdId($request->skpd ?? $edit->skpd_id)->get() : null;

        } else {
            $skpd = null;
            $unit = Unit::whereSkpdId($request->user()->skpd->id)->get();

        }
        return view(
            'sisukma::admin.layanan.form',
            [
                'edit' => $edit,
                'skpd' => $skpd,
                'unit' => $unit
            ]
        );

        return view('sisukma::admin.dashboard');
    }
    public function storeLayanan(Request $request)
    {
        if ($request->user()->isAdmin()) {
            Layanan::create([
                'nama_layanan' => $request->nama_layanan,
                'skpd_id' => $request->skpd_id,
                'unit_id' => $request->unit_id
            ]);
        } else {
            Layanan::create([
                'nama_layanan' => $request->nama_layanan,
                'skpd_id' => $request->user()->skpd->id,
                'unit_id' => $request->unit_id

            ]);
        }
        return to_route('layanan.index')->with('success', 'Berhasil');
    }

    public function updateLayanan(Request $request, Layanan $layanan)
    {
        if ($request->user()->isAdmin()) {
            $layanan->update([
                'nama_layanan' => $request->nama_layanan,
                'skpd_id' => $request->skpd_id,
                'unit_id' => $request->unit_id

            ]);
        } else {
            $layanan->update([
                'nama_layanan' => $request->nama_layanan,
                'skpd_id' => $request->user()->skpd->id,
                'unit_id' => $request->unit_id

            ]);
        }
        return to_route('layanan.index')->with('success', 'Berhasil');
    }
    public function destroyLayanan(Request $request, Layanan $layanan)
    {
        abort_if($request->user()->isSkpd() && $layanan->skpd_id != $request->user()->skpd->id, 403, 'Aksi tidak di izinkan');
        if ($layanan->respons()->exists()) {
            return to_route('layanan.index')->with('danger', 'Tidak berhasil');
        }
        try {
            $layanan->delete();
            return to_route('layanan.index')->with('success', 'Berhasil');
        } catch (\Exception $e) {
            return to_route('layanan.index')->with('danger', 'Terjadi Kesalahan ' . $e->getMessage());
        }
    }


    //Layanan
    public function indexUnsur(Request $request)
    {
        abort_if(!$request->user()->isAdmin(),'403','Akses tidak dizinkan');
        $data = Unsur::orderBy('urutan')->get();
        return view('sisukma::admin.unsur.index', compact('data'));
    }
    public function formUnsur(Request $request, Unsur $unsur)
    {
        abort_if(!$request->user()->isAdmin(),'403','Akses tidak dizinkan');
        $edit = $unsur;
        return view('sisukma::admin.unsur.form', compact('edit'));
    }
    public function storeUnsur(Request $request)
    {
        if ($request->user()->isAdmin()) {
            Unsur::create([
                'nama_unsur' => $request->nama_unsur,
                'a'=>$request->a,
                'b'=>$request->b,
                'c'=>$request->c,
                'd'=>$request->d,
                'urutan'=>$request->urutan,
            ]);
        return to_route('unsur.index')->with('success', 'Berhasil');
        }
        abort('403','Akses tidak dizinkan');

    }
    public function updateUnsur(Request $request,Unsur $unsur)
    {
        if ($request->user()->isAdmin()) {
           $unsur->update([
                'nama_unsur' => $request->nama_unsur,
                'a'=>$request->a,
                'b'=>$request->b,
                'c'=>$request->c,
                'd'=>$request->d,
                'urutan'=>$request->urutan,
            ]);
        return to_route('unsur.index')->with('success', 'Berhasil');
        }
        abort('403','Akses tidak dizinkan');
    }
    public function destroyUnsur(Request $request,Unsur $unsur)
    {

    }


    //User
    public function indexUser(Request $request)
    {
        return view('sisukma::admin.unsur.index');
    }
    public function formUser(Request $request)
    {
        return view('sisukma::admin.dashboard');
    }
    public function storeUser(Request $request)
    {
        return view('sisukma::admin.dashboard');
    }
    public function updateUser(Request $request)
    {
        return view('sisukma::admin.dashboard');
    }
    public function destroyUser(Request $request)
    {
        return view('sisukma::admin.dashboard');
    }
}
