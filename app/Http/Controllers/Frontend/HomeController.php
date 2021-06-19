<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\User;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Twilio\Rest\Client; 
use Twilio\Jwt\AccessToken;

class HomeController extends Controller
{
    public function __construct(){

        $this->middleware('auth')->except(['sendSMS']);

    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')
                            ->select('users.*', 'caregiving_licenses.name as license')
                            ->Join('role_user', 'role_user.user_id', '=', 'users.id')
                            ->LeftJoin('caregiving_licenses', 'caregiving_licenses.id', '=', 'users.care_giving_license')
                            ->where('role_user.role_id', 3)
                            ->get();

        // $cur_date = User::getformattime();
        // $cur_day = Carbon::parse($cur_date['dates']);

        // $expiredCredentials = DB::table('credential_users')
        //                         ->select('credential_users.file_name', 'credential_users.expire_date', 'credentials.title', 'users.email', 'users.firstname')
        //                         ->Join('credentials', 'credentials.id', '=', 'credential_users.credentialid')
        //                         ->Join('users', 'users.id', '=', 'credential_users.userid')
        //                         ->whereDate('credential_users.expire_date', '>=', $cur_day)
        //                         ->get();
        
        // if (@$expiredCredentials) {
        //     foreach ($expiredCredentials as $expiredCredential) {
        //         $expire_date = Carbon::parse($expiredCredential->expire_date);
        //         if ($expiredCredential->expire_date == NULL) {
        //             # code...
        //         }else{
        //             $today = Carbon::parse($cur_date['date']); 
        //             $diff_in_months = $today->diffInDays($expire_date);

        //             $username = $expiredCredential->firstname;
        //             // $useremail = $expiredCredential->email;
        //             $useremail = "jovanovic.nemanja.1029@gmail.com";

        //             $subject = "Please check and update your credential. It can be expire in 1 months.";
        //             $data = [];
        //             $data['name'] = $username;
        //             $data['body'] = "Hello! Welcome to Bluely document organizer. Thank you for uploading credentials. <br> Your credential can be expire in 1 months now. Please check it and update your credential - ".$expiredCredential->title.". <br> Thanks for your checking our E-mail. <br> Kindly regards.";

        //             // if ($diff_in_months == 30) {
        //             //     Mail::send('frontend.mail.expiredemail', $data, function($message) use ($username, $useremail, $subject) {
        //             //         $message->to($useremail, $username)->subject($subject);
        //             //         $message->from('solaris.dubai@gmail.com', 'Administrator');
        //             //     });
        //             // }

        //             Mail::send('frontend.mail.expiredemail', $data, function($message) use ($username, $useremail, $subject) {
        //                 $message->to($useremail, $username)->subject($subject);
        //                 $message->from('solaris.dubai@gmail.com', 'Administrator');
        //             });
        //         }
        //     }
        // }

        return view('frontend.home', compact('users'));
    }

    /**
     * Show the application users info by filtered looking for job status 0 or 1.
     * @author Nemanja
     * @since 2021-06-08
     * @return \Illuminate\Http\Response
     */
    public function indexbyfilter(Request $request)
    {
        if (@$request->looking_job) {
            if ($request->looking_job == "1") {
                $users = DB::table('users')
                            ->select('users.*', 'caregiving_licenses.name as license')
                            ->Join('role_user', 'role_user.user_id', '=', 'users.id')
                            ->LeftJoin('caregiving_licenses', 'caregiving_licenses.id', '=', 'users.care_giving_license')
                            ->where('role_user.role_id', 3)
                            ->where('users.looking_job', 1)
                            ->get();

                $actived = "1";
            }else{
                $users = DB::table('users')
                            ->select('users.*', 'caregiving_licenses.name as license')
                            ->Join('role_user', 'role_user.user_id', '=', 'users.id')
                            ->LeftJoin('caregiving_licenses', 'caregiving_licenses.id', '=', 'users.care_giving_license')
                            ->where('role_user.role_id', 3)
                            ->where('users.looking_job', '!=', 1)
                            ->get();

                $actived = "2";
            }                
        }else{
            $users = DB::table('users')
                        ->select('users.*', 'caregiving_licenses.name as license')
                        ->Join('role_user', 'role_user.user_id', '=', 'users.id')
                        ->LeftJoin('caregiving_licenses', 'caregiving_licenses.id', '=', 'users.care_giving_license')
                        ->where('role_user.role_id', 3)
                        ->get();

            $actived = "0";
        }

        return view('frontend.indexhome', compact('users', 'actived'));
    }

    public function sendSMS(Request $request)
    {
        try {
            $sid    = "ACd4e5ed72783f7d33ef1f0b3347c407ba"; 
            $token  = "ddca32cc5409e5343a40f1176f82382f"; 
            $twilio = new Client($sid, $token); 
             
            $message = $twilio->messages 
                ->create("+971586770127",
                    [
                        "body" => "Hello! Welcome to Bluely document organizer.",
                        "from" => "971586770127"
                    ] 
                );
        } catch (TwilioException $e) {
            $result = 'Twilio replied with: ' . $e;
        }
    }
}
