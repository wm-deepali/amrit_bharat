<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    // Display list of banners
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // Filter by status

        $query = Banner::with('user')->orderBy('created_at', 'desc');

        if (in_array($type, ['pending', 'published', 'rejected'])) {
            $query->where('status', $type);
        }

        $banners = $query->get();

        return view('admin.banners.index', compact('banners', 'type'));
    }

    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.view', compact('banner'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    // Store banner
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'nullable|url',
            'status' => 'required|in:pending,published,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate slug from title
        $slug = Str::slug($request->title);

        // Ensure unique slug
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

        Banner::create([
            'title' => $request->title,
            'slug' => $slug,
            'image' => $imagePath,
            'url' => $request->url,
            'status' => $request->status,
            'user_id' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Banner Created Successfully']);
    }

    // Show edit form
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    // Update banner
    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'nullable|url',
            'status' => 'required|in:pending,published,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate slug from title
        $slug = Str::slug($request->title);

        // Ensure unique slug, excluding current banner
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
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'Banner Updated Successfully']);
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return response()->json(['success' => true, 'message' => 'Banner Deleted Successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,published,rejected',
        ]);

        $banner->status = $request->status;
        $banner->save();

        return response()->json(['success' => true, 'message' => 'Status Updated Successfully']);
    }
}
