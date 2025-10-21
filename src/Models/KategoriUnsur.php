<?php
namespace Sisukma\V2\Models;
use Illuminate\Database\Eloquent\Model;


class KategoriUnsur extends Model
{
    protected $fillable = ['nama','urutan'];

    public function unsurs()
    {
        return $this->hasMany(Unsur::class);
    }
}
