<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\FCMService;
use App\User;
use App\Post;
use App\OtpVerification;
use Carbon\Carbon;
use App\CloseAccountRequest;
use App\Help;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\Messaging\InvalidArgument;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpEmail;
use App\Mail\SendResetPasswordSuccessEmail;
use DB;


class AuthController extends Controller
{
   
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'role' => 'required|in:user,reporter',
            'fcm_token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status'=>false, 'message' => 'Invalid Credentials', 'data'=>''], 401);
        }
        else if(isset($user) && !empty($user) && $user->status == 'pending')
        {
            return response()->json(['status'=>false, 'message' => 'Your account not approved yet, Contact your administrator to approved it', 'data'=>''], 401);
        }
        else if(isset($user) && !empty($user) && $user->account_status == 'Closed')
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
        else
        {
            $url = env('APP_URL').'/storage/app/public/';
            User::where('id', $user->id)->update(['fcm_token'=>$request->fcm_token]);
            $data = User::where('id',$user->id)->select("users.*",DB::raw("CONCAT('".$url."', users.image) as profile_pic"))->first();
            
            $token = $user->createToken('apiToken')->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'Login Successfully',
                'data' => $data,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
        
    }


    public function mobLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'country_code'      => 'required|regex:/^\+\d{1,3}$/',
            'role' => 'required|in:user,reporter',
            'fcm_token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        // Get user record
        $user = User::where('contact',$request->contact)->where('country_code',$request->country_code)->first();
        // Check Condition contact No. Found or Not
        if (!$user || $request->contact != $user->contact) {
            return response()->json(['status'=>false, 'message' => 'Please Register First contact number.!!', 'data'=>''], 500);
        }
        else if(isset($user) && !empty($user) && $user->status == 'pending')
        {
            return response()->json(['status'=>false, 'message' => 'Your account not approved yet, Contact your administrator to approved it', 'data'=>''], 401);
        }
        else if(isset($user) && !empty($user) && $user->account_status == 'Closed')
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
        else
        {
            User::where('id', $user->id)->update(['fcm_token'=>$request->fcm_token]);
            $verificationOtp = OtpVerification::where('user_id', $user->id)->latest()->first();

            $now = Carbon::now();
    
            if($verificationOtp && $now->isBefore($verificationOtp->expire_at)){
                
                $otp = $verificationOtp->otp;
                $message="$otp is the One Time Password(OTP) to verify your MOB number at Web Mingo, This OTP is Usable only once and is valid for 10 min,PLS DO NOT SHARE THE OTP WITH ANYONE";
                $dlt_id = '1307161465983326774';
                $request_parameter = array(
                    'authkey'   => '133780AZGqc6gKWfh63da1812P1',
                    'mobiles'   => $user->contact,
                    'message'   => urlencode($message),
                    'sender'    => 'WMINGO',
                    'route'     => '4',
                    'country'   => '91',
                    'unicode'   => '1',
                );
                $url = "http://sms.webmingo.in/api/sendhttp.php?";
                foreach($request_parameter as $key=>$val)
                {
                    $url.=$key.'='.$val.'&';
                }
                $url = $url.'DLT_TE_ID='.$dlt_id;
                $url =rtrim($url , "&");
                try {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    //get response
                    $output = curl_exec($ch);
                    curl_close($ch);
                    
                    $verificationOtp::create([
                    'user_id' => $user->id,
                    'otp' => $otp,
                    'expire_at' => Carbon::now()->addMinutes(10)
                    ]);
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP send successfully',
                        'otp' => $otp,
                        'data' => [],
                    ]);
                   
                } catch (\Exception $e) {
                    //dd($e->getMessage());
                    return response()->json(['status'=>false, 'message' => $e->getMessage(), 'data'=>''], 500);
                }
            }
            else
            {
                $otp = substr(str_shuffle("0123456789"), 0, 4);
                $message="$otp is the One Time Password(OTP) to verify your MOB number at Web Mingo, This OTP is Usable only once and is valid for 10 min,PLS DO NOT SHARE THE OTP WITH ANYONE";
                $dlt_id = '1307161465983326774';
                $request_parameter = array(
                    'authkey'   => '133780AZGqc6gKWfh63da1812P1',
                    'mobiles'   => $user->contact,
                    'message'   => urlencode($message),
                    'sender'    => 'WMINGO',
                    'route'     => '4',
                    'country'   => '91',
                    'unicode'   => '1',
                );
                $url = "http://sms.webmingo.in/api/sendhttp.php?";
                foreach($request_parameter as $key=>$val)
                {
                    $url.=$key.'='.$val.'&';
                }
                $url = $url.'DLT_TE_ID='.$dlt_id;
                $url =rtrim($url , "&");
                try {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    //get response
                    $output = curl_exec($ch);
                    curl_close($ch);
                    
                    OtpVerification::create([
                    'user_id' => $user->id,
                    'otp' => $otp,
                    'expire_at' => Carbon::now()->addMinutes(10)
                    ]);
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP send successfully',
                        'otp' => $otp,
                        'data' => [],
                    ]);
                   
                } catch (\Exception $e) {
                    //dd($e->getMessage());
                    return response()->json(['status'=>false, 'message' => $e->getMessage(), 'data'=>''], 500);
                }
                
            }
            
            
            
            
        }
    }
    
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'country_code'      => 'required|regex:/^\+\d{1,3}$/',
            'role' => 'required|in:user,reporter',
            'otp' => 'required',
            'fcm_token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        // Get user record
        $user = User::where('contact',$request->contact)->where('country_code',$request->country_code)->first();
        // Check Condition contact No. Found or Not
        if (!$user || $request->contact != $user->contact) {
            return response()->json(['status'=>false, 'message' => 'Please Register First contact number.!!', 'data'=>''], 500);
        }
        else if(isset($user) && !empty($user) && $user->status == 'pending')
        {
            return response()->json(['status'=>false, 'message' => 'Your account not approved yet, Contact your administrator to approved it', 'data'=>''], 401);
        }
        else if(isset($user) && !empty($user) && $user->account_status == 'Closed')
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
        else
        {
            
            $verificationOtp = OtpVerification::where('user_id', $user->id)->where('otp', $request->otp)->first();

            $now = Carbon::now();
            if (!$verificationOtp) {
                return response()->json(['status'=>false, 'message' => 'Your OTP is not correct', 'data'=>''], 401);
            }
            elseif($verificationOtp && $now->isAfter($verificationOtp->expire_at))
            {
                return response()->json(['status'=>false, 'message' => 'Your OTP has been expired', 'data'=>''], 401);
            }
            
            User::where('id', $user->id)->update(['fcm_token'=>$request->fcm_token]);
            $user = User::where('id', $user->id)->first();
            $verificationOtp->delete();
            $token = $user->createToken('apiToken')->plainTextToken;
            return response()->json([
                'status'=>true,
                'message'=> 'Login Successfully', 
                'data' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
            
        }
    }

    public function glogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name'=>'required',
            'google_id'=>'required',
            'role' => 'required|in:user,reporter',
            'fcm_token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }

        $user = User::where('email', $request->email)->first();
        if(isset($user) && !empty($user))
        {
            if($user->status == 'pending')
            {
                return response()->json(['status'=>false, 'message' => 'Your account not approved yet, Contact your administrator to approved it', 'data'=>''], 401);
            }
            else if($user->account_status == 'Closed')
            {
                return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
            }
            else
            {
                User::where('id', $user->id)->update(['fcm_token'=>$request->fcm_token]);
                $user = User::where('id', $user->id)->first();
                $token = $user->createToken('apiToken')->plainTextToken;
                return response()->json([
                    'status'=>true,
                    'message'=> 'Login Successfully', 
                    'data' => $user,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
            }
        }
        else
        {   
            if($request->role == 'user')
            {
                $code = 'USER'.rand ( 100 , 999 );
            }
            else{
                $code = 'RP'.rand ( 100 , 999 );
            }
            $request->user_number = $code;
            $user =  User::saveGoogleData($request);
            User::where('id', $user)->update(['fcm_token'=>$request->fcm_token]);
            $data = User::where('id',$user)->first();
            
            
            $token = $data->createToken('apiToken')->plainTextToken;
            return response()->json([
                'status'=>true,
                'message'=> 'Login Successfully', 
                'data' => $data,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'nullable|string|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6',
            'country_code' => 'required|regex:/^\+\d{1,3}$/',
            'contact' => 'required|regex:/[0-9]{10}/|unique:users',
            'gender' => 'required|in:male,female',
            'address' => 'required',
            'fcm_token' => 'required',
            'state' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role' => 'required|in:user,reporter',
            'dob' => 'required|date|date_format:Y-m-d|before:today'
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $profile_pic = '';
        if($request->hasFile('image')){
            $file = $request->file('image');
            $path = public_path('users');
            
            $filename = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $request->file('image')->storeAs('users', $filename);
           // $file->move($path, $filename);   
          if($request->role == 'user')
            {
                $profile_pic = 'users/'.$filename;
               
            }
            else{
                $profile_pic = 'reporters/'.$filename;
                
            }  
        }
        if($request->role == 'user')
        {
            $code = 'USER'.rand ( 100 , 999 );
        }
        else{
            $code = 'RP'.rand ( 100 , 999 );
        }
        $request->user_number = $code;
        $request->image = $profile_pic;
        $user =  User::saveData($request);
        $data = User::where('id',$user)->first();
        FCMService::send(
            $data->fcm_token,
            [
                'title' => 'Registration',
                'body' => 'You have been successfully registered!',
            ]
        );
        return response()->json([
            'status'=>true,
            'message'=> 'You have been successfully registered!. Please login to continue...', 
            'data'=>[],
            // 'authorisation' => [
            //     'token' => $token,
            //     'type' => 'bearer',
            // ]
        ]);
    }


    public function getUser()
    {
        $user =  Auth::user();
        $url = env('APP_URL').'/storage/app/public/';
        $user = User::with('state', 'city')->where('id',$user->id)->select("users.*",DB::raw("CONCAT('".$url."', users.image) as profile_pic"))->first();
        return response()->json(['status'=>true, 'message' => 'User Data', 'data'=>[$user]], 200);
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['status'=>true,'message'=> 'Successfully logged out.', 'data'=>''], 201);
    }

    public function usernameExist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user = User::where('username', $request->username)
                                    ->first();
        if(!empty($user)) 
        {
            return response()->json([
            'status'=>true,
            'message'=> 'Username already used.', 
            'data'=>[],
            ]);
         }
         else{
            return response()->json([
                'status'=>true,
                'message'=> 'Username not found.', 
                'data'=>[],
                ]);
         }

    }


    public function resetPasswordByEmail(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }

        $user = User::where('email', $request->email)->first();
        if($user)
        {
            $verificationOtp = OtpVerification::where('user_id', $user->id)->latest()->first();

            $now = Carbon::now();
    
            if($verificationOtp && $now->isBefore($verificationOtp->expire_at)){
                
                $otp = $verificationOtp->otp;
                
                Mail::to($request->email)->send(new SendOtpEmail($otp));
            }
            else
            {
                $otp = substr(str_shuffle("0123456789"), 0, 4);
                
                OtpVerification::create([
                    'user_id' => $user->id,
                    'otp' => $otp,
                    'expire_at' => Carbon::now()->addMinutes(10)
                ]);
                 Mail::to($request->email)->send(new SendOtpEmail($otp));
            }

            return response()->json([
                'status'=>true, 
                'message' => "OTP send successfully on your registered Email address!", 
                'data'=>$user,
                'otp'=>$otp
            ]);
        }   
        else{
            return response()->json([
                'status'=>true, 
                'message' => 'Email id not exist', 
                'data'=>[]
            ]);
        }
        
    }
    
    public function verifyEmailOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'otp' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        
        
        $user = User::where('email', $request->email)->first();
        if($user)
        {
            $verificationOtp = OtpVerification::where('user_id', $user->id)->where('otp', $request->otp)->first();
            $now = Carbon::now();
            if (!$verificationOtp) {
                return response()->json(['status'=>false, 'message' => 'Your OTP is not correct', 'data'=>''], 401);
            }
            elseif($verificationOtp && $now->isAfter($verificationOtp->expire_at))
            {
                return response()->json(['status'=>false, 'message' => 'Your OTP has been expired', 'data'=>''], 401);
            }
            
            $user = User::where('id', $user->id)->first();
            $verificationOtp->delete();
            $token = $user->createToken('apiToken')->plainTextToken;
            return response()->json([
                'status'=>true,
                'message'=> 'OTP verified Successfully', 
                'data' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
        else{
            return response()->json([
                'status'=>true, 
                'message' => 'Email id not exist', 
                'data'=>[]
            ]);
        }
      
    }
    
    public function verifyMobileOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'otp' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        
        
        $user = User::where('contact', $request->contact)->first();
        if($user)
        {
            $verificationOtp = OtpVerification::where('user_id', $user->id)->where('otp', $request->otp)->first();
            $now = Carbon::now();
            if (!$verificationOtp) {
                return response()->json(['status'=>false, 'message' => 'Your OTP is not correct', 'data'=>''], 401);
            }
            elseif($verificationOtp && $now->isAfter($verificationOtp->expire_at))
            {
                return response()->json(['status'=>false, 'message' => 'Your OTP has been expired', 'data'=>''], 401);
            }
            
            $user = User::where('id', $user->id)->first();
            $verificationOtp->delete();
            $token = $user->createToken('apiToken')->plainTextToken;
            return response()->json([
                'status'=>true,
                'message'=> 'OTP verified Successfully', 
                'data' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
        else{
            return response()->json([
                'status'=>true, 
                'message' => 'Mobile number not exist', 
                'data'=>[]
            ]);
        }
      
    }


    public function resetPasswordByMobile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }

        $user = User::where('contact', $request->contact)->first();
        if($user)
        {
            $verificationOtp = OtpVerification::where('user_id', $user->id)->latest()->first();

            $now = Carbon::now();
    
            if($verificationOtp && $now->isBefore($verificationOtp->expire_at)){
                
                $otp = $verificationOtp->otp;
                $message="$otp is the One Time Password(OTP) to verify your MOB number at Web Mingo, This OTP is Usable only once and is valid for 10 min,PLS DO NOT SHARE THE OTP WITH ANYONE";
                $dlt_id = '1307161465983326774';
                $request_parameter = array(
                    'authkey'   => '133780AZGqc6gKWfh63da1812P1',
                    'mobiles'   => $user->contact,
                    'message'   => urlencode($message),
                    'sender'    => 'WMINGO',
                    'route'     => '4',
                    'country'   => '91',
                    'unicode'   => '1',
                );
                $url = "http://sms.webmingo.in/api/sendhttp.php?";
                foreach($request_parameter as $key=>$val)
                {
                    $url.=$key.'='.$val.'&';
                }
                $url = $url.'DLT_TE_ID='.$dlt_id;
                $url =rtrim($url , "&");
                try {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    //get response
                    $output = curl_exec($ch);
                    curl_close($ch);
                    
                    $verificationOtp::create([
                    'user_id' => $user->id,
                    'otp' => $otp,
                    'expire_at' => Carbon::now()->addMinutes(10)
                    ]);
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP send successfully',
                        'otp' => $otp,
                        'data' => $user,
                    ]);
                   
                } catch (\Exception $e) {
                    //dd($e->getMessage());
                    return response()->json(['status'=>false, 'message' => $e->getMessage(), 'data'=>''], 500);
                }
            }
            else
            {
                $otp = substr(str_shuffle("0123456789"), 0, 4);
                $message="$otp is the One Time Password(OTP) to verify your MOB number at Web Mingo, This OTP is Usable only once and is valid for 10 min,PLS DO NOT SHARE THE OTP WITH ANYONE";
                $dlt_id = '1307161465983326774';
                $request_parameter = array(
                    'authkey'   => '133780AZGqc6gKWfh63da1812P1',
                    'mobiles'   => $user->contact,
                    'message'   => urlencode($message),
                    'sender'    => 'WMINGO',
                    'route'     => '4',
                    'country'   => '91',
                    'unicode'   => '1',
                );
                $url = "http://sms.webmingo.in/api/sendhttp.php?";
                foreach($request_parameter as $key=>$val)
                {
                    $url.=$key.'='.$val.'&';
                }
                $url = $url.'DLT_TE_ID='.$dlt_id;
                $url =rtrim($url , "&");
                try {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    //get response
                    $output = curl_exec($ch);
                    curl_close($ch);
                    
                    OtpVerification::create([
                    'user_id' => $user->id,
                    'otp' => $otp,
                    'expire_at' => Carbon::now()->addMinutes(10)
                    ]);
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP send successfully',
                        'otp' => $otp,
                        'data' => $user,
                    ]);
                   
                } catch (\Exception $e) {
                    //dd($e->getMessage());
                    return response()->json(['status'=>false, 'message' => $e->getMessage(), 'data'=>''], 500);
                }
                
            }
            
        }   
        else{
            return response()->json([
                'status'=>true, 
                'message' => 'Mobile number not exist', 
                'data'=>[]
            ]);
        }
        
    }
    public function passwordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|exists:users',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }

        // update user password
        User::where('id', $request->id)->update(['password' => Hash::make($request->password)]);
        
        $user = User::where('id', $request->id)->first();
        Mail::to($user->email)->send(new SendResetPasswordSuccessEmail());
        
        return response()->json([
            'status'=>true, 
            'message' => 'Password has been successfully reset!', 
            'data'=>[]
        ]);
    }

    public function profileUpdate(Request $request)
    {
        $user =  Auth::user();
        $userid = $user->id;
        $required = $user->fcm_token === null ? 'required|' : '';
        $validator = Validator::make($request->all(), [
            'username' => 'nullable|string|unique:users,username,'.$user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|unique:users,email,'.$user->id,
            'country_code' => 'required|regex:/^\+\d{1,3}$/',
            'contact' => 'required|unique:users,contact,'.$user->id,
            'gender' => 'required|in:male,female',
            'address' => 'required',
            'state' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role' => 'required|in:user,reporter',
            'dob' => 'required|date|date_format:Y-m-d|before:today',
            'fcm_token' => $required
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        if($request->has('username') && $request->username !=''){
            $request->username = $request->username;
        }
        else{
            $request->username = $user->username;
        }
        
       
        
        

        if($user->fcm_token !== null)
        {
            $request->fcm_token = $user->fcm_token;
        }
        User::saveData($request,$user->id);

        if($request->hasFile('image')){
            $file = $request->file('image');
            $path = public_path('users');
            
            $filename = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $request->file('image')->storeAs('users', $filename);
            if($user->role == 'user')
            {
                $profile_pic = 'users/'.$filename;
               
            }
            else{
                $profile_pic = 'reporters/'.$filename;
                
            }  
            $profile_pic = 'users/'.$filename;
            User::where('id',$user->id)->update(['image' => $profile_pic]);
        }
        
        $url = env('APP_URL').'/storage/app/public/';
        $user = User::with('state', 'city')->where('id',$user->id)->select("users.*",DB::raw("CONCAT('".$url."', users.image) as profile_pic"))->first();
        return response()->json(['status'=>true, 'message' => 'User Profile update successfully', 'data'=>[$user]], 200);
    }


    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $currentPassword = Auth::User()->password;
        if(Hash::check($request->current_password, $currentPassword))
        {
            $userId = Auth::User()->id;
            $user = User::find($userId);
            $user->password = Hash::make($request->new_password);
            $user->save();
            
            Mail::to($user->email)->send(new SendResetPasswordSuccessEmail());
            
            return response()->json([
                'status'=>true, 
                'message' => 'Your password has been updated successfully.', 
                'data'=>[]
            ]);
        }
        else{
            return response()->json(['status'=>false, 'message' => 'Sorry, your current password was not recognised. Please try again.', 'data'=>''], 401);
        }
    }


    public function helpRequest(Request $request)
    {
        $user =  Auth::user();
        $userid = $user->id;
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'details' => 'required',
            'document' => 'nullable|mimes:jpeg,bmp,png,gif,svg,pdf|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        
        $document = '';
        if($request->hasFile('document')){
            $file = $request->file('document');
            $path = public_path('help');
            
            $filename = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $request->file('document')->storeAs('help', $filename);
            $document = $filename;
        }
        $request->user_id = $userid;
        $request->document = $document;
        Help::saveData($request);
        return response()->json(['status'=>true, 'message' => 'Request submitted successfully', 'data'=>[]], 200);
    }
    
    public function closeAccountRequest(Request $request)
    {
        $user =  Auth::user();
        $userid = $user->id;
        $validator = Validator::make($request->all(), [
            'reason' => 'required',
            'detail' => 'required',
            'image' => 'nullable|image|mimes:jpeg,bmp,png,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $exist  = CloseAccountRequest::where('user_id', $userid)->first();
        if(!empty($exist))
        {
           return response()->json(['status'=>false, 'message'=> 'Request already exist', 'data'=> []], 401);
            
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
            return response()->json(['status'=>true, 'message' => 'Request submitted successfully', 'data'=>[]], 200);
        }
        
        
    }
    
    public function deleteAccountRequest(Request $request)
    {
        $user =  Auth::user();
        $userid = $user->id;
        
        $deleteExist  = User::where('id', $userid)->where('delete_status', '0')->first();
        $exist  = CloseAccountRequest::where('user_id', $userid)->first();
        if(!empty($deleteExist))
        {
           return response()->json(['status'=>false, 'message'=> 'Request already exist', 'data'=> []], 401);
            
        }
        else
        {
            $deleteExist->delete_status ='1';
            $deleteExist->delete_date =date('Y-m-d H:i:s'); 
            $deleteExist->account_status = 'Closed';
            $deleteExist->save();
            Post::where('user_id', $userid)->update(['user_delete_status'=>'1']);
            if(!empty($exist))
            {
                $exist->status = 'Approved';
                $exist->save();
            }
            
            return response()->json(['status'=>true, 'message' => 'Request submitted successfully', 'data'=>[]], 200);
        }
        
        
    }
    
    public function updateFcmToken(Request $request)
    {
        $user =  Auth::user();
        $userid = $user->id;
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required',
         ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $url = env('APP_URL').'/storage/app/public/';
        User::where('id', $user->id)->update(['fcm_token'=>$request->fcm_token]);
        $data = User::where('id',$user->id)->select("users.*",DB::raw("CONCAT('".$url."', users.image) as profile_pic"))->first();
        
        return response()->json(['status'=>true, 'message' => 'Token updated successfully', 'data' => $data], 200);
    }
    

}