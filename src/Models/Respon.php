<?php
namespace Sisukma\V2\Models;
use Illuminate\Database\Eloquent\Model;


class Respon extends Model
{


    protected $casts = [
        'created'=>'datetime',
        'tgl_survei'=>'datetime',
    ];
    protected $fillable = ['layanan_id','tgl_survei','usia','nik','jam_survei','jenis_kelamin','pendidikan','pekerjaan','pekerjaan2','u1','u2','u3','u4','u5','u6','u7','u8','u9','u10','u11','u12','u13','u14','u16','saran','created','reference','disabilitas','jenis_disabilitas'];

    public function layanan(){
        return $this->belongsTo(Layanan::class);
    }

}
