<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Caregivinglicenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CaregivinglicensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $licenses = Caregivinglicenses::all();

        return view('admin.licenses.index', compact('licenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.licenses.create');
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
            'name' => 'required|max:64'
        ]);

        $dates = User::getformattime();
        $date = $dates['date'];

        $licenses = Caregivinglicenses::create([
            'name' => $request->name,
            'sign_date' => $date,
        ]);

        return redirect()->route('licenses.index')->with('flash', 'Care giving license has been successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Caregivinglicenses::where('id', $id)->first();

        return view('admin.licenses.edit', compact('result'));
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
            'name' => 'required|max:64'
        ]);

        $record = Caregivinglicenses::where('id', $id)->first();
        if (@$record) {
            $record->name = $request->name;

            $record->update();
        }

        return redirect()->route('licenses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Caregivinglicenses::where('id', $id)->delete();
        
        return redirect()->route('licenses.index');
    }
}
