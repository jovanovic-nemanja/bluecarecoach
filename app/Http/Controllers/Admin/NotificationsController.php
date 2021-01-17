<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\User;
use Carbon\Carbon;
use App\Medications;
use App\Notifications;
use App\Switchreminder;
use App\ReminderConfigs;
use App\Assignmedications;

class NotificationsController extends Controller
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
        $notifications = Notifications::where('is_read', 1)->get();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function getNotificationdata(Request $request)
    {
        $enable = Switchreminder::first();
        if (@$enable) { //disabled case
            $results = [];
        }else{  //enabled case
            $results = Notifications::where('is_read', 1)->get();
        }

        return response()->json($results);
    }

    public function updateIsread(Request $request)
    {
        $dates = User::getformattime();
        $date = $dates['date'];
        $time = $dates['time'];

        if (@$request->notificationId) {
            $record = Notifications::where('id', $request->notificationId)->first();
            $record->is_read = 2;
            $record->update();
        }

        return response()->json("status", 200);
    }

    /**
     * Update is_read status in notifications table.
     * @param $id notification row id
     * @return \Illuminate\Http\Response
     */
    public function confirmIsread($id)
    {
        $dates = User::getformattime();
        $date = $dates['date'];
        $time = $dates['time'];

        if (@$id) {
            $record = Notifications::where('id', $id)->first();
            $record->is_read = 2;
            $record->update();
        }

        return redirect()->route('notifications.index');
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
}
