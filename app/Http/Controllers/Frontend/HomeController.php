<?php

namespace App\Http\Controllers\Frontend;

use App\User;
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

        return view('frontend.home', compact('users'));
    }
}
