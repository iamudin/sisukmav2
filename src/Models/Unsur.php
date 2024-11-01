<?php
namespace Sisukma\V2\Models;
use Illuminate\Database\Eloquent\Model;


class Unsur extends Model
{
    protected $fillable = ['nama_unsur','a','b','c','d','urutan'];

    public function skpd(){
        return $this->belongsTo(Skpd::class);
    }
}
