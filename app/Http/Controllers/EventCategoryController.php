<?php

namespace App\Http\Controllers;

use App\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventCategoryController extends Controller
{
    /**
     * Display list of categories
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // default: all

        $query = EventCategory::query();

        if ($type === 'active') {
            $query->where('status', 'active');
        } elseif ($type === 'inactive') {
            $query->where('status', 'inactive');
        }

        $categories = $query->latest()->get();

        return view('admin.event_categories.index', compact('categories', 'type'));
    }

    public function create()
    {
        return view('admin.event_categories.create');
    }

    /**
     * Store new category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:event_categories,name',
            'slug' => 'nullable|string|max:191|unique:event_categories,slug',
            'status' => 'required|in:active,inactive',
        ]);

        EventCategory::create([
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->name),
            'status' => $request->status,
        ]);

        return back()->with('success', 'Event Category created successfully.');
    }

    /**
     * Show single category (AJAX)
     */
    public function edit($id)
    {
        $category = EventCategory::findOrFail($id);
        return view('admin.event_categories.edit', compact('category'));
    }

    /**
     * Update category
     */
    public function update(Request $request, $id)
    {
        $category = EventCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:event_categories,name,' . $id,
            'slug' => 'nullable|string|max:191|unique:event_categories,slug,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->name),
            'status' => $request->status,
        ]);

        return back()->with('success', 'Event Category updated successfully.');
    }

    /**
     * Delete category
     */
    public function destroy($id)
    {
        EventCategory::findOrFail($id)->delete();
       return response()->json(['msg' => 'Category Deleted']);
    }
}
