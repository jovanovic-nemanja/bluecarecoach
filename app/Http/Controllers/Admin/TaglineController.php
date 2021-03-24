<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Tagline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TaglineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taglines = Tagline::all();

        return view('admin.tagline.index', compact('taglines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tagline.create');
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
            'description' => 'required'
        ]);

        $dates = User::getformattime();
        $date = $dates['date'];

        $tagline = Tagline::create([
            'description' => $request->description,
            'sign_date' => $date
        ]);

        return redirect()->route('tagline.index')->with('flash', 'Tag line has been successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Tagline::where('id', $id)->first();

        return view('admin.tagline.edit', compact('result'));
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
            'description' => 'required'
        ]);

        $record = Tagline::where('id', $id)->first();

        if (@$record) {
            $record->description = $request->description;

            $record->update();
        }

        return redirect()->route('tagline.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Tagline::where('id', $id)->delete();
        
        return redirect()->route('tagline.index');
    }
}
