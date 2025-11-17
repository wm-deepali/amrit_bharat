<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reason;
use Illuminate\Support\Facades\Storage;

class ReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $reasons=Reason::all();
        return view('admin.manage-reasons.index')->with('reasons',$reasons);
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
                "html" => view('admin.manage-reasons.add-reason')->render(),
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
            'reason'=>'required',
            'status' => 'required',
         ]);
        
        try {
            $reason = $request->input('reason');
            $data=array(
                'reason'=>$request->reason,
                'status'=>$request->status,
            );
            Reason::create($data);
            return redirect(route('manage-reasons.index'))->with('success','Add SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-reasons.index'))->with('error','Error Encountered '.$ex->getMessage());
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
            $reason=Reason::findOrFail($id);
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.manage-reasons.edit-reason')->with('reason',$reason)->render(),
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
            'status' => 'required',
            'reason'=>'required',
        ]);
        try {
            $reason=Reason::findOrFail($id);
            $data=array(
                'reason'=>$request->reason,
                'status'=>$request->status,
            );
            
            Reason::where('id',$id)->update($data);
            return redirect(route('manage-reasons.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-reasons.index'))->with('error','Error Encountered '.$ex->getMessage());
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
            $reason=Reason::findOrFail($id);
            Reason::where('id',$id)->delete();
            return redirect(route('manage-reasons.index'))->with('success','Delete SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-reasons.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }
}
