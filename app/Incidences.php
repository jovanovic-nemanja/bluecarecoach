<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Incidences extends Model
{
    public $table = "incidences";

    public $fillable = ['title', 'type', 'content', 'sign_date'];

    public function getTypeasstring($id) 
    {
    	if (@$id) {
    		if ($id == 1) {
    			$result = "Family Visit";
    		}if ($id == 2) {
    			$result = "Mood Change";
    		}if ($id == 3) {
    			$result = "Body Harm";
    		}
    	}else{
    		$result = "None";
    	}

    	return $result;
    }
}
