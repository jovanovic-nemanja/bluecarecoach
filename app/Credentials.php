<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credentials extends Model
{
    public $fillable = ['title', 'created_by', 'care_licenses', 'sign_date'];

    public $table = 'credentials';
}
