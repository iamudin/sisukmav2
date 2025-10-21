<?php
namespace Sisukma\V2\Controllers;
use Sisukma\V2\Models\Skpd;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class SkpdController extends Controller implements HasMiddleware
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
        abort_if(!$request->user()->isAdmin(), '403', 'Akses tidak dizinkan');
        $data = Skpd::with('user', 'periode_aktif')->withCount('layanans', 'units')->get();
        return view('sisukma::admin.skpd.index', compact('data'));
    }
    public function form(Request $request, Skpd $skpd)
    {
        abort_if(!$request->user()->isAdmin(), '403', 'Akses tidak dizinkan');
        $edit = $skpd->load('periode_aktif', 'user');
        return view('sisukma::admin.skpd.form', compact('edit'));
    }
    public function store(Request $request)
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
                    'username' => ['required', 'alpha_dash:ascii', 'unique'],
                    'password' => ['required', Password::min(8)->symbols()->numbers()->mixedCase()],
                ]);
                $id->user()->create([
                    'nama' => $request->nama_admin,
                    'username' => $request->username,
                    'password' => bcrypt($request->password)
                ]);
            }
            return to_route('skpd.edit', $id->id)->with('success', 'Berhasil');
        }
        abort('403', 'Akses tidak dizinkan');
    }
    public function profile(Request $request)
    {
        abort_if(Auth::user()->isAdmin(), '403', 'Akses tidak dizinkan');

        $skpd = Auth::user()->skpd;
        if ($request->isMethod('post')) {

            $data = $request->validate([
                'nama_skpd' => 'required|string|' . Rule::unique('skpds')->ignore($skpd->id),
                'alamat' => 'required|string',
                'tampilkan_banner' => 'nullable|in:Y,N',
                'email' => 'nullable|email|' . Rule::unique('skpds')->ignore($skpd->id),
                'website' => 'nullable|url|' . Rule::unique('skpds')->ignore($skpd->id),
                'telp' => 'nullable|numeric|' . Rule::unique('skpds')->ignore($skpd->id),
                'banner' => 'nullable|image|max:1024',
            ]);

            if (!$request->tampilkan_banner) {
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
        return view('sisukma::admin.skpd.profile', ['edit' => $skpd]);
    }
    public function update(Request $request, Skpd $skpd)
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
                'username' => $skpd->user ? ['required', 'alpha_dash:ascii', Rule::unique('users')->ignore($skpd->user->id)] : ['required', 'unique:users,username'],
                'password' => $skpd->user ? ['nullable', Password::min(8)->symbols()->numbers()->mixedCase()] : ['required'],
            ]);
            $skpd->user()->updateOrCreate(['skpd_id' => $skpd->id], [
                'nama' => $request->nama_admin,
                'username' => $request->username,
                'status' => 'Aktif',
                'level' => 'skpd',
                'password' => $request->password ? bcrypt($request->password) : ($skpd->user->password ?? bcrypt($request->password))
            ]);
            return back()->with('success', 'Berhasil');
        }
        abort('403', 'Akses tidak dizinkan');
    }
    public function destroy(Request $request, Skpd $skpd)
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
}