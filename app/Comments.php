<?php

namespace App;

use App\Useractivityreports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    public $table = "comments";

    public $fillable = ['name', 'type', 'ref_id', 'sign_date'];

    public static function getCommentsname($id)
    {
    	if (@$id) {
    		$result = Comments::where('id', $id)->first();
    	}

    	return $result['name'];
    }

    /**
    * Get comment name by ID
    * @param user_activities table id
    * @return comment name as string
    * @since 2020-11-28
    */
    public static function getCommentById($id)
    {
        if (@$id) {
            $res = Useractivityreports::where('assign_id', $id)->first();
            if (@$res->comment) {
            	$result = Comments::where('id', $res->comment)->first();
                $name = $result->name;
            }else{
                $name = '';
            }
        }
        else{
            $name = '';
        }

        return $name;
    }
}
