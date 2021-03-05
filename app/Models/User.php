<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
   	protected $table = "user";
   	protected $fillable = array('us_email', 'us_password');
    public $timestamps = false;
    public static $rules = array();
    protected $primaryKey = "us_id";
    protected $hidden = [
        'us_password',
    ];
    public function getAuthPassword()
    {
        return $this->us_password;
    }
}
