<?php

namespace App;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Adminlogs extends Model
{
    public $table = "admin_logs";

    public $fillable = ['content', 'caretakerId', 'sign_date'];

    /**
    * @param time
    * @return time
    * @since 2020-10-16
    * @author Nemanja
    */
    public static function Addlogs($data)
    {
    	if (@$data) {
    		$dates = User::getformattime();
	        $date = $dates['date'];
	        $time = $dates['time'];

    		$res = Adminlogs::create([
	            'caretakerId' => $data['caretakerId'],
	            'content' => $data['content'],
	            'sign_date' => $date,
	        ]);

	        return true;
    	}

    	return false;
    }
}
