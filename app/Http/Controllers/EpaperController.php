<?php

namespace App\Http\Controllers;

use App\Epaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EpaperController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $epapers=Epaper::all();
        return view('admin.manage-epaper')->with('epapers',$epapers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.add-epaper')->render(),
            ]);
        }
        catch(\Exception $ex){
            return response()->json([
                'msgCode' => '400',
                'msgText' =>$ex->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required|max:255',
            'file'=>'required|mimetypes:application/pdf',
            'date'=>'required',
        ]);
        try {
            Epaper::create([
                'title'=>$request->title,
                'file'=>$request->file->store('epapers'),
                'date'=>$request->date,
            ]);
            return redirect(route('manage-epaper.index'))->with('success','Add SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-epaper.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
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
        try{
            $this->authorize('is-admin');
            $epaper=Epaper::findOrFail($id);
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.edit-epaper')->with('epaper',$epaper)->render(),
            ]);
        }
        catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex){
            return response()->json([
                'msgCode' => '400',
                'msgText' => 'Data Not found by id#' . $id,
            ]);
        }
        catch(\Exception $ex){
            return response()->json([
                'msgCode' => '400',
                'msgText' =>$ex->getMessage(),
            ]);
        }
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
        $request->validate([
            'title'=>'required|max:255',
            'file'=>'nullable|mimetypes:application/pdf',
            'date'=>'required',
        ]);
        try {
            $epaper=Epaper::findOrFail($id);
            $data=array(
                'title'=>$request->title,
                'date'=>$request->date,
            );
            if($request->hasFile('file')) {
                $data['file']=$request->file->store('epapers');
                if(isset($epaper->file) && Storage::exists($epaper->file)){
                    Storage::delete($request->file);
                }
            }
            Epaper::where('id',$id)->update($data);
            return redirect(route('manage-epaper.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-epaper.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
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
            $epaper=Epaper::findOrFail($id);
            Storage::delete($epaper->file);
            Epaper::where('id',$id)->delete();
            return redirect(route('manage-epaper.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-epaper.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function changestatus($id)
    {
        try {
            $epaper=Epaper::findOrFail($id);
            if($epaper->status=='active'){
                $data=array(
                    'status'=>'block'
                );
            } else {
                $data=array(
                    'status'=>'active'
                );
            }
            Epaper::where('id',$id)->update($data);
            return redirect(route('manage-epaper.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-epaper.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }
}
