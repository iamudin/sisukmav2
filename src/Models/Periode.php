<?php
namespace Sisukma\V2\Models;
use Illuminate\Database\Eloquent\Model;


class Periode extends Model
{
    public function scopeUnsurTambahan($query){
        return $query->where('unsur_tambahan','Y');
    }
}
