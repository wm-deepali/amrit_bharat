<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Help;
use Illuminate\Support\Facades\Storage;

class HelpRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $requests=Help::with('user')->get();
        return view('admin.help-request')->with('requests',$requests);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $request=Help::findOrFail($id);
            if(isset($request->file) && $request->file !='' && file_exists(storage_path('app/public/help/'.$request->file)))
            {
                unlink(storage_path('app/public/help/'.$request->file));
            }
            Help::where('id',$id)->delete();
            return redirect(route('manage-help-request.index'))->with('success','Delete SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-help-request.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    
}
