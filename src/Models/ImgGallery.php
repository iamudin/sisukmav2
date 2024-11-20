<?php
namespace Sisukma\V2\Models;
use Illuminate\Database\Eloquent\Model;
class ImgGallery extends Model
{
    protected $fillable = ['gallery_id','path','caption'];

    public function gallery(){
        return $this->belongsTo(Gallery::class);
    }

}
