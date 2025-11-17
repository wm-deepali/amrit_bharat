<?php

namespace App\Http\Controllers;

use App\Adsetting;
use Illuminate\Http\Request;

class AdsettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function adsetting()
    {
        $setting=Adsetting::first();
        return view('admin.ad-settings')->with('setting',$setting);
    }

    public function updateadsetting(Request $request)
    {
        $request->validate([
            'homepageuppersidebar300x250time'=>'required|gt:0',
            'homepageuppersidebar300x250number'=>'required|gt:0',
            'homepagemiddlesidebar300x250time'=>'required|gt:0',
            'homepagemiddlesidebar300x250number'=>'required|gt:0',
            'homepagelowersidebar300x250time'=>'required|gt:0',
            'homepagelowersidebar300x250number'=>'required|gt:0',
            'homepageuppersidebar300x600time'=>'required|gt:0',
            'homepageuppersidebar300x600number'=>'required|gt:0',
            'homepagemiddlesidebar300x600time'=>'required|gt:0',
            'homepagemiddlesidebar300x600number'=>'required|gt:0',
            'homepagelowersidebar300x600time'=>'required|gt:0',
            'homepagelowersidebar300x600number'=>'required|gt:0',
            'homepageupperbanner728x90time'=>'required|gt:0',
            'homepageupperbanner728x90number'=>'required|gt:0',
            'homepagemiddlebanner728x90time'=>'required|gt:0',
            'homepagemiddlebanner728x90number'=>'required|gt:0',
            'homepagelowerbanner728x90time'=>'required|gt:0',
            'homepagelowerbanner728x90number'=>'required|gt:0',
            'homepagelowestbanner728x90time'=>'required|gt:0',
            'homepagelowestbanner728x90number'=>'required|gt:0',
            'categorypagesidebar300x250time'=>'required|gt:0',
            'categorypagesidebar300x250number'=>'required|gt:0',
            'categorypagesidebar300x600time'=>'required|gt:0',
            'categorypagesidebar300x600number'=>'required|gt:0',
            'postpageuppersidebar300x250time'=>'required|gt:0',
            'postpageuppersidebar300x250number'=>'required|gt:0',
            'postpagelowersidebar300x250time'=>'required|gt:0',
            'postpagelowersidebar300x250number'=>'required|gt:0',
            'postpagesidebar300x600time'=>'required|gt:0',
            'postpagesidebar300x600number'=>'required|gt:0',
        ]);
        try {
            Adsetting::updateOrCreate(['id'=>1],[
                'homepageuppersidebar300x250time'=>$request->homepageuppersidebar300x250time,
                'homepageuppersidebar300x250number'=>$request->homepageuppersidebar300x250number,
                'homepagemiddlesidebar300x250time'=>$request->homepagemiddlesidebar300x250time,
                'homepagemiddlesidebar300x250number'=>$request->homepagemiddlesidebar300x250number,
                'homepagelowersidebar300x250time'=>$request->homepagelowersidebar300x250time,
                'homepagelowersidebar300x250number'=>$request->homepagelowersidebar300x250number,
                'homepageuppersidebar300x600time'=>$request->homepageuppersidebar300x600time,
                'homepageuppersidebar300x600number'=>$request->homepageuppersidebar300x600number,
                'homepagemiddlesidebar300x600number'=>$request->homepagemiddlesidebar300x600number,
                'homepagemiddlesidebar300x600time'=>$request->homepagemiddlesidebar300x600time,
                'homepagelowersidebar300x600time'=>$request->homepagelowersidebar300x600time,
                'homepagelowersidebar300x600number'=>$request->homepagelowersidebar300x600number,
                'homepageupperbanner728x90time'=>$request->homepageupperbanner728x90time,
                'homepageupperbanner728x90number'=>$request->homepageupperbanner728x90number,
                'homepagemiddlebanner728x90time'=>$request->homepagemiddlebanner728x90time,
                'homepagemiddlebanner728x90number'=>$request->homepagemiddlebanner728x90number,
                'homepagelowerbanner728x90time'=>$request->homepagelowerbanner728x90time,
                'homepagelowerbanner728x90number'=>$request->homepagelowerbanner728x90number,
                'homepagelowestbanner728x90time'=>$request->homepagelowerbanner728x90time,
                'homepagelowestbanner728x90number'=>$request->homepagelowerbanner728x90number,
                'categorypagesidebar300x250time'=>$request->categorypagesidebar300x250time,
                'categorypagesidebar300x250number'=>$request->categorypagesidebar300x250number,
                'categorypagesidebar300x600time'=>$request->categorypagesidebar300x600time,
                'categorypagesidebar300x600number'=>$request->categorypagesidebar300x600number,
                'postpageuppersidebar300x250time'=>$request->postpageuppersidebar300x250time,
                'postpageuppersidebar300x250number'=>$request->postpageuppersidebar300x250number,
                'postpagelowersidebar300x250time'=>$request->postpagelowersidebar300x250time,
                'postpagelowersidebar300x250number'=>$request->postpagelowersidebar300x250number,
                'postpagesidebar300x600time'=>$request->postpagesidebar300x600time,
                'postpagesidebar300x600number'=>$request->postpagesidebar300x600number,
            ]);
            return redirect(route('ad-setting'))->with('success','Update Successfull');
        } catch (\Exception $ex) {
            return redirect(route('ad-setting'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }
}
