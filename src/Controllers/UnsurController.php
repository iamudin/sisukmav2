<?php
namespace Sisukma\V2\Controllers;
use Illuminate\Http\Request;
use Sisukma\V2\Models\Unsur;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class UnsurController extends Controller implements HasMiddleware
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
        $data = Unsur::orderBy('urutan')->get();
        return view('sisukma::admin.unsur.index', compact('data'));
    }
    public function form(Request $request, Unsur $unsur)
    {
        abort_if(!$request->user()->isAdmin(), '403', 'Akses tidak dizinkan');
        $edit = $unsur;
        return view('sisukma::admin.unsur.form', compact('edit'));
    }
    public function store(Request $request)
    {
        if ($request->user()->isAdmin()) {
            Unsur::create([
                'nama_unsur' => $request->nama_unsur,
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
    public function update(Request $request, Unsur $unsur)
    {
        if ($request->user()->isAdmin()) {
            $unsur->update([
                'nama_unsur' => $request->nama_unsur,
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
    public function destroy(Request $request, Unsur $unsur)
    {
        if ($request->user()->isAdmin()) {
            $unsur->delete();
            return to_route('unsur.index')->with('success', 'Berhasil');
        }
        abort('403', 'Akses tidak dizinkan');
    }
}