<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Medications;
use App\Usermedications;


class Usermedications extends Model
{
    public $table = "user_medications";

    public $fillable = ['assign_id', 'user', 'resident', 'comment', 'sign_date'];

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
        $medications = Usermedications::where('id', $id)->first();

        if($medications) {
            Storage::disk('public_local')->delete('uploads/', $medications->file);
            $medications->file = $file->hashName();
            $medications->update();
        }
    }

    public function getResident($id) 
    {
    	if (@$id) {
    		$user_medications = Usermedications::where('id', $id)->first();
    		if (@$user_medications) {
    			$user_resident_id = $user_medications->resident;
    			$user = User::where('id', $user_resident_id)->first();
    		}else{
    			$user = "";	
    		}
    	}else{
    		$user = "";
    	}

    	return $user;
    }

    public static function getassignedMedication($id)
    {
        if (@$id) {
            $result = Usermedications::where('assign_id', $id)->first();
            if (@$result) {
                return 2;
            }else{
                return 1;
            }
        }else{
            return 1;
        }
    }
}
