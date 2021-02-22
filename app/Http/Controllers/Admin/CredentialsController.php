<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Credentials;
use App\Caregivinglicenses;
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
        $care_licenses = Caregivinglicenses::all();

        return view('admin.credentials.create', compact('care_licenses'));
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
            'title' => 'required|max:64',
            'care_licenses' => 'required'
        ]);

        $dates = User::getformattime();
        $date = $dates['date'];
        $userid = auth()->id();
        $care_licenses = '';

        foreach ($request->care_licenses as $key => $value) {
            if ($key == 0) {
                $care_licenses = $value;
            }else{
                $care_licenses .= ', ' . $value;
            }
        }

        $credential = Credentials::create([
            'title' => $request->title,
            'created_by' => $userid,
            'care_licenses' => $care_licenses,
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
        $care_licenses = Caregivinglicenses::all();

        $result = Credentials::where('id', $id)->first();

        return view('admin.credentials.edit', compact('result', 'care_licenses'));
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
            'title' => 'required|max:64',
            'care_licenses' => 'required'
        ]);

        $record = Credentials::where('id', $id)->first();
        $userid = auth()->id();
        $care_licenses = '';

        foreach ($request->care_licenses as $key => $value) {
            if ($key == 0) {
                $care_licenses = $value;
            }else{
                $care_licenses .= ', ' . $value;
            }
        }

        if (@$record) {
            $record->title = $request->title;
            $record->care_licenses = $care_licenses;

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
