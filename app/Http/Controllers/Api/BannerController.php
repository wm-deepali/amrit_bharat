<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Banner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    // -----------------------------
    // ALL BANNERS
    // -----------------------------
    public function allBanners(Request $request)
    {
        $type = $request->type ?? 'published';
        $query = Banner::query();

        if ($type != 'all') {
            $query->where('status', $type);
        }

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('url', 'like', "%$search%");
            });
        }

        $banners = $query->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $banners
        ]);
    }

    // -----------------------------
    // MY BANNERS (auth user only)
    // -----------------------------
    public function myBanners(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $type = $request->type ?? 'all';
        $query = Banner::where('user_id', $user->id);

        if ($type != 'all') {
            $query->where('status', $type);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('url', 'like', "%$search%");
            });
        }

        $banners = $query->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $banners
        ]);
    }

    // -----------------------------
    // VIEW SINGLE BANNER
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

        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json([
                'status' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $banner
        ]);
    }

    // -----------------------------
    // CREATE BANNER
    // -----------------------------
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'nullable|url',
            'status' => 'required|in:pending,published,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Post Request',
                'data' => $validator->errors()
            ], 422);
        }

        // Generate unique slug
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Banner::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = $slug . '_' . time() . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('banners', $imageName, 'public');
        }

        $banner = Banner::create([
            'title' => $request->title,
            'slug' => $slug,
            'image' => $imagePath,
            'url' => $request->url,
            'status' => $request->status,
            'user_id' => $user->id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Banner created successfully',
            'data' => $banner
        ]);
    }

    // -----------------------------
    // UPDATE BANNER
    // -----------------------------
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $banner = Banner::find($id);
        if (!$banner || $banner->user_id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to update this banner'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'nullable|url',
            'status' => 'required|in:pending,published,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Post Request',
                'data' => $validator->errors()
            ], 422);
        }

        // Generate unique slug
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Banner::where('slug', $slug)->where('id', '!=', $banner->id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $imagePath = $banner->image;
        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $file = $request->file('image');
            $imageName = $slug . '_' . time() . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('banners', $imageName, 'public');
        }

        $banner->update([
            'title' => $request->title,
            'slug' => $slug,
            'image' => $imagePath,
            'url' => $request->url,
            'status' => $request->status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Banner updated successfully',
            'data' => $banner
        ]);
    }

    // -----------------------------
    // DELETE BANNER
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

        $banner = Banner::find($id);
        if (!$banner || $banner->user_id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to delete this banner'
            ], 404);
        }

        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return response()->json([
            'status' => true,
            'message' => 'Banner deleted successfully'
        ]);
    }

    // -----------------------------
    // UPDATE STATUS
    // -----------------------------
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json([
                'status' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:pending,published,rejected',
        ]);

        $banner->status = $request->status;
        $banner->save();

        return response()->json([
            'status' => true,
            'message' => 'Banner status updated successfully',
            'data' => $banner
        ]);
    }
}
