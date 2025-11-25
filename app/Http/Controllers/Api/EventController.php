<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Event;
use App\EventBookmarkLike;
use App\EventView;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\EventCategory;

class EventController extends Controller
{
    // --------------------------------------------
    // LIST EVENTS
    // --------------------------------------------
    public function index(Request $request)
    {
        $user = auth()->user();
        $cityId = $request->city_id ?? ($user->city_id ?? null);
        $requestedCity = $request->city_id ? true : false; // check if query city
        $stateId = null;

        // Get city and state for fallback
        if ($cityId) {
            $city = \App\City::find($cityId);
            $stateId = $city ? $city->state_id : null;
        }

        if (!$cityId && $user) {
            if ($user->city_id) {
                $cityId = $user->city_id;
                $stateId = $user->state_id;
            } elseif ($user->state_id) {
                $stateId = $user->state_id;
            }
        }

        // Base query
        $baseQuery = Event::with('user', 'city', 'state')
            ->where('status', 'published');

        /**
         * 1️⃣ TRY CITY EVENTS
         */
        if ($cityId) {
            $cityEvents = (clone $baseQuery)->where('city_id', $cityId)->latest()->get();

            if ($cityEvents->count() > 0) {
                return $this->finalResponse($request, $cityEvents);
            }

            // If cityId came from query param → show "no events" message
            if ($requestedCity) {
                // 2️⃣ TRY STATE EVENTS
                if ($stateId) {
                    $stateEvents = (clone $baseQuery)->where('state_id', $stateId)->latest()->get();

                    if ($stateEvents->count() > 0) {
                        return $this->fallbackResponse("No Events in Your City", "View Other Events", "state", $stateEvents, $request);
                    }
                }

                // 3️⃣ FALLBACK → ALL INDIA EVENTS
                $allIndia = (clone $baseQuery)->latest()->get();
                return $this->fallbackResponse("No Events in Your City", "View Other Events", "india", $allIndia, $request);
            }
        }

        /**
         * No cityId came from Query → USER BASED LOGIC (NO message needed)
         */
        // User fallback (city → state → india)
        if ($user) {

            // state fallback
            if ($stateId) {
                $stateEvents = (clone $baseQuery)->where('state_id', $stateId)->latest()->get();

                if ($stateEvents->count() > 0) {
                    return $this->finalResponse($request, $stateEvents);
                }
            }

            // All India
            $allEvents = (clone $baseQuery)->latest()->get();
            return $this->finalResponse($request, $allEvents);
        }

        /**
         * No user, no city → return all India
         */
        $allEvents = (clone $baseQuery)->latest()->get();
        return $this->finalResponse($request, $allEvents);
    }



    public function getCategories()
    {
        $categories = EventCategory::where('status', 'active')
            ->orderBy('name', 'ASC')
            ->get(['id', 'name', 'slug', 'status', 'created_at']);

        return response()->json([
            'success' => true,
            'message' => 'Event categories fetched successfully',
            'data' => $categories
        ]);
    }


    /**
     * NORMAL RESPONSE (no messages)
     */
    private function finalResponse($request, $events)
    {
        $events = $this->applySearch($request, $events);

        return response()->json([
            'status' => true,
            'data' => $events
        ]);
    }


    /**
     * FALLBACK RESPONSE WITH MESSAGE
     */
    private function fallbackResponse($message, $label, $type, $events, $request)
    {
        $events = $this->applySearch($request, $events);

        return response()->json([
            'status' => true,
            'message' => $message,          // "No Events in Your City"
            'fallback_label' => $label,     // "View Other Events"
            'data_type' => $type,           // "state" or "india"
            'data' => $events
        ]);
    }


    /**
     * SEARCH FILTER
     */
    private function applySearch($request, $events)
    {
        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $events = $events->filter(function ($event) use ($search) {
                return str_contains(strtolower($event->title), $search)
                    || str_contains(strtolower($event->short_content), $search);
            })->values();
        }

