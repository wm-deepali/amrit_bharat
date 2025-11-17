<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContactusContent;
use Illuminate\Support\Facades\Storage;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $intro=ContactusContent::first();
        return view('admin.manage-content.index')->with('intro',$intro);
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
        try{
            $this->authorize('is-admin');
            $intro=ContactusContent::findOrFail($id);
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.manage-content.edit-content')->with('intro',$intro)->render(),
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
            'heading'=>'required|max:255',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'text'=>'required',
        ]);
        try {
            $intro=ContactusContent::findOrFail($id);
            $data=array(
                'heading'=>$request->heading,
                'text'=>$request->text,
            );
            $image = $intro->banner;
            if($request->hasFile('banner')) {
                $file = $request->file('banner');
                $path = public_path('banner');
                
                $filename = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $request->file('banner')->storeAs('banner', $filename);
                $image = $filename;
            }
                $data['banner']= $image;
            ContactusContent::where('id',$id)->update($data);
            return redirect(route('manage-content.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-content.index'))->with('error','Error Encountered '.$ex->getMessage());
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
        
    }
}
