<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CloseAccountRequest;
use App\User;
use App\Reason;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CloseAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $requests=CloseAccountRequest::with('reason', 'user')->get();
        return view('admin.close-account-request')->with('requests',$requests);
    }

    public function request()
    {
        $reasons =   Reason::select('id', 'reason', 'status')
        ->where('status', 'Active')
            ->orderBy('id', 'desc')
            ->get();

        $request=CloseAccountRequest::with('reason', 'user')->where('user_id', Auth::user()->id)->first();
            
        return view('admin.close-account.request')->with('reasons',$reasons)->with('request',$request);
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
        $user =  Auth::user();
        $userid = $user->id;
        $request->validate([
            'reason' => 'required',
            'detail' => 'required',
            'image' => 'nullable|image|mimes:jpeg,bmp,png,gif,svg|max:2048',
        ]);
        try {
            $exist  = CloseAccountRequest::where('user_id', $userid)->first();
            if(!empty($exist))
            {
                return redirect(route('manage-close-account-request.request'))->with('success','Request already exist');
            }
            else
            {
                $image = '';
                if($request->hasFile('image')){
                    $file = $request->file('image');
                    $path = public_path('help');
                    
                    $filename = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $request->file('image')->storeAs('help', $filename);
                    $image = $filename;
                }
                $request->user_id = $userid;
                $request->image = $image;
                CloseAccountRequest::saveData($request);
                return redirect(route('manage-close-account-request.request'))->with('success','Request submitted SuccessFully');
            } 
            
        } catch (\Exception $ex) {
            return redirect(route('manage-close-account-request.request'))->with('error','Error Encountered '.$ex->getMessage());
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
            $request=CloseAccountRequest::findOrFail($id);
            if(isset($request->file) && $request->file !='' && file_exists(storage_path('app/public/help/'.$request->file)))
            {
                unlink(storage_path('app/public/help/'.$request->file));
            }
            CloseAccountRequest::where('id',$id)->delete();
            return redirect(route('manage-close-account-request.index'))->with('success','Delete SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-close-account-request.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            if ($request->has('id')) {
                $crequest = CloseAccountRequest::find($request->input('id'));
                if ($request->has('status')) {
                    $crequest->status = $request->input('status');
                    $accountStatus = $crequest->status == 'Approved' ? 'Closed' : 'Active';
                    CloseAccountRequest::where('id', $request->input('id'))->update(['status' => $crequest->status]);
                    $user = User::where('id', $crequest->user_id)->first();
                    User::where('id', $crequest->user_id)->update(['account_status' => $accountStatus]);
                    if($user->account_status == 'Closed')
                    {
                        $user->tokens()->delete();
                    }
                    
                }

                
            }
            return response()->json([
                "msgCode" => "200",
                "msgText" => "Status updated successfully",
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'msgCode' => '400',
                'msgText' =>$ex->getMessage(),
            ]);
        }
       
    }
}
