<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\User;
use App\Routes;
use App\Comments;
use App\Activities;
use App\Useractivities;

class ActivitiesController extends Controller
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
        $activities = Activities::all();

        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.activities.create');
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
            'title' => 'required',
            'type' => 'required'
        ]);

        $dates = User::getformattime();
        $date = $dates['date'];

        $activities = Activities::create([
            'title' => $request->title,
            'type' => $request->type,
            'comments' => $request->comments,
            'sign_date' => $date,
        ]);

        if (@$request->comments) {
            $arrs = explode(',', $request->comments);
            foreach ($arrs as $comm) {
                $comments = Comments::create([
                    'type' => 1,
                    'sign_date' => $date,
                    'name' => $comm,
                    'ref_id' => $activities['id']
                ]);
            }
        }

        return redirect()->route('activities.index')->with('flash', 'Activity has been successfully created.');
    }

    public function getcommentsbyactivity(Request $request)
    {
        if (@$request->activity) {
            $results = Comments::where('type', 1)->where('ref_id', $request->activity)->get();
        }else if (@$request->route) {
            $results = Routes::all();
        }else
            $results = [];

        return response()->json($results);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Activities::where('id', $id)->first();

        return view('admin.activities.edit', compact('result'));
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
            'title' => 'required',
            'type' => 'required'
        ]);

        $record = Activities::where('id', $id)->first();
        if (@$record) {
            $record->title = $request->title;
            $record->type = $request->type;
            $record->comments = $request->comments;

            $record->update();
        }

        $dates = User::getformattime();
        $date = $dates['date'];

        if (@$request->comments) {
            $arrs = explode(',', $request->comments);
            $del = Comments::where('type', 1)->where('ref_id', $record->id)->delete();
            foreach ($arrs as $comm) {
                $comments = Comments::create([
                    'type' => 1,
                    'name' => $comm,
                    'sign_date' => $date,
                    'ref_id' => $record->id
                ]);
            }
        }

        return redirect()->route('activities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $useractivities = Useractivities::where('activities', $id)->delete();
        $del = Comments::where('type', 1)->where('ref_id', $id)->delete();
        $record = Activities::where('id', $id)->delete();
        
        return redirect()->route('activities.index');
    }
}
