<?php

namespace App;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Vitalsign extends Model
{
    public $table = "vital_sign";

    public $fillable = ['data', 'resident_id', 'type', 'sign_date'];

    /**
    * get type as string
    * @param type as integer
    * @author Nemanja
    * @since 202-12-24
    */
    public static function getType($type) {
    	switch ($type) {
    		case '1':
    			$result = "Temperature";
    			break;

    		case '2':
    			$result = "Blood Pressure";
    			break;

    		case '3':
    			$result = "Heart Rate";
    			break;
    		
    		default:
    			$result = "Temperature";
    			break;
    	}

    	return $result;
    }
}
