<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = "langplace";
    protected $primaryKey = "lang_id";
    public $timestamps = false;
}
