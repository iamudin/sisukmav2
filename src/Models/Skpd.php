<?php
namespace Sisukma\V2\Models;
use Illuminate\Database\Eloquent\Model;


class Skpd extends Model
{
    protected $fillable = ['nama_skpd','dibatasi','alamat','banner','maps','sort','sort','permalink','email','website','total_unsur','telp'];

    public function user(){
        return $this->hasOne(User::class);
    }
    public function layanans(){
        return $this->hasMany(Layanan::class);
    }
    public function units(){
        return $this->hasMany(Unit::class);
    }

    public function periode_aktif(){
        return $this->hasMany(Periode::class);
    }
}
