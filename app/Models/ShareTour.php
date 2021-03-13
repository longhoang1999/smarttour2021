<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareTour extends Model
{
    protected $table = "sharetour";
    protected $primaryKey = "sh_id";
    public $timestamps = false;
}
