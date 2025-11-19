<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // --------------------------------------------
    // LIST EVENTS
    // --------------------------------------------
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!isset($user) || empty($user) || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $query = Event::with('user', 'city', 'state')
            ->where('status', 'published'); // only active/published events

        // Filter by city
        if ($request->has('city_id') && !empty($request->city_id)) {
            $query->where('city_id', $request->city_id);
        }

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('short_content', 'like', "%$search%");
            });
        }

        $events = $query->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $events
        ]);
    }

    public function myEvents()
    {
        $user = Auth::user();
        if (!isset($user) || empty($user) || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $events = Event::with('city', 'state')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $events
        ]);
    }


    // --------------------------------------------
    // VIEW SINGLE EVENT
    // --------------------------------------------
    public function show($id)
    {
        $user = Auth::user();

        if (!isset($user) || empty($user) || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it'
            ], 401);
        }


        $event = Event::with('user', 'city', 'state')->find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $event->images = json_decode($event->images, true) ?? [];

        return response()->json([
            'status' => true,
            'data' => $event
        ]);
    }

    // --------------------------------------------
    // CREATE EVENT
    // --------------------------------------------
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!isset($user) || empty($user) || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it'
            ], 401);
        }


        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'short_content' => 'required|string|max:140',
            'description' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'venue' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'type' => 'required|in:free,paid',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,published',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'default_image' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Post Request',
                'data' => $validator->errors()
            ], 422);
        }

        // Slug
        $slug = $request->slug ?: Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;

        while (Event::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        // Upload images
        $imageNames = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/events'), $filename);
                $imageNames[] = $filename;
            }
        }
        // Default image
        $defaultImage = null;
        if (!empty($imageNames) && $request->default_image !== null) {
            $defaultIndex = (int) $request->default_image;
            if (isset($imageNames[$defaultIndex])) {
                $defaultImage = $imageNames[$defaultIndex];
            }
        }
        if (!$defaultImage && count($imageNames) > 0) {
            $defaultImage = $imageNames[0];
        }
        
      
        $event = new Event();
        $event->user_id = Auth::id() ?? 0; // if API has auth, otherwise 0
        $event->title = $request->title;
        $event->slug = $slug;
        $event->short_content = $request->short_content;
        $event->description = $request->description;
        $event->start_datetime = $request->start_datetime;
        $event->end_datetime = $request->end_datetime;
        $event->venue = $request->venue;
        $event->state_id = $request->state_id;
        $event->city_id = $request->city_id;
        $event->type = $request->type;
        $event->price = $request->type == "paid" ? $request->price : null;
        $event->status = $request->status;
        $event->images = json_encode($imageNames);
        $event->default_image = $defaultImage;
        $event->save();

        return response()->json([
            'status' => true,
            'message' => 'Event created successfully',
            'data' => $event
        ]);
    }

    // --------------------------------------------
    // UPDATE EVENT
    // --------------------------------------------
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!isset($user) || empty($user) || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it'
            ], 401);
        }

        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }

        // Check if this event belongs to the logged-in user
        if ($event->user_id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to update this event'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events,slug,' . $event->id,
            'short_content' => 'required|string|max:140',
            'description' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'venue' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'type' => 'required|in:free,paid',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,published,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Post Request',
                'data' => $validator->errors()
            ], 422);
        }

        $event->title = $request->title;
        $event->slug = $request->slug;
        $event->short_content = $request->short_content;
        $event->description = $request->description;
        $event->start_datetime = $request->start_datetime;
        $event->end_datetime = $request->end_datetime;
        $event->venue = $request->venue;
        $event->state_id = $request->state_id;
        $event->city_id = $request->city_id;
        $event->type = $request->type;
        $event->price = $request->type == "paid" ? $request->price : null;
        $event->status = $request->status;

        $event->save();

        return response()->json([
            'status' => true,
            'message' => 'Event updated successfully',
            'data' => $event
        ]);
    }


    // --------------------------------------------
    // DELETE EVENT
    // --------------------------------------------
    public function destroy($id)
    {
        $user = Auth::user();
        if (!isset($user) || empty($user) || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it'
            ], 401);
        }

        $event = Event::find($id);
        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }

        // Check ownership
        if ($event->user_id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to delete this event'
            ], 403);
        }

        // Delete images
        $images = json_decode($event->images ?? '[]', true);
        foreach ($images as $img) {
            $path = public_path('uploads/events/' . $img);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $event->delete();

        return response()->json([
            'status' => true,
            'message' => 'Event deleted successfully'
        ]);
    }

}
