<?php

namespace App;

use App\User;
use App\Bodyharms;
use App\Bodyharmcomments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Bodyharms extends Model
{
    public $table = "body_harms";

    public $fillable = ['resident', 'comment', 'sign_date', 'screenshot_3d'];

    /**
    * @return file name
    * This is a feature to upload a screen shot image
    */
    public static function upload_file($file) 
    {
        $image = $file;

        $location = "uploads/";

        $image_parts = explode(";base64,", $image);

        $image_base64 = base64_decode($image_parts[1]);

        $filename = "screenshot_".uniqid().'.png';

        $file = $location . $filename;

        if (file_put_contents($file, $image_base64)) {
            $result = $filename;
        }else{
            $result = "";
        }

        return $result;
    }

    public static function deleteUploadedfile($image) {
        Storage::disk('public_local')->delete('uploads/', $image);
    }

    public static function getCommentbystring($comment_id)
    {
    	if (@$comment_id) {
    		$res = Bodyharmcomments::where('id', $comment_id)->first();
    	}else{
    		$res = [];
    	}

    	return $res->name;
    }
}
