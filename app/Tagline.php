<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Tagline extends Model
{
    public $fillable = ['description', 'type', 'sign_date'];
    public $table = 'tagline';

    public static function getType($type) {
    	switch ($type) {
    		case '1':
    			$string = "Home screen area";
    			break;

    		case '2':
    			$string = "Profile screen area";
    			break;
    		
    		default:
    			$string = "Home screen area";
    			break;
    	}
    	return $string;
    }
}
