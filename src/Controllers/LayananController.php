<?php
namespace Sisukma\V2\Controllers;
use Sisukma\V2\Models\Skpd;
use Sisukma\V2\Models\Unit;
use Illuminate\Http\Request;
use Sisukma\V2\Models\Layanan;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class LayananController extends Controller implements HasMiddleware
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
        $data = Layanan::with('skpd', 'unit')->withCount('respons')
            ->when($request->user()->isSkpd(), function ($query) use ($request) {
                return $query->whereSkpdId($request->user()->skpd->id); // Ganti dengan kondisi yang sesuai
            })->orderBy('skpd_id')
            ->get();
        return view('sisukma::admin.layanan.index', compact('data'));
    }
    public function form(Request $request, Layanan $layanan)
    {
        abort_if($request->user()->isSkpd() && $layanan->exists && $layanan->skpd_id != $request->user()->skpd->id, '403', 'Aksi tidak di izinkan');
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

    }
    public function store(Request $request)
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

    public function update(Request $request, Layanan $layanan)
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
    public function destroy(Request $request, Layanan $layanan)
    {
        abort_if($request->user()->isSkpd() && $layanan->skpd_id != $request->user()->skpd->id, 403, 'Aksi tidak di izinkan');
        if ($layanan->respons()->exists()) {
            if ($request->user()->isAdmin()) {
                $layanan->delete();
                return to_route('layanan.index')->with('success', 'Data Berhasil Dihapus');
            } else {
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

}