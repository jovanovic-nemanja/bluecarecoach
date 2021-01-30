<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Credentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CredentialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $credentials = Credentials::all();

        return view('admin.credentials.index', compact('credentials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.credentials.create');
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
            'title' => 'required|max:64'
        ]);

        $dates = User::getformattime();
        $date = $dates['date'];

        $credential = Credentials::create([
            'title' => $request->title,
            'sign_date' => $date,
        ]);

        return redirect()->route('credentials.index')->with('flash', 'Credential has been successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Credentials::where('id', $id)->first();

        return view('admin.credentials.edit', compact('result'));
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
            'title' => 'required|max:64'
        ]);

        $record = Credentials::where('id', $id)->first();
        if (@$record) {
            $record->title = $request->title;

            $record->update();
        }

        return redirect()->route('credentials.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Credentials::where('id', $id)->delete();
        
        return redirect()->route('credentials.index');
    }
}
