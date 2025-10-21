@extends('sisukma::layout.app')
@section('content')
    <div class="container-fluid px-4">
        <h3 class="mt-4"><i class="fas fa-gear"></i> Pengaturan</h3>

        <div class="row">
            <div class="col-lg-12">
                <form action="{{ URL::current() }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="">Status 16 Unsur</label><br>
                        <input type="radio" value="aktif" name="unsur_16" {{ Cache::has('unsur_16') ? 'checked' : '' }}> Aktif 
                        <input type="radio" value="nonaktif" name="unsur_16" {{ !Cache::has('unsur_16') ? 'checked' : '' }}> Tidak Aktif
                    </div>
                    <div class="form-group mt-2">
                        <label for="">Layanan Uji Coba 16 Unsur</label><br>
                        <input type="text" name="layanan_ujicoba" value="{{ Cache::get('layanan_ujicoba') }}">
                    </div>
                    <div class="form-group mt-4">
                        <button class="btn btn-sm btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div></div>


@endsection