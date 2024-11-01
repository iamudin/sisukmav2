<?php
namespace Sisukma\V2\Models;
use Illuminate\Database\Eloquent\Model;


class Unit extends Model
{
    protected $fillable = ['nama','skpd_id'];

    public function skpd(){
        return $this->belongsTo(Skpd::class);
    }
    public function layanans(){
        return $this->hasMany(Layanan::class);
    }
}
