<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Homepagewidget;
class CategoryController extends Controller
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
        $this->authorize('is-admin');
        $categories = Category::all();
        return view('admin.manage-category')->with('categories',$categories);
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
                "html" => view('admin.ajax.add-category')->render(),
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
        $requestData = $request->all();
        $requestData['slug'] = Str::slug($request->slug, '-');
        $request->replace($requestData);
        $validator = Validator::make($requestData, [
            'name' => 'required|max:255',
            'hassubcategory' => 'nullable',
            'showonheader' => 'nullable',
            'showonfooter' => 'nullable',
            'slug' => 'required|max:255|unique:categories',
            'metatitle' => 'required|max:255',
            'metadescription' => 'required|max:255',
            'metakeyword' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            try {
                Category::create([
                    'name'=>$request->name,
                    'hassubcategory'=>$request->hassubcategory ?? 'no',
                    'showonheader'=>$request->showonheader ?? 'no',
                    'showonfooter'=>$request->showonfooter ?? 'no',
                    'slug'=>$request->slug,
                    'metatitle'=>$request->metatitle,
                    'metadescription'=>$request->metadescription,
                    'metakeyword'=>$request->metakeyword
                ]);
                return response()->json([
                    'msgCode' => '200',
                    'msgText' => 'Category Created',
                ]);
            } catch(\Exception $ex) {
                return response()->json([
                    'msgCode' => '400',
                    'msgText' => $ex->getMessage(),
                ]);
            }
        } else {
            return response()->json([
                'msgCode'=>'401',
                'errors'=>$validator->errors(),
            ]);
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
            $category=Category::findOrFail($id);
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.edit-category')->with('category',$category)->render(),
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
        $requestData = $request->all();
        $requestData['slug'] = Str::slug($request->slug, '-');
        $request->replace($requestData);
        $validator = Validator::make($requestData, [
            'name' => 'required|max:255',
            'hassubcategory' => 'nullable',
            'showonheader' => 'nullable',
            'showonfooter' => 'nullable',
            "slug"=>["required",Rule::unique('categories')->ignore($id)],
            'metatitle' => 'required|max:255',
            'metadescription' => 'required|max:255',
            'metakeyword' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            try {
                Category::findOrFail($id);
                Category::where('id',$id)->update([
                    'name'=>$request->name,
                    'hassubcategory'=>$request->hassubcategory ?? 'no',
                    'showonheader'=>$request->showonheader ?? 'no',
                    'showonfooter'=>$request->showonfooter ?? 'no',
                    'slug'=>$request->slug,
                    'metatitle'=>$request->metatitle,
                    'metadescription'=>$request->metadescription,
                    'metakeyword'=>$request->metakeyword
                ]);
                return response()->json([
                    'msgCode' => '200',
                    'msgText' => 'Category Updated',
                ]);
            } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex){
                return response()->json([
                    'msgCode' => '400',
                    'msgText' => 'Data Not found by id#' . $id,
                ]);
            } catch(\Exception $ex) {
                return response()->json([
                    'msgCode' => '400',
                    'msgText' => $ex->getMessage(),
                ]);
            }
        } else {
            return response()->json([
                'msgCode'=>'401',
                'errors'=>$validator->errors(),
            ]);
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
           $category = Category::findOrFail($id);
           
           if(count($category->posts) > 0)
           {
               $category->posts->each->delete();
           }
           
           
        //   $category->videoposts->delete();
        //   $category->subcategories->delete();
        //   Homepagewidget::where('cataloguecategory',$category)->update(['cataloguecategory'=>""]);
        // //   Homepagewidget::where('catalogueposttype',$category)->update(['catalogueposttype'=>""]);
        //   Homepagewidget::where('categorytab1',$category)->update(['categorytab1'=>""]);
        // //   Homepagewidget::where('categorytab1posttype',$category)->update(['categorytab1posttype'=>""]);
        //   Homepagewidget::where('categorytab2',$category)->update(['categorytab2'=>""]);
        // //   Homepagewidget::where('categorytab2posttype',$category)->update(['categorytab2posttype'=>""]);
        //   Homepagewidget::where('categorytab3',$category)->update(['categorytab3'=>""]);
        // //   Homepagewidget::where('categorytab3posttype',$category)->update(['categorytab3posttype'=>""]);
        //   Homepagewidget::where('categorytab4',$category)->update(['categorytab4'=>""]);
        // //   Homepagewidget::where('categorytab4posttype',$category)->update(['categorytab4posttype'=>""]);
        //   Homepagewidget::where('slidercategory',$category)->update(['slidercategory'=>""]);
        // //   Homepagewidget::where('sliderposttype',$category)->update(['sliderposttype'=>""]);
        //   Homepagewidget::where('trendingcategory',$category)->update(['trendingcategory'=>""]);
        // //   Homepagewidget::where('trendingposttype',$category)->update(['trendingposttype'=>""]);
        //   Homepagewidget::where('otherwidgetcategory',$category)->update(['otherwidgetcategory'=>""]);
        // //   Homepagewidget::where('otherwidgetposttype',$category)->update(['otherwidgetposttype'=>""]);
        //   Homepagewidget::where('mustreadcategory',$category)->update(['mustreadcategory'=>""]);
        // //   Homepagewidget::where('mustreadposttype',$category)->update(['mustreadposttype'=>""]);
        //   Homepagewidget::where('youmaylikecategory',$category)->update(['youmaylikecategory'=>""]);
        // //   Homepagewidget::where('youmaylikeposttype',$category)->update(['youmaylikeposttype'=>""]);
        //   Homepagewidget::where('sidebartab1category',$category)->update(['sidebartab1category'=>""]);
        // //   Homepagewidget::where('sidebartab1posttype',$category)->update(['sidebartab1posttype'=>""]);
        //   Homepagewidget::where('sidebartab2category',$category)->update(['sidebartab2category'=>""]);
        //   Homepagewidget::where('sidebartab3category',$category)->update(['sidebartab3category'=>""]);
        //   Homepagewidgetcentercategory::where('sidebartab3category',$category)->delete();
            $category->delete();
            return response()->json([
                'msgCode' => '200',
                'msgText' => 'Category Deleted',
            ]);
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response()->json([
                'msgCode' => '400',
                'msgText' => 'Data Not found by id#' . $id,
            ]);
        } catch(\Exception $ex) {
            return response()->json([
                'msgCode' => '400',
                'msgText' => $ex->getMessage(),
            ]);
        }
    }

    public function changestatus($id,$status)
    {
        try {
            if ($status=='active') {
                $updatedstatus='block';
            } else {
                $updatedstatus='active';
            }
            Category::findOrFail($id);
            Category::where('id',$id)->update([
                'status'=>$updatedstatus,
            ]);
            return response()->json([
                'msgCode' => '200',
                'msgText' => 'Status Changed',
                'status' => $updatedstatus,
            ]);
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response()->json([
                'msgCode' => '400',
                'msgText' => 'Data Not found by id#' . $id,
            ]);
        } catch(\Exception $ex) {
            return response()->json([
                'msgCode' => '400',
                'msgText' => $ex->getMessage(),
            ]);
        }
    }
}
