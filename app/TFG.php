<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Medications;
use App\TFG;

class TFG extends Model
{
    public $table = "tfg";

    public $fillable = ['medications', 'time', 'resident', 'comment', 'file', 'status', 'sign_date'];

    public function getMedication($id) 
    {
    	if (@$id) {
    		$tfg = TFG::where('id', $id)->first();
    		if (@$tfg) {
    			$user_medications_id = $tfg->medications;
    			$medications = Medications::where('id', $user_medications_id)->first();
    		}else{
    			$medications = "";	
    		}
    	}else{
    		$medications = "";
    	}

    	return $medications;
    }

    public function getResident($id) 
    {
    	if (@$id) {
    		$tfg = TFG::where('id', $id)->first();
    		if (@$tfg) {
    			$user_resident_id = $tfg->resident;
    			$user = User::where('id', $user_resident_id)->first();
    		}else{
    			$user = "";	
    		}
    	}else{
    		$user = "";
    	}

    	return $user;
    }

    /**
    * @param user_id
    * This is a feature to upload a company logo
    */
    public static function upload_file($id, $existings = null) {
        if(!request()->hasFile('file')) {
            return false;
        }

        Storage::disk('public_local')->put('uploads/', request()->file('file'));

        self::save_file($id, request()->file('file'));
    }

    public static function save_file($id, $file) {
        $tfg = TFG::where('id', $id)->first();

        if($tfg) {
            Storage::disk('public_local')->delete('uploads/', $tfg->file);
            $tfg->file = $file->hashName();
            $tfg->update();
        }
    }
}
