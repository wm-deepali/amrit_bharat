<?php

namespace App\Http\Controllers;

use App\Questionoftheday;
use Illuminate\Http\Request;

class QuestionofthedayController extends Controller
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
        $questions=Questionoftheday::orderBy('id','DESC')->get();
        return view('admin.manage-question-of-the-day')->with('questions',$questions);
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
                "html" => view('admin.ajax.add-poll-question')->render(),
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
        $request->validate([
            'question'=>'required',
            'option1'=>'required',
            'option2'=>'required',
            'option3'=>'required',
            'option4'=>'nullable',
        ]);
        try {
            $question=Questionoftheday::create([
                'question'=>$request->question,
                'option1'=>$request->option1,
                'option2'=>$request->option2,
                'option3'=>$request->option3,
                'option4'=>$request->option4,
            ]);
            Questionoftheday::where('id','!=',$question->id)->update([
                'status'=>'block',
            ]);
            return redirect(route('manage-question-of-the-day.index'))->with('success','Add SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-question-of-the-day.index'))->with('error','Error Encountered '.$ex->getMessage());
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
            $question=Questionoftheday::findOrFail($id);
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.edit-poll-question')->with('question',$question)->render(),
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
        $request->validate([
            'question'=>'required',
            'option1'=>'required',
            'option2'=>'required',
            'option3'=>'required',
            'option4'=>'nullable',
        ]);
        try {
            Questionoftheday::findOrFail($id);
            Questionoftheday::where('id',$id)->update([
                'question'=>$request->question,
                'option1'=>$request->option1,
                'option2'=>$request->option2,
                'option3'=>$request->option3,
                'option4'=>$request->option4,
            ]);
            return redirect(route('manage-question-of-the-day.index'))->with('success','Add SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-question-of-the-day.index'))->with('error','Error Encountered '.$ex->getMessage());
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
            Questionoftheday::findOrFail($id);
            Questionoftheday::where('id',$id)->delete();
            return redirect(route('manage-question-of-the-day.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-question-of-the-day.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function changestatus($id)
    {
        try {
            $question=Questionoftheday::findOrFail($id);
            if($question->status=='active'){
                $data=array(
                    'status'=>'block'
                );
            } else {
                $data=array(
                    'status'=>'active'
                );
            }
            Questionoftheday::where('id',$id)->update($data);
            return redirect(route('manage-question-of-the-day.index'))->with('success','Update SuccessFull');
        } catch (\Exception $ex) {
            return redirect(route('manage-question-of-the-day.index'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }
}
