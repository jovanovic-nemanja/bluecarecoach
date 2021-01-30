<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credentials extends Model
{
    public $fillable = ['title', 'sign_date'];

    public $table = 'credentials';
}
