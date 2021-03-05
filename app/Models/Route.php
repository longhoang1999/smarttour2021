<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table = "route";
    protected $primaryKey = "ro_id";
    public $timestamps = false;
}

