<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Vitalsign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class VitalsignController extends Controller
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
        $result = Vitalsign::all();

        return view('admin.resident.create');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexresidentvitalsign($id)
    {
        $user = User::where('id', $id)->first();
        $vitalsigns = Vitalsign::where('resident_id', $id)->orderby('sign_date', 'DESC')->get();

        return view('admin.vitalsign.index', compact('user', 'vitalsigns'));
    }

    /**
    * @param resident id
    * @author Nemanja
    * @since 2020-12-23
    * @return Resource render for putting resident vital sign data
    */
    public function createvitalsign($resident)
    {
        $user = User::where('id', $resident)->first();
        
        return view('admin.vitalsign.create', compact('user', 'resident'));
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
        $dates = User::getformattime();
        $date = $dates['date'];

        if (@$request->temperature) {
            $vitalsign = Vitalsign::create([
                'resident_id' => $request->resident_id,
                'data' => $request->temperature,
                'type' => 1,
                'sign_date' => $date,
            ]);
        }if (@$request->blood_pressure) {
            $vitalsign = Vitalsign::create([
                'resident_id' => $request->resident_id,
                'data' => $request->blood_pressure,
                'type' => 2,
                'sign_date' => $date,
            ]);
        }if (@$request->heart_rate) {
            $vitalsign = Vitalsign::create([
                'resident_id' => $request->resident_id,
                'data' => $request->heart_rate,
                'type' => 3,
                'sign_date' => $date,
            ]);
        }

        return redirect()->route('vitalsign.indexresidentvitalsign', $request->resident_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = [];

        $res = Vitalsign::where('id', $id)->first();
        $result['data'] = $res;
        $result['user'] = User::where('id', $res->resident_id)->first();

        return view('admin.vitalsign.edit', compact('result'));
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
        $vitalsign = Vitalsign::where('id', $id)->first();
        if (@$vitalsign) {
            $vitalsign->data = @$request->data;

            $vitalsign->update();
        }

        return redirect()->route('vitalsign.indexresidentvitalsign', $request->resident_id)->with('flash', 'Vital Sign has been successfully changed.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = Vitalsign::where('id', $id)->first();
        $record = Vitalsign::where('id', $id)->delete();
        
        return redirect()->route('vitalsign.indexresidentvitalsign', $res->resident_id);
    }
}
