<?php

namespace App\Http\Controllers;

use App\User;
use App\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Traits\Notification;

class PushNotificationController extends Controller
{
    use Notification;
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
        $this->authorize('is-admin');
        $users=User::whereNotNull('fcm_token')->where('account_status', 'Active')->get();
        $notifications=PushNotification::all();
        return  view('admin.custom-notification')->with('notifications',$notifications)->with('users',$users);
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
        $requestData = $request->all();
        
        $validator = Validator::make($requestData, [
            'type'=>'required',
            'title' => 'required',
            'message' => 'required'
        ]);
        if ($validator->passes()) {
            try {
                PushNotification::create([
                    'user_id'=>$request->user_id,
                    'type'=>$request->type,
                    'title'=>$request->title,
                    'message'=>$request->message,
                    'is_all_user'=>$request->user_id == 0 ? 1 : 0
                ]);
                
                if($request->user_id == 0)
                {
                    $notifyArray=array(
                        'id' => Auth::user()->id,
                        'title' => $request->title,
                        'message' => $request->message,
                    );
                    $this->multiUserCustomNotification($notifyArray);
                }
                else
                {
                    $notifyArray=array(
                        'id' => $request->user_id,
                        'title' => $request->title,
                        'message' => $request->message,
                    );
                    $this->userCustomNotification($notifyArray);
                }
                
                
                return redirect()->route('custom-notification.index')->with('success', 'Notification send successfully!');
            } catch(\Exception $ex) {
                return redirect()->route('custom-notification.index')->with('error', $ex->getMessage());
            }
        } else {
            return redirect()->route('custom-notification.index')->with('error', $validator->errors());
            
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
        
    }

    
}