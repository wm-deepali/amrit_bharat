<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hashtag;
use Illuminate\Support\Str;
use Auth;
use Validator;

class HashtagController extends Controller
{
    // -----------------------
    // LIST / INDEX
    // -----------------------
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');

        $hashtags = Hashtag::query();

        if ($type == 'active') {
            $hashtags->where('status', 'active');
        } elseif ($type == 'inactive') {
            $hashtags->where('status', 'inactive');
        }

        $hashtags = $hashtags->orderBy('id', 'desc')->get();

        return view('admin.hashtags.index', compact('hashtags', 'type'));
    }

    // -----------------------
    // CREATE FORM
    // -----------------------
    public function create()
    {
        return view('admin.hashtags.create');
    }

    // -----------------------
    // STORE
    // -----------------------
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:150',
            'hashtag' => 'required|string|max:50|unique:hashtags,hashtag',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Hashtag::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'hashtag' => Str::slug($request->hashtag, '_'),
            'status' => $request->status
        ]);

        return response()->json(['msg' => 'Hashtag Created Successfully']);
    }

    // -----------------------
    // EDIT FORM
    // -----------------------
    public function edit($id)
    {
        $hashtag = Hashtag::findOrFail($id);
        return view('admin.hashtags.edit', compact('hashtag'));
    }

    // -----------------------
    // UPDATE
    // -----------------------
    public function update(Request $request, $id)
    {
        $hashtag = Hashtag::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:150',
            'hashtag' => 'required|string|max:50|unique:hashtags,hashtag,' . $id,
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $hashtag->update([
            'title' => $request->title,
            'hashtag' => Str::slug($request->hashtag, '_'),
            'status' => $request->status
        ]);

        return response()->json(['msg' => 'Hashtag Updated Successfully']);
    }

    // -----------------------
    // VIEW
    // -----------------------
    public function show($id)
    {
        $hashtag = Hashtag::findOrFail($id);
        return view('admin.hashtags.view', compact('hashtag'));
    }

    // -----------------------
    // DELETE
    // -----------------------
    public function destroy($id)
    {
        $hashtag = Hashtag::findOrFail($id);
        $hashtag->delete();

        return response()->json(['msg' => 'Hashtag Deleted Successfully']);
    }
}
