<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Role;
use App\RoleUser;
use App\Adminlogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Frontend\EmailsController;

class ManagemanagersController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'admin']);
    }
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.managemanagers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.managemanagers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'gender' => 'required|integer',
            'birthday' => 'required|date',
            'address' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'phone_number' => 'string|max:20',
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'block' => 0,
            'password' => Hash::make($request['password']),
            'phone_number' => $request['phone_number'],
            'sign_date' => date('Y-m-d h:i:s'),
        ]);

        RoleUser::create([
            'user_id' => $user->id,
            'role_id' => 4,
        ]);

        $controller = new EmailsController;
        $array = [];
        
        $array['username'] = $request['name'];
        $array['receiver_address'] = $request['email'];
        $array['data'] = array('name' => $array['username'], "body" => "Welcome for sign up our site!");
        $array['subject'] = "Successfully sign up your account.";
        $array['sender_address'] = "jovanovic.nemanja.1029@gmail.com";

        $controller->save($array);

        $data = [];
        $data['title'] = 'Added';
        $data['description'] = 'Manager Name: '.$request['name'];
        $add_logs = Adminlogs::Addlog($data);

        return redirect()->route('managemanagers.index')->with('flash', 'Successfully added your account.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (@$id) {
            $records = User::where('id', $id)->get();
            if (@$records) {
                $records[0]['block'] = 0;
                $records[0]->update();

                $data = [];
                $data['title'] = 'Un-Blocked';
                $data['description'] = 'Manager Name: '.$records[0]['name'];
                $add_logs = Adminlogs::Addlog($data);

                return redirect()->route('managemanagers.index')->with('flash', 'Manager has been successfully changed the status.');
            }
        }else{
            return redirect()->route('managemanagers.index');
        }  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (@$id) {
            $records = User::where('id', $id)->get();
            if (@$records) {
                $records[0]['block'] = 1;
                $records[0]->update();

                $data = [];
                $data['title'] = 'Blocked';
                $data['description'] = 'Manager Name: '.$records[0]['name'];
                $add_logs = Adminlogs::Addlog($data);

                return redirect()->route('managemanagers.index')->with('flash', 'Manager has been successfully changed the status.');
            }
        }else{
            return redirect()->route('managemanagers.index');
        }        
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
}
