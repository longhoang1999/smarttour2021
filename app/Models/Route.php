<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table = "tour";
    protected $primaryKey = "to_id";
    public $timestamps = false;
}

