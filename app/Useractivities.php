<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Comments;
use App\Activities;
use App\Useractivities;
use App\Useractivityreports;

class Useractivities extends Model
{
    public $table = "user_activities";

    public $fillable = ['activities', 'day', 'time', 'resident', 'type', 'comment', 'other_comment', 'file', 'status', 'sign_date', 'start_day', 'end_day'];

    public function getActivities($id) 
    {
    	if (@$id) {
    		$user_activities = Useractivities::where('id', $id)->first();
    		if (@$user_activities) {
    			$user_activities_id = $user_activities->activities;
    			$activities = Activities::where('id', $user_activities_id)->first();
    		}else{
    			$activities = "";	
    		}
    	}else{
    		$activities = "";
    	}

    	return $activities;
    }

    public function getResident($id) 
    {
    	if (@$id) {
    		$user_activities = Useractivities::where('id', $id)->first();
    		if (@$user_activities) {
    			$user_resident_id = $user_activities->resident;
    			$user = User::where('id', $user_resident_id)->first();
    		}else{
    			$user = "";	
    		}
    	}else{
    		$user = "";
    	}

    	return $user;
    }

    public function getTypeasstring($id) 
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
        $activities = Useractivities::where('id', $id)->first();

        if($activities) {
            Storage::disk('public_local')->delete('uploads/', $activities->file);
            $activities->file = $file->hashName();
            $activities->update();
        }
    }

    /**
    * Get status
    * @param id
    * @return status name as string
    * @since 2020-11-02
    */
    public static function getStatus($status)
    {
        $str = '';

        switch ($status) {
            case '1':
                $str = "Assigning";
                break;
            case '2':
                $str = "Assigned";
                break;
            default:
                $str = "Assigning";
                break;
        }

        return $str;
    }

    /**
    * Get comment data by ID
    * @param user_activities table id
    * @return comment name as string
    * @since 2020-11-02
    */
    public static function getCommentById($id)
    {
        if (@$id) {
            $res = Useractivities::where('id', $id)->first();
            if (@$res->comment) {
                if ($res->comment == -1) {
                    $name = "Other : ".$res->other_comment;
                }else {
                    $result = Comments::where('id', $res->comment)->first();
                    if (@$result) {
                        $name = $result->name;
                    }else{
                        $name = '';
                    }
                }
            }else{
                $name = '';
            }
        }
        else{
            $name = '';
        }

        return $name;
    }

    /**
    * Get type name by ID
    * @param user_activities table id
    * @return comment name as string
    * @since 2020-11-02
    */
    public static function getTypename($id)
    {
        $str = '';

        switch ($id) {
            case '1':
                $str = "Daily";
                break;
            case '2':
                $str = "Weekly";
                break;
            default:
                $str = "Monthly";
                break;
        }

        return $str;
    }

    /**
    * get counts of between days as type
    * @param user_activities table id
    * @return boolean true or false
    * @since 2020-11-12
    * @author Nemanja
    */
    public static function getCalculateDaysById($id)
    {
        if (@$id) {
            $record = Useractivities::where('id', $id)->first();
            if (@$record) {
                $report = Useractivityreports::where('assign_id', $id)->latest()->first();
                if (@$report) {
                    $report_date = User::formatdate($report['sign_date']);
                    $type = $record->type;
                    
                    $dates = User::getformattime();
                    $current_date = $dates['dates'];

                    $diff_days = date_diff(date_create($report_date), date_create($current_date));
                    $diff_counts = $diff_days->format('%a');

                    switch ($type) {
                        case '1':   //daily case
                            $dd = 1;
                            break;

                        case '2':   //weekly case
                            $dd = 7;
                            break;

                        case '3':   //monthly case
                            $dd = 31;
                            break;
                        
                        default:
                            # code...
                            break;
                    }

                    if ($diff_counts >= $dd) {
                        return 1;
                    }else{
                        return -1;
                    }
                }else{
                    return 1;
                }
            }
        }
    }
}
