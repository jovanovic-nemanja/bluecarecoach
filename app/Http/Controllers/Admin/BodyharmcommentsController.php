<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use App\User;
use App\Bodyharmcomments;

class BodyharmcommentsController extends Controller
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
        $comments = Bodyharmcomments::all();

        return view('admin.bodyharmcomments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.bodyharmcomments.create');
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
            'name' => 'required'
        ]);

        $dates = User::getformattime();
        $date = $dates['date'];

        $activities = Bodyharmcomments::create([
            'name' => $request->name,
            'sign_date' => $date,
        ]);

        return redirect()->route('bodyharmcomments.index')->with('flash', 'Comment has been successfully created.');
    }

    public function getbodyharmcomments(Request $request)
    {
        $results = Bodyharmcomments::all();

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
        $result = Bodyharmcomments::where('id', $id)->first();

        return view('admin.bodyharmcomments.edit', compact('result'));
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
            'name' => 'required'
        ]);

        $record = Bodyharmcomments::where('id', $id)->first();
        if (@$record) {
            $record->name = $request->name;

            $record->update();
        }

        return redirect()->route('bodyharmcomments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Bodyharmcomments::where('id', $id)->delete();
        
        return redirect()->route('bodyharmcomments.index');
    }
}
