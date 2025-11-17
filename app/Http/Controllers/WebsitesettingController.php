<?php

namespace App\Http\Controllers;

use App\Aboutus;
use App\Contactus;
use App\Cookiepolicy;
use App\Privacypolicy;
use App\Termsofuse;
use Illuminate\Http\Request;

class WebsitesettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function editaboutus()
    {
        $this->authorize('is-admin');
        $aboutus = Aboutus::first();
        return view('admin.manage-about-us')->with('aboutus',$aboutus);
    }

    public function updateaboutus(Request $request)
    {
        $request->validate([
            "content1" => 'required',
            "content2" => 'required',
            "address" => 'required',
            "contact1" => 'required|digits:10',
            "contact2" => 'nullable|digits:10',
            "map" => 'required',
        ]);
        try {
            Aboutus::updateOrCreate(['id'=>1],[
                'content1'=>$request->content1,
                'content2'=>$request->content2,
                'address'=>$request->address,
                'contact1'=>$request->contact1,
                'contact2'=>$request->contact2,
                'map'=>$request->map,
            ]);
            return redirect(route('edit-about-us'))->with('success','Update Successfull');
        } catch (\Exception $ex) {
            return redirect(route('edit-about-us'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function editprivacypolicy()
    {
        $this->authorize('is-admin');
        $privacypolicy = Privacypolicy::first();
        return view('admin.manage-privacy-policy')->with('privacypolicy',$privacypolicy);
    }

    public function updateprivacypolicy(Request $request)
    {
        $request->validate([
            "content" => 'required',
        ]);
        try {
            Privacypolicy::updateOrCreate(['id'=>1],[
                'content'=>$request->content,
            ]);
            return redirect(route('edit-privacy-policy'))->with('success','Update Successfull');
        } catch (\Exception $ex) {
            return redirect(route('edit-privacy-policy'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function editcookiepolicy()
    {
        $this->authorize('is-admin');
        $cookiepolicy = Cookiepolicy::first();
        return view('admin.manage-cookie-policy')->with('cookiepolicy',$cookiepolicy);
    }

    public function updatecookiepolicy(Request $request)
    {
        $request->validate([
            "content" => 'required',
        ]);
        try {
            Cookiepolicy::updateOrCreate(['id'=>1],[
                'content'=>$request->content,
            ]);
            return redirect(route('edit-cookie-policy'))->with('success','Update Successfull');
        } catch (\Exception $ex) {
            return redirect(route('edit-cookie-policy'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function edittermsofuse()
    {
        $this->authorize('is-admin');
        $termsofuse = Termsofuse::first();
        return view('admin.manage-terms-of-use')->with('termsofuse',$termsofuse);
    }

    public function updatetermsofuse(Request $request)
    {
        $request->validate([
            "content" => 'required',
        ]);
        try {
            Termsofuse::updateOrCreate(['id'=>1],[
                'content'=>$request->content,
            ]);
            return redirect(route('edit-terms-of-use'))->with('success','Update Successfull');
        } catch (\Exception $ex) {
            return redirect(route('edit-terms-of-use'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function managecontactus()
    {
        $this->authorize('is-admin');
        $contactuses = Contactus::all();
        return view('admin.manage-contact-us')->with('contactuses',$contactuses);
    }

    public function deletecontactus($id)
    {
        try {
            Contactus::where('id',$id)->delete();
            return redirect(route('manage-contact-us'))->with('success','Delete Successfull');
        } catch (\Exception $ex) {
            return redirect(route('manage-contact-us'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }
}
