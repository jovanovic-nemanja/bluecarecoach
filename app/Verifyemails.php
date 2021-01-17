<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Verifyemails extends Model
{
    public $fillable = ['email', 'verify_code', 'password'];

    public $table = 'verify_emails';
}
