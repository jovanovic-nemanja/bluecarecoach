<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Role;
use App\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class CaretakerController extends Controller
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
        $caretakers = User::all();
        return view('admin.caretaker.index', compact('caretakers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.caretaker.create');
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
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone_number' => 'string|max:20',
            'profile_logo'      => 'required',
        ]);

        $dates = User::getformattime();
        $date = $dates['date'];
        
        DB::beginTransaction();

        try {
            $user = User::create([
                'firstname' => $request['firstname'],
                'lastname' => $request['lastname'],
                'username' => $request['username'],
                'email' => $request['email'],
                'profile_logo' => $request['profile_logo'],
                'password' => Hash::make($request['password']),
                'phone_number' => $request['phone_number'],
                'sign_date' => $date,
            ]);

            User::upload_logo_img($user->id);

            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => 2,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }  

        return redirect()->route('caretaker.index')->with('flash', 'Successfully added caretaker.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)->first();

        return view('admin.caretaker.edit', compact('user'));
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
        $this->validate(request(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'phone_number' => 'string|max:20',
        ]);

        $record = User::where('id', $id)->first();
        if (@$record) {
            $record->firstname = $request->firstname;
            $record->lastname = $request->lastname;
            $record->username = $request->username;
            $record->password = Hash::make($request['password']);
            $record->phone_number = $request->phone_number;
            if (@$request->profile_logo) {
                $record->profile_logo = $request->profile_logo;
            }
            $record->update();
        }
        
        if (@$request->profile_logo) {
            User::upload_logo_img($record->id);
        }

        return redirect()->route('caretaker.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = User::where('id', $id)->delete();
        
        return redirect()->route('caretaker.index');
    }
}
