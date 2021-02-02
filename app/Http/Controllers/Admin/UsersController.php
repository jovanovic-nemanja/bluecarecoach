<?php

namespace App\Http\Controllers\Admin;

use Mail;
use App\User;
use App\Role;
use App\RoleUser;
use App\Verifyemails;
use App\Caregivinglicenses;
use App\Credentialusers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'admin'])->except(['store', 'emailverify', 'validateCode', 'loginUserwithApple', 'loginUserwithGoogle', 'loginUserwithFacebook', 'loginUser', 'logout', 'uploadCredentialFile', 'forgotpassword', 'resetpwd', 'resetUserpassword', 'getUserinformation', 'getCredentials', 'getLicenses']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Swift API : User emailverify by iOS mobile.
     *
     * @since 2021-01-29
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emailverify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first()]);
        }

        DB::beginTransaction();

        $str = rand(100000, 999999);
        $data = [];
        $data['name'] = 'Welcome User,';
        $data['body'] = 'Hello! Welcome to Bluely hub. Thank you for registering with us. To complete your sign up process please verify your email address by entering the following code (' . $str . ') on the home screen.';

        $useremail = $request['email'];
        $username = 'Bluely hub';
        $subject = "Verify your email for Bluely hub";

        try {
            Mail::send('frontend.mail.mail', $data, function($message) use ($username, $useremail, $subject) {
                $message->to($useremail, $username)->subject($subject);
                $message->from('solaris.dubai@gmail.com', 'Administrator');
            });

            $verifyuser = Verifyemails::where('email', $useremail)->first();
            if (@$verifyuser) {
                $verifyuser->verify_code = $str;
                $verifyuser->update();
            }else{
                $verifyuser = Verifyemails::create([
                    'email' => $request['email'],
                    'verify_code' => $str,
                ]);
            }

            $result = $verifyuser['email'];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }  

        return response()->json(['status' => "success", 'data' => $result, 'msg' => 'We sent a verification code now. Please check your inbox.']);
    }

    /**
     * Swift API : validate verify code.
     *
     * @since 2021-01-29
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validateCode(Request $request)
    {
        $useremail = $request['email'];
        $verify_codes = $request['code'];
        $validate = Verifyemails::where('email', $useremail)->first();

        if (@$validate) {
            if ($validate->verify_code == $verify_codes) {
                return response()->json(['status' => "success", 'data' => $useremail, 'msg' => 'Successfully validated now.']);
            }else{
                $msg = "Your verification code is invalid. ";
                return response()->json(['status' => "failed", 'data' => $useremail, 'msg' => $msg]);
            }
        }else{
            $msg = "Not found your email address in our records. ";
            return response()->json(['status' => "failed", 'data' => $useremail, 'msg' => $msg]);
        }
    }

    /**
     * Swift API : User register by iOS mobile.
     *
     * @since 2021-01-29
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:190',
            'last_name' => 'required|string|max:190',
            'email' => 'required|string|unique:users|email|max:255', //|unique:users
            'care_giving_license' => 'required|integer',
            'zip_code' => 'required',
            'care_giving_experience' => 'required',
            'birthday' => 'required',
            'phone_number' => 'required',
            'password' => 'required|string|min:6'
        ]);

        $path = env('APP_URL')."uploads/";

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first(), 'path' => $path]);
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'firstname' => $request['first_name'],
                'lastname' => $request['last_name'],
                'email' => $request['email'],
                'birthday' => $request['birthday'],
                'care_giving_license' => $request['care_giving_license'],
                'care_giving_experience' => $request['care_giving_experience'],
                'zip_code' => $request['zip_code'],
                'password' => Hash::make($request['password']),
                'phone_number' => $request['phone_number'],
                'sign_date' => date('Y-m-d h:i:s'),
            ]);

            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => 3,
            ]);

            if (@$request->photo) {
                User::upload_photo($user->id);
            }

            $data = $this->getUserinformation($user->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }  

        return response()->json(['status' => "success", 'data' => $data, 'msg' => 'Successfully registered.', 'path' => $path, 'isNewUser' => 1]);
    }

    /**
     * Swift API : save skills and hobbies.
     *
     * @since 2021-02-01
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveSkillandhobby(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required'
        ]);

        $path = env('APP_URL')."uploads/";

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first(), 'path' => $path]);
        }

        $user = User::where('id', $userid)->first();

        if (@$user) {
            $user->skill1 = @$request['skill1'];
            $user->skill2 = @$request['skill2'];
            $user->skill3 = @$request['skill3'];
            $user->skill4 = @$request['skill4'];
            $user->skill5 = @$request['skill5'];
            $user->hobby1 = @$request['hobby1'];
            $user->hobby2 = @$request['hobby2'];
            $user->hobby3 = @$request['hobby3'];
            $user->hobby4 = @$request['hobby4'];
            $user->hobby5 = @$request['hobby5'];

            $user->update();
        }

        $data = $this->getUserinformation($user->id);

        return response()->json(['status' => "success", 'data' => $data, 'msg' => 'Successfully updated.', 'path' => $path]);
    }

    /**
     * Swift API : User login by apple device.
     *
     * @since 2021-01-29
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginUserwithApple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'apple_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first()]);
        }

        $apple_id = $request->apple_id;
        $user = User::where('apple_id', $apple_id)->first();
        $result = [];

        if (!$user) {   //register
            $user = User::create([
                'apple_id' => $request['apple_id'],
                'email' => @$request['user_mail'],
                'firstname' => @$request['firstname'],
                'lastname' => @$request['lastname'],
                'sign_date' => date('Y-m-d h:i:s'),
            ]);

            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => 3,
            ]);
            
            $result = User::where('id', $user->id)->first();
            $data = $this->getUserinformation($user->id);

            $msg = 'Successfully Logged In.';
            $isNewUser = 1;
        }else{
            $result = $user;
            $data = $this->getUserinformation($user->id);
            $msg = 'Successfully Logged In.';
            $isNewUser = 0;
        }

        return response()->json(['status' => 'success', 'data' => $data, 'msg' => $msg, 'isNewUser' => $isNewUser]);
    }

    /**
     * Swift API : login with google.
     *
     * @since 2021-01-31
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginUserwithGoogle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'google_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first()]);
        }

        $google_id = $request->google_id;
        $user = User::where('google_id', $google_id)->first();
        $result = [];

        if (!$user) {   //register
            $user = User::create([
                'google_id' => $request['google_id'],
                'email' => @$request['user_mail'],
                'firstname' => @$request['firstname'],
                'lastname' => @$request['lastname'],
                'sign_date' => date('Y-m-d h:i:s'),
            ]);

            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => 3,
            ]);
            
            $result = User::where('id', $user->id)->first();
            $data = $this->getUserinformation($user->id);

            $msg = 'Successfully Logged In.';
            $isNewUser = 1;
        }else{
            $result = $user;
            $data = $this->getUserinformation($user->id);
            $msg = 'Successfully Logged In.';
            $isNewUser = 0;
        }

        return response()->json(['status' => 'success', 'data' => $data, 'msg' => $msg, 'isNewUser' => $isNewUser]);
    }

    /**
     * Swift API : login with facebook.
     *
     * @since 2021-01-31
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginUserwithFacebook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fb_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first()]);
        }

        $fb_id = $request->fb_id;
        $user = User::where('fb_id', $fb_id)->first();
        $result = [];

        if (!$user) {   //register
            $user = User::create([
                'fb_id' => $request['fb_id'],
                'email' => @$request['user_mail'],
                'firstname' => @$request['firstname'],
                'lastname' => @$request['lastname'],
                'sign_date' => date('Y-m-d h:i:s'),
            ]);

            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => 3,
            ]);
            
            $result = User::where('id', $user->id)->first();
            $data = $this->getUserinformation($user->id);

            $msg = 'Successfully Logged In.';
            $isNewUser = 1;
        }else{
            $result = $user;
            $data = $this->getUserinformation($user->id);
            $msg = 'Successfully Logged In.';
            $isNewUser = 0;
        }

        return response()->json(['status' => 'success', 'data' => $data, 'msg' => $msg, 'isNewUser' => $isNewUser]);
    }

    /**
     * Swift API : User login by iOS mobile.
     *
     * @since 2021-01-29
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first()]);
        }

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'status' => "failed",
                'msg' => 'Unauthorized Access, please confirm credentials or verify your email.'
            ]);

        $user = $request->user();
        $userid = $user->id;
        $data = $this->getUserinformation($userid);
        
        return response()->json(['status' => 'success', 'data' => $data, 'msg' => 'Successfully Logged In.']);
    }

    /**
     * Swift API : User logout by iOS mobile.
     *
     * @since 2021-01-29
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */  
    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Swift API : uploadCredential file.
     *
     * @since 2021-01-30
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadCredentialFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'credentialfile' => 'required',
            'credentialid' => 'required',
            'userid' => 'required',
            'expire_date' => 'required'
        ]);

        $path = env('APP_URL')."uploads/";

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first(), 'path' => $path]);
        }

        DB::beginTransaction();

        try {
            $credential = Credentialusers::where('userid', $request->userid)->where('credentialid', $request->credentialid)->first();
            if (@$credential) {
                $credential->file_name = $request['credentialfile'];
                $credential->expire_date = $request['expire_date'];
                $credential->update();
            }else{
                $credential = Credentialusers::create([
                    'userid' => $request['userid'],
                    'credentialid' => $request['credentialid'],
                    'file_name' => $request['credentialfile'],
                    'expire_date' => $request['expire_date'],
                    'sign_date' => date('Y-m-d h:i:s'),
                ]);
            }

            Credentialusers::Upload_credentialfile($credential->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }  

        $id = $credential->id;
        $data = DB::table('credentials')
                        ->leftJoin('credential_users', function ($join) use ($id) {
                            $join->on('credentials.id', '=', 'credential_users.credentialid')
                                 ->where('credential_users.userid', '=', $id);
                        })
                        ->select('credentials.id', 'credentials.title', 'credential_users.file_name', 'credential_users.expire_date')
                        ->get();

        return response()->json(['status' => "success", 'data' => $data, 'msg' => 'Successfully uploaded.', 'path' => $path]);
    }

    /**
     * Swift API : get credential information.
     *
     * @since 2021-01-30
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCredentials(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required'
        ]);

        $path = env('APP_URL')."uploads/";

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first(), 'path' => $path]);
        }

        $id = $request->userid;
        $result = DB::table('credentials')
                        ->leftJoin('credential_users', function ($join) use ($id) {
                            $join->on('credentials.id', '=', 'credential_users.credentialid')
                                 ->where('credential_users.userid', '=', $id);
                        })
                        ->select('credentials.id', 'credentials.title', 'credential_users.file_name', 'credential_users.expire_date')
                        ->get();
        
        return response()->json(['status' => "success", 'data' => $result, 'msg' => 'Successfully got credentials data.', 'path' => $path]);
    }

    /**
     * Swift API : get care licenses information.
     *
     * @since 2021-02-02
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getLicenses(Request $request)
    {
        $result = Caregivinglicenses::all();
        
        return response()->json(['status' => "success", 'data' => $result, 'msg' => 'Successfully got licenses data.']);
    }

    /**
     * Swift API : get user information.
     *
     * @since 2021-02-01
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function getUserinformation($userid)
    {
        $result = User::where('id', $userid)->first();
        
        return $result;
    }

    /**
     * Swift API : User forgot password.
     *
     * @since 2021-02-01
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgotpassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first()]);
        }

        DB::beginTransaction();

        $token = substr("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", mt_rand(0, 51), 1).substr(md5(time()), 1);

        $data = [];
        $data['name'] = 'User!';
        $data['resetLink'] = env('APP_URL') . 'users/resetpwd/' . $token;
        $data['body'] = 'You are receiving this email because we received a password reset request for your account.';
        $data['pre_footer'] = 'If you did not request a password reset, no further action is required. <br> Regards, <br> MamboDubai';
        $data['footer'] = 'If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below <br> into your web browser: <a href="' . $data['resetLink'] . '" target="_blank">' . $data['resetLink'] . '</a>';
        
        // $data['email'] = $request['email'];

        $useremail = $request['email'];
        $username = 'Bluely Hub';
        $subject = "Bluely Hub : Reset Password";

        try {
            Mail::send('frontend.mail.mail_forgotpassword', $data, function($message) use ($username, $useremail, $subject) {
                $message->to($useremail, $username)->subject($subject);
                $message->from('solaris.dubai@gmail.com', 'Administrator');
            });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }  

        return response()->json(['status' => "success", 'msg' => 'Successfully sent now. Please check your inbox.']);
    }

    /**
     * Resource page render reset password.
     *
     * @since 2021-02-01
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetpwd($token)
    {
        return view('admin.users.reset', compact('token'));
    }

    /**
     * Swift API : Reset password.
     *
     * @since 2021-02-01
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetUserpassword(Request $request)
    {
        $this->validate(request(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();
        if (@$user) {
            $user->password = Hash::make(request('password'));
            $user->save();
        }
            
        return redirect()->route('users.resetpwd', $request->_token)->with('flash', 'Password has been successfully resetted.');
    }
}
