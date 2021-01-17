<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

use App\Comments;

class Activities extends Model
{
    public $table = "activities";

    public $fillable = ['title', 'type', 'comments', 'sign_date'];

    public static function getTypeasstring($id) 
    {
    	if (@$id) {
    		if ($id == 1) {
    			$result = "Primary ADL";
    		}if ($id == 2) {
    			$result = "Secondary ADL";
    		}
    	}else{
    		$result = "None";
    	}

    	return $result;
    }

    public static function getCommentsByID($id) 
    {

    }
}
