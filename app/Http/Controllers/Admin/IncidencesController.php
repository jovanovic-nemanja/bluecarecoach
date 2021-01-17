<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Incidences;
use App\User;

class IncidencesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incidences = Incidences::all();

        return view('admin.incidences.index', compact('incidences'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.incidences.create');
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

        $incidences = Incidences::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'sign_date' => $date,
        ]);

        return redirect()->route('incidences.index')->with('flash', 'Incidence has been successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Incidences::where('id', $id)->first();

        return view('admin.incidences.edit', compact('result'));
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

        $record = Incidences::where('id', $id)->first();
        if (@$record) {
            $record->title = $request->title;
            $record->type = $request->type;
            $record->content = $request->content;

            $record->update();
        }

        return redirect()->route('incidences.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Incidences::where('id', $id)->delete();
        
        return redirect()->route('incidences.index');
    }
}
