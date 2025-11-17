<?php

namespace App\Http\Controllers;

use App\City;
use App\State;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = User::where('id',Auth::user()->id)->firstOrFail();
        $states=State::where('country_id','101')->get();
        $cities=City::where('state_id',$user->state_id)->get();
        return view('admin.manage-profile')->with('user',$user)->with('states',$states)->with('cities',$cities);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'nullable|image',
            "email"=>["required",Rule::unique('users')->ignore(Auth::user()->id),"email"],
            "contact"=>["required",Rule::unique('users')->ignore(Auth::user()->id),"digits:10"],
            "state"=> "required|integer",
            "city"=> "required|integer",
            "address"=> "required",
        ]);
        try{
            $user = User::where('id',Auth::user()->id)->firstOrFail();
            $data=array(
                'name'=>$request->name,
                'email'=>$request->email,
                'contact'=>$request->contact,
                'state_id'=>$request->state,
                'city_id'=>$request->city,
                'address'=>$request->address,
            );
            if($request->hasFile('image')){
                $data['image'] = $request->image->store('reporters');
                Storage::delete($user->image);
            }
            User::where('id',Auth::user()->id)->update($data);
            return redirect(route('manage-profile'))->with('success','Update Successfull');
        } catch (\Exception $ex) {
            return redirect(route('manage-profile'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function updatepassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);
        try{
            User::where('id',Auth::user()->id)->firstOrFail();
            User::where('id',Auth::user()->id)->update([
                'password'=>Hash::make($request->password),
            ]);
            return redirect(route('manage-profile'))->with('success','Update Successfull');
        } catch (\Exception $ex) {
            return redirect(route('manage-profile'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }
}
