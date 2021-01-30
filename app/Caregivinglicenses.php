<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caregivinglicenses extends Model
{
    public $fillable = ['name', 'sign_date'];

    public $table = 'caregiving_licenses';
}
