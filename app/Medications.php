<?php

namespace App;

use App\Medications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Medications extends Model
{
    public $table = "medications";

    public $fillable = ['name', 'dose', 'photo', 'comments', 'sign_date'];

    /**
    * @param user_id
    * This is a feature to upload a company logo
    */
    public static function upload_file($id, $existings = null) {
        if(!request()->hasFile('photo')) {
            return false;
        }

        Storage::disk('public_local')->put('uploads/', request()->file('photo'));

        self::save_file($id, request()->file('photo'));
    }

    public static function save_file($id, $file) {
        $medication = Medications::where('id', $id)->first();

        if($medication) {
            Storage::disk('public_local')->delete('uploads/', $medication->photo);
            $medication->photo = $file->hashName();
            $medication->update();
        }
    }
}
