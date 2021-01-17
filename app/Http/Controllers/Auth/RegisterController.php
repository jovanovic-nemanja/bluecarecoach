<?php

namespace App\Http\Controllers\Auth;

use Mail;
use Session;
use App\User;
use App\Role;
use App\RoleUser;
use App\Verifyemails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\Http\Controllers\Frontend\EmailsController;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'string|max:255',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if(!@$data['role']) {
            return false;
        }else{
            if($data['role'] == 2) {    //careowner
                $role = 2;
            }else if($data['role'] == 3) {    //caregiver
                $role = 3;
            }else{
                
            }

            $verify = Verifyemails::where('email', $data['email'])->first();
            $data['password'] = $verify->password;

            DB::beginTransaction();

            try {
                $user = User::create([
                    'firstname' => $data['firstname'],
                    'lastname' => $data['lastname'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'phone_number' => @$data['phone_number'],
                    'sign_date' => date('Y-m-d h:i:s'),
                ]);

                User::Upload_avatar($user->id);

                RoleUser::create([
                    'user_id' => $user->id,
                    'role_id' => $role,
                ]);

                DB::commit();

                $controller = new EmailsController;
                $array = [];
                
                $array['username'] = $data['firstname'];
                $array['receiver_address'] = $data['email'];
                $array['data'] = array('name' => $array['username'], "body" => "Welcome for sign up our site!");
                $array['subject'] = "Successfully sign up your account.";
                $array['sender_address'] = "jovanovic.nemanja.1029@gmail.com";

                $controller->save($array);

                return $user;
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }            
        }
    }

    public function signupasowner() 
    {
        return view('auth.register');
    }

    public function signupasgiver() 
    {
        return view('auth.signupasgiver');
    }

    public function emailverifyowner() 
    {
        Session::put('role', 'careowner');
        $role = "careowner";
        $email = '';
        return view('auth.emailverify', compact('role', 'email'));
    }

    public function emailverifygiver() 
    {
        Session::put('role', 'caregiver');
        $role = "caregiver";
        $email = '';
        return view('auth.emailverify', compact('role', 'email'));
    }

    public function emailverifyforresend($email, $role) 
    {
        $role = $role;

        return view('auth.emailverify', compact('role', 'email'));
    }

    public function sendverifycode(Request $request)
    {
        $this->validate(request(), [
            'email'        => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        DB::beginTransaction();

        $str = rand(100000, 999999);
        $url = "https://bluecarecoach.com/directconfirmpage/".$request['email']."/".$request['role']."/".$str;
        $data = [];
        $data['name'] = 'Welcome User,';
        $data['body'] = 'Thank you for registering with us. <br> To complete your registration, please verify your email by clicking on this <a href="'.$url.'">link</a> and entering the following code '.$str.'.<br><br><br><a href="'.$url.'" style="padding: 10px 30px; text-decoration: none; font-size: 24px; border-radius: 0; background-color: #476B91; color: #ffffff;">Verify</a>';

        $useremail = $request['email'];
        $role = $request['role'];
        $username = 'BlueCare Coach';
        $subject = "Verify your email for BlueCare Coach";

        try {
            Mail::send('frontend.mail.maillogin', $data, function($message) use ($username, $useremail, $subject) {
                $message->to($useremail, $username)->subject($subject);
                $message->from('solaris.dubai@gmail.com', 'Administrator');
            });

            $verify = Verifyemails::where('email', $request['email'])->first();
            if (@$verify) {
                $verify->verify_code = $str;
                $verify->password = $request['password'];
                $verify->update();
            }else{
                $Verifyemails = Verifyemails::create([
                    'email' => $useremail,
                    'verify_code' => $str,
                    'password' => $request['password'],
                ]);
            }

            $id = '';
            $msg = '';

            DB::commit();
            return view('auth.confirmverifycode', compact('useremail', 'role', 'id', 'msg'));
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }   
    }

    public function directconfirmpage($email, $role, $id, $msg = null)
    {
        if (@$id && @$role && @$email) {
            $useremail = $email;

            return view('auth.confirmverifycode', compact('useremail', 'role', 'id', 'msg'));
        }
    }

    public function validatecode(Request $request)
    {
        $useremail = $request['email'];
        $role = $request['role'];
        $verify_code = $request['verify_code'];
        $validate = Verifyemails::where('email', $useremail)->first();

        if (@$validate) {
            if ($validate->verify_code == $verify_code) {
                if ($role == 'careowner') {
                    return view('auth/signupasowner', compact('useremail'));
                }
                if ($role == 'caregiver') {
                    return view('auth/signupasgiver', compact('useremail'));
                }
            }else{
                $msg = "Verify codes is failed. ";
                $id = '';
                return view('auth.confirmverifycode', compact('useremail', 'role', 'id', 'msg'));
            }
        }
    }
}
