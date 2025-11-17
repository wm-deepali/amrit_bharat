<?php

namespace App\Http\Controllers;

use App\Category;
use App\Footersetting;
use App\Headersetting;
use App\Homepagewidget;
use App\Homepagewidgetcentercategory;
use App\Homepagewidgetlowercategory;
use App\Homepagewidgetvideocategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SitesettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function editheadersetting()
    {
        $this->authorize('is-admin');
        $header = Headersetting::first();
        return view('admin.manage-header-setting')->with('header',$header);
    }

    public function updateheadersetting(Request $request)
    {
        $request->validate([
            "image" => 'nullable|image',
            "link" => 'nullable|max:255',
            "title" => 'nullable|max:255',
            "datetimeformat" => 'required|max:255',
            "facebook" => 'nullable|max:255',
            "twitter" => 'nullable|max:255',
            "youtube" => 'nullable|max:255',
            "instagram" => 'nullable|max:255',
        ]);
        try {
            $data=array(
                'link' => $request->link,
                'title' => $request->title,
                'datetimeformat' => $request->datetimeformat,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'youtube' => $request->youtube,
                'instagram' => $request->instagram,
            );
            if($request->hasFile('image')){
                $data['image'] = $request->image->store('headersettings');
            }
            Headersetting::updateOrCreate(['id'=>1],$data);
            return redirect(route('manage-header-setting'))->with('success','Update Successfull');
        } catch (\Exception $ex) {
            return redirect(route('manage-header-setting'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function editfootersetting()
    {
        $this->authorize('is-admin');
        $footer = Footersetting::first();
        return view('admin.manage-footer-setting')->with('footer',$footer);
    }

    public function updatefootersetting(Request $request)
    {
        $request->validate([
            "image" => 'nullable|image',
            "link" => 'nullable|max:255',
            "content" => 'nullable|max:255',
            "facebook" => 'nullable|max:255',
            "twitter" => 'nullable|max:255',
            "rss" => 'nullable|max:255',
        ]);
        try {
            $data=array(
                'link' => $request->link,
                'content' => $request->content,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'rss' => $request->rss,
            );
            if($request->hasFile('image')){
                $data['image'] = $request->image->store('footersettings');
            }
            Footersetting::updateOrCreate(['id'=>1],$data);
            return redirect(route('manage-footer-setting'))->with('success','Update Successfull');
        } catch (\Exception $ex) {
            return redirect(route('manage-footer-setting'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function edithomepagewidget()
    {
        $this->authorize('is-admin');
        $categories=Category::all();
        $homepagewidget=Homepagewidget::first();
        $centercategoryids=Homepagewidgetcentercategory::pluck('category_id')->toArray();
        $lowercategoryids=Homepagewidgetlowercategory::pluck('category_id')->toArray();
        $videocategoryids=Homepagewidgetvideocategory::pluck('category_id')->toArray();
        return view('admin.manage-homepage-widget')->with('categories',$categories)
        ->with('homepagewidget',$homepagewidget)->with('centercategoryids',$centercategoryids)
        ->with('lowercategoryids',$lowercategoryids)->with('videocategoryids',$videocategoryids);
    }

    public function updatehomepagewidget(Request $request)
    {
        $request->validate([
            'cataloguecategory'=>'required',
            'catalogueposttype'=>'required',
            'categorytab1'=>'required',
            'categorytab1posttype'=>'required',
            'categorytab2'=>'required',
            'categorytab2posttype'=>'required',
            'categorytab3'=>'required',
            'categorytab3posttype'=>'required',
            'categorytab4'=>'required',
            'categorytab4posttype'=>'required',
            'slidercategory'=>'required',
            'sliderposttype'=>'required',
            'trendingcategory'=>'required',
            'trendingposttype'=>'required',
            'centercategorytab'=>'required|array|size:3',
            'videocategorytab'=>'required|array|size:3',
            'otherwidgetcategory'=>'required',
            'otherwidgetposttype'=>'required',
            'mustreadcategory'=>'required',
            'mustreadposttype'=>'required',
            'youmaylikecategory'=>'required',
            'youmaylikeposttype'=>'required',
            'lowercategorytab'=>'required|array|size:3',
            'sidebartab1category'=>'required',
            'sidebartab1posttype'=>'required',
            'sidebartab2category'=>'required',
            'sidebartab2posttype'=>'required',
            'sidebartab3category'=>'required',
            'sidebartab3posttype'=>'required',
        ]);
        try {
            DB::beginTransaction();
            Homepagewidget::updateOrCreate(['id'=>1],
            [
                'cataloguecategory'=>$request->cataloguecategory,
                'catalogueposttype'=>$request->catalogueposttype,
                'categorytab1'=>$request->categorytab1,
                'categorytab1posttype'=>$request->categorytab1posttype,
                'categorytab2'=>$request->categorytab2,
                'categorytab2posttype'=>$request->categorytab2posttype,
                'categorytab3'=>$request->categorytab3,
                'categorytab3posttype'=>$request->categorytab3posttype,
                'categorytab4'=>$request->categorytab4,
                'categorytab4posttype'=>$request->categorytab4posttype,
                'slidercategory'=>$request->slidercategory,
                'sliderposttype'=>$request->sliderposttype,
                'trendingcategory'=>$request->trendingcategory,
                'trendingposttype'=>$request->trendingposttype,
                'otherwidgetcategory'=>$request->otherwidgetcategory,
                'otherwidgetposttype'=>$request->otherwidgetposttype,
                'mustreadcategory'=>$request->mustreadcategory,
                'mustreadposttype'=>$request->mustreadposttype,
                'youmaylikecategory'=>$request->youmaylikecategory,
                'youmaylikeposttype'=>$request->youmaylikeposttype,
                'sidebartab1category'=>$request->sidebartab1category,
                'sidebartab1posttype'=>$request->sidebartab1posttype,
                'sidebartab2category'=>$request->sidebartab2category,
                'sidebartab2posttype'=>$request->sidebartab2posttype,
                'sidebartab3category'=>$request->sidebartab3category,
                'sidebartab3posttype'=>$request->sidebartab3posttype,
            ]);
            Homepagewidgetcentercategory::where('homepagewidget_id',1)->delete();
            foreach($request->centercategorytab as $category_id){
                Homepagewidgetcentercategory::create([
                    'homepagewidget_id'=>1,
                    'category_id'=>$category_id,
                ]);
            }
            Homepagewidgetvideocategory::where('homepagewidget_id',1)->delete();
            foreach($request->videocategorytab as $category_id){
                Homepagewidgetvideocategory::create([
                    'homepagewidget_id'=>1,
                    'category_id'=>$category_id,
                ]);
            }
            Homepagewidgetlowercategory::where('homepagewidget_id',1)->delete();
            foreach($request->lowercategorytab as $category_id){
                Homepagewidgetlowercategory::create([
                    'homepagewidget_id'=>1,
                    'category_id'=>$category_id,
                ]);
            }
            DB::commit();
            return redirect(route('manage-homepage-widget'))->with('success','Updated Successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect(route('manage-homepage-widget'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }
}
