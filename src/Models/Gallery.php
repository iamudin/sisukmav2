<?php
namespace Sisukma\V2\Models;
use Illuminate\Database\Eloquent\Model;
class Gallery extends Model
{
    protected $fillable = ['skpd_id','nama','slug','aktif'];

    public function images(){
        return $this->hasMany(ImgGallery::class);
    }

    public function skpd(){
        return $this->belongsTo(Skpd::class);
    }

}
