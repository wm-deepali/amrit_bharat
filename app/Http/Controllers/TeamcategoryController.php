<?php

namespace App\Http\Controllers;

use App\Teamcategory;
use Illuminate\Http\Request;

class TeamcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories=Teamcategory::all();
        return view('admin.manage-team-category')->with('categories',$categories);
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
                "html" => view('admin.ajax.add-team-category')->render(),
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
            'name'=>'required',
        ]);
        try {
            Teamcategory::create([
                'name'=>$request->name
            ]);
            return redirect(route('manage-team-category.index'))->with('success','Add SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-team-category.index'))->with('error','Error Encountered '.$ex->getMessage());
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
            $category=Teamcategory::findOrFail($id);
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.edit-team-category')->with('category',$category)->render(),
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
            'name'=>'required',
        ]);
        try {
            Teamcategory::findOrFail($id);
            $data=array(
                'name'=>$request->name,
            );
            Teamcategory::where('id',$id)->update($data);
            return redirect(route('manage-team-category.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-team-category.index'))->with('error','Error Encountered '.$ex->getMessage());
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
            Teamcategory::findOrFail($id);
            Teamcategory::where('id',$id)->delete();
            return redirect(route('manage-team-category.index'))->with('success','Delete SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-team-category.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function changestatus($id)
    {
        try {
            $category=Teamcategory::findOrFail($id);
            if($category->status=='active'){
                $data=array(
                    'status'=>'block'
                );
            } else {
                $data=array(
                    'status'=>'active'
                );
            }
            Teamcategory::where('id',$id)->update($data);
            return redirect(route('manage-team-category.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-team-category.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }
}
