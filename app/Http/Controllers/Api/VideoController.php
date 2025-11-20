<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    // -----------------------------
    // ALL VIDEOS (active/published)
    // -----------------------------
    public function allVideos(Request $request)
    {
        $type = $request->type ?? 'published'; // default show only published

        $query = Video::query();

        if ($type != 'all') {
            $query->where('status', $type);
        }

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('short_description', 'like', "%$search%");
            });
        }

        $videos = $query->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $videos
        ]);
    }

    // -----------------------------
    // MY VIDEOS (auth user only)
    // -----------------------------
    public function myVideos(Request $request)
    {
        $user = Auth::user();
        if (!isset($user) || empty($user) || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $type = $request->type ?? 'all';

        $query = Video::where('user_id', $user->id);

        if ($type != 'all') {
            $query->where('status', $type);
        }

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('short_description', 'like', "%$search%");
            });
        }

        $videos = $query->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $videos
        ]);
    }


    // -----------------------------
    // VIEW SINGLE VIDEO
    // -----------------------------
    public function show($id)
    {
        $user = Auth::user();
        if (!isset($user) || empty($user) || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $video = Video::find($id);

        if (!$video) {
            return response()->json([
                'status' => false,
                'message' => 'Video not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $video
        ]);
    }

    // -----------------------------
    // CREATE VIDEO
    // -----------------------------
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!isset($user) || empty($user) || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:videos,slug',
            'short_description' => 'nullable|string|max:200',
            'youtube_link' => 'required|url',
            'detail_content' => 'nullable|string',
            'status' => 'required|in:pending,published,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Post Request',
                'data' => $validator->errors()
            ], 422);
        }

        $video = new Video();
        $video->user_id = $user->id;
        $video->title = $request->title;
        $video->slug = $request->slug ?? Str::slug($request->title);
        $video->short_description = $request->short_description;
        $video->youtube_link = $request->youtube_link;
        $video->detail_content = $request->detail_content;
        $video->status = $request->status;
        $video->save();

        return response()->json([
            'status' => true,
            'message' => 'Video created successfully',
            'data' => $video
        ]);
    }

    // -----------------------------
    // UPDATE VIDEO
    // -----------------------------
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!isset($user) || empty($user) || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $video = Video::find($id);

        if (!$video || $video->user_id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to update this event'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:videos,slug,' . $id,
            'short_description' => 'nullable|string|max:200',
            'youtube_link' => 'required|url',
            'detail_content' => 'nullable|string',
            'status' => 'required|in:pending,published,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Post Request',
                'data' => $validator->errors()
            ], 422);
        }

        $video->title = $request->title;
        $video->slug = $request->slug ?? Str::slug($request->title);
        $video->short_description = $request->short_description;
        $video->youtube_link = $request->youtube_link;
        $video->detail_content = $request->detail_content;
        $video->status = 'pending';

        $video->save();

        return response()->json([
            'status' => true,
            'message' => 'Video updated successfully',
            'data' => $video
        ]);
    }

    // -----------------------------
    // DELETE VIDEO
    // -----------------------------
    public function destroy($id)
    {
        $user = Auth::user();
        if (!isset($user) || empty($user) || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $video = Video::find($id);

        if (!$video || $video->user_id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to update this event'
            ], 404);
        }

        $video->delete();

        return response()->json([
            'status' => true,
            'message' => 'Video deleted successfully'
        ]);
    }
}
