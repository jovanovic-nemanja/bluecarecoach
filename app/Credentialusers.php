<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credentialusers extends Model
{
    public $fillable = ['userid', 'credentialid', 'file_name', 'expire_date', 'sign_date'];

    public $table = 'credential_users';
}
