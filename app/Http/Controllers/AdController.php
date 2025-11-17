<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Adcategory;
use App\Adsetting;
use App\Category;
use App\City;
use App\Post;
use App\State;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
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
        $ads=Ad::all();
        $category=Category::where('status','active')->first();
        $post = Post::whereHas('categories', function (Builder $query) use($category) {
            $query->where('category_id',$category->id);
        })->where('status','published')->orderBy('id', 'desc')->first();
        return view('admin.manage-ads')->with('ads',$ads)->with('category',$category)->with('post',$post);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states=State::where('country_id','101')->get();
        $categories=Category::all();
        return view('admin.add-ads')->with('states',$states)->with('categories',$categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'=>'required',
            'type' => 'required',
            'page' => 'required',
            'position' => 'required',
            'image' => 'nullable|required_if:type,custom|image',
            'link' => 'required_if:type,custom|nullable|url',
            'code' => 'required_if:type,google',
            'startdate' => 'required',
            'enddate' => 'required|after:startdate',
            'amount' => 'required_if:type,custom',
            'paymentmethod' => 'required_if:type,custom',
            'remark' => 'required_if:type,custom',
            'collectedby' => 'required_if:paymentmethod,cash',
            'chequedate' => 'required_if:paymentmethod,cheque',
            'chequenumber' => 'required_if:paymentmethod,cheque',
            'bankname' => 'required_if:paymentmethod,cheque',
            'bankbranch' => 'required_if:paymentmethod,cheque',
            'utrnumber' => 'required_if:paymentmethod,netbanking',
            'utrdate' => 'required_if:paymentmethod,netbanking',
            'upiid' => 'required_if:paymentmethod,upi',
            'upidate' => 'required_if:paymentmethod,upi',
            'upireferencenumber' => 'required_if:paymentmethod,upi',
            'wallet' => 'required_if:paymentmethod,wallet',
            'walletdate' => 'required_if:paymentmethod,wallet',
            'walletreferencenumber' => 'required_if:paymentmethod,wallet',
            'name' => 'required_if:type,custom',
            'contact' => 'required_if:type,custom|nullable|digits:10',
            'email' => 'required_if:type,custom|nullable|email',
            'address' => 'required_if:type,custom',
            'state' => 'required_if:type,custom',
            'city' => 'required_if:type,custom',
            'pincode' => 'required_if:type,custom',
            'category'=>'required_if:page,categorypage',
        ]);
        $validator->after(function($validator) use($request){
            if(isset($request->startdate) && isset($request->enddate)){
                $setting=Adsetting::first();
                $ad=Ad::where('page',$request->page)->where('position',$request->position)->whereDate('enddate','>=',$request->startdate)->whereDate('startdate','<=',$request->enddate)->count();
                if($request->page=='homepage'){
                    if($request->position=='uppersidebar300x250'){
                        if($ad >= $setting->homepageuppersidebar300x250number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='middlesidebar300x250'){
                        if($ad >= $setting->homepagemiddlesidebar300x250number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='lowersidebar300x250') {
                        if($ad >= $setting->homepagelowersidebar300x250number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='uppersidebar300x600') {
                        if($ad >= $setting->homepageuppersidebar300x600number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='middlesidebar300x600') {
                        if($ad >= $setting->homepagemiddlesidebar300x600number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='lowersidebar300x600') {
                        if($ad >= $setting->homepagelowersidebar300x600number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='upperbanner728x90') {
                        if($ad >= $setting->homepageupperbanner728x90number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='middlebanner728x90') {
                        if($ad >= $setting->homepagemiddlebanner728x90number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='lowerbanner728x90') {
                        if($ad >= $setting->homepagelowerbanner728x90number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    }
                } elseif($request->page=='categorypage') {
                    if($request->position=='sidebar300x250'){
                        if($ad >= $setting->categorypagesidebar300x250number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='sidebar300x600'){
                        if($ad >= $setting->categorypagesidebar300x600number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    }
                } elseif($request->page=='postpage') {
                    if($request->position=='uppersidebar300x250'){
                        if($ad >= $setting->postpageuppersidebar300x250number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='lowersidebar300x250'){
                        if($ad >= $setting->postpagelowersidebar300x250number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='sidebar300x600'){
                        if($ad >= $setting->postpagesidebar300x600number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    }
                }
                // if($ad>0){
                //     $validator->errors()->add('startdate', 'Date Range Not Available');
                //     $validator->errors()->add('enddate', 'Date Range Not Available');
                // }
            } else {
                $validator->errors()->add('startdate', 'Required');
                $validator->errors()->add('enddate', 'Required');
            }
        });
        if($validator->passes()){
            try{
                DB::beginTransaction();
                $data=array(
                    'title'=>$request->title,
                    'type'=>$request->type,
                    'page'=>$request->page,
                    'position'=>$request->position,
                    'startdate'=>$request->startdate,
                    'enddate'=>$request->enddate,
                    'transactionnumber'=>Str::uuid(),
                    'paymentstatus'=>'success',
                );
                if($request->type=='custom') {
                    $data['amount']=$request->amount;
                    $data['paymentmethod']=$request->paymentmethod;
                    $data['remark']=$request->remark;
                    $data['name']=$request->name;
                    $data['contact']=$request->contact;
                    $data['email']=$request->email;
                    $data['address']=$request->address;
                    $data['state_id']=$request->state;
                    $data['city_id']=$request->city;
                    $data['pincode']=$request->pincode;
                    $data['image']=$request->image->store('ads');
                    $data['link']=$request->link;
                    if($request->paymentmethod=='cash') {
                        $data['collectedby']=$request->collectedby;
                        $data['chequedate']=Null;
                        $data['chequenumber']=Null;
                        $data['bankname']=Null;
                        $data['bankbranch']=Null;
                        $data['utrnumber']=Null;
                        $data['utrdate']=Null;
                        $data['upiid']=Null;
                        $data['upidate']=Null;
                        $data['upireferencenumber']=Null;
                        $data['wallet']=Null;
                        $data['walletdate']=Null;
                        $data['walletreferencenumber']=Null;
                    } elseif($request->paymentmethod=='cheque') {
                        $data['collectedby']=Null;
                        $data['chequedate']=$request->chequedate;
                        $data['chequenumber']=$request->chequenumber;
                        $data['bankname']=$request->bankname;
                        $data['bankbranch']=$request->bankbranch;
                        $data['utrnumber']=Null;
                        $data['utrdate']=Null;
                        $data['upiid']=Null;
                        $data['upidate']=Null;
                        $data['upireferencenumber']=Null;
                        $data['wallet']=Null;
                        $data['walletdate']=Null;
                        $data['walletreferencenumber']=Null;
                    } elseif($request->paymentmethod=='netbanking') {
                        $data['collectedby']=Null;
                        $data['chequedate']=Null;
                        $data['chequenumber']=Null;
                        $data['bankname']=Null;
                        $data['bankbranch']=Null;
                        $data['utrnumber']=$request->utrnumber;
                        $data['utrdate']=$request->utrdate;
                        $data['upiid']=Null;
                        $data['upidate']=Null;
                        $data['upireferencenumber']=Null;
                        $data['wallet']=Null;
                        $data['walletdate']=Null;
                        $data['walletreferencenumber']=Null;
                    } elseif($request->paymentmethod=='upi') {
                        $data['collectedby']=Null;
                        $data['chequedate']=Null;
                        $data['chequenumber']=Null;
                        $data['bankname']=Null;
                        $data['bankbranch']=Null;
                        $data['utrnumber']=Null;
                        $data['utrdate']=Null;
                        $data['upiid']=$request->upiid;
                        $data['upidate']=$request->upidate;
                        $data['upireferencenumber']=$request->upireferencenumber;
                        $data['wallet']=Null;
                        $data['walletdate']=Null;
                        $data['walletreferencenumber']=Null;
                    } elseif($request->paymentmethod=='wallet') {
                        $data['collectedby']=Null;
                        $data['chequedate']=Null;
                        $data['chequenumber']=Null;
                        $data['bankname']=Null;
                        $data['bankbranch']=Null;
                        $data['utrnumber']=Null;
                        $data['utrdate']=Null;
                        $data['upiid']=Null;
                        $data['upidate']=Null;
                        $data['upireferencenumber']=Null;
                        $data['wallet']=$request->wallet;
                        $data['walletdate']=$request->walletdate;
                        $data['walletreferencenumber']=$request->walletreferencenumber;
                    }
                } elseif($request->type=='google') {
                    $data['code']=$request->code;
                }
                $today=now()->toDateString();
                if($request->startdate<=$today){
                    $data['status']='active';
                }
                $ad=Ad::create($data);
                if($request->page=='categorypage'){
                    foreach(explode(',',$request->category) as $category_id){
                        Adcategory::create([
                            'ad_id'=>$ad->id,
                            'category_id'=>$category_id,
                        ]);
                    }
                }
                DB::commit();
                return response()->json([
                    'msgCode' => '200',
                    'msgText' => 'Ad Added',
                ]);
            } catch(\Exception $ex) {
                DB::rollback();
                return response()->json([
                    'msgCode' => '400',
                    'msgText' => $ex->getMessage(),
                ]);
            }
        } else {
            DB::rollback();
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
            $ad=Ad::findOrFail($id);
            $states=State::where('country_id','101')->get();
            $cities=City::where('state_id',$ad->state_id)->get();
            $categories=Category::all();
            return view('admin.edit-ad')->with('ad',$ad)->with('states',$states)->with('cities',$cities)->with('categories',$categories);
        } catch (\Exception $ex) {

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
        $validator = Validator::make($request->all(), [
            'title'=>'required',
            'type' => 'required',
            'page' => 'required',
            'position' => 'required',
            'image' => 'nullable|image',
            'link' => 'required_if:type,custom|nullable|url',
            'code' => 'required_if:type,google',
            'startdate' => 'required',
            'enddate' => 'required|after:startdate',
            'amount' => 'required_if:type,custom',
            'name' => 'required_if:type,custom',
            'contact' => 'required_if:type,custom|nullable|digits:10',
            'email' => 'required_if:type,custom|nullable|email',
            'address' => 'required_if:type,custom',
            'state' => 'required_if:type,custom',
            'city' => 'required_if:type,custom',
            'pincode' => 'required_if:type,custom',
            'category' => 'required_if:page,categorypage',
        ]);
        $validator->after(function($validator) use($request,$id){
            if(isset($request->startdate) && isset($request->enddate)){
                $setting=Adsetting::first();
                $ad=Ad::where('page',$request->page)->where('position',$request->position)->whereDate('enddate','>=',$request->startdate)->whereDate('startdate','<=',$request->enddate)->where('id','!=',$id)->first();
                if($request->page=='homepage'){
                    if($request->position=='uppersidebar300x250'){
                        if($ad >= $setting->homepageuppersidebar300x250number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='middlesidebar300x250'){
                        if($ad >= $setting->homepagemiddlesidebar300x250number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='lowersidebar300x250') {
                        if($ad >= $setting->homepagelowersidebar300x250number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='uppersidebar300x600') {
                        if($ad >= $setting->homepageuppersidebar300x600number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='middlesidebar300x600') {
                        if($ad >= $setting->homepagemiddlesidebar300x600number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='lowersidebar300x600') {
                        if($ad >= $setting->homepagelowersidebar300x600number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='upperbanner728x90') {
                        if($ad >= $setting->homepageupperbanner728x90number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='middlebanner728x90') {
                        if($ad >= $setting->homepagemiddlebanner728x90number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='lowerbanner728x90') {
                        if($ad >= $setting->homepagelowerbanner728x90number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    }
                } elseif($request->page=='categorypage') {
                    if($request->position=='sidebar300x250'){
                        if($ad >= $setting->categorypagesidebar300x250number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='sidebar300x600'){
                        if($ad >= $setting->categorypagesidebar300x600number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    }
                } elseif($request->page=='postpage') {
                    if($request->position=='uppersidebar300x250'){
                        if($ad >= $setting->postpageuppersidebar300x250number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='lowersidebar300x250'){
                        if($ad >= $setting->postpagelowersidebar300x250number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    } elseif($request->position=='sidebar300x600'){
                        if($ad >= $setting->postpagesidebar300x600number){
                            $validator->errors()->add('position', 'Ad limit reached');
                            $validator->errors()->add('position', 'Ad limit reached');
                        }
                    }
                }
                // if($ad>0){
                //     $validator->errors()->add('startdate', 'Date Range Not Available');
                //     $validator->errors()->add('enddate', 'Date Range Not Available');
                // }
            } else {
                $validator->errors()->add('startdate', 'Required');
                $validator->errors()->add('enddate', 'Required');
            }
        });
        if($validator->passes()){
            try{
                DB::beginTransaction();
                $data=array(
                    'title'=>$request->title,
                    'type'=>$request->type,
                    'page'=>$request->page,
                    'position'=>$request->position,
                    'startdate'=>$request->startdate,
                    'enddate'=>$request->enddate,
                );
                if($request->type=='custom') {
                    if($request->hasFile('image')){
                        $data['image']=$request->image->store('ads');
                    }
                    $data['link']=$request->link;
                    $data['amount']=$request->amount;
                    $data['name']=$request->name;
                    $data['contact']=$request->contact;
                    $data['email']=$request->email;
                    $data['address']=$request->address;
                    $data['state_id']=$request->state;
                    $data['city_id']=$request->city;
                    $data['pincode']=$request->pincode;
                } elseif($request->type=='google') {
                    $data['code']=$request->code;
                }
                $today=now()->toDateString();
                if($request->startdate<=$today){
                    $data['status']='active';
                } else {
                    $data['status']='block';
                }
                Ad::where('id',$id)->update($data);
                Adcategory::where('ad_id',$id)->delete();
                if($request->page=='categorypage'){
                    foreach(explode(',',$request->category) as $category_id){
                        Adcategory::create([
                            'ad_id'=>$id,
                            'category_id'=>$category_id,
                        ]);
                    }
                }
                DB::commit();
                return response()->json([
                    'msgCode' => '200',
                    'msgText' => 'Ad Updated',
                ]);
            } catch(\Exception $ex) {
                DB::rollback();
                return response()->json([
                    'msgCode' => '400',
                    'msgText' => $ex->getMessage(),
                ]);
            }
        } else {
            DB::rollback();
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
            Ad::findOrFail($id);
            Ad::where('id',$id)->delete();
            return redirect(route('manage-ad.index'))->with('success','Delete SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-ad.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function viewclientdetail($id)
    {
        try{
            $ad=Ad::findOrFail($id);
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.client-detail')->with('ad',$ad)->render(),
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
    public function viewpaymentdetail($id)
    {
        try{
            $ad=Ad::findOrFail($id);
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.payment-detail')->with('ad',$ad)->render(),
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

    public function stopad($id)
    {
        try {
            Ad::findOrFail($id);
            Ad::where('id',$id)->update([
                'status'=>'block',
            ]);
            return redirect(route('manage-ad.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-ad.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }
}
