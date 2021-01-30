<?php

namespace App\Http\Controllers\Admin;

use Mail;
use App\User;
use App\Role;
use App\RoleUser;
use App\Verifyemails;
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
        $this->middleware(['auth', 'admin'])->except(['store', 'emailverify', 'validateCode', 'loginUserwithApple', 'loginUser', 'logout', 'uploadCredentialFile', 'getCredentials']);
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
            'password' => 'required|string|min:6|confirmed'
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
                'skill1' => @$request['skill1'],
                'skill2' => @$request['skill2'],
                'skill3' => @$request['skill3'],
                'skill4' => @$request['skill4'],
                'skill5' => @$request['skill5'],
                'hobby1' => @$request['hobby1'],
                'hobby2' => @$request['hobby2'],
                'hobby3' => @$request['hobby3'],
                'hobby4' => @$request['hobby4'],
                'hobby5' => @$request['hobby5'],
                'sign_date' => date('Y-m-d h:i:s'),
            ]);

            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => 3,
            ]);

            if (@$request->photo) {
                User::upload_photo($user->id);
            }

            $data = $this->getCredentials($user->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }  

        return response()->json(['status' => "success", 'data' => $data, 'msg' => 'Successfully registered.', 'path' => $path]);
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
            $data = $this->getCredentials($user->id);

            $msg = 'Successfully Logged In.';
            // $newUser = 1;
        }else{
            $result = $user;
            $data = $this->getCredentials($user->id);
            $msg = 'Successfully Logged In.';
            // $newUser = 0;
        }

        return response()->json(['status' => 'success', 'data' => $data, 'msg' => $msg]);
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
        $data = $this->getCredentials($userid);
        
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

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first(), 'path' => $path]);
        }

        DB::beginTransaction();

        try {
            $credential = Credentialusers::create([
                'userid' => $request['userid'],
                'credentialid' => $request['credentialid'],
                'file_name' => $request['credentialfile'],
                'expire_date' => $request['expire_date'],
                'sign_date' => date('Y-m-d h:i:s'),
            ]);

            Credentialusers::Upload_credentialfile($credential->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }  

        $data = $this->getCredentials($request['userid']);

        return response()->json(['status' => "success", 'data' => $data, 'msg' => 'Successfully uploaded.']);
    }

    /**
     * Swift API : get credential information.
     *
     * @since 2021-01-30
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCredentials($userid)
    {
        if (@$userid) {
            $result = DB::table('credentials')
                            ->leftJoin('credential_users', function ($join) {
                                $join->on('credentials.id', '=', 'credential_users.credentialid')
                                     ->where('credential_users.userid', '=', $userid);
                            })
                            ->select('credentials.title', 'credential_users.file_name', 'credential_users.expire_date')
                            ->get();
        }else{
            $result = [];
        }
        
        return $result;
    }
}
