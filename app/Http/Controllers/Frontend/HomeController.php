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
        $residents = DB::table('users')
                            ->select('users.*')
                            ->Join('role_user', 'role_user.user_id', '=', 'users.id')
                            ->where('role_user.role_id', 3)
                            ->get();

        return view('frontend.home', compact('residents'));
    }
}
