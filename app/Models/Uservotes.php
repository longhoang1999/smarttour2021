<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uservotes extends Model
{
    protected $table = "uservotes";
    protected $primaryKey = "id";
    public $timestamps = false;
}
