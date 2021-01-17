<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Bodyharmcomments extends Model
{
    public $table = "body_harm_comments";

    public $fillable = ['name', 'sign_date'];
}
