<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Video;
use App\VideoBookmarkLike;
use App\VideoView;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

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

        foreach ($videos as $video) {
            $video->duration = $this->getYoutubeDuration($video->youtube_link);
        }


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

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('short_description', 'like', "%$search%");
            });
        }

        $videos = $query->latest()->get();
        foreach ($videos as $video) {
            $video->duration = $this->getYoutubeDuration($video->youtube_link);
        }

        return response()->json([
            'status' => true,
            'data' => $videos
        ]);
    }

    // VIEW SINGLE VIDEO
// -----------------------------

    public function show($id)
    {
        $user = Auth::user();
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $video = Video::with([
            'comments.user',
            'comments.reactions'
        ])->find($id);

        if (!$video) {
            return response()->json([
                'status' => false,
                'message' => 'Video not found'
            ], 404);
        }

        // Track unique view per IP per day
        $ip = request()->ip();
        $today = Carbon::today();

        $exists = VideoView::where('video_id', $video->id)
            ->where('ip_address', $ip)
            ->whereDate('created_at', $today)
            ->exists();

        if (!$exists) {
            VideoView::create([
                'video_id' => $video->id,
                'ip_address' => $ip
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => $video,
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
        $video->views = 0;
        $video->published_at = $request->status === 'published' ? now() : null;
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
                'message' => 'You are not authorized to update this video'
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

        // Update published_at if status changes to published
        if ($request->status === 'published') {
            $video->published_at = now();
        }

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
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $video = Video::find($id);

        if (!$video || $video->user_id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to delete this video'
            ], 404);
        }

        $video->delete();

        return response()->json([
            'status' => true,
            'message' => 'Video deleted successfully'
        ]);
    }


    // -----------------------------
// ADD LIKE
// -----------------------------
    public function addLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'video_id' => 'required|exists:videos,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Video Request',
                'data' => $validator->errors()
            ], 401);
        }

        $user = Auth::user();
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it',
            ], 401);
        }

        $video = Video::find($request->video_id);
        if (!$video) {
            return response()->json([
                'status' => false,
                'message' => 'Video not found'
            ], 404);
        }

        $existLike = VideoBookmarkLike::where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->first();

        if (!$existLike) {
            VideoBookmarkLike::create([
                'user_id' => $user->id,
                'video_id' => $video->id,
                'likes' => 1,
            ]);
        } else {
            if ($existLike->likes == 0) {
                $existLike->update(['likes' => 1]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Like added successfully.',
            'data' => []
        ]);
    }

    // -----------------------------
// REMOVE LIKE
// -----------------------------
    public function removeLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'video_id' => 'required|exists:videos,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Video Request',
                'data' => $validator->errors()
            ], 401);
        }

        $user = Auth::user();
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it',
            ], 401);
        }

        $video = Video::find($request->video_id);
        if (!$video) {
            return response()->json([
                'status' => false,
                'message' => 'Video not found'
            ], 404);
        }

        $existLike = VideoBookmarkLike::where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->first();

        if ($existLike && $existLike->likes == 1) {
            $existLike->update(['likes' => 0]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Like removed successfully.',
            'data' => []
        ]);
    }

    // -----------------------------
// GET YOUTUBE DURATION
// -----------------------------
    private function getYoutubeDuration($youtubeLink)
    {
        if (!$youtubeLink)
            return null;

        preg_match("/(?:v=|\/)([a-zA-Z0-9_-]{11})/", $youtubeLink, $matches);
        if (!isset($matches[1]))
            return null;

        $youtubeId = $matches[1];
        $apiKey = env('YOUTUBE_API_KEY');

        $response = Http::get("https://www.googleapis.com/youtube/v3/videos", [
            'part' => 'contentDetails',
            'id' => $youtubeId,
            'key' => $apiKey
        ]);

        if ($response->ok() && !empty($response['items'][0]['contentDetails']['duration'])) {
            $isoDuration = $response['items'][0]['contentDetails']['duration'];
            $interval = new \DateInterval($isoDuration);
            return $interval->format('%H:%I:%S');
        }

        return null;
    }

}
