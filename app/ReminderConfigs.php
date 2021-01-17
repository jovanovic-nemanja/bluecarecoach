<?php

namespace App;

use App\ReminderConfigs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ReminderConfigs extends Model
{
    public $table = "reminder_configs";

    public $fillable = ['minutes', 'active', 'sign_date'];

    public static function getActiveasString($active)
    {
    	if (@$active) {
    		$result = "Actived";
    	}else{
    		$result = "None";
    	}

    	return $result;
    }
}
