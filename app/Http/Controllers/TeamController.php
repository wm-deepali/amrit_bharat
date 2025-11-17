<?php

namespace App\Http\Controllers;

use App\City;
use App\State;
use App\Team;
use App\Teamcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class TeamController extends Controller
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
        $teams=Team::all();
        return view('admin.manage-team')->with('teams',$teams);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            $categories=Teamcategory::where('status','active')->get();
            $states=State::where('country_id','101')->get();
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.add-team')->with('states',$states)->with('categories',$categories)->render(),
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
            'category'=>'required',
            'name'=>'required',
            'designation'=>'required',
            'state'=>'required|integer',
            'city'=>'required|integer',
            'image'=>'required|image',
        ]);
        try {
            Team::create([
                'teamcategory_id'=>$request->category,
                'name'=>$request->name,
                'designation'=>$request->designation,
                'state_id'=>$request->state,
                'city_id'=>$request->city,
                'image'=>$request->image->store('teams'),
            ]);
            $imagehash=$request->image->hashName();
            $path = storage_path('app/public/teams/'.$imagehash);
            Image::make($request->image)->resize(450,450,function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($path);
            return redirect(route('manage-team.index'))->with('success','Add SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-team.index'))->with('error','Error Encountered '.$ex->getMessage());
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
            $team=Team::findOrFail($id);
            $categories=Teamcategory::where('status','active')->get();
            $states=State::where('country_id','101')->get();
            $cities=City::where('state_id',$team->state_id)->get();
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.edit-team')->with('team',$team)->with('states',$states)->with('cities',$cities)->with('categories',$categories)->render(),
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
            'category'=>'required',
            'name'=>'required',
            'designation'=>'required',
            'state'=>'required|integer',
            'city'=>'required|integer',
            'image'=>'nullable|image',
        ]);
        try {
            $team=Team::findOrFail($id);
            $data=array(
                'teamcategory_id'=>$request->category,
                'name'=>$request->name,
                'designation'=>$request->designation,
                'state_id'=>$request->state,
                'city_id'=>$request->city,
            );
            if($request->hasFile('image')){
                $data['image']=$request->image->store('teams');
                if(isset($team->image) && Storage::exists($team->image)){
                    Storage::delete($team->image);
                }
                $imagehash=$request->image->hashName();
                $path = storage_path('app/public/teams/'.$imagehash);
                Image::make($request->image)->resize(450,450,function($constraint){
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path);
            }
            Team::where('id',$id)->update($data);
            return redirect(route('manage-team.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-team.index'))->with('error','Error Encountered '.$ex->getMessage());
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
            $team=Team::findOrFail($id);
            Storage::delete($team->image);
            Team::where('id',$id)->delete();
            return redirect(route('manage-team.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-team.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function changestatus($id)
    {
        try {
            $team=Team::findOrFail($id);
            if($team->status=='active'){
                $data=array(
                    'status'=>'block'
                );
            } else {
                $data=array(
                    'status'=>'active'
                );
            }
            Team::where('id',$id)->update($data);
            return redirect(route('manage-team.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-team.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }
}