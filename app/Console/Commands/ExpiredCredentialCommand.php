<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use App\EmailSettings;
use App\DefaultEmail;
use App\Credentialusers;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Mail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExpiredCredentialCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiredcredential:sendemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send an expired email to users.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cur_date = User::getformattime();
        $cur_day = Carbon::parse($cur_date['dates']);

        $expiredCredentials = DB::table('credential_users')
                                ->select('credential_users.file_name', 'credential_users.expire_date', 'credentials.title', 'users.email', 'users.firstname')
                                ->Join('credentials', 'credentials.id', '=', 'credential_users.credentialid')
                                ->Join('users', 'users.id', '=', 'credential_users.userid')
                                ->whereDate('credential_users.expire_date', '>=', $cur_day)
                                ->get();

        if (@$expiredCredentials) {
            foreach ($expiredCredentials as $expiredCredential) {
                $expire_date = Carbon::parse($expiredCredential->expire_date);
                if ($expiredCredential->expire_date == NULL) {
                    # code...
                }else{
                    $today = Carbon::parse($cur_date['date']); 
                    $diff_in_months = $today->diffInDays($expire_date);

                    $username = $expiredCredential->firstname;
                    if (@$username) {
                        // code...
                    }else{
                        $username = "Bluely Credentials";
                    }
                    
                    $useremail = $expiredCredential->email;
                    if (@$useremail) {
                        // code...
                    }else{
                        $defaultemail = DefaultEmail::latest()->take(1)->first();
                        $useremail = ($defaultemail->address != "") ? $defaultemail->address : "core.solutions06@gmail.com";
                    }

                    $emailsettings = EmailSettings::where('type', 4)->first();

                    $subject = $emailsettings->subject;
                    $data = [];
                    $data['name'] = $username;
                    $data['body'] = "Hello! Welcome to Bluely Credentials. Thank you for uploaded your credential document. <br> Your credential can be expire in 1 month now. Please check it and update your credential - ".$expiredCredential->title.". <br> Thanks for your checking our E-mail. <br> Kindly regards. <br> Bluely Credentials.";

                    if ($diff_in_months < 31) {
                        Mail::send('frontend.mail.expiredemail', $data, function($message) use ($username, $useremail, $subject, $emailsettings) {
                            $message->to($useremail, $username)->subject($subject);
                            $message->from($emailsettings->from_address, $emailsettings->from_title);
                        });
                    }
                }
            }
        }
    }
}
