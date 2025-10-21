<?php
namespace Sisukma\V2\Models;
use Illuminate\Database\Eloquent\Model;


class Unsur extends Model
{
    protected $fillable = ['nama_unsur','a','b','c','d','urutan','kategori_unsur_id'];

    public function skpd(){
        return $this->belongsTo(Skpd::class);
    }

    public function kategoriUnsur(){
        return $this->belongsTo(KategoriUnsur::class);
    }
}
