<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $table = "destination";
    protected $primaryKey = "de_id";
    public $timestamps = false;
}
