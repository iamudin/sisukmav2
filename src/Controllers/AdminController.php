<?php

namespace Sisukma\V2\Controllers;

use Illuminate\Support\Str;
use Sisukma\V2\Contracts\IkmCounter;
use Sisukma\V2\Models\Skpd;
use Sisukma\V2\Models\Unit;
use Illuminate\Http\Request;
use Sisukma\V2\Models\Unsur;
use Sisukma\V2\Models\Respon;
use Sisukma\V2\Models\Gallery;
use Sisukma\V2\Models\Layanan;
use Illuminate\Validation\Rule;
use Sisukma\V2\Models\ImgGallery;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Sisukma\V2\Contracts\IkmManager;
use Sisukma\V2\Models\KategoriUnsur;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Barryvdh\Snappy\Facades\SnappyImage;
use Illuminate\Validation\Rules\Password;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Routing\Controllers\Middleware;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Illuminate\Routing\Controllers\HasMiddleware;

class AdminController extends Controller  implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth',null, ['cetakQR']),
        ];
    }

    function cetakikmkab(Request $req){
        $data = $req->cetakikmkab ? json_decode(base64_decode($req->cetakikmkab)): null;
        $periode = $req->periode;
        $pdf = PDF::loadView('sisukma::report.ringkasan_ikm_kabupaten',compact('data','periode'));
        return $pdf->download('Data IKM Kabupaten.pdf');
    }
    public function index(Request $request)
    {
        return to_route('dashboard-v2');
        (new IkmManager)->get_periode_name();
        if ($request->isMethod('post')) {
            // return (new IkmManager)->getSurveyRekap($request);
            View::share('ajaxdata', json_encode($request->all()));
        }
        if ($request->user()->isAdmin()) {
            // return (new IkmManager)->nilai_ikm_kabupaten(9);

            return view('sisukma::dashboard.admin');
        } else {
            return view('sisukma::dashboard.skpd');
        }
    }
