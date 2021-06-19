<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\EmailSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EmailSettingsController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emailsettings = EmailSettings::all();
        return view('admin.emailsettings.index', compact('emailsettings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.emailsettings.create');
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
            'type' => 'required',
            'from_address' => 'required',
            'from_title' => 'required',
            'subject' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $emailsettings = EmailSettings::create([
                'type' => $request['type'],
                'from_address' => $request['from_address'],
                'from_title' => $request['from_title'],
                'subject' => $request['subject'],
                'content_name' => @$request['content_name'],
                'content_body' => @$request['content_body'],
                'pre_footer' => @$request['pre_footer'],
                'footer' => @$request['footer'],
                'sign_date' => date('Y-m-d H:i:s'),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }  

        return redirect()->route('emailsettings.index')->with('flash', 'Successfully added Email template.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $emailsettings = EmailSettings::where('id', $id)->first();

        return view('admin.emailsettings.edit', compact('emailsettings'));
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
        $emailsettings = EmailSettings::where('id', $id)->first();
        if (@$emailsettings) {
            $emailsettings->type = @$request->type;
            switch ($emailsettings->type) {
                case '1':
                    $emailsettings->content_name = @$request->content_name;
                    break;
                case '2':
                    $emailsettings->content_name = @$request->content_name;
                    $emailsettings->content_body = @$request->content_body;
                    $emailsettings->pre_footer = @$request->pre_footer;
                    break;
                case '3':
                    break;
                case '4':
                    break;
                case '5':
                    break;
                default:
                    break;
            }

            $emailsettings->from_address = @$request->from_address;
            $emailsettings->from_title = @$request->from_title;
            $emailsettings->subject = @$request->subject;

            $emailsettings->update();
        }

        return redirect()->route('emailsettings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $emailsettings = EmailSettings::where('id', $id)->delete();
        
        return redirect()->route('emailsettings.index');
    }
}
