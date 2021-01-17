<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Medications;
use App\Assignmedications;

class Assignmedications extends Model
{
    public $table = "assign_medications";

    public $fillable = ['medications', 'dose', 'resident', 'route', 'sign_date', 'time', 'start_day', 'end_day'];

    public static function getMedications($id) 
    {
    	if (@$id) {
    		$user_medications = Assignmedications::where('id', $id)->first();
    		if (@$user_medications) {
    			$user_medications_id = $user_medications->medications;
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
    		$user_medications = Assignmedications::where('id', $id)->first();
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

    public static function getRemainingDays($start)
    {
        if (@$start) {
            $current = User::getformattime();
            $cur_date = $current['dates'];

            $startTimeStamp = strtotime($start);
            $endTimeStamp = strtotime($cur_date);

            $timeDiff = abs($endTimeStamp - $startTimeStamp);

            $numberDays = $timeDiff/86400;  // 86400 seconds in one day

            // and you might want to convert to integer
            $numberDays = intval($numberDays);
            return $numberDays;
        }
    }
}
