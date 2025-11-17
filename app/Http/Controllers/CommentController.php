<?php

namespace App\Http\Controllers;

use App\Comment;
use App\PostComment;
use App\CommentReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
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
        $comments=Comment::all();
        return view('admin.manage-comment')->with('comments',$comments);
    }
    public function appComments()
    {
        $this->authorize('is-admin');
        $comments=PostComment::with('user', 'post')->get();
        //dd($comments);
        return view('admin.manage-app-comment')->with('comments',$comments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $comment=Comment::findOrFail($id);
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.comment-user-info')->with('comment',$comment)->render(),
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
    
    public function viewAppComments($id)
    {
        try{
            $comment=PostComment::with('user', 'post', 'commentreplies')->where('id', $id)->first();
            //dd($comment);
            $replies=CommentReply::with('user')->where('comment_id', $id)->get();
            
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.app-comment-user-info')->with('comment',$comment)->with('replies',$replies)->render(),
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $this->authorize('is-admin');
            $comment=Comment::findOrFail($id);
            return response()->json([
                "msgCode" => "200",
                "html" => view('admin.ajax.edit-comment')->with('comment',$comment)->render(),
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
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ]);
        if ($validator->passes()) {
            try {
                Comment::findOrFail($id);
                Comment::where('id',$id)->update([
                    'content'=>$request->comment,
                ]);
                return response()->json([
                    'msgCode' => '200',
                    'msgText' => 'Comment Updated',
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
            Comment::findOrFail($id);
            Comment::where('id',$id)->delete();
            return response()->json([
                'msgCode' => '200',
                'msgText' => 'Comment Deleted',
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
    
    public function deleteAppComment($id)
    {
        try {
            PostComment::findOrFail($id);
            PostComment::where('id',$id)->delete();
            return response()->json([
                'msgCode' => '200',
                'msgText' => 'Comment Deleted',
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


    public function deleteReply($id)
    {
        try {
            CommentReply::findOrFail($id);
            CommentReply::where('id',$id)->delete();
            return response()->json([
                'msgCode' => '200',
                'msgText' => 'Reply Deleted',
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


    public function approvecomment($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            
            if($comment->status == 'Approved')
            {
                Comment::where('id',$id)->update([
                'status'=>'Block',
                ]);
                $msg = 'Comment Block';
            }
            else
            {
                Comment::where('id',$id)->update([
                'status'=>'Approved',
                
             ]);
             $msg = 'Comment Approved';
            }
            return response()->json([
                'msgCode' => '200',
                'msgText' => $msg,
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
    public function approveappcomment($id)
    {
        try {
            $comment = PostComment::findOrFail($id);
            
            if($comment->status == 'Approved')
            {
                PostComment::where('id',$id)->update([
                'status'=>'Block',
                ]);
                $msg = 'Comment Block';
            }
            else
            {
                PostComment::where('id',$id)->update([
                'status'=>'Approved',
                
             ]);
             $msg = 'Comment Approved';
            }
            return response()->json([
                'msgCode' => '200',
                'msgText' => $msg,
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
    public function approveappreply($id)
    {
        try {
            $reply = CommentReply::findOrFail($id);
            
            if($reply->status == 'Approved')
            {
                CommentReply::where('id',$id)->update([
                'status'=>'Block',
                ]);
                $msg = 'Reply Block';
            }
            else
            {
                CommentReply::where('id',$id)->update([
                'status'=>'Approved',
                
             ]);
             $msg = 'Reply Approved';
            }
            return response()->json([
                'msgCode' => '200',
                'msgText' => $msg,
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
}
