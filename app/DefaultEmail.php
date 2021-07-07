<?php

namespace App;

use App\DefaultEmail;
use Illuminate\Database\Eloquent\Model;

class DefaultEmail extends Model
{
    public $fillable = ['address', 'sign_date'];

    public $table = 'default_email';
}
