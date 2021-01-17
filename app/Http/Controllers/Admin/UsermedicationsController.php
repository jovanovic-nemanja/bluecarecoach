<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use App\User;
use App\Routes;
use Carbon\Carbon;
use App\Adminlogs;
use App\Comments;
use App\Medications;
use App\Usermedications;
use App\Assignmedications;
use Illuminate\Support\Facades\DB;


class UsermedicationsController extends Controller
{
    public function __construct(){
        // $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usermedications = Usermedications::all();
        $assignmedications = Assignmedications::all();

        return view('admin.usermedications.index', compact('usermedications', 'assignmedications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.usermedications.create');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexusermedication($id)
    {
        $usermedications = Usermedications::where('resident', $id)->orderBy('sign_date', 'asc')->get();
        $assignmedications = Assignmedications::where('resident', $id)->orderBy('time')->get();
        $arrs = $assignmedications;

        $user = User::where('id', $id)->first();

        $comments = Comments::where('type', 2)->get();

        return view('admin.usermedications.index', compact('usermedications', 'user', 'arrs', 'comments'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexusermedicationgiven($id)
    {
        $usermedications = Usermedications::where('resident', $id)->orderBy('sign_date', 'asc')->get();
        $assignmedications = Assignmedications::where('resident', $id)->orderBy('time')->get();
        $arrs = $assignmedications;

        $user = User::where('id', $id)->first();

        $comments = Comments::where('type', 2)->get();

        return view('admin.usermedications.indexgiven', compact('usermedications', 'user', 'arrs', 'comments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createusermedication($resident, $assign_id, $medication_id)
    {
        $result = [];

        $user = User::where('id', $resident)->first();
        if (@$user) {
            $result['user'] = $user;
        }

        $result['medications'] = Medications::all();
        $result['assigns'] = Assignmedications::where('id', $assign_id)->first();
        $result['assign_id'] = $assign_id;
        $result['medication_id'] = $medication_id;
        $result['medication'] = Medications::where('id', $medication_id)->first();

        return view('admin.usermedications.create', compact('result'));
    }

    public function getCurrentTimeByAjax(Request $request)
    {
        $date = date('y-m-d h:i:s');
        $current = User::formattime($date);
        return response()->json($current);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createassignmedication($resident)
    {
        $result = [];

        $user = User::where('id', $resident)->first();
        if (@$user) {
            $result['user'] = $user;
        }

        $result['medications'] = Medications::all();

        $result['routes'] = Routes::all();

        return view('admin.usermedications.createassign', compact('result'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (@$request->assign) {    //assign medication for admin
            $this->validate(request(), [
                'medications' => 'required',
                'dose' => 'required',
                'start_day' => 'required',
                'end_day' => 'required',
                'resident' => 'required'
            ]);

            $dates = User::getformattime();
            $date = $dates['date'];

            if (@$request->time1) {
                $assignmedications = Assignmedications::create([
                    'medications' => $request->medications,
                    'dose' => $request->dose,
                    'resident' => $request->resident,
                    'route' => $request->route,
                    'sign_date' => $date,
                    'time' => @$request->time1,
                    'start_day' => $request->start_day,
                    'end_day' => $request->end_day
                ]);
            } if (@$request->time2) {
                $assignmedications = Assignmedications::create([
                    'medications' => $request->medications,
                    'dose' => $request->dose,
                    'resident' => $request->resident,
                    'route' => $request->route,
                    'sign_date' => $date,
                    'time' => @$request->time2,
                    'start_day' => $request->start_day,
                    'end_day' => $request->end_day
                ]);
            } if (@$request->time3) {
                $assignmedications = Assignmedications::create([
                    'medications' => $request->medications,
                    'dose' => $request->dose,
                    'resident' => $request->resident,
                    'route' => $request->route,
                    'sign_date' => $date,
                    'time' => @$request->time3,
                    'start_day' => $request->start_day,
                    'end_day' => $request->end_day
                ]);
            } if (@$request->time4) {
                $assignmedications = Assignmedications::create([
                    'medications' => $request->medications,
                    'dose' => $request->dose,
                    'resident' => $request->resident,
                    'route' => $request->route,
                    'sign_date' => $date,
                    'time' => @$request->time4,
                    'start_day' => $request->start_day,
                    'end_day' => $request->end_day
                ]);
            }

            return redirect()->route('usermedications.indexusermedication', $request->resident)->with('flash', 'Medication has been successfully assigned.');
        }else{  //give medication for care taker or admin
            $this->validate(request(), [
                'assign_id' => 'required',
                'resident' => 'required'
            ]);

            $dates = User::getformattime();
            $date = $dates['date'];

            $assigned = Assignmedications::where('id', $request->assign_id)->first();
            $time = $assigned->time;

            $usermedications = Usermedications::create([
                'assign_id' => $request->assign_id,
                'resident' => $request->resident,
                'comment' => $request->comment,
                'user' => auth()->id(),
                'sign_date' => $date,
            ]);

            $medicationss = Medications::where('id', $assigned->medications)->first();
            $medicName = $medicationss->name;

            $data = [];
            $data['caretakerId'] = auth()->id();
            $data['content'] = User::getUsernameById($data['caretakerId']) . " gave " . $medicName . "(" . $time . ")" . " to " . User::getUsernameById($request->resident);
            Adminlogs::Addlogs($data);

            return redirect()->route('usermedications.indexusermedication', $request->resident)->with('flash', 'Medication has been successfully given.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $res = Usermedications::where('id', $id)->first();
        $result = [];
        $result['usermedications'] = $res;
        $result['user'] = User::where('id', $res->resident)->first();
        $result['medications'] = Medications::where('id', $res->medications)->get();
        $result['medication'] = Medications::where('id', $res->medications)->first();
        $result['allmedications'] = Medications::all();

        return view('admin.usermedications.edit', compact('result'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showassign($id)
    {
        $res = Assignmedications::where('id', $id)->first();
        $result = [];
        $result['usermedications'] = $res;
        $result['user'] = User::where('id', $res->resident)->first();
        $result['medications'] = Medications::where('id', $res->medications)->get();
        $result['medication'] = Medications::where('id', $res->medications)->first();
        $result['allmedications'] = Medications::all();

        $comment = $res->medications;
        $result['routes'] = Routes::all();

        return view('admin.usermedications.editassign', compact('result'));
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
        if (@$request->assign) {
            $this->validate(request(), [
                'medications' => 'required',
                'dose' => 'required',
                'start_day' => 'required',
                'end_day' => 'required',
                'resident' => 'required'
            ]);

            $dates = User::getformattime();
            $date = $dates['date'];
            $time = $dates['time'];

            $record = Assignmedications::where('id', $id)->first();
            if (@$record) {
                $record->medications = $request->medications;
                $record->dose = $request->dose;
                $record->resident = $request->resident;
                $record->route = $request->route;
                $record->time = @$request->time;
                $record->start_day = $request->start_day;
                $record->end_day = $request->end_day;

                $record->update();
            }
        }else {
            $this->validate(request(), [
                'medications' => 'required',
                'dose' => 'required',
                'resident' => 'required'
            ]);

            $dates = User::getformattime();
            $date = $dates['date'];
            $time = $dates['time'];

            $record = Usermedications::where('id', $id)->first();
            if (@$record) {
                $record->medications = $request->medications;
                $record->dose = $request->dose;
                $record->resident = $request->resident;
                $record->comment = $request->comment;
                $record->file = $request->file;

                $record->update();
            }

            Usermedications::upload_file($record->id);
        }

        return redirect()->route('usermedications.indexusermedication', $request->resident);
    }

    /**
    * put start day and end day as 0000-00-00
    * @param activity ID
    * @return bool true or false
    * @author Nemanja
    * @since 2020-12-18
    */
    public function stop(Request $request)
    {
        $this->validate(request(), [
            'medication_id' => 'required'
        ]);

        $medication = Assignmedications::where('id', $request->medication_id)->first();
        $medication->start_day = '0000-00-00';
        $medication->end_day = '0000-00-00';
        $medication->update();

        return redirect()->route('usermedications.indexusermedication', $request->resident)->with('flash', 'Medication has been successfully stopped.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $medication = Usermedications::where('id', $id)->first();
        $record = Usermedications::where('id', $id)->delete();
        
        return redirect()->route('usermedications.indexusermedication', $medication->resident);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyassign($id)
    {
        $medication = Assignmedications::where('id', $id)->first();
        $assigned = Usermedications::where('assign_id', $id)->delete();
        $record = Assignmedications::where('id', $id)->delete();
        
        return redirect()->route('usermedications.indexusermedication', $medication->resident);
    }
}