        return $events;
    }


    public function getEventsByCategory(Request $request, $category)
    {
        // Detect category by ID or slug
        $categoryObj = EventCategory::where('id', $category)
            ->orWhere('slug', $category)
            ->first();

        if (!$categoryObj) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // Base query
        $query = Event::with('user', 'city', 'state')
            ->where('status', 'published')
            ->where('category_id', $categoryObj->id);

        // Optional: Apply search if needed
        if ($request->filled('search')) {
            $query = $this->applySearch($query, $request->search);
        }

        $events = $query->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Events fetched successfully',
            'category' => $categoryObj->name,
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

        // -------------------------
        // Track unique view per IP per day
        // -------------------------
        $ip = request()->ip();
        $today = Carbon::today();

        $exists = EventView::where('event_id', $event->id)
            ->where('ip_address', $ip)
            ->whereDate('created_at', $today)
            ->exists();

        if (!$exists) {
            EventView::create([
                'event_id' => $event->id,
                'user_id' => $user ? $user->id : null,
                'ip_address' => $ip
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $event,
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

        // Validation (category_id must accept "other")
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'short_content' => 'required|string|max:140',
            'description' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'venue' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'category_id' => 'required',  // removed exists rule because it can be "other"
            'new_category' => 'required_if:category_id,other|string|max:255',
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

        // -----------------------------------------
        // 1️⃣ CREATE NEW CATEGORY IF SELECTED "OTHER"
        // -----------------------------------------
        if ($request->category_id === "other") {

            // create the category
            $cat = new EventCategory();
            $cat->name = $request->new_category;
            $cat->slug = Str::slug($request->new_category);
            $cat->status = 'active';
            $cat->save();

            // replace category_id with new ID
            $categoryId = $cat->id;
        } else {
            // existing category
            $categoryId = $request->category_id;
        }

        // -----------------------------------------
        // 2️⃣ GENERATE UNIQUE SLUG
        // -----------------------------------------
        $slug = $request->slug ?: Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;

        while (Event::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        // -----------------------------------------
        // 3️⃣ UPLOAD IMAGES
        // -----------------------------------------
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

        // -----------------------------------------
        // 4️⃣ SAVE EVENT
        // -----------------------------------------
        $event = new Event();
        $event->user_id = Auth::id();
        $event->title = $request->title;
        $event->slug = $slug;
        $event->short_content = $request->short_content;
        $event->description = $request->description;
        $event->start_datetime = $request->start_datetime;
        $event->end_datetime = $request->end_datetime;
        $event->venue = $request->venue;
        $event->state_id = $request->state_id;
        $event->city_id = $request->city_id;
        $event->category_id = $categoryId;   // updated category ID
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

        // Check if this event belongs to current user
        if ($event->user_id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to update this event'
            ], 403);
        }

        // ------------------------------
        // VALIDATION
        // ------------------------------
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

            // category validation
            'category_id' => 'required',
            'new_category' => 'required_if:category_id,other|max:255',

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

        // -----------------------------------------------------
        // IF USER SELECTED "OTHER" → CREATE NEW CATEGORY
        // -----------------------------------------------------
        if ($request->category_id === "other") {

            $newCat = EventCategory::create([
                'name' => $request->new_category,
                'slug' => Str::slug($request->new_category),
                'status' => 'active'
            ]);

            $finalCategoryId = $newCat->id;

        } else {
            $finalCategoryId = $request->category_id;
        }

        // ------------------------------
        // UPDATE EVENT
        // ------------------------------
        $event->title = $request->title;
        $event->slug = $request->slug;
        $event->short_content = $request->short_content;
        $event->description = $request->description;
        $event->start_datetime = $request->start_datetime;
        $event->end_datetime = $request->end_datetime;
        $event->venue = $request->venue;
        $event->state_id = $request->state_id;
        $event->city_id = $request->city_id;
        $event->category_id = $finalCategoryId; // ← SET FINAL CATEGORY

        $event->type = $request->type;
        $event->price = $request->type == "paid" ? $request->price : null;

        // Always make status pending after update
        $event->status = "pending";

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

    // --------------------------------------------
    // ADD LIKE
    // --------------------------------------------
    public function addLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Event Request',
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

        $event = Event::find($request->event_id);
        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }

        // Increment likes if not already liked
        $existLike = EventBookmarkLike::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$existLike) {
            EventBookmarkLike::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'likes' => 1,
            ]);
        } else {
            // If previously unliked, set likes to 1
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

    // --------------------------------------------
    // REMOVE LIKE
    // --------------------------------------------
    public function removeLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Event Request',
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

        $event = Event::find($request->event_id);
        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $existLike = EventBookmarkLike::where('user_id', $user->id)
            ->where('event_id', $event->id)
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

}