function cetakrekap9v2(Request $request, $skpd=null){
        $nama_skpd = nama_skpd($skpd);
        $tahun = request('tahun', date('Y'));
        $periode = request('periode', null);
        $jenis_periode = request('jenis_periode', 'tahun');
        $data = (new IkmCounter)->getDataIkm9($skpd, null,$jenis_periode, $tahun, $periode);
        $data = json_decode(json_encode($data));
        $pdf = Pdf::loadView($skpd ? 'sisukma::dashboard.v2.report.rekapitulasi-opd-9' : 'sisukma::dashboard.v2.report.rekapitulasi-kab-9', [
            'data' => $data,
            'jenis_periode' => $jenis_periode,
            'tahun' => $tahun,
            'periode' => $periode,
            'nama_skpd' => $nama_skpd,
        ])->setOrientation('landscape');

        // 🔹 Download file
        return $pdf->stream('rekap-data-9-unsur-' . Str::slug($nama_skpd . ' ' . getNamaPeriode($jenis_periode, $periode, $tahun)) . '.pdf'
        );
}
    function cetakrekapv2(Request $request, $skpd = null)
    {
        $nama_skpd = nama_skpd($skpd);
        $tahun = request('tahun', date('Y'));
        $periode = request('periode', null);
        $jenis_periode = request('jenis_periode', 'tahun');
        $data = $this->getRekapSurvei($skpd, $jenis_periode, $tahun, $periode);
        $data = $skpd ? $data : $data;
        $pdf = Pdf::loadView($skpd ? 'sisukma::dashboard.v2.report.rekapitulasi-opd' : 'sisukma::dashboard.v2.report.rekapitulasi-kab', [
            'data' => $data,
            'jenis_periode' => $jenis_periode,
            'tahun' => $tahun,
            'periode' => $periode,
            'nama_skpd' => $nama_skpd,
        ])->setOrientation('landscape');

        // 🔹 Download file
        return response()->streamDownload(
            fn() => print ($pdf->output()),
            'rekap-data-' . Str::slug($nama_skpd . ' ' . getNamaPeriode($jenis_periode, $periode, $tahun)) . '.pdf'
        );
    }
    function cetakolahanv2(Request $request,$skpd=null){
        $nama_skpd = nama_skpd($skpd);
        $tahun = request('tahun', date('Y'));
        $periode = request('periode', null);
        $jenis_periode = request('jenis_periode', 'tahun');
        $data = $this->getRekapOlahan($skpd,$jenis_periode,$tahun,$periode);
        $data = !$skpd ? json_decode($data) : $data;
          $pdf = Pdf::loadView('sisukma::dashboard.v2.report.pengolahan-data-opd', [
                                'data' => $data,
                                'jenis_periode' => $jenis_periode,
                                'tahun' => $tahun,
                                'periode' => $periode,
                                'nama_skpd' => $nama_skpd,
                            ])->setOrientation('landscape');

                            // 🔹 Download file
        return response()->streamDownload(
            fn() => print ($pdf->output()),
            'olahan-data-' . Str::slug($nama_skpd.' '.getNamaPeriode($jenis_periode, $periode, $tahun)). '.pdf'
        );
    }

        function cetakolahan9v2(Request $request, $skpd = null)
        {
            $nama_skpd = nama_skpd($skpd);
            $tahun = request('tahun', date('Y'));
            $periode = request('periode', null);
            $jenis_periode = request('jenis_periode', 'tahun');
            $data = (new IkmCounter)->getDataIkm9($skpd, null,$jenis_periode, $tahun, $periode);
            $data = !$skpd ? json_decode($data) : json_decode(json_encode($data));
            $pdf = Pdf::loadView('sisukma::dashboard.v2.report.pengolahan-data-opd-9', [
                'data' => $data,
                'jenis_periode' => $jenis_periode,
                'tahun' => $tahun,
                'periode' => $periode,
                'nama_skpd' => $nama_skpd,
            ])->setOrientation('landscape');

            // 🔹 Download file
            return $pdf->stream('olahan-data-' . Str::slug($nama_skpd . ' ' . getNamaPeriode($jenis_periode, $periode, $tahun)) . '.pdf');
        }
    function getDataSurvei($periode, $tahun = null, $bulan = null)
    {
        // 🔹 Buat key unik berdasarkan parameter
        // $cacheKey = "data_survei_kab_{$periode}_{$tahun}_{$bulan}";

        // // 🔹 Gunakan Cache::remember untuk menyimpan hasil query selama 30 menit
        // return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($periode, $tahun, $bulan) {

            // Contoh isi query aslinya
            return collect(json_decode(json_encode(getStatistik9(null, null, $periode, $tahun, $bulan))));
        // });
    }
    function getRekapOlahan($skpd = null, $periode, $tahun = null, $bulan = null)
    {
            return json_decode(json_encode(collect(getDataIKM($skpd, null, $periode, $tahun, $bulan))));
    }
      function getRekapOlahan9($skpd = null, $periode, $tahun = null, $bulan = null)
    {
            return json_decode(json_encode(collect(getDataIKM9($skpd, null, $periode, $tahun, $bulan))));
    }
    function getRekapSurvei9($skpd=null,$periode, $tahun = null, $bulan = null)
    {
        // 🔹 Buat key unik berdasarkan parameter
       
            return json_decode(json_encode(collect(getDataUnsur9($skpd, null, $periode, $tahun, $bulan))));
    }
    function getRekapSurvei($skpd = null, $periode, $tahun = null, $bulan = null)
    {
        // 🔹 Buat key unik berdasarkan parameter
        $cacheKey = "data_rekap_{$periode}_{$tahun}_{$bulan}_{$skpd}";

        // 🔹 Gunakan Cache::remember untuk menyimpan hasil query selama 30 menit
        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($periode, $tahun, $bulan, $skpd) {
            // Contoh isi query aslinya
            return json_decode(json_encode(collect(getDataUnsur($skpd, null, $periode, $tahun, $bulan))));
        });
    }

    function statsresp(Request $request){
    
        $view = view('sisukma::dashboard.v2.statsresp',[
            'data'=>json_decode(base64_decode($request->data)) ?? []
        ])->render();
        return response($view);
    }
    public function indexv2(Request $request){
    if(!Cache::has('unsur_16')){
            abort('404');
    }
    $tahun = request('tahun',date('Y'));
    $periode = request('periode',null);
    $jenis_periode = request('jenis_periode','tahun');
        $cacheKey = "data_survei_kab_{$jenis_periode}_{$tahun}_{$periode}";

        $nama_periode = getNamaPeriode($jenis_periode, $periode, $tahun);
        if ($request->user()->isAdmin()) {
                 if($request->isMethod('post')){
            if (!Cache::has($cacheKey)) {
                $baru = true;
            }
               Cache::remember($cacheKey, now()->addMinutes(30), function () use ($jenis_periode, $tahun, $periode) {
                    return collect(json_decode(json_encode((new IkmCounter)->getStatistik9(null, null, $jenis_periode, $tahun, $periode))));
                });
               if(Cache::has($cacheKey)){
                if(isset($baru)){
                    return response()->json(['msg' => 'new']);

                }
                return response()->json(['msg' => 'old']);
            }

            // // 🔹 Gunakan Cache::remember untuk menyimpan hasil query selama 30 menit
          
    }
        
            return view('sisukma::dashboard.v2.admin', ['periode' => getNamaPeriode($jenis_periode, $periode, $tahun), 'data' => Cache::get($cacheKey) ?? []]);
        } else {
            $data = json_decode(json_encode(getStatistik(auth()->user()->skpd->id,null,$jenis_periode, $tahun, $periode)));
            $data9 = json_decode(json_encode((new IkmCounter)->getStatistik9(auth()->user()->skpd->id,null,$jenis_periode, $tahun, $periode)));
            return view('sisukma::dashboard.v2.skpd',compact('data','nama_periode','data9'));
        }
    }
    public function account(Request $request){
        $user = Auth::user();
        if($request->isMethod('post')){
            $request->validate([
                'nama'=>'required|string',
                'password' => 'nullable|confirmed|min:6',
            ]);

            $user->update([
                'nama'=>$request->nama,
                'password'=> $request->password ? bcrypt($request->password) : $user->password,
            ]);
            return back()->with('success','Akun berhasil diperbarui');
        }
        return view('sisukma::admin.account',compact('user'));
    }
    public function cetakQR(Skpd $skpd,$layanan=null)
    {
        $lcek = Layanan::find($layanan);
        $ly = $layanan && $lcek ? $lcek : null;
        if($vc = request()->cetak){
            $path = 'dataqr/'.str('qr '.$skpd->nama_skpd.' '.($ly?->nama_layanan??null))->slug().'.jpg';
            if(Storage::exists($path)){
                Storage::delete($path);
            }
            if($vc=='ori'){
                SnappyImage::generate(route('skpd.cetakqr',[$skpd->id,$layanan]),Storage::path($path));
            }else{
                SnappyImage::generate(route('skpd.cetakqr', [$skpd->id, $layanan]).'?cetaktemplate=true',Storage::path($path));
            }
            if(Storage::exists($path)){
                return response()->download(Storage::path($path))->deleteFileAfterSend();
            }
        }
        if(request()->cetaktemplate){
        return view('sisukma::report.qrcode', ['skpd' => $skpd,'layanan'=>$layanan]);
        }
        return view('sisukma::report.qrcode-ori', ['skpd' => $skpd,'layanan'=>$layanan]);
    }
    public function indexSKPD(Request $request)
    {
        abort_if(!$request->user()->isAdmin(), '403', 'Akses tidak dizinkan');
        $data = Skpd::with('user', 'periode_aktif')->withCount('layanans', 'units')->get();
        return view('sisukma::admin.skpd.index', compact('data'));
    }
    public function formSKPD(Request $request, Skpd $skpd)
    {
        abort_if(!$request->user()->isAdmin(), '403', 'Akses tidak dizinkan');
        $edit = $skpd->load('periode_aktif', 'user');
        return view('sisukma::admin.skpd.form', compact('edit'));
    }
    public function storeSKPD(Request $request)
    {
        $data = $request->validate([
            'nama_skpd' => 'required|string|unique:skpds,nama_skpd',
            'alamat' => 'required|string',
            'email' => 'nullable|email|unique:skpds,email',
            'website' => 'nullable|url|unique:skpds,website',
            'total_unsur' => 'required|in:11,9',
            'telp' => 'nullable|numeric|unique:skpds,telp',
            'banner' => 'nullable|image|max:1024',
            'periode_aktif' => 'nullable|array',
        ]);
        if ($request->user()->isAdmin()) {
            $id = Skpd::create($data);
            if ($id) {
                if ($per = $request->periode_aktif) {
                    foreach ($per as $row) {
                        $id->periode_aktif()->updateOrCreate(['tahun' => $row], ['tahun' => $row]);
                    }
                }
                if ($request->hasFile('banner')) {
                    $id->update([
                        'banner' => $request->file('banner')->store('banner', 'public')
                    ]);
                }
                $request->validate([
                    'nama_admin' => 'required|string',
                    'username' => 'required|alpha_dash:ascii|unique:users,username',
                    'password' => ['required', Password::min(8)->symbols()->numbers()->mixedCase()],
                ]);
                $id->user()->create([
                    'nama' => $request->nama_admin,
                    'username' => $request->username,
                    'level'=>'skpd',
                    'status' => 'Aktif',
                    'password' => bcrypt($request->password)
                ]);
            }
            return to_route('skpd.edit', $id->id)->with('success', 'Berhasil');
        }
        abort('403', 'Akses tidak dizinkan');
    }
    public function profileSKPD(Request $request)
    {
        abort_if(Auth::user()->isAdmin(),'403', 'Akses tidak dizinkan');

        $skpd = Auth::user()->skpd;
        if($request->isMethod('post')){

        $data = $request->validate([
            'nama_skpd' => 'required|string|' . Rule::unique('skpds')->ignore($skpd->id),
            'alamat' => 'required|string',
            'tampilkan_banner' => 'nullable|in:Y,N',
            'email' => 'nullable|email|' . Rule::unique('skpds')->ignore($skpd->id),
            'website' => 'nullable|url|' . Rule::unique('skpds')->ignore($skpd->id),
            'telp' => 'nullable|numeric|' . Rule::unique('skpds')->ignore($skpd->id),
            'banner' => 'nullable|image|max:1024',
        ]);

        if(!$request->tampilkan_banner){
            $data['tampilkan_banner'] = 'N';
        }

            if ($request->hasFile('banner')) {
                if ($skpd->banner && Storage::disk('public')->exists($skpd->banner)) {
                    Storage::disk('public')->delete($skpd->banner);
                }
                $data['banner'] = $request->file('banner')->store('banner', 'public');
            }
            $data['dibatasi'] = $request->dibatasi;
            $skpd->update($data);

            return back()->with('success', 'Berhasil');
        }
        return view('sisukma::admin.skpd.profile',['edit'=>$skpd]);
    }
    public function updateSKPD(Request $request, Skpd $skpd)
    {
        $data = $request->validate([
            'nama_skpd' => 'required|string|' . Rule::unique('skpds')->ignore($skpd->id),
            'alamat' => 'required|string',
            'email' => 'nullable|email|' . Rule::unique('skpds')->ignore($skpd->id),
            'website' => 'nullable|url|' . Rule::unique('skpds')->ignore($skpd->id),
            'telp' => 'nullable|numeric|' . Rule::unique('skpds')->ignore($skpd->id),
            'total_unsur' => 'required|in:11,9',
            'banner' => 'nullable|image|max:1024',
            'periode_aktif' => 'nullable|array',
        ]);

        if ($request->user()->isAdmin()) {
            if ($request->hasFile('banner')) {
                if ($skpd->banner && Storage::disk('public')->exists($skpd->banner)) {
                    Storage::disk('public')->delete($skpd->banner);
                }
                $data['banner'] = $request->file('banner')->store('banner', 'public');
            }
            $skpd->update($data);
            $skpd->periode_aktif()->delete();
            foreach ($request->periode_aktif ?? [] as $r) {
                $skpd->periode_aktif()->updateOrCreate(['tahun' => $r], ['tahun' => $r]);
            }
            $request->validate([
                'nama_admin' => 'required|string',
                'username' => $skpd->user ? ['required', 'alpha_dash:ascii', Rule::unique('users')->ignore($skpd->user->id)] : ['required','unique:users,username'],
                'password' => $skpd->user ?['nullable', Password::min(8)->symbols()->numbers()->mixedCase()] :  ['required'],
            ]);
            $skpd->user()->updateOrCreate(['skpd_id'=>$skpd->id],[
                'nama' => $request->nama_admin,
                'username' => $request->username,
                'status' => 'Aktif',
                'level' => 'skpd',
                'password' => $request->password ? bcrypt($request->password) : ($skpd->user->password ??  bcrypt($request->password))
            ]);
            return back()->with('success', 'Berhasil');
        }
        abort('403', 'Akses tidak dizinkan');
    }
    public function destroySKPD(Request $request, Skpd $skpd)
    {

        if ($request->user()->isAdmin()) {
            if ($skpd->layanans()->exists()) {
                return to_route('skpd.index')->with('danger', 'Data memiliki relasi Layanan');
            }
            $skpd->delete();
            return to_route('skpd.index')->with('success', 'Berhasil');
        }
        abort('403', 'Akses tidak dizinkan');
    }
    //gallery
    public function indexGallery(Request $request)
    {
        $data = Gallery::with('images','skpd')
            ->when($request->user()->isSkpd(), function ($query) use ($request) {
                return $query->whereSkpdId($request->user()->skpd->id);
            })
            ->latest('created_at')
            ->get();
        return view('sisukma::admin.gallery.index', compact('data'));
    }
    public function formGallery(Gallery $gallery)
    {
        if($gallery->exists && Auth::user()->isSkpd() && $gallery->skpd_id != Auth::user()->skpd->id){
            abort(403,'Tidak diizinkan');
        }
        $edit = $gallery;
        return view('sisukma::admin.gallery.form', compact('edit'));
    }
    public function storeGallery(Request $request, Gallery $gallery)
    {
        $request->validate([
            'nama'=>'required|string',
            'aktif'=>'required|string',
        ]);
        $data= $gallery->create([
            'nama' => $request->nama,
            'aktif'=>$request->aktif,
            'skpd_id'=>$request->user()->skpd->id,
            'slug'=>str($request->nama)->slug().'-'.Str::random(4)
        ]);
        return  to_route('gallery.edit',$data->id)->with('success', 'Gallery Tersimpan');

    }
    public function updateGallery(Request $request, Gallery $gallery)
    {
        if($request->upload){
            $request->validate([
                'gambar'=>'required|image|max:1024',
                'caption'=>'required|string'
            ]);
            if($request->hasFile('gambar')){

                $gallery->images()->create([
                   'path'=> $request->file('gambar')->store('gallery', 'public'),
                   'caption'=> $request->caption,
                ]);
            }
        }else{
            $gallery->update([
                'nama' => $request->nama,
                'aktif'=>$request->aktif,
                'skpd_id' => $request->user()->isAdmin() ? $gallery->skpd_id : $request->user()->skpd->id,
            ]);
        }


        return back()->with('success', 'Berhasil Tersimpan');
    }
    public function destroyGallery(Gallery $gallery)
    {
            abort_if(Auth::user()->skpd->id != $gallery->skpd_id,404,'Tidak diizinkan');
            $gallery->delete();
            return back()->with('success', 'Berhasil');
    }
    public function destroyImgGallery(ImgGallery $imgGallery)
    {
            if(Storage::disk('public')->exists($imgGallery->path)){
                Storage::disk('public')->delete($imgGallery->path);
            }
            $imgGallery->delete();
            return back()->with('success', 'Berhasil');
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
        abort_if($request->user()->isSkpd() && $unit->exists && $unit->skpd_id != $request->user()->skpd->id, '403', 'Aksi tidak di izinkan');
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
        $data = Layanan::with('skpd', 'unit')->withCount('respons')
            ->when($request->user()->isSkpd(), function ($query) use ($request) {
                return $query->whereSkpdId($request->user()->skpd->id); // Ganti dengan kondisi yang sesuai
            })->orderBy('skpd_id')
            ->get();
        return view('sisukma::admin.layanan.index', compact('data'));
    }
    public function formLayanan(Request $request, Layanan $layanan)
    {
        abort_if($request->user()->isSkpd() && $layanan->exists && $layanan->skpd_id != $request->user()->skpd->id, '403', 'Aksi tidak di izinkan');
        $edit = $layanan->load('evaluasi');
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

    }
    function createEvaluasiLayanan(Request $request, Layanan $layanan){
        $layanan->evaluasi()->updateOrCreate([
            'tahun'=>$request->tahun
        ],$request->all());

        return back();
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
            if($request->user()->isAdmin()){
                $layanan->delete();
                return to_route('layanan.index')->with('success', 'Data Berhasil Dihapus');
            }else{
            return to_route('layanan.index')->with('danger', 'Layanan Memiliki Responden, Hub. Admin utk Konfirmasi Penghapusan data ini.');
            }
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
        abort_if(!$request->user()->isAdmin(), '403', 'Akses tidak dizinkan');
        $data = Unsur::with('kategoriUnsur')->orderBy('urutan')->get();
        return view('sisukma::admin.unsur.index', compact('data'));
    }
    public function formUnsur(Request $request, Unsur $unsur)
    {
        abort_if(!$request->user()->isAdmin(), '403', 'Akses tidak dizinkan');
        $edit = $unsur;
        return view('sisukma::admin.unsur.form', compact('edit'));
    }
    public function storeUnsur(Request $request)
    {
        if ($request->user()->isAdmin()) {
            Unsur::create([
                'nama_unsur' => $request->nama_unsur,
                'kategori_unsur_id' => $request->kategori_unsur_id,
                'a' => $request->a,
                'b' => $request->b,
                'c' => $request->c,
                'd' => $request->d,
                'urutan' => $request->urutan,
            ]);
            return to_route('unsur.index')->with('success', 'Berhasil');
        }
        abort('403', 'Akses tidak dizinkan');
    }

    public function updateUnsur(Request $request, Unsur $unsur)
    {
        if ($request->user()->isAdmin()) {
            $unsur->update([
                'nama_unsur' => $request->nama_unsur,
                'kategori_unsur_id' => $request->kategori_unsur_id,
                'a' => $request->a,
                'b' => $request->b,
                'c' => $request->c,
                'd' => $request->d,
                'urutan' => $request->urutan,
            ]);
            return to_route('unsur.index')->with('success', 'Berhasil');
        }
        abort('403', 'Akses tidak dizinkan');
    }
    public function destroyUnsur(Request $request, Unsur $unsur) {
        $unsur->delete();
        return to_route('unsur.index');
    }

    public function indexKategori(Request $request)
    {
        abort_if(!$request->user()->isAdmin(), '403', 'Akses tidak dizinkan');
        $data = KategoriUnsur::orderBy('urutan')->get();
        return view('sisukma::admin.kategori.index', compact('data'));
    }
    public function formKategori(Request $request, KategoriUnsur $kategori)
    {
        abort_if(!$request->user()->isAdmin(), '403', 'Akses tidak dizinkan');
        $edit = $kategori;
        return view('sisukma::admin.kategori.form', compact('edit'));
    }
    public function storeKategori(Request $request)
    {
        if ($request->user()->isAdmin()) {
            KategoriUnsur::create([
                'nama' => $request->nama,
                'urutan' => $request->urutan,
            ]);
            return to_route('kategori.index')->with('success', 'Berhasil');
        }
        abort('403', 'Akses tidak dizinkan');
    }
    public function updateKategori(Request $request, KategoriUnsur $kategori)
    {
        if ($request->user()->isAdmin()) {
            $kategori->update([
                'nama' => $request->nama,
                'urutan' => $request->urutan,
            ]);
            return to_route('kategori.index')->with('success', 'Berhasil');
        }
        abort('403', 'Akses tidak dizinkan');
    }
    public function destroyKategori(Request $request, KategoriUnsur $kategori)
    {
        $kategori->delete();
        return to_route('kategori.index');
    }

    //User
