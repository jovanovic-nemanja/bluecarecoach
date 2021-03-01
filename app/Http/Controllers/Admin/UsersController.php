<?php

namespace App\Http\Controllers\Admin;

use Mail;
use App\User;
use App\Role;
use App\Video;
use Carbon\Carbon;
use App\RoleUser;
use App\Verifyemails;
use App\Caregivinglicenses;
use App\Credentials;
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
        $this->middleware(['auth', 'admin'])->except(['store', 'emailverify', 'validateCode', 'loginUserwithApple', 'loginUserwithGoogle', 'loginUserwithFacebook', 'loginUser', 'logout', 'uploadCredentialFile', 'deleteCredentialuser', 'forgotpassword', 'resetpwd', 'resetUserpassword', 'getUserinformation', 'getCredentials', 'getLicenses', 'updateAccount', 'addCredential', 'saveSkillandhobby', 'getvideolink']);
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
            'firstname' => 'required|string|max:190',
            'lastname' => 'required|string|max:190',
            'email' => 'required|string|unique:users|email|max:255', //|unique:users
            'care_giving_license' => 'required|integer',
            'zip_code' => 'required',
            'care_giving_experience' => 'required',
            'birthday' => 'required',
            'phone_number' => 'required',
            // 'password' => 'required|string|min:6'
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
                'firstname' => $request['firstname'],
                'lastname' => $request['lastname'],
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

        $user = User::where('id', $request['userid'])->first();

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

            $data = $this->getCredentialdata($request->userid, $credential->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }

        return response()->json(['status' => "success", 'data' => $data, 'msg' => 'Successfully uploaded.', 'path' => $path]);
    }

    /**
     * Swift API : delete credential file.
     *
     * @since 2021-02-22
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCredentialuser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cre_uid' => 'required',
            'userid' => 'required'
        ]);

        $path = env('APP_URL')."uploads/";

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first(), 'path' => $path]);
        }

        DB::beginTransaction();

        try {
            $credential = Credentialusers::where('id', $request->cre_uid)->first();
            $cre = Credentials::where('id', $credential->credentialid)->first();
            $created_by = $cre['created_by'];
            
            $del = Credentialusers::where('id', $request->cre_uid)->delete();

            $data = $this->getCredentialdata($request->userid, $created_by);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }

        return response()->json(['status' => "success", 'data' => $data, 'msg' => 'Successfully deleted.', 'path' => $path]);
    }

    private function getCredentialdata($userid, $credentialid)
    {
        $admin = User::where('firstname', 'Admin')->first();
        $adminId = $admin->id;
        $id = $userid;

        $user = User::where('id', $userid)->first();
        $caregiving_license = $user->care_giving_license;
        $query = "JSON_CONTAINS(credentials.care_licenses, ".$caregiving_license.", '$')=1";

        if ($credentialid == -1) {  //for special user
            if(@$caregiving_license == NULL) {
                $result = DB::table('credentials')
                        ->leftJoin('credential_users', function ($join) use ($userid) {
                            $join->on('credentials.id', '=', 'credential_users.credentialid')
                                 ->where('credential_users.userid', '=', $userid);
                        })
                        ->whereIn('credentials.created_by', [$id, $adminId])
                        ->select('credentials.id', 'credentials.title', 'credential_users.id as cre_uid', 'credential_users.file_name', DB::raw('DATE_FORMAT(credential_users.expire_date, "%Y-%m-%d") as expire_date'), 'credentials.created_by', DB::raw('DATEDIFF(credential_users.expire_date, NOW()) as expired'))
                        ->get();
            }else{
                $result = DB::table('credentials')
                        ->leftJoin('credential_users', function ($join) use ($userid) {
                            $join->on('credentials.id', '=', 'credential_users.credentialid')
                                 ->where('credential_users.userid', '=', $userid);
                        })
                        ->whereIn('credentials.created_by', [$id, $adminId])
                        ->whereRaw($query)
                        ->select('credentials.id', 'credentials.title', 'credential_users.id as cre_uid', 'credential_users.file_name', DB::raw('DATE_FORMAT(credential_users.expire_date, "%Y-%m-%d") as expire_date'), 'credentials.created_by', DB::raw('DATEDIFF(credential_users.expire_date, NOW()) as expired'))
                        ->get();
            }
        }else{
            if(@$caregiving_license == NULL) {
                $result = DB::table('credentials')
                        ->leftJoin('credential_users', function ($join) use ($userid) {
                            $join->on('credentials.id', '=', 'credential_users.credentialid')
                                 ->where('credential_users.userid', '=', $userid);
                        })
                        ->whereIn('credentials.created_by', [$id, $adminId])
                        ->select('credentials.id', 'credentials.title', 'credential_users.id as cre_uid', 'credential_users.file_name', DB::raw('DATE_FORMAT(credential_users.expire_date, "%Y-%m-%d") as expire_date'), 'credentials.created_by', DB::raw('DATEDIFF(credential_users.expire_date, NOW()) as expired'))
                        ->get();
            }else{
                $result = DB::table('credentials')
                        ->leftJoin('credential_users', function ($join) use ($userid) {
                            $join->on('credentials.id', '=', 'credential_users.credentialid')
                                 ->where('credential_users.userid', '=', $userid);
                        })
                        ->whereIn('credentials.created_by', [$id, $adminId])
                        ->whereRaw($query)
                        ->select('credentials.id', 'credentials.title', 'credential_users.id as cre_uid', 'credential_users.file_name', DB::raw('DATE_FORMAT(credential_users.expire_date, "%Y-%m-%d") as expire_date'), 'credentials.created_by', DB::raw('DATEDIFF(credential_users.expire_date, NOW()) as expired'))
                        ->get();
            }
        }

        return $result;
    }

    /**
     * Swift API : add user credential information.
     *
     * @since 2021-01-30
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addCredential(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first()]);
        }

        $result = Credentials::where('title', $request->title)->where('created_by', $request->userid)->first();
        if (@$result) {
            return response()->json(['status' => "failed", 'msg' => 'You already created it.']);   
        }

        $admin = User::where('firstname', 'Admin')->first();
        $adminId = $admin->id;
        $result1 = Credentials::where('title', $request->title)->where('created_by', $adminId)->first();
        if (@$result) {
            return response()->json(['status' => "failed", 'msg' => 'Admin already created it.']);   
        }

        $user = User::where('id', $request->userid)->first();
        $caregiving_license = $user->care_giving_license;
        
        $record = Credentials::create([
            'title' => $request->title,
            'sign_date' => date('Y-m-d H:i:s'),
            'care_licenses' => '['.$caregiving_license.']',
            'created_by' => $request->userid
        ]);

        $data = $this->getCredentialdata($request->userid, -1);

        return response()->json(['status' => "success", 'data' => $data, 'msg' => 'Successfully added.']);
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
        $admin = User::where('firstname', 'Admin')->first();
        $adminId = $admin->id;
        $created_by = $request->userid;

        $user = User::where('id', $id)->first();
        $caregiving_license = $user->care_giving_license;
        if(@$caregiving_license == NULL) {
            $result = DB::table('credentials')
                            ->leftJoin('credential_users', function ($join) use ($id) {
                                $join->on('credentials.id', '=', 'credential_users.credentialid')
                                     ->where('credential_users.userid', '=', $id);
                            })
                            ->whereIn('credentials.created_by', [$id, $adminId])
                            ->select('credentials.id', 'credentials.title', 'credential_users.id as cre_uid', 'credential_users.file_name', DB::raw('DATE_FORMAT(credential_users.expire_date, "%Y-%m-%d") as expire_date'), 'credentials.created_by', DB::raw('DATEDIFF(credential_users.expire_date, NOW()) as expired'))
                            ->get();
        }else{
            $query = "JSON_CONTAINS(credentials.care_licenses, ".$caregiving_license.", '$')=1";
            $result = DB::table('credentials')
                            ->leftJoin('credential_users', function ($join) use ($id) {
                                $join->on('credentials.id', '=', 'credential_users.credentialid')
                                     ->where('credential_users.userid', '=', $id);
                            })
                            ->whereIn('credentials.created_by', [$id, $adminId])
                            ->whereRaw($query)
                            // ->select('credentials.id', 'credentials.title', 'credential_users.file_name', 'credential_users.expire_date', 'credentials.created_by')
                            ->select('credentials.id', 'credentials.title', 'credential_users.id as cre_uid', 'credential_users.file_name', DB::raw('DATE_FORMAT(credential_users.expire_date, "%Y-%m-%d") as expire_date'), 'credentials.created_by', DB::raw('DATEDIFF(credential_users.expire_date, NOW()) as expired'))
                            ->get();  
        }

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

    /**
     * Swift API : Update user account information.
     *
     * @since 2021-02-03
     * @author Nemanja
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first()]);
        }

        $user = User::where('id', $request->userid)->first();
        if (@$user) {
            if (@$request->firstname) {
                $user->firstname = $request->firstname;
            }
            if (@$request->lastname) {
                $user->lastname = $request->lastname;
            }
            if (@$request->birthday) {
                $user->birthday = $request->birthday;
            }
            if (@$request->care_giving_experience) {
                $user->care_giving_experience = $request->care_giving_experience;
            }
            if (@$request->care_giving_license) {
                $user->care_giving_license = $request->care_giving_license;
            }
            if (@$request->zip_code) {
                $user->zip_code = $request->zip_code;
            }
            if (@$request->phone_number) {
                $user->phone_number = $request->phone_number;
            }

            $user->update();
        }

        $result = [];
        $result = User::where('id', $user->id)->first();
            
        return response()->json(['status' => "success", 'data' => $result, 'msg' => 'Successfully updated your account information.']);
    }

    /**
     * Swift API: get link of Video for introducing.
     *
     * @since 2021-03-01
     * @author Nemanja
     * @return \Illuminate\Http\Response
     */
    public function getvideolink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            //pass validator errors as errors object for ajax response
            return response()->json(['status' => "failed", 'msg' => $messages->first()]);
        }

        $data = [];

        $result = Video::where('active', 1)->first();
        if (@$result) {
            $status = "success";
            $link = env('APP_URL') . "uploads/" . $result->link;
        }else {
            $status = "success";
            $link = "https://youtu.be/VSo41Y9i2Ug";
        }

        $all_uploaded_credentials = Credentialusers::where('userid', $request->userid)->get();
        if (count($all_uploaded_credentials) > 0) {
            $all_uploaded_credentials_count = count($all_uploaded_credentials);
        }else{
            $all_uploaded_credentials_count = 0;
        }

        $now = getdate();
        $expired_credentials = Credentialusers::where('userid', $request->userid)->whereDate('expire_date', '<', $now)->get();
        if (count($expired_credentials) > 0) {
            $expired_credentials_count = count($expired_credentials);
        }else{
            $expired_credentials_count = 0;
        }
        
        $data['link'] = $link;
        $data['all_uploaded_credentials_count'] = $all_uploaded_credentials_count;
        $data['expired_credentials_count'] = $expired_credentials_count;

        return response()->json(['status' => $status, 'data' => $data, 'msg' => 'success']);
    }
}
