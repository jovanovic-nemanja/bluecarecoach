<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\User;
use Mail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct(){

        $this->middleware('auth');

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
}
