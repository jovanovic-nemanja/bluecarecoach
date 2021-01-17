<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Bodyharms;
use App\Bodyharmcomments;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class BodyharmController extends Controller
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
        //
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexbodyharm($id)
    {
        $bodyharms = Bodyharms::where('resident', $id)->get();
        $user = User::where('id', $id)->first();

        return view('admin.bodyharm.index', compact('bodyharms', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createbodyharm($resident)
    {
        $resident = $resident;

        return view('admin.bodyharm.create', compact('resident'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Store a new saved ajax request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeStorage(Request $request) 
    {
        $dates = User::getformattime();
        $date = $dates['date'];
        $screnshot_3d = Bodyharms::upload_file($request->screenshot_3d);
        if($screnshot_3d) {
            $bodyharm = Bodyharms::create([
                'resident' => $request->resident,
                'comment' => $request->comment,
                'screenshot_3d' => $screnshot_3d,
                'sign_date' => $date,
            ]);      
        }

        $result = route('bodyharm.indexbodyharm', $request->resident);
        
        return response()->json($result); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Bodyharms::where('id', $id)->first();
        $res = Bodyharms::deleteUploadedfile($result->screenshot_3d);
        $record = Bodyharms::where('id', $id)->delete();
        
        return redirect()->route('bodyharm.indexbodyharm', $result->resident);
    }
}
