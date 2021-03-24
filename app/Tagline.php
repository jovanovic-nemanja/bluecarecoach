<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Tagline extends Model
{
    public $fillable = ['description', 'sign_date'];
    public $table = 'tagline';
}
