<?php

namespace App;

use App\EmailSettings;
use Illuminate\Database\Eloquent\Model;

class EmailSettings extends Model
{
    public $fillable = ['type', 'from_address', 'from_title', 'subject', 'content_name', 'content_body', 'pre_footer', 'footer', 'sign_date'];

    public $table = 'email_settings';

    public static function getType($val)
    {
        switch ($val) {
            case '1':   //Email verify
                $result = 'Email Verification';
                break;
            case '2':   //Forgot Password
                $result = 'Forgot Password';
                break;
            case '3':   //changed looking for job status
                $result = 'Looking For Job Status';
                break;
            case '4':   //Expiry reminder cronjob
                $result = 'Expiry Reminder Cronjob';
                break;
            case '5':   //Expiry reminder template when user uploaded the document as less 30 days than now.
                $result = 'Expiry Reminder Schedule less 31days';
                break;
            
            default:
                $result = 'Email verification';
                break;
        }
        return $result;
    }
}
