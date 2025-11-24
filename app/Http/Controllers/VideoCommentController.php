<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VideoComment;

class VideoCommentController extends Controller
{
    // List all comments
    public function index()
    {
        $comments = VideoComment::with(['video', 'user'])->orderBy('created_at', 'desc')->get();
        return view('admin.video-comments.index', compact('comments'));
    }

    // Delete a single comment
    public function destroy($id)
    {
        $comment = VideoComment::find($id);
        if ($comment) {
            $comment->delete();
            return response()->json(['status' => true, 'message' => 'Comment deleted successfully']);
        }
        return response()->json(['status' => false, 'message' => 'Comment not found']);
    }

    // Bulk delete multiple comments
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids; // array of comment IDs
        if (!empty($ids)) {
            VideoComment::whereIn('id', $ids)->delete();
            return response()->json(['status' => true, 'message' => 'Selected comments deleted successfully']);
        }
        return response()->json(['status' => false, 'message' => 'No comments selected']);
    }

    // Toggle status
    public function toggleStatus($id)
    {
        $comment = VideoComment::findOrFail($id);

        $comment->status = $comment->status == 'Approved' ? 'Blocked' : 'Approved';
        $comment->save();

        return response()->json([
            'status' => true,
            'new_status' => $comment->status
        ]);
    }


}
