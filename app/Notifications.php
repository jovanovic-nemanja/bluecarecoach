<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    public $table = "notifications";

    public $fillable = ['user_name', 'resident_name', 'contents', 'is_read', 'sign_date'];
}
