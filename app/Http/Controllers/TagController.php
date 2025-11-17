<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class TagController extends Controller
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
        $tags=Tag::all();
        return view('admin.manage-tag')->with('tags',$tags);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            "msgCode" => "200",
            "html" => view('admin.ajax.add-tag')->render(),
        ]);
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
        $names = explode(',',$request->name);
        foreach($names as $name){
            $requestData['slug'][] = Str::slug($name, '-');
        }
        $request->replace($requestData);
        $validator = Validator::make($requestData, [
            'name' => 'required|max:255',
            'slug.*' => 'required|max:255|unique:tags,slug',
            'metatitle' => 'required|max:255',
            'metadescription' => 'required|max:255',
            'metakeyword' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            try {
                for($i=0;$i<count($names);$i++){
                    Tag::create([
                        'name'=>$names[$i],
                        'slug'=>$requestData['slug'][$i],
                        'metatitle'=>$request->metatitle,
                        'metadescription'=>$request->metadescription,
                        'metakeyword'=>$request->metakeyword
                    ]);
                }
                return response()->json([
                    'msgCode' => '200',
                    'msgText' => 'Tag Created',
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
            $tag=Tag::findOrFail($id);
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.edit-tag')->with('tag',$tag)->render(),
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
        $requestData['slug'] = Str::slug($request->name, '-');
        $request->replace($requestData);
        $validator = Validator::make($requestData, [
            'name' => 'required|max:255',
            "slug"=>["required",Rule::unique('tags')->ignore($id)],
            'metatitle' => 'required|max:255',
            'metadescription' => 'required|max:255',
            'metakeyword' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            try {
                Tag::where('id',$id)->update([
                    'name'=>$request->name,
                    'slug'=>$requestData['slug'],
                    'metatitle'=>$request->metatitle,
                    'metadescription'=>$request->metadescription,
                    'metakeyword'=>$request->metakeyword
                ]);
                return response()->json([
                    'msgCode' => '200',
                    'msgText' => 'Tag Updated',
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
            Tag::findOrFail($id);
            Tag::where('id',$id)->delete();
            return response()->json([
                'msgCode' => '200',
                'msgText' => 'Tag Deleted',
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
    }

    public function changestatus($id,$status)
    {
        try {
            if ($status=='active') {
                $updatedstatus='block';
            } else {
                $updatedstatus='active';
            }
            Tag::findOrFail($id);
            Tag::where('id',$id)->update([
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
