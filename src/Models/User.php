<?php
namespace Sisukma\V2\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['last_login_at','last_login_ip','nama','email','password','username','skpd_id','status'];
    public function isAdmin(){
        return $this->level=='admin';
    }

    public function isSkpd(){
        return $this->level=='skpd';
    }

    public function skpd(){
        return $this->belongsTo(Skpd::class);
    }
}
