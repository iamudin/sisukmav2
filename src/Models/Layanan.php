<?php
namespace Sisukma\V2\Models;
use Illuminate\Database\Eloquent\Model;


class Layanan extends Model
{
    protected $fillable = ['nama_layanan','skpd_id','unit_id'];

    public function skpd(){
        return $this->belongsTo(Skpd::class);
    }
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function respons(){
        return $this->hasMany(Respon::class);
    }
}
