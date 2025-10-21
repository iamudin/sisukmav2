<?php
namespace Sisukma\V2\Models;
use Illuminate\Database\Eloquent\Model;


class Evaluasi extends Model
{
    protected $fillable = ['unsur_perbaikan','rencana_tindak_lanjut','rtl','tahun','layanan_id'];

    public function layanan(){
        return $this->belongsTo(Layanan::class);
    }
}
