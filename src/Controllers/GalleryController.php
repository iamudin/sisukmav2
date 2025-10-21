<?php
namespace Sisukma\V2\Controllers;
use Illuminate\Support\Str;
use Sisukma\V2\Models\Skpd;
use Sisukma\V2\Models\Unit;
use Illuminate\Http\Request;
use Sisukma\V2\Models\Gallery;
use Sisukma\V2\Models\Layanan;
use Sisukma\V2\Models\ImgGallery;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class GalleryController extends Controller implements HasMiddleware
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
        $data = Gallery::with('images', 'skpd')
            ->when($request->user()->isSkpd(), function ($query) use ($request) {
                return $query->whereSkpdId($request->user()->skpd->id);
            })
            ->latest('created_at')
            ->get();
        return view('sisukma::admin.gallery.index', compact('data'));
    }
    public function form(Gallery $gallery)
    {
        $edit = $gallery;
        return view('sisukma::admin.gallery.form', compact('edit'));
    }
    public function store(Request $request, Gallery $gallery)
    {
        $request->validate([
            'nama' => 'required|string',
            'aktif' => 'required|string',
        ]);
        $data = $gallery->create([
            'nama' => $request->nama,
            'aktif' => $request->aktif,
            'slug' => str($request->nama)->slug() . '-' . Str::random(4)
        ]);
        return to_route('gallery.edit', $data->id)->with('success', 'Gallery Tersimpan');

    }
    public function update(Request $request, Gallery $gallery)
    {
        if ($request->upload) {
            $request->validate([
                'gambar' => 'required|image|max:1024',
                'caption' => 'required|string'
            ]);
            if ($request->hasFile('gambar')) {

                $gallery->images()->create([
                    'path' => $request->file('gambar')->store('gallery', 'public'),
                    'caption' => $request->caption,
                ]);
            }
        } else {
            $gallery->update([
                'nama' => $request->nama,
                'aktif' => $request->aktif
            ]);
        }


        return back()->with('success', 'Berhasil Tersimpan');
    }
    public function destroy(Gallery $gallery)
    {
        abort_if(Auth::user()->skpd->id != $gallery->skpd_id, 404, 'Tidak diizinkan');
        $gallery->delete();
        return back()->with('success', 'Berhasil');
    }
    public function destroyImgGallery(ImgGallery $imgGallery)
    {
        if (Storage::disk('public')->exists($imgGallery->path)) {
            Storage::disk('public')->delete($imgGallery->path);
        }
        $imgGallery->delete();
        return back()->with('success', 'Berhasil');
    }
}