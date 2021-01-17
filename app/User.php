<?php

namespace App;

use App\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'middlename', 'lastname', 'username', 'email', 'birthday', 'street1', 'street2', 'city', 'zip_code', 'state', 'password', 'phone_number', 'sign_date', 'gender', 'email_verified_at', 'profile_logo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles() {
    	return $this->belongsToMany('App\Role');
    }

    public function hasRole($role_name) {
        foreach ($this->roles()->get() as $role)
        {
            if ($role->name == $role_name)
            {
                return true;
            }
        }

        return false;
    }

    /**
    * @param user id
    * @return user name
    * @since 2020-10-16
    * @author Nemanja
    */
    public function getUsername($id) {
        if (@$id) {
            $user = User::where('id', $id)->first();
            $name = $user->firstname;
        }

        return $name;
    }

    /**
    * @param user_id
    * This is a feature to upload a profile logo
    */
    public static function upload_logo_img($user_id, $existings = null) {
        if(!request()->hasFile('profile_logo')) {
            return false;
        }

        Storage::disk('public_local')->put('uploads/', request()->file('profile_logo'));

        self::save_logo_img($user_id, request()->file('profile_logo'));
    }

    /**
    * file upload
    * @param userid and photo file
    * @return boolean true or false
    * @since 2020-10-16
    * @author Nemanja
    */
    public static function save_logo_img($user_id, $image) {
        $user = User::where('id', $user_id)->first();

        if($user) {
            Storage::disk('public_local')->delete('uploads/', $user->profile_logo);
            $user->profile_logo = $image->hashName();
            $user->update();
        }
    }

    /**
    * @return date, datetime, time
    * @since 2020-10-16
    * @author Nemanja
    */
    public static function getformattime()
    {
        $timeZone = 'America/Los_Angeles';
        date_default_timezone_set($timeZone);
        $date = date('Y-m-d H:i:s');
        $dates = date('Y-m-d');
        $time = date('H:i:s');
        $arr = [];
        $arr['date'] = $date;
        $arr['dates'] = $dates;
        $arr['time'] = $time;

        return $arr;
    }

    /**
    * @param datetime
    * @return date
    * @since 2020-11-12
    * @author Nemanja
    */
    public static function formatdate($date)
    {
        $timeZone = 'America/Los_Angeles';
        date_default_timezone_set($timeZone);
        $dates = date_create($date, timezone_open('America/Los_Angeles'));
        $cur_date = date_format($dates, 'Y-m-d');

        return $cur_date;
    }

    /**
    * @param time
    * @return time
    * @since 2020-10-16
    * @author Nemanja
    */
    public static function formattime($time)
    {
        $timeZone = 'America/Los_Angeles';
        date_default_timezone_set($timeZone);
        $date = date_create($time, timezone_open('America/Los_Angeles'));
        $time = date_format($date, 'H:i:s');

        return $time;
    }

    /**
    * @param time
    * @return time
    * @since 2020-10-16
    * @author Nemanja
    */
    public static function formattime1($time)
    {
        $timeZone = 'America/Los_Angeles';
        date_default_timezone_set($timeZone);
        $date = date_create($time, timezone_open('America/Los_Angeles'));
        $time = date_format($date, 'H:i');

        return $time;
    }

    /**
    * @param id is user table id
    * @return user name
    * @since 2020-11-10
    * @author Nemanja
    */
    public static function getUsernameById($id) {
        if (@$id) {
            $user = User::where('id', $id)->first();
            $name = $user->firstname;
        }

        return $name;
    }

    /**
    * @param gender integer ID
    * @return gender as string
    * @since 2020-12-21
    * @author Nemanja
    */
    public static function getGender($genderId) {
        switch ($genderId) {
            case '0':
                $gender = "Male";
                break;

            case '1':
                $gender = "Female";
                break;
            
            default:
                $gender = "";
                break;
        }

        return $gender;
    }
}
