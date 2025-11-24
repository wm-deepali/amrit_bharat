<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Video;
use App\VideoComment;
use App\CommentLike;

class VideoCommentController extends Controller
{
    /**************************************
     * Add Comment to Video
     **************************************/
    public function addComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'video_id' => 'required|exists:videos,id',
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Request',
                'data' => $validator->errors()
            ], 400);
        }

        $user = Auth::user();
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact admin to activate it',
                'data' => []
            ], 401);
        }

        $video = Video::where('id', $request->video_id)
            ->first();

        if (!$video) {
            return response()->json([
                'status' => false,
                'message' => 'Video not found or deleted',
                'data' => []
            ], 404);
        }

        $comment = VideoComment::create([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment added successfully',
            'data' => $comment
        ]);
    }

    /**************************************
     * Edit Comment
     **************************************/
    public function editComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|exists:video_comments,id',
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Request',
                'data' => $validator->errors()
            ], 400);
        }

        $user = Auth::user();
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive',
                'data' => []
            ], 401);
        }

        $comment = VideoComment::where('id', $request->comment_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$comment) {
            return response()->json([
                'status' => false,
                'message' => 'Comment not found or you are not authorized',
                'data' => []
            ], 403);
        }

        $comment->update(['comment' => $request->comment, 'is_edit' => 'Yes']);

        return response()->json([
            'status' => true,
            'message' => 'Comment updated successfully',
            'data' => $comment
        ]);
    }

    /**************************************
     * Delete Comment
     **************************************/
    public function deleteComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Request',
                'data' => $validator->errors()
            ], 400);
        }

        $user = Auth::user();
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive',
                'data' => []
            ], 401);
        }

        $ids = explode(",", $request->comment_id);

        VideoComment::whereIn('id', $ids)
            ->where('user_id', $user->id)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Comment(s) deleted successfully',
            'data' => []
        ]);
    }

    /**************************************
     * Add Like on Comment
     **************************************/
    public function addLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|exists:video_comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Request',
                'data' => $validator->errors()
            ], 400);
        }

        $user = Auth::user();
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive',
                'data' => []
            ], 401);
        }

        $comment = VideoComment::find($request->comment_id);
        $like = CommentLike::firstOrCreate(
            ['user_id' => $user->id, 'comment_id' => $request->comment_id],
            ['likes' => 1]
        );

        if ($like->wasRecentlyCreated || $like->likes == 0) {
            $comment->increment('total_likes');
            $like->update(['likes' => 1]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Like added successfully',
            'data' => []
        ]);
    }

    /**************************************
     * Remove Like on Comment
     **************************************/
    public function removeLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|exists:video_comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Request',
                'data' => $validator->errors()
            ], 400);
        }

        $user = Auth::user();
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive',
                'data' => []
            ], 401);
        }

        $like = CommentLike::where('user_id', $user->id)
            ->where('comment_id', $request->comment_id)
            ->where('likes', 1)
            ->first();

        if ($like) {
            $comment = VideoComment::find($request->comment_id);
            $comment->decrement('total_likes');
            $like->update(['likes' => 0]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Like removed successfully',
            'data' => []
        ]);
    }
}
