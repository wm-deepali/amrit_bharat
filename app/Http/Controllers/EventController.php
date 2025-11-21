<?php

namespace App\Http\Controllers;

use App\Event;
use App\City;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class EventController extends Controller
{
    /* ----------------------------------------------------------------------
     | LIST EVENTS
     ---------------------------------------------------------------------- */
    public function index(Request $req)
    {
        $type = $req->type ?? 'all';

        $query = Event::with('user');

        if ($type != 'all') {
            $query->where('status', $type);
        }

        return view('admin.events.index', [
            'events' => $query->latest()->get(),
            'type' => $type,
        ]);
    }

    /* ----------------------------------------------------------------------
     | SHOW CREATE FORM
     ---------------------------------------------------------------------- */
    public function create()
    {
        $states = State::select('id', 'name')->get();
        $cities = City::select('id', 'state_id', 'name')->get();
        $categories = \App\EventCategory::select('id', 'name')->where('status', 'active')->get();

        return view('admin.events.create', compact('states', 'cities', 'categories'));
    }


    /* ----------------------------------------------------------------------
     | STORE NEW EVENT
     ---------------------------------------------------------------------- */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'short_content' => 'required|string|max:140',
                'description' => 'required|string',
                'start_datetime' => 'required|date',
                'end_datetime' => 'required|date|after_or_equal:start_datetime',
                'venue' => 'required|string|max:255',
                'state_id' => 'required|exists:states,id',
                'city_id' => 'required|exists:cities,id',
                'category_id' => 'required|exists:event_categories,id',
                'type' => 'required|in:free,paid',
                'price' => 'nullable|numeric|min:0',
                'status' => 'required|in:pending,published',
                'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
                'default_image' => 'nullable|numeric',
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'msgCode' => 422,
                    'msgText' => 'Validation Failed',
                    'errors' => $validator->errors()
                ], 422);
            }


            // Generate slug
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

            // Handle default image index
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

            // Save event
            $event = new Event();
            $event->user_id = Auth::id(); // 🟢 store who created the event
            $event->title = $request->title;
            $event->slug = $slug;
            $event->short_content = $request->short_content;
            $event->description = $request->description;
            $event->start_datetime = $request->start_datetime;
            $event->end_datetime = $request->end_datetime;
            $event->venue = $request->venue;
            $event->state_id = $request->state_id;
            $event->city_id = $request->city_id;
            $event->category_id = $request->category_id;
            $event->type = $request->type;
            $event->price = $request->type == "paid" ? $request->price : null;
            $event->status = $request->status;
            $event->images = json_encode($imageNames);
            $event->default_image = $defaultImage;

            $event->save();

            return response()->json([
                'msgCode' => 200,
                'msgText' => 'Event Created Successfully',
                'event' => $event
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'msgCode' => 500,
                'msgText' => $e->getMessage()
            ]);
        }
    }


    public function view($id)
    {
        $event = Event::findOrFail($id);

        $images = json_decode($event->images, true) ?? [];

        return view('admin.events.view', compact('event', 'images'));
    }


    /* ----------------------------------------------------------------------
     | EDIT EVENT
     ---------------------------------------------------------------------- */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $states = State::select('id', 'name')->get();
        $cities = City::select('id', 'state_id', 'name')->get();
        $categories = \App\EventCategory::select('id', 'name')->get();

        return view('admin.events.edit', compact('event', 'states', 'cities', 'categories'));
    }


    /* ----------------------------------------------------------------------
     | UPDATE EVENT
     ---------------------------------------------------------------------- */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // ---------------------------
        // VALIDATION (JSON Response)
        // ---------------------------
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
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'removed_images' => 'array',
            'removed_images.*' => 'string',
            'default_image' => 'nullable|string',
            'category_id' => 'required|exists:event_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msgCode' => 422,
                'msgText' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // ---------------------------
        // 1. LOAD OLD IMAGES
        // ---------------------------
        $oldImages = json_decode($event->images ?? '[]', true);
        if (!is_array($oldImages))
            $oldImages = [];

        // ---------------------------
        // 2. REMOVE OLD IMAGES
        // ---------------------------
        $removed = $request->removed_images ?? [];

        foreach ($removed as $filename) {
            if (in_array($filename, $oldImages)) {
                $path = public_path('uploads/events/' . $filename);
                if (file_exists($path)) {
                    @unlink($path);
                }
                $oldImages = array_values(array_diff($oldImages, [$filename]));
            }
        }

        // ---------------------------
        // 3. HANDLE NEW IMAGES
        // ---------------------------
        $newImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/events'), $filename);
                $newImages[] = $filename;
            }
        }

        // MERGE old + new
        $finalImages = array_merge($oldImages, $newImages);

        // ---------------------------
        // 4. DEFAULT IMAGE HANDLING
        // default_image can be:
        //  - existing filename
        //  - "new-0", "new-1", etc → map to $newImages index
        // ---------------------------
        $defaultImage = $event->default_image; // fallback

        if ($request->default_image) {

            $value = $request->default_image;

            // CASE 1: old image filename
            if (in_array($value, $finalImages)) {
                $defaultImage = $value;
            }

            // CASE 2: new image → new-x index mapping
            if (str_starts_with($value, "new-")) {
                $index = intval(str_replace("new-", "", $value));
                if (isset($newImages[$index])) {
                    $defaultImage = $newImages[$index];
                }
            }
        }

        // ---------------------------
        // 5. UPDATE MODEL FIELDS
        // ---------------------------
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
        $event->category_id = $request->category_id;

        $event->images = json_encode($finalImages);
        $event->default_image = $defaultImage;

        $event->save();

        return response()->json([
            'msgCode' => 200,
            'msgText' => 'Event updated successfully',
        ]);
    }


    public function updateStatus(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->status = $request->status;
        $event->save();

        return response()->json(['msg' => 'Status updated successfully']);
    }


    /* ----------------------------------------------------------------------
     | DELETE EVENT
     ---------------------------------------------------------------------- */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        $images = json_decode($event->images ?? '[]', true);

        // Delete images
        foreach ($images as $image) {
            $path = public_path('uploads/events/' . $image);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $event->delete();
        return response()->json(['msgText' => 'Event deleted successfully']);
    }
}
