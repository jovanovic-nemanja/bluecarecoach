<?php

namespace App;

use App\Credentialusers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Credentialusers extends Model
{
    public $fillable = ['userid', 'credentialid', 'file_name', 'expire_date', 'sign_date'];
    public $appends = ['expired'];
    public $table = 'credential_users';

    /**
    * Credential file upload
    * @param credential id
    * @author Nemanja
    * @since 2021-01-30
    * This is a feature to upload a credential file
    */
    public static function Upload_credentialfile($credentialid, $existings = null) 
    {
        if(!request()->hasFile('credentialfile')) {
            return false;
        }

        Storage::disk('public_local')->put('uploads/', request()->file('credentialfile'));

        self::save_file($credentialid, request()->file('credentialfile'));
    }

    /**
    * file upload
    * @param credential id and photo file
    * @return boolean true or false
    * @since 2021-01-30
    * @author Nemanja
    */
    public static function save_file($credentialid, $file) {
        $record = Credentialusers::where('id', $credentialid)->first();

        if(@$record) {
            Storage::disk('public_local')->delete('uploads/', $record->file_name);
            $record->file_name = $file->hashName();
            $record->update();
        }
    }

    public function getExpiredAttribute($value)
    {
        return date_diff(date_create($this->expire_date), date_create($this->sign_date))->invert;
    }
}