public function indexResponden(){
    abort_if(!Auth::user()->isSkpd(),'403','Akses tidak diizinkan');

    $data = Layanan::whereSkpdId(Auth::user()->skpd->id)->withCount('respons')->get();
    return view('sisukma::admin.responden.index',compact('data'));
}

public function importResponden(Request $request, Layanan $layanan){
    abort_if($layanan->skpd_id != Auth::user()->skpd_id || !Auth::user()->isSkpd(),'403','Akses tidak diizinkan');
        if($request->isMethod('post')){

        if($request->hasFile('fileimport') && $xlsx = $request->file('fileimport'))
        {
          if($xlsx->getClientOriginalExtension() != 'xlsx')
          return back();

          $total = 0;
          $gagal = 0;
          $data = Excel::toArray(null,$xlsx);
          foreach($data as $v){
            foreach($v as $k=>$value){
              if($k > 0 && is_numeric($value[0])){
                if(isDate($value[1])){
              $t['tgl_survei'] = $value[1].' 00:00:00';
              $t['jam_survei'] = $value[2];
              $t['jenis_kelamin'] = $value[3];
              $t['usia'] = $value[4];
              $t['pendidikan'] = Str::upper($value[5]);
              $t['pekerjaan'] = Str::upper($value[6]);
              $t['u1'] = $value[7];
              $t['u2'] = $value[8];
              $t['u3'] = $value[9];
              $t['u4'] = $value[10];
              $t['u5'] = $value[11];
              $t['u6'] = $value[12];
              $t['u7'] = $value[13];
              $t['u8'] = $value[14];
              $t['u9'] = $value[15];
              $t['u10'] = $value[16] ?? 0;
              $t['u11'] = $value[17] ?? 0;
              $t['saran'] = $value[18];
              $t['created'] = now();
              $t['layanan_id'] = $layanan->id;
              $t['reference'] = 'xlsx';
              Respon::create($t);
              $total++;
              }else{
                $gagal++;

              }
              }

            }
          }
          $desc = $total + $gagal;
          return back()->with('success','Total data '.$desc.' | '.$total.' Responden berhasil diimport dan '.$gagal.' Gagal Tanggal Tidak Sesuai Format');

    }
    }
    $data = Respon::whereBelongsTo($layanan)
    ->whereReference('xlsx')
    ->selectRaw('created as date, COUNT(*) as count,reference as ref')
    ->groupBy('created')
    ->get();
    return view('sisukma::admin.responden.import',compact('data','layanan'));
}

public function destroyDateResponden(Request $request, Layanan $layanan){

    Respon::whereBelongsTo($layanan)->whereCreated($request->date)->delete();
    return back()->with('success','Data tanggal '.$request->date.' berhasil dihapus');
}
    function pengaturan(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($request->unsur_16) {
                if ($request->unsur_16 == 'aktif') {
                    Cache::put('unsur_16', 'aktif');
                } else {
                    Cache::forget('unsur_16');
                }
            }
            if($id=$request->layanan_ujicoba){
                Cache::put('layanan_ujicoba', $id);
            }
            return back()->with('success','Berhasil disimpan');
        }

        return view('sisukma::pengaturan');
    }

}


