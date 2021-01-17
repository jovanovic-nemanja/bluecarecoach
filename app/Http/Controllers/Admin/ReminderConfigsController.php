<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\ReminderConfigs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReminderConfigsController extends Controller
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
        $reminderconfigs = ReminderConfigs::all();

        return view('admin.reminderconfigs.index', compact('reminderconfigs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.reminderconfigs.create');
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
            'minutes' => 'required'
        ]);

        $dates = User::getformattime();
        $date = $dates['date'];

        $reminderConfigs = ReminderConfigs::create([
            'minutes' => $request->minutes,
            'active' => $request->active,
            'sign_date' => $date,
        ]);

        return redirect()->route('reminderconfigs.index')->with('flash', 'Reminder Config has been successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = ReminderConfigs::where('id', $id)->first();

        return view('admin.reminderconfigs.edit', compact('result'));
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
            'minutes' => 'required'
        ]);

        $dates = User::getformattime();
        $date = $dates['date'];
        
        $record = ReminderConfigs::where('id', $id)->first();
        if (@$record) {
            $record->minutes = $request->minutes;
            $record->active = $request->active;

            $record->update();
        }

        return redirect()->route('reminderconfigs.index');
    }

    /**
     * Update the active status .
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function active($id)
    {
        $dates = User::getformattime();
        $date = $dates['date'];
        $result = ReminderConfigs::whereNotNull('active')->first();
        if (@$result) {
            $result->active = NULL;
            $result->update();
        }

        $record = ReminderConfigs::where('id', $id)->first();
        if (@$record) {
            $record->active = 1;

            $record->update();
        }

        return redirect()->route('reminderconfigs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = ReminderConfigs::where('id', $id)->delete();
        
        return redirect()->route('reminderconfigs.index');
    }
}
