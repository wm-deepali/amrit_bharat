<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SiteIntro;
use Illuminate\Support\Facades\Storage;

class SiteIntroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $intros=SiteIntro::all();
        return view('admin.manage-intro.index')->with('intros',$intros);
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
                "html" => view('admin.manage-intro.add-intro')->render(),
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
            'heading'=>'required|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'short_description'=>'required',
        ]);
        
        try {

            $image = '';
            if($request->hasFile('image')){
                $file = $request->file('image');
                $path = public_path('intro');
                
                $filename = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $request->file('image')->storeAs('intro', $filename);
                $image = $filename;
            }
            $desc = $request->input('short_description');
            $data=array(
                'heading'=>$request->heading,
                'short_description'=>'',
                'image'=>$image
            );
            //dd($data);
            $site = SiteIntro::create($data);
            SiteIntro::where('id',$site->id)->update(['short_description' => $desc]);
            return redirect(route('manage-site-intro.index'))->with('success','Add SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-site-intro.index'))->with('error','Error Encountered '.$ex->getMessage());
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
            $intro=SiteIntro::findOrFail($id);
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.manage-intro.edit-intro')->with('intro',$intro)->render(),
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'short_description'=>'required',
        ]);
        try {
            $intro=SiteIntro::findOrFail($id);
            $data=array(
                'heading'=>$request->heading,
                'short_description'=>$request->short_description,
            );
            $image = $intro->image;
            if($request->hasFile('image')) {
                $file = $request->file('image');
                $path = public_path('intro');
                
                $filename = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $request->file('image')->storeAs('intro', $filename);
                $image = $filename;
            }
                $data['image']= $image;
            SiteIntro::where('id',$id)->update($data);
            return redirect(route('manage-site-intro.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-site-intro.index'))->with('error','Error Encountered '.$ex->getMessage());
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
            $intro=SiteIntro::findOrFail($id);
            if(isset($intro->image) && $intro->image !='' && file_exists(storage_path('app/public/intro/'.$intro->image)))
            {
                unlink(storage_path('app/public/intro/'.$intro->image));
            }
            SiteIntro::where('id',$id)->delete();
            return redirect(route('manage-site-intro.index'))->with('success','Delete SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-site-intro.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }
}
