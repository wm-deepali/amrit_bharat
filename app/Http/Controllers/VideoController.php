<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;
use Auth;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type ?? 'all';

        if ($type == 'published') {
            $videos = Video::where('status', 'published')->latest()->get();
        } elseif ($type == 'pending') {
            $videos = Video::where('status', 'pending')->latest()->get();
        } elseif ($type == 'rejected') {
            $videos = Video::where('status', 'rejected')->latest()->get();
        } else {
            $videos = Video::latest()->get();
        }

        return view('admin.videos.index', compact('videos', 'type'));
    }


    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required|unique:videos,slug',
            'short_description' => 'nullable|max:200',
            'youtube_link' => 'required|url',
            'detail_content' => 'nullable',
            'status' => 'required|in:pending,published,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $video = Video::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => $request->slug,
            'short_description' => $request->short_description,
            'youtube_link' => $request->youtube_link,
            'detail_content' => $request->detail_content,
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? now() : null,
            'views' => 0, // initialize views
        ]);

        return response()->json(['msg' => 'Video Added Successfully', 'data' => $video]);
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:videos,slug,' . $id,
            'short_description' => 'nullable|string|max:200',
            'youtube_link' => 'required|url',
            'detail_content' => 'nullable|string',
            'status' => 'required|in:pending,published,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $video = Video::findOrFail($id);

        $video->title = $request->title;
        $video->slug = $request->slug ?? \Str::slug($request->title);
        $video->short_description = $request->short_description;
        $video->youtube_link = $request->youtube_link;
        $video->detail_content = $request->detail_content;

        // Update published_at if status is changed to published
        if ($request->status === 'published') {
            $video->published_at = now();
        }

        $video->status = $request->status;

        $video->save();

        return response()->json(['msg' => 'Video Updated Successfully', 'data' => $video]);
    }

    public function show($id)
    {
        $video = Video::findOrFail($id);
        return view('admin.videos.view', compact('video'));
    }

    public function updateStatus(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        $video->status = $request->status;

        // Set published_at if status is published
        if ($request->status === 'published' && !$video->published_at) {
            $video->published_at = now();
        }

        $video->save();

        return response()->json(['msg' => 'Status Updated', 'data' => $video]);
    }

    public function destroy($id)
    {
        Video::findOrFail($id)->delete();
        return response()->json(['msg' => 'Video Deleted']);
    }
}
