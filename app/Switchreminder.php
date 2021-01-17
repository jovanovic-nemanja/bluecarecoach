<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Switchreminder extends Model
{
    public $table = "switch_reminder";

    public $fillable = ['status', 'set_time'];
}
